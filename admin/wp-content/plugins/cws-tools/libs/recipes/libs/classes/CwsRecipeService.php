<?php

use Illuminate\Support\Collection;

class CwsRecipeService extends CwsBaseService
{

	/**
	 * Returns list of recipes
	 * 
	 * @param [Array] $params
	 * @return Collection
	 */ 
	public function getRecipes($params = []): Collection
	{
		$recipes = [];

		$default_args = [
			'post_type' 		=> 'recipe',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> -1,
			'meta_query' 		=> [],
			'tax_query' 		=> []
		];

		$query_args = array_merge($default_args, $params);

		$query = new WP_Query($query_args);

		if ($query->have_posts()) {
			foreach ($query->posts as $post) {
				$prepareRecipe = [
					'id' 				=> $post->ID,
					'name' 				=> $post->post_title,
					'author' 			=> '',
					'preparationTime' 	=> get_post_meta($post->ID, 'preparation_time', true) ?? '',
					'image' 			=> '',
					'liked' 			=> 0,
					'comments' 			=> 0
				];

				$author_id = $post->post_author;

				if ($author_id) {
					$prepareRecipe['author'] = (new CwsUserService)->getUserFullName($author_id);
				}

				$thumbnail_id = get_post_thumbnail_id($post->ID);

				if ($thumbnail_id) {
					$thumbnail_url = wp_get_attachment_url($thumbnail_id);

					if ($thumbnail_url) {
						$prepareRecipe['image'] = $thumbnail_url;
					}
				}

				$terms = get_the_terms($post->ID, 'recipe_category');

				if (!empty($terms) && !is_wp_error($terms)) {
					$term = $terms[key($terms)];

					if ($term) {
						$prepareRecipe['category'] = $term->name;
					}
				}

				$recipes[] = $prepareRecipe;
			}
		}

		return collect($recipes);
	}

	public function getRecipeDetails($params = []): Collection
	{
		$recipe = [];

		__sd($params, "GET RECIPE DETAILS");

		if (isset($params['id']) && $params['id'] != '') {
			$post = get_post($params['id']);

			if (!empty($post) && !is_wp_error($post)) {
				$recipe = [
					'id' 				=> $post->ID,
					'name' 				=> $post->post_title,
					'author' 			=> '',
					'preparationTime' 	=> get_post_meta($post->ID, 'preparation_time', true) ?? '',
					'image' 			=> '',
					'liked' 			=> 0,
					'comments' 			=> 0
				];

				$author_id = $post->post_author;

				if ($author_id) {
					$recipe['author'] = (new CwsUserService)->getUserFullName($author_id);
				}

				$recipe['kcal'] = get_post_meta($post->ID, 'kcal', true) ?? '';
				$recipe['servings'] = get_post_meta($post->ID, 'servings', true) ?? '';
				$recipe['fats'] = get_post_meta($post->ID, 'fats', true) ?? '';
				$recipe['carbs'] = get_post_meta($post->ID, 'carbs', true) ?? '';
				$recipe['proteins'] = get_post_meta($post->ID, 'proteins', true) ?? '';
				$recipe['preparation'] = get_post_meta($post->ID, 'preparation', true) ?? '';
				$recipe['shortDescription'] = $post->post_excerpt;
				$recipe['description'] = $post->post_content;

				$recipe['ingredients'] = get_post_meta($post->ID, 'ingredients', false) ?? [];

				foreach ($recipe['ingredients'] as $ingredient) {
					if (is_array($ingredient)) {
						$recipe['ingredients'] = $ingredient;
						break;
					}
				}

				foreach ($recipe['ingredients'] as $key => $ingredient) {
					if (trim($ingredient) == '') {
						unset($recipe['ingredients'][$key]);
					}
				}

				$thumbnail_id = get_post_thumbnail_id($post->ID);

				if ($thumbnail_id) {
					$thumbnail_url = wp_get_attachment_url($thumbnail_id);

					if ($thumbnail_url) {
						$recipe['image'] = $thumbnail_url;
					}
				}

				$terms = get_the_terms($post->ID, 'recipe_category');

				if (!empty($terms) && !is_wp_error($terms)) {
					foreach ($terms as $term) {
						$recipe['categories'][] = [
							'id' 	=> $term->term_id,
							'name' 	=> $term->name
						];
					}
				}
			}
		}

		return collect($recipe);
	}

	public function saveOrUpdateRecipe()
	{
		$response = new Collection(['errors' => collect(), 'data' => collect()]);

        $fields = $this->setFields();
        $errors = $this->validateFields($fields);

        if ($errors->isEmpty()) {
            $saveResponseData = $this->saveRecipe($fields);

            if ($saveResponseData->has('errors') && $saveResponseData->get('errors')->isNotEmpty()) {
            	$response->put('errors', $saveResponseData->get('errors'));
            } else {
            	$response->put('data', $saveResponseData->get('data'));
            }

        } else {

            $response->put('errors', $errors);
        }

        return $response;
	}

