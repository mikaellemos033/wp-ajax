<?php 

namespace Ziriga;
use Ziriga\Search\LoadPosts;

class Bootstrap 
{
	public static function run()
	{
		$request   = static::getParams();
		$loadPosts = new LoadPosts($request);


		return $loadPosts->run();
	}

	protected static function getParams()
	{
		$request = filter_input_array(INPUT_GET, FILTER_DEFAULT);
		$request = !$request ? [] : array_map('addslashes', $request);

		return array_merge(static::getDefaultParams(), $request, static::subquery($request));
	}

	protected static function subquery($request)
	{
		if (empty($request['taxonomy_type']) || (empty($request['taxonomy_field']) && empty($request['taxonomy_terms'])) ) {
			return [];
		}

		$params = array_filter([
			'taxonomy' 		   => $request['taxonomy_type'],
        	'field'	   		   => empty($request['taxonomy_field']) ? null : $request['taxonomy_field'],
        	'terms'    		   => empty($request['taxonomy_terms']) ? null : $request['taxonomy_terms'],
        	'include_children' => true,
        	'operator'		   => 'in'
        ]);

		return [
			'tax_query' => [ $params ]
		];
	}

	protected static function getDefaultParams()
	{
		return [
			'posts_per_page'   => 6,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'post',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'author'	   	   => '',
			'author_name'	   => '',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'load_meta'		   => '',
		];
	}

}