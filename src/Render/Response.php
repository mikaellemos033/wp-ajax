<?php 

namespace Ziriga\Render;

class Response
{
	public static function json(array $params, $code = 200)
	{
		return die(static::render(json_encode($params), $code));
	}

	public static function render($html, $code = 200)
	{
		http_response_code($code);
		return print($html);
	}
}