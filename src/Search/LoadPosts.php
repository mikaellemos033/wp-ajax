<?php 

namespace Ziriga\Search;

use Ziriga\Render\Response;
use Ziriga\Meta\ParseMeta;
use \wpdb;

class LoadPosts
{
	protected $parameters = [];

	public function __construct(array $params)
	{
		$metas = new ParseMeta( empty($params['load_meta']) ? '' : $params['load_meta'] );

		$this->parameters = $params;
		$this->metas 	  = $metas->getMetas();
	}	


	/**
	 * find data and response content
	 *
	 * @return Ziriga\Render\Response::json
	 */
	public function run()
	{
		$posts = $this->getMasterPosts();

		if (empty($posts)) return $this->notFound();

		foreach ($posts as $post) {
			$post->thumbnail = $this->getThumbnail($post->ID);
			$post->postmeta  = $this->getMetas($post->ID);
		}

		return $this->responseSuccess($posts);
	}

	/**
	 * return instance of wpdb
	 *
	 * @return wpbd object
	 */
	public function getConnect()
	{
		return new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
	}

	/**
	 * return response json of error 404
	 *
	 * @return Ziriga\Render\Response::json
	 */
	public function notFound()
	{
		return Response::json([
			'success' => false,
			'message' => 'Nada encontrado!'
		], 404);
	}

	/**
	 * return response json success
	 *
	 * @return Ziriga\Render\Response::json
	 */
	public function responseSuccess(array $posts)
	{
		return Response::json([
			'success' => true,
			'message' => 'Posts carregados com sucesso!',
			'posts'   => $posts
		]);
	}


	/**
	 * return thumbnail image by post id
	 *
	 * @param int $id
	 * @return string 
	 */
	public function getThumbnail($id)
	{
		return get_the_post_thumbnail_url($id);	
	}

	/**
	 * find posts by get parameters
	 *
	 * @return array
	 */
	public function getMasterPosts()
	{
		return get_posts($this->parameters);
	}

	/**
	 * get metas 
	 *
	 * @return array
	 */
	public function getMetas($id)
	{
		$postmetas = [];
		$uniques   = [];
		$multiples = [];
		$posts 	   = [];


		foreach ($this->metas as $meta => $filters) {
		
			if (!empty($filters['post'])   && $filters['post'] == 'true')   array_push($posts, $meta);
			if (!empty($filters['unique']) && $filters['unique'] == 'true'){
				array_push($uniques, $meta);
				continue;	
			} 

			array_push($multiples, $meta);
		}

		$postmetas = $this->mergeMeta($id, $uniques, $multiples);

		if (!empty($posts)) {

			foreach ( $postmetas as $meta => $value ) {

				if ( !in_array($meta, $posts) ) continue;
				$ids = [];

				foreach ($value as $item) {					
					if (!is_numeric($item)) continue;
					array_push($ids, $item);
				}

				if (empty($ids)) continue;

				$temp = $this->getPostsIds($ids);

				if (!empty($temp)) $postmetas[$meta] = $temp;			
			}
		}

		return $postmetas;
	}

	/**
	 * get posts by ids
	 *
	 * @param array $ids 
	 * @return array
	 */
	public function getPostsIds(array $ids)
	{
		global $table_prefix;

		$posts = [];
		$query = sprintf("SELECT * FROM %sposts where id in (%s)", $table_prefix, implode(',', $ids));
		$data  = $this->getConnect()->get_results($query);

		if (empty($data)) return [];
		
		foreach ($data as $post) {
			$post->thumbnail = $this->getThumbnail($post->ID);
			$post->postmeta  = $this->getMetas($post->ID);

			array_push($posts, $post);
		}

		return $posts;
	}


	/**
	 *  
	 * find and merge postmeta
	 * 
	 * @param int $id
	 * @param array $uniques
	 * @param array $multiples
	 * @return array
	 */

	public function mergeMeta($id, array $uniques, array $multiples)
	{
		$uniques   = $this->getUniqueMetas($id, $uniques);
		$multiples = $this->getMultipleMetas($id, $multiples);
		$postmeta  = [];	

		foreach ( array_merge($uniques, $multiples) as $meta ) {

			if (empty($postmeta[$meta->meta_key])) $postmeta[$meta->meta_key] = [];
			array_push($postmeta[$meta->meta_key], $meta->meta_value);

		}

		return $postmeta;
	}

	/**
	 * get unique metas
	 *
	 * @return array
	 */
	public function getUniqueMetas($id, array $metas)
	{
		if (empty($metas)) return [];
		global $table_prefix;

		$query = sprintf("SELECT distict(meta_key) FROM %spostmeta where meta_key in (%s) and post_id = %d", $table_prefix, '"' . implode('","', $metas) . '"', $id);
		$posts = $this->getConnect()->get_results($query);

		if (empty($posts)) return [];
		return $posts;
	}

	/**
	 * get multiple metas
	 *
	 * @return array
	 */
	public function getMultipleMetas($id, array $metas)
	{
		if (empty($metas)) return [];
		global $table_prefix;

		$query = sprintf("SELECT * FROM %spostmeta where meta_key in (%s) and post_id = %d", $table_prefix, '"' . implode('","', $metas) . '"', $id);		
		$posts = $this->getConnect()->get_results($query);			

		if (empty($posts)) return [];
		return $posts;
	}
}