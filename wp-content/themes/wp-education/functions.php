<?php

/*------------------------------------------------------------------------------------------
	Theme's function.php file, here remain all important configurations theme based.
------------------------------------------------------------------------------------------*/

# Main constants
define('VZ_THEME_PATH', get_template_directory_uri());
define('VZ_THEME_NAME', 'WP-Education');
define('VZ_THEME_PREFIX', 'wpe');
define('VZ_THEMEOPTIONS_INC', VZ_THEME_PATH.'/admin/includes');
define('VZ_THEMEOPTIONS_COPYRIGHT', '<span class="by"> Vuzzu </span>');

# Including Front End files
include_once('includes/main/function_register.php');  				## Including register functions
include_once('includes/main/frontend_ajax.php');  					## Including frontend ajax
include_once('includes/main/custom_widgets.php');  					## Including custom widgets
include_once('includes/main/theme_parts.php');  					## Including theme parts
include_once('includes/main/shortcodes.php');  						## Including theme shortcodes

# Including Admin End files
include_once('admin/options/theme_options.php');					## Including theme_options
include_once('admin/options/theme_options_ajax.php');				## Including theme_options_ajax
include_once('admin/options/theme_options_functions.php');  		## Including theme_options_functions
include_once('admin/options/theme_options_pages.php');				## Including theme_options_pages
include_once('admin/options/theme_options_pages_generator.php');	## Including theme_options_pages_generator

# Including plugins
include_once('includes/plugins/events.php');						## Including events post type
include_once('includes/plugins/courses.php');						## Including courses post type
include_once('includes/plugins/galleries.php');						## Including gallery post type
include_once('includes/plugins/subscribers.php');					## Including subscribers plugin
include_once('includes/plugins/calendars.php');						## Including calendars plugin
include_once('includes/plugins/forms.php');							## Including forms plugin
include_once('includes/plugins/staff.php');							## Including staff plugin
include_once('includes/plugins/books.php');							## Including books plugin