	public function setFields()
	{
		$fields = new Collection();

		if ($this->vars->has('id')) {
			$fields->put('id', $this->vars->get('id'));
		}

		if ($this->vars->has('name')) {
			$fields->put('post_title', $this->vars->get('name'));
		}

		if ($this->vars->has('description')) {
			$fields->put('post_content', $this->vars->get('description'));
		}

		if ($this->vars->has('short_description')) {
			$fields->put('post_excerpt', $this->vars->get('short_description'));
		}

		if ($this->vars->has('kcal')) {
			$fields->put('kcal', $this->vars->get('kcal'));
		}

		if ($this->vars->has('preparation_time')) {
			$fields->put('preparation_time', $this->vars->get('preparation_time'));
		}

		if ($this->vars->has('servings')) {
			$fields->put('servings', $this->vars->get('servings'));
		}

		if ($this->vars->has('kcal')) {
			$fields->put('kcal', $this->vars->get('kcal'));
		}

		if ($this->vars->has('carbs')) {
			$fields->put('carbs', $this->vars->get('carbs'));
		}

		if ($this->vars->has('fats')) {
			$fields->put('fats', $this->vars->get('fats'));
		}

		if ($this->vars->has('proteins')) {
			$fields->put('proteins', $this->vars->get('proteins'));
		}

		if ($this->vars->has('ingredients')) {
			$fields->put('ingredients', $this->vars->get('ingredients'));
		}

		if ($this->vars->has('preparation')) {
			$fields->put('preparation', $this->vars->get('preparation'));
		}

        return $fields;
	}

	public function validateFields($fields) {
		$errors = new Collection();

       	if (!$fields->has('post_title') || $fields->get('post_title') == '') {
            $errors->put('post_title', __('Recipe name is required', 'cws-tools'));
        }

        return $errors;
	}

	public function saveRecipe($fields) {
		$post_id = $fields->has('id') ? $fields->get('id') : 0;

		if ($post_id > 0) {
			$post_fields = [
				'ID' 			=> $post_id,
				'post_status' 	=> 'publish',
				'author' 		=> get_current_user_id(),
				'post_author' 	=> get_current_user_id()
			];

			if ($fields->has('post_title')) {
				$post_fields['post_title'] = $fields->get('post_title');
			}
			if ($fields->has('post_content')) {
				$post_fields['post_content'] = $fields->get('post_content');
			}
			if ($fields->has('post_excerpt')) {
				$post_fields['post_excerpt'] = $fields->get('post_excerpt');
			}

			$post_id = wp_update_post($post_fields);
		} else {
			$post_fields = [
				'post_type' 	=> 'recipe',
				'post_status' 	=> 'publish',
				'author' 		=> get_current_user_id(),
				'post_author' 	=> get_current_user_id()
			];

			if ($fields->has('post_title')) {
				$post_fields['post_title'] = $fields->get('post_title');
			}
			if ($fields->has('post_content')) {
				$post_fields['post_content'] = $fields->get('post_content');
			}
			if ($fields->has('post_excerpt')) {
				$post_fields['post_excerpt'] = $fields->get('post_excerpt');
			}

			$post_id = wp_insert_post($post_fields);
		}

		if ($post_id && !is_wp_error($post_id)) {
			if ($fields->has('preparation_time')) {
				update_post_meta($post_id, 'preparation_time', $fields->get('preparation_time'));
			}
			if ($fields->has('servings')) {
				update_post_meta($post_id, 'servings', $fields->get('servings'));
			}
			if ($fields->has('kcal')) {
				update_post_meta($post_id, 'kcal', $fields->get('kcal'));
			}
			if ($fields->has('carbs')) {
				update_post_meta($post_id, 'carbs', $fields->get('carbs'));
			}
			if ($fields->has('fats')) {
				update_post_meta($post_id, 'fats', $fields->get('fats'));
			}
			if ($fields->has('proteins')) {
				update_post_meta($post_id, 'proteins', $fields->get('proteins'));
			}
			if ($fields->has('preparation')) {
				update_post_meta($post_id, 'preparation', $fields->get('preparation'));
			}
			if ($fields->has('ingredients')) {
				update_post_meta($post_id, 'ingredients', $fields->get('ingredients'));
			}

			$recipe = $this->getRecipeDetails(['id' => $post_id]);

			return collect(['data' => collect($recipe), 'errors' => collect([])]);
		}

		return collect(['data' => collect([]), 'errors' => collect(['message' => __('Invalid save', 'cws-tools')])]);
	}
}