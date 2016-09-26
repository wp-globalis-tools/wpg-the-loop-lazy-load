<?php
/**
 * Remember plugin path & URL
 */
define( 'LL_PATH', plugin_basename( realpath( dirname( __FILE__ )) ) );
define( 'LL_COMPLETE_PATH', WP_PLUGIN_DIR.'/'.LL_PATH );
define( 'LL_URL', WP_PLUGIN_URL.'/'.LL_PATH );

define( 'LL_ASSETS_PATH', WP_PLUGIN_URL.'/'.LL_PATH.'/assets' );
define( 'LL_JS_PATH', LL_ASSETS_PATH.'/js' );

/*
 * Params DEFAULT
 */
// Default container class
define( 'DEFAULT_LAZYLOAD_CONTAINER_CLASS', '.js-load-more' );

// Default trigger offset : auto | <pixel>
define( 'DEFAULT_LAZYLOAD_TRIGGER_OFFSET', 'auto' );
// define( 'DEFAULT_LAZYLOAD_TRIGGER_OFFSET', 400 );

// Default percentage for auto trigger offset. Only use if DEFAULT_LAZYLOAD_TRIGGER_OFFSET == auto
define( 'DEFAULT_LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT', 40 );

// Default query vars for lazyload
define( 'DEFAULT_LAZYLOAD_QUERY_VARS', false );
// define( 'DEFAULT_LAZYLOAD_QUERY_VARS', ['posts_per_page' => 3, ...] );
