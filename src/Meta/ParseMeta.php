<?php 

namespace Ziriga\Meta;

class ParseMeta
{
	protected $metas;

	public function __construct($metas)
	{		
		$this->metas = (string) $metas;
	}

	/**
	 * list metas sended by url
	 *
	 * @return array
	 */
	public function getMetas()
	{
		$postmeta = [];
			
		if (!is_string($this->metas) || empty($this->metas)) return $postmeta;

		foreach ( explode(';', $this->metas) as $meta )  {
			$postmeta = array_merge($postmeta, $this->parseMeta($meta));
		}

		return $postmeta;
	}


	/**
	 * extract options of attribute
	 *
	 * @return array
	 */
	public function parseMeta($meta)
	{
		$list 		= explode('|', $meta);
		$name 		= $list[0];
		$attributes = [];

		unset($list[0]);	

		if (!empty($list)) {

			foreach ( $list as $item ) {

				if (!is_string($item)) continue;

				$metas 				   = explode(':', $item);
				$attributes[$metas[0]] = empty($metas[1]) ? null : $metas[1];
			}

		}

		return [
			$name => $attributes
		];
	}
}