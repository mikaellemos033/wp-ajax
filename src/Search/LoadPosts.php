<?php 

namespace Ziriga\Search;

use Ziriga\Render\Response;

class LoadPosts
{
	public static function find(array $params)
	{
		$posts = get_posts($params);

		if (empty($posts)) return static::notFound();
		return static::loadThumbnailPosts($posts, $params['load_meta']);
	}

	public static function loadThumbnailPosts(array $posts, $postmeta)
	{
		$publish = [];
		$metas   = array_map([self, 'getPostMeta'], explode(';', $postmeta));

		foreach ( $posts as $post ){

			$post->thumbnail = get_the_post_thumbnail_url($post->ID);
			$postmeta 		 = [];
			
			foreach ( $metas as $meta ) {
				$postmeta[$meta[0]] = static::loadMeta($post->ID, $meta[0], (!empty($meta[1]) && $meta[1] == 'true'));
			}

			$post->postmetas = $postmeta;
			array_push($publish, $post);
		}

		return Response::json([
			'success' => true,
			'message' => 'Posts carregados com sucesso',
			'posts'   => $publish
		]);
	}

	public static function loadMeta($id, $meta, $single = false)
	{
		return get_post_meta($id, $meta, $single);
	}

	public static function notFound()
	{
		return Response::json([
			'failed'  => true,
			'message' => 'Nada encontrado'
		], 404);
	}

	public static function getPostMeta($item)
	{
		return explode(':', $item);
	}

}