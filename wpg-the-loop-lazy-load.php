<?php
/*
Plugin Name:  WPG Lazy Load
Description:  Load more with main query.
Version:      1.0.0
Author:       David Daugreilh, Pierre Dargham
*/
namespace WPG\LazyLoad;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
};

// Load configuration
require_once realpath( dirname( __FILE__ ) ) . '/config.php';

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\ajax_args', 110 );
add_action( 'wp_ajax_get_ajax_content', __NAMESPACE__ . '\\get_ajax_content' );
add_action( 'wp_ajax_nopriv_get_ajax_content', __NAMESPACE__ . '\\get_ajax_content' );

// add_filter('lazyload_query_vars', __NAMESPACE__ . '\\test_filter', 10, 2);

// function test_filter($query_vars, $args){
// 	$query_vars['posts_per_page'] = 1;
// 	return $query_vars;
// }

function ajax_args($custom_query = false) {
	wp_register_script( 'lazy-load', LL_JS_PATH.'/lazy-load.js', 'jquery', '1.0' );
	wp_enqueue_script( 'lazy-load' );

	global $wp, $wp_query;
	$queryVars = [];

	/*
	 * Custom query vars to lazyload content
	 */
	if(defined('DEFAULT_LAZYLOAD_QUERY_VARS') && DEFAULT_LAZYLOAD_QUERY_VARS && is_array(DEFAULT_LAZYLOAD_QUERY_VARS)){
		// $wp_query->query_vars = array_merge($wp_query->query_vars, DEFAULT_LAZYLOAD_QUERY_VARS);
		$queryVars = array_merge($queryVars, DEFAULT_LAZYLOAD_QUERY_VARS);
	}
	if(defined('LAZYLOAD_QUERY_VARS') && LAZYLOAD_QUERY_VARS && is_array(LAZYLOAD_QUERY_VARS)){
		// $wp_query->query_vars = array_merge($wp_query->query_vars, LAZYLOAD_QUERY_VARS);
		$queryVars = array_merge($queryVars, LAZYLOAD_QUERY_VARS);
	}

	$queryVars = array_merge($queryVars, apply_filters('lazyload_query_vars', $queryVars, $wp_query->query_vars));
	$wp_query->query_vars = array_merge($wp_query->query_vars, $queryVars);

	/*
	 * Trigger mode
	 */
	// $trigger_mode = apply_filters('lazyload_trigger_mode', (defined('LAZYLOAD_TRIGGER_MODE') && LAZYLOAD_TRIGGER_MODE ? LAZYLOAD_TRIGGER_MODE : DEFAULT_LAZYLOAD_TRIGGER_MODE), $wp_query);
	
	// if($custom_queries = apply_filters('lazyload_register_queries', false, $wp_query)){
	// 	foreach ($custom_queries as $query_id => $query) {
	// 		$queries[$query_id] = $query;
	// 	}
	// 	$queries_data = array_map(__NAMESPACE__ . '\\get_query_data', $queries);
	// }
	
	if(isset($queryVars['posts_per_page'])){
		$wp_query->max_num_pages = ceil($wp_query->found_posts/$queryVars['posts_per_page']);
	}

	if($custom_query){
		$queries[key($custom_query)] = current($custom_query);
		$queries_data = array_map(__NAMESPACE__ . '\\get_query_data', $queries);
	}
	else{
		$queries_data = ['maxPages' => $wp_query->max_num_pages, 'queryVars' => $queryVars];
	}

	foreach ($queries_data['queryVars'] as $key => $value) {
		$queries_data['queryVars']['option_'.$key] = $value;
		unset($queries_data['queryVars'][$key]);
	}

	wp_localize_script(
	  	'lazy-load',
	  	'lazy_load',
	  	array(
	    	'url'       		=> admin_url( 'admin-ajax.php' ),
	    	'queries' 			=> $queries_data,
	    	'containerClass' 	=> (defined('LAZYLOAD_CONTAINER_CLASS') && !empty(LAZYLOAD_CONTAINER_CLASS) ? LAZYLOAD_CONTAINER_CLASS : DEFAULT_LAZYLOAD_CONTAINER_CLASS),
	    	'triggerOffset' 	=> (defined('LAZYLOAD_TRIGGER_OFFSET') && !empty(LAZYLOAD_TRIGGER_OFFSET) ? LAZYLOAD_TRIGGER_OFFSET : DEFAULT_LAZYLOAD_TRIGGER_OFFSET),
	    	'triggerOffsetAuto'	=> (defined('LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT') && !empty(LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT) ? LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT : DEFAULT_LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT),
	  	)
	);
}

function get_ajax_content() {
	setup_pre_option_get();
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

function get_query_data($query) {
	return ['maxPages' => $query->max_num_pages, 'queryVars' => $query->query_vars];
}

function setup_pre_option_get($queryVars){
	if(!isset($_GET) || empty($_GET)){
		return;
	}

	foreach($_GET as $key => $val){
		if(substr($key,0,7) === 'option_'){
			add_filter('pre_'.$key, __NAMESPACE__ . '\\init_pre_option');
		}
	}	
}

function init_pre_option($default_option) {
	if(!empty($_GET[substr(current_filter(), 4)])) {
		return $_GET[substr(current_filter(), 4)];
	} else {
		return $default_option;
	}
}

function register_query($query, $id) {
	global $wp_query, $wp_query_backup;
	$wp_query_backup = $wp_query;
	$wp_query = $query;
	ajax_args([$id => $query]);
}

function reset_wp_query() {
	global $wp_query, $wp_query_backup;
	$wp_query = $wp_query_backup;
}
