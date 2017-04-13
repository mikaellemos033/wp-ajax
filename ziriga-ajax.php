<?php
/**
 * Plugin Name:Ziriga Ajax 
 * Plugin URI: https://github.com/mikaellemos033/wp-ajax
 * Description: Listagem de posts via ajax
 * Author: Agencia Ziriga
 * Version: 1.0
 * Author URI: http://ziriga.com.br
*/


define(ZIRIGA_PATH, __DIR__);
define(AJAX_ZIRIGA_URL, 'load-posts-json');

require ZIRIGA_PATH . '/vendor/autoload.php';

add_action('init', 'zirigaBootstrap');

function zirigaBootstrap(){

	$url = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
	$url = explode('/', $url);

	if (end($url) === AJAX_ZIRIGA_URL){
		Ziriga\Bootstrap::run();
	}
}
