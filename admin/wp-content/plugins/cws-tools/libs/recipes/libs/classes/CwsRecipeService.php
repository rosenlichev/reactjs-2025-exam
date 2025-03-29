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
}