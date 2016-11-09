<?php
/*
Plugin Name:  WPG Lazy Load
Description:  Load more with main query.
Version:      1.0.0
Author:       David Daugreilh, Pierre Dargham
*/
namespace Globalis\LazyLoad;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
};

// Load configuration
require_once realpath( dirname( __FILE__ ) ) . '/config.php';

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\ajax_args', 110 );
add_action( 'wp_ajax_get_ajax_content', __NAMESPACE__ . '\\get_ajax_content' );
add_action( 'wp_ajax_nopriv_get_ajax_content', __NAMESPACE__ . '\\get_ajax_content' );

function ajax_args() {
	wp_register_script( 'lazy-load', LL_JS_PATH.'/lazy-load.js', 'jquery', '1.0' );
	wp_enqueue_script( 'lazy-load' );

	global $wp, $wp_query;

	/*
	 * Custom query vars to lazyload content
	 */
	if(defined('DEFAULT_LAZYLOAD_QUERY_VARS') && DEFAULT_LAZYLOAD_QUERY_VARS && is_array(DEFAULT_LAZYLOAD_QUERY_VARS)){
		$wp_query->query_vars = array_merge($wp_query->query_vars, DEFAULT_LAZYLOAD_QUERY_VARS);
	}
	if(defined('LAZYLOAD_QUERY_VARS') && LAZYLOAD_QUERY_VARS && is_array(LAZYLOAD_QUERY_VARS)){
		$wp_query->query_vars = array_merge($wp_query->query_vars, LAZYLOAD_QUERY_VARS);
	}

	$wp_query->query_vars = apply_filters('lazyload_query_vars', $wp_query->query_vars);

	/*
	 * Trigger mode
	 */
	// $trigger_mode = apply_filters('lazyload_trigger_mode', (defined('LAZYLOAD_TRIGGER_MODE') && LAZYLOAD_TRIGGER_MODE ? LAZYLOAD_TRIGGER_MODE : DEFAULT_LAZYLOAD_TRIGGER_MODE), $wp_query);


	wp_localize_script(
	  	'lazy-load',
	  	'lazy_load',
	  	array(
	    	'url'       		=> admin_url( 'admin-ajax.php' ),
	    	'queryVars' 		=> $wp_query->query_vars,
	    	'maxPages' 			=> $wp_query->max_num_pages,
	    	'containerClass' 	=> (defined('LAZYLOAD_CONTAINER_CLASS') && !empty(LAZYLOAD_CONTAINER_CLASS) ? LAZYLOAD_CONTAINER_CLASS : DEFAULT_LAZYLOAD_CONTAINER_CLASS),
	    	'triggerOffset' 	=> (defined('LAZYLOAD_TRIGGER_OFFSET') && !empty(LAZYLOAD_TRIGGER_OFFSET) ? LAZYLOAD_TRIGGER_OFFSET : DEFAULT_LAZYLOAD_TRIGGER_OFFSET),
	    	'triggerOffsetAuto'	=> (defined('LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT') && !empty(LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT) ? LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT : DEFAULT_LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT),
	  	)
	);
}

function get_ajax_content() {
	add_filter('pre_option_posts_per_page', __NAMESPACE__ . '\\init_posts_per_page');
	$template = $_GET['template'];
	wp();
	ob_start();
	get_template_part($template);
	$output = ob_get_clean();
	wp_send_json( $output );
}

function init_posts_per_page($default_option) {
	if(isset($_GET['posts_per_page']) && !empty($_GET['posts_per_page'])) {
		return $_GET['posts_per_page'];
	} else {
		return $default_option;
	}
}
