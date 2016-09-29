# wpg-the-loop-lazy-load

Easy to load ajax content !

## Installation

You can install this plugin via the command-line or the WordPress admin panel.

### via Command-line

1. Download the [latest zip](https://github.com/wp-globalis-tools/wpg-the-loop-lazy-load/archive/master.zip) of this repo.
2. Add the folder in your plugins directory (wp-content/plugins)
3. Activate the plugin via [wp-cli](http://wp-cli.org/commands/plugin/activate/).

```sh
wp plugin activate wpg-the-loop-lazy-load
```

### via WordPress Admin Panel

1. Download the [latest zip](https://github.com/wp-globalis-tools/wpg-the-loop-lazy-load/archive/master.zip) of this repo.
2. In your WordPress admin panel, navigate to Plugins->Add New
3. Click Upload Plugin
4. Upload the zip file that you downloaded.


## How to use

### Button :

 ```php
class			= "js-load-more" 
data-template 	= <PATH FROM YOUR THEME FOLDER> 
data-wrapper  	= <ID WRAPPER> 
```

Exemple :

 ```php
<div id="wrapper">
	<?php get_template_part('template-parts/my-template'); ?>
</div>
<button data-template="template-parts/my-template" data-wrapper="wrapper" role="button" class="js-load-more">
	<?= __('Afficher plus d\'articles', 'asl-correspondants'); ?>
</button>
```

### Scroll

Add attributes on container : 
 ```php
class			= "js-load-more" 
data-template 	= <PATH FROM YOUR THEME FOLDER> 
```

Exemple : 

```php
<div class="js-load-more"  data-template="template-parts/my-template">
    <?php get_template_part('template-parts/my-template'); ?>
</div>
```

## Configuration

You can change few options in your config file

```php
// Default container class
define( 'LAZYLOAD_CONTAINER_CLASS', '.js-load-more' );

// Default trigger offset : auto | <pixel>
define( 'LAZYLOAD_TRIGGER_OFFSET', 'auto' );
// define( 'LAZYLOAD_TRIGGER_OFFSET', 400 );

// Default percentage for auto trigger offset. Only use if DEFAULT_LAZYLOAD_TRIGGER_OFFSET == auto
define( 'LAZYLOAD_TRIGGER_OFFSET_AUTO_PERCENT', 40 );

// Default query vars for lazyload
define( 'LAZYLOAD_QUERY_VARS', false );
// define( 'LAZYLOAD_QUERY_VARS', ['posts_per_page' => 3, ...] );
```