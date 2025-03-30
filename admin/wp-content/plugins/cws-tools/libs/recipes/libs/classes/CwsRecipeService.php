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

				if (is_user_logged_in()) {
					$user_id = get_current_user_id();
					$wishlist = get_user_meta($user_id, 'wishlist', false);

					if (is_array($wishlist)) {
						if (in_array($post->ID, $wishlist)) {
							$prepareRecipe['liked'] = 1;
						}
					} else if ($post->ID == $wishlist) {
						$prepareRecipe['liked'] = 1;
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

				if (is_user_logged_in()) {
					$user_id = get_current_user_id();
					$wishlist = get_user_meta($user_id, 'wishlist', false);

					if (is_array($wishlist)) {
						if (in_array($post->ID, $wishlist)) {
							$recipe['liked'] = 1;
						}
					} else if ($post->ID == $wishlist) {
						$recipe['liked'] = 1;
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

		if ($this->vars->has('image')) {
			$fields->put('image', $this->vars->get('image'));
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

			__sd($post_fields, "POST FIELDS BEFORE SAVE");

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

			if ($fields->has('image') && $fields->get('image') != '') {
				$saveThumbnail = $this->saveRecipeImage($post_id, $fields->get('image'));
			}

			$recipe = $this->getRecipeDetails(['id' => $post_id]);

			return collect(['data' => collect($recipe), 'errors' => collect([])]);
		}

		return collect(['data' => collect([]), 'errors' => collect(['message' => __('Invalid save', 'cws-tools')])]);
	}

	/**
	 * Saves post featured image
	 * 
	 * @param [Integer] $post_id
	 * @param [String] $image_url
	 * @return [array] $response
	 */
	public function saveRecipeImage($post_id, $image_url)
	{	
		require_once ABSPATH . 'wp-admin/includes/file.php';
    	require_once ABSPATH . 'wp-admin/includes/media.php';
    	require_once ABSPATH . 'wp-admin/includes/image.php';

		$response = ['data' => [], 'errors' => []];

		$file_name = basename(parse_url($image_url, PHP_URL_PATH));

	    // Download the image to a temporary location
	    $temp_file = download_url($image_url);

	    // removing white space
		$fileName = preg_replace('/\s+/', '-', $file_name);

		// removing special characters
		$fileName = preg_replace('/[^A-Za-z0-9.\-]/', '', $fileName);

		// upload file
		$upload = wp_upload_bits($fileName, null, file_get_contents($temp_file));

		if (isset($upload['error']) && !empty($upload['error'])) {
			$response['errors']['image'] = $upload['error'] ?? __('An error occurred while uploading the image. Please try again.',  'cws-tools');
		} elseif (isset($upload['file'])) {
			$params['guid'] 			= $upload['url'];
			$params['post_mime_type'] 	= $upload['type'];

			// Insert the attachment.
			$attach_id = wp_insert_attachment($params, $upload['file'], 0);

			if (is_numeric($attach_id)) {
		        if (!function_exists('wp_generate_attachment_metadata')) {
	        		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
	        		require_once ABSPATH . 'wp-admin/includes/image.php';
	        	}

	        	// Generate the metadata for the attachment, and update the database record.
			    $metas['_wp_attachment_metadata'] = wp_generate_attachment_metadata($attach_id, $upload['file']);
			    foreach ($metas as $_key => $value) {
			        update_post_meta($attach_id, $_key, $value);
			    }

			    set_post_thumbnail($post_id, $attach_id);
		    }
		}

		return $response;
	}
}