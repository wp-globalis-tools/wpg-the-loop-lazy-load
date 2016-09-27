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


## Configuration

### Button :

 ```php
class			= "js-load-more" 
data-template 	= <PATH FROM YOUR THEME FOLDER> 
data-wrapper  	= <ID WRAPPER> 
```

### Scroll

 ```php
Add attributes on container : 
class			= "js-load-more" 
data-template 	= <PATH FROM YOUR THEME FOLDER> 
```
