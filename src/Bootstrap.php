<?php 

namespace Ziriga;
use Ziriga\Search\LoadPosts;

class Bootstrap 
{
	public static function run()
	{
		$request = static::getParams();
		return LoadPosts::find($request);
	}

	protected static function getParams()
	{
		$request = array_map('addslashes', filter_input_array(INPUT_GET, FILTER_DEFAULT));
		$request = !$request ? [] : $request;

		return array_merge(static::getDefaultParams(), $request);
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