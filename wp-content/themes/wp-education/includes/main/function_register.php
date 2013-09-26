<?php

/****************************************
* Register functions
****************************************/


# Setting content width
if ( ! isset( $content_width ) ) $content_width = 940;

# Automated feed links
add_theme_support( 'automatic-feed-links' );
if ( is_singular() ) wp_enqueue_script( "comment-reply" );



/****************************************
* Loading default trasnlation files
****************************************/
add_action('after_setup_theme', 'vz_front_terms_translation');
function vz_front_terms_translation(){
	load_theme_textdomain('vz_front_terms', get_template_directory().'/languages/');
}



/****************************************
* Setting current user role
****************************************/
if( is_user_logged_in() ) :
	global $current_user_role;
	$c_user = new WP_User( get_current_user_id( ) ); 
	$current_user_role = $c_user->roles[0];
endif;



/*-----------------------------------------------------------------------------------*/
/*	Register Javascripts
/*-----------------------------------------------------------------------------------*/
add_action('wp_enqueue_scripts', 'register_wpe_js');
function register_wpe_js() {
	global $current_user_role;

	wp_register_script('wp-education', 			VZ_THEME_PATH."/includes/js/wp_education.js", false, "1.0",true);   
	wp_register_script('wpu-education', 		VZ_THEME_PATH."/includes/js/wpu_education.js", false, "1.0",true);   
	wp_register_script('jquery-flexslider', 	VZ_THEME_PATH."/includes/js/jquery.flexslider.js", false, "2.1",true);   
	wp_register_script('jquery-hover-intent', 	VZ_THEME_PATH."/includes/js/jquery.hover_intent.js", false, "1.0",true);   
	wp_register_script('jquery-superfish', 		VZ_THEME_PATH."/includes/js/jquery.superfish.js", false, "1.5.1",true);   
	wp_register_script('jquery-lightbox', 		VZ_THEME_PATH."/includes/js/jquery.lightbox.js", false, "2.51",true); 
	wp_register_script('jquery-ui-timepicker',  VZ_THEME_PATH.'/includes/js/jquery-ui.timepicker.js',false,"1.2",true);  
    wp_register_script('jquery-selectbox', 		VZ_THEME_PATH.'/includes/js/jquery.selectbox.js',false,"0.2",true);
    wp_register_script('jquery-ticker', 		VZ_THEME_PATH.'/includes/js/jquery.ticker.js',false,"1.0",true);
    wp_register_script('jquery-books', 			VZ_THEME_PATH.'/includes/js/jquery.books.js',false,"1.0",true);
    wp_register_script('jquery-mixitup', 		VZ_THEME_PATH.'/includes/js/jquery.mixitup.js',false,"1.5.4",true);

	wp_enqueue_script('jquery','/wp-includes/js/jquery/jquery.js','','1.8.3',true);
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-timepicker');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-flexslider');
	wp_enqueue_script('jquery-selectbox');
	wp_enqueue_script('jquery-ticker');
	wp_enqueue_script('jquery-hover-intent');
	wp_enqueue_script('jquery-superfish');
	wp_enqueue_script('jquery-lightbox');
	wp_enqueue_script('wp-education');
	if( is_page_template('template-book.php') || is_page_template('template-books.php') ) wp_enqueue_script('jquery-books');
	if( is_page_template('template-books.php') ) wp_enqueue_script('jquery-mixitup');

	//This variable holds the string which is passed to ajax file
	$ajaxaction = (is_user_logged_in()) ? 'vz_ufrontajax' : 'vz_frontajax';

	//Javascript settings
	$vz_js_settings = array( 
		'ajaxurl' => admin_url('admin-ajax.php'),
		'wpe_dir' => VZ_THEME_PATH,
		'ajaxaction' => $ajaxaction,
		'next_slide_sec' => get_option('vz_options_home_next_slide',7).'000'
	);

	wp_localize_script( 'wp-education', 'vz_vj_settings', $vz_js_settings );

	if( $current_user_role && ( $current_user_role == 'student' || $current_user_role == 'professor' ) ) {
		wp_enqueue_script('wpu-education');
	}

}





/*-----------------------------------------------------------------------------------*/
/*	Register Cascading style sheets
/*-----------------------------------------------------------------------------------*/
add_action('wp_enqueue_scripts', 'register_wpe_css');
function register_wpe_css() {
    wp_register_style('grid-text', 	VZ_THEME_PATH.'/includes/css/grid/text.css', false, "1.0", "all");
    wp_register_style('grid-reset',	VZ_THEME_PATH.'/includes/css/grid/reset.css', false, "1.0", "all");
    wp_register_style('grid-960',  	VZ_THEME_PATH.'/includes/css/grid/960.css', false, "1.0", "all");
    wp_register_style('responsive', VZ_THEME_PATH.'/includes/css/responsive.css', false, "1.0", "all");
    wp_register_style('preset-css', VZ_THEME_PATH.'/includes/css/skins/'.get_option('vz_styling_presets_actual','red').'.css', false, "1.0", "all");
    wp_register_style('flexslider', VZ_THEME_PATH.'/includes/css/flexslider.css', false, "2.0", "all");
    wp_register_style('superfish', 	VZ_THEME_PATH.'/includes/css/superfish.css', false, "1.0", "all");
    wp_register_style('lightbox',  	VZ_THEME_PATH.'/includes/css/lightbox.css', false, "1.0", "all");
    wp_register_style('jquery-ui', 	VZ_THEME_PATH.'/includes/css/jqueryui.css', false, "1.0", "all");
    wp_register_style('wpe-style', 	VZ_THEME_PATH.'/style.css', false, "1.0", "all");



    wp_enqueue_style('grid-text');
    wp_enqueue_style('grid-reset');
    wp_enqueue_style('grid-960');
    wp_enqueue_style('wpe-style');
    wp_enqueue_style('flexslider');
    wp_enqueue_style('superfish');
    wp_enqueue_style('lightbox');
    wp_enqueue_style('jquery-ui');
    wp_enqueue_style('preset-css');
    wp_enqueue_style('responsive');
}



/*-----------------------------------------------------------------------------------*/
/*	Loading style customization
/*-----------------------------------------------------------------------------------*/
add_action('wp_head', 'load_custom_styling');
function load_custom_styling() {
    ## If advanced styling is not enabled we go through simple styling otherwise advanced styling is included
	if( !get_option('vz_styling_main_advanced_enabled') ) :
		include('style.php');
	else :
		include('style_advanced.php');
	endif;
}



/*-----------------------------------------------------------------------------------*/
/*	Register WP3.0+ Menus
/*-----------------------------------------------------------------------------------*/
add_action('init', 'register_menus');
function register_menus() {

	if(function_exists('add_theme_support')) {

		add_theme_support('menus');
		register_nav_menus(array('main_nav' =>'Main navigation Menu', 
								 'top_nav' => 'Top navigation Menu',
								 'footer_nav' => 'Footer navigation Menu'));
	
	}

}



/*-----------------------------------------------------------------------------------*/
/*	Configure WP2.9+ Thumbnails
/*-----------------------------------------------------------------------------------*/
add_action('init', 'configure_thumbnails');
function configure_thumbnails() {
	
	if(function_exists('add_theme_support')) {
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size( 71, 71, true ); 				// Normal post thumbnails
		add_image_size( 'small', 125, '', true ); 				// Small thumbnails
		add_image_size( 'featured', 128, 135, true ); 			// Featured post thumbnail
		add_image_size( 'medium', 250, '', true ); 				// Medium thumbnails
		add_image_size( 'large', 680, '', true ); 				// Large thumbnails
	}

}



/*-----------------------------------------------------------------------------------*/
/*	Registering sidebars
/*-----------------------------------------------------------------------------------*/
if (function_exists('register_sidebar')){	
	
	$args = array(
		'name' => '',
		'id'   => '',
		'before_widget' => '<div class="widget rounded_2 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h1>',
		'after_title' => '</h1>'
	);

	$default_sidebars = array('home','home2-center','blog','page','post','404');

	foreach ($default_sidebars as $sidebar_name) {
		$args['name'] = ucfirst($sidebar_name);
		$args['id']   = $sidebar_name;
		register_sidebar($args);
	}

	register_sidebar(array(
		'name' => 'Home-bottom',
		'id'   => 'home-bottom',
		'before_widget' => '<div class="home-bottom widget grid_4 rounded_2 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h1>',
		'after_title' => '</h1>'
	));

	$footer_sidebars = array('footer1','footer2','footer3','footer4');

	foreach ($footer_sidebars as $sidebar_name) {
		$args['name'] = ucfirst($sidebar_name);
		$args['id']   = $sidebar_name;

		register_sidebar($args);
	}



}



/*-----------------------------------------------------------------------------------*/
/*	Setting default avatar for users who don't have an avatar
/*-----------------------------------------------------------------------------------*/
add_filter( 'avatar_defaults', 'vz_custom_gravatar' );
function vz_custom_gravatar ($avatar) {
	$custom_avatar = VZ_THEME_PATH.'/includes/images/no_avatar.png';
	$avatar[$custom_avatar] = 'WPE-User'; //change this name to your preferences
	return $avatar;
}



/*-----------------------------------------------------------------------------------*/
/*	Thumbnail sizes
/*-----------------------------------------------------------------------------------*/
$thumb_sizes['vz_gallery_mini'] 	= array( 'width' => 55,  'height' => 55 );
$thumb_sizes['vz_gallery_album'] 	= array( 'width' => 300, 'height' => 155 );
$thumb_sizes['vz_gallery_thumb']	= array( 'width' => 140, 'height' => 73 );
$thumb_sizes['vz_gallery_zoom'] 	= array( 'width' => 900, 'height' => 600 );
$thumb_sizes['vz_widget_popular'] 	= array( 'width' => 55,  'height' => 36 );
$thumb_sizes['vz_blog_wall'] 		= array( 'width' => 115, 'height' => 75 );
$thumb_sizes['vz_block_blog'] 		= array( 'width' => 300, 'height' => 135 );
$thumb_sizes['vz_block_bloghead'] 	= array( 'width' => 620, 'height' => 279 );
$thumb_sizes['vz_about_head'] 		= array( 'width' => 940, 'height' => 200 );
$thumb_sizes['vz_about_staff'] 		= array( 'width' => 260, 'height' => 194 );
$thumb_sizes['vz_home_slider']		= array( 'width' => 630, 'height' => get_option('vz_options_home_slider_height',330) );
$thumb_sizes['vz_home_slider_wide']	= array( 'width' => 940, 'height' => get_option('vz_options_home_slider_height',330) );



/*-----------------------------------------------------------------------------------*/
/*	VZ resize thumb generator
/*-----------------------------------------------------------------------------------*/
function vz_resize($thumb_id,$thumb_size,$crop=true) {

    global $thumb_sizes;
	$fly_width = $thumb_sizes[$thumb_size]['width'];
	$fly_height = $thumb_sizes[$thumb_size]['height'];

	$real_img = wp_get_attachment_image_src( $thumb_id, 'full' );

    $upload_dir = wp_upload_dir();
 
    $filename  = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $real_img[0] );
    $pathinfo  = pathinfo( $filename );
    $dirname   = $pathinfo['dirname'];
    $extension = $pathinfo['extension'];
 
    $imgname = wp_basename( $filename, '.' . $extension );
 
    $suffix    = $fly_width . 'x' . $fly_height;
    $the_img   = $dirname . '/' . $imgname . '-' . $suffix . '.' . $extension; 
    $the_url  = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $the_img );

    //If file exists we return the file url, else we create and return the file url
    if ( !file_exists( $the_img ) ) {
        
        $image = wp_get_image_editor( $filename );
 
        if ( ! is_wp_error( $image ) ) {
            $image->resize( $fly_width, $fly_height, true );
            $image->save( $the_img );
        }

    }
 
    return $the_url;
}



/*-----------------------------------------------------------------------------------*/
/*	Devault custom avatar for users without avatar
/*-----------------------------------------------------------------------------------*/
add_filter('get_avatar','fix_avatar_css');
function fix_avatar_css($class) {
	$class = str_replace("class='avatar", "class='alignleft", $class) ;
	return $class;
}



/*-----------------------------------------------------------------------------------*/
/*	Disabling admin bar on frontend for non-admin users
/*-----------------------------------------------------------------------------------*/
if( !current_user_can('administrator') ):
	show_admin_bar(false);
endif;



/*-----------------------------------------------------------------------------------*/
/*	Repairing contact methods on user profile page
/*-----------------------------------------------------------------------------------*/
add_filter( 'user_contactmethods', 'update_contact_methods',10,1);
function update_contact_methods( $contactmethods ) {

	unset($contactmethods['aim']);  
	unset($contactmethods['jabber']);  
	unset($contactmethods['yim']);  
	
	$contactmethods['twitter'] = 'Twitter Profile link';
	$contactmethods['linkedin'] = 'LinkedIn Profile link';

	return $contactmethods;
}



/*-----------------------------------------------------------------------------------*/
/*	Managing custom roles
/*-----------------------------------------------------------------------------------*/
add_action('init', 'vz_custom_roles');
function vz_custom_roles( ) {

	$author = get_role('author');
	$caps 	= $author->capabilities;

	// Add the new role.
	add_role('student', __('Student','vz_front_terms'), $caps);
	add_role('professor', __('Professor','vz_front_terms'), $caps);
}



/*-----------------------------------------------------------------------------------*/
/*	Get week start date and end date
/*-----------------------------------------------------------------------------------*/
function weekDates($week, $year) {

	$dates = array();

	$dates[1] = date("j F Y ", strtotime("{$year}-W{$week}-1"));
	$dates[2] = date("j F Y ", strtotime("{$year}-W{$week}-2"));
	$dates[3] = date("j F Y ", strtotime("{$year}-W{$week}-3"));
	$dates[4] = date("j F Y ", strtotime("{$year}-W{$week}-4"));
	$dates[5] = date("j F Y ", strtotime("{$year}-W{$week}-5"));
	$dates[6] = date("j F Y ", strtotime("{$year}-W{$week}-6"));

    return $dates;

}



/*-----------------------------------------------------------------------------------*/
/*	Hiding others posts from admin panel except the logged user's
/*-----------------------------------------------------------------------------------*/
add_filter('pre_get_posts', 'posts_for_current_author');
function posts_for_current_author($query) {
	global $pagenow;

	if( ($pagenow == 'edit.php' || $pagenow == 'upload.php') && (current_user_can( 'professor' ) || current_user_can( 'student' )) ) {
		global $user_ID;
		$query->set('author', $user_ID );
	}

	return $query;
}



/*-----------------------------------------------------------------------------------*/
/*	Styling comment form
/*-----------------------------------------------------------------------------------*/

add_action( 'comment_form_before', 'begin_comment_form' );
function begin_comment_form() {
	echo '<div class="new_comment alignright grid_8 rounded_2">';
}

add_action( 'comment_form_after', 'end_comment_form' );
function end_comment_form() {
	echo '</div>';
}



/*-----------------------------------------------------------------------------------*/
/*	Counting views to post
/*-----------------------------------------------------------------------------------*/
add_action('wp_enqueue_scripts','vz_count_view');
function vz_count_view() {

	if( is_single() ) :

		global $wp_query;

		$post = $wp_query->post;

		$count_key = 'post_views_count';
		$count = get_post_meta($post->ID, $count_key, true);
		if($count==''){
		    $count = 0;
		    delete_post_meta($post->ID, $count_key);
		    add_post_meta($post->ID, $count_key, '0');
		}else{
		    $count++;
		    update_post_meta($post->ID, $count_key, $count);
		}

	endif;
}



/*-----------------------------------------------------------------------------------*/
/*	Fetch tweets
/*-----------------------------------------------------------------------------------*/
function fetch_tweets($user,$count) {
	$token = '1179135854-wvBaJr9ptIo85Vh64yijYMrbWhXwXrUBWxxqk5g';
	$token_secret = 'FGjrc2jaCZ3G6uTCyakIX6AHYLlNobtzCkufPm7uw';
	$consumer_key = 'XvtHL1Fu2fKsMzhodIWhTg';
	$consumer_secret = 'Huf8EnHH6blOrou2lrIBseiww2f5P4l6p0fRhOQywM';

	$host = 'api.twitter.com';
	$method = 'GET';
	$path = '/1.1/statuses/user_timeline.json'; // api call path

	$query = array( // query parameters
	    'screen_name' => $user,
	    'count' => $count
	);

	$oauth = array(
	    'oauth_consumer_key' => $consumer_key,
	    'oauth_token' => $token,
	    'oauth_nonce' => (string)mt_rand(), // a stronger nonce is recommended
	    'oauth_timestamp' => time(),
	    'oauth_signature_method' => 'HMAC-SHA1',
	    'oauth_version' => '1.0'
	);

	$oauth = array_map("rawurlencode", $oauth); // must be encoded before sorting
	$query = array_map("rawurlencode", $query);

	$arr = array_merge($oauth, $query); // combine the values THEN sort

	asort($arr); // secondary sort (value)
	ksort($arr); // primary sort (key)

	// http_build_query automatically encodes, but our parameters
	// are already encoded, and must be by this point, so we undo
	// the encoding step
	$querystring = urldecode(http_build_query($arr, '', '&'));

	$url = "https://$host$path";

	// mash everything together for the text to hash
	$base_string = $method."&".rawurlencode($url)."&".rawurlencode($querystring);

	// same with the key
	$key = rawurlencode($consumer_secret)."&".rawurlencode($token_secret);

	// generate the hash
	$signature = rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $key, true)));

	// this time we're using a normal GET query, and we're only encoding the query params
	// (without the oauth params)
	$url .= "?".http_build_query($query);
	$url=str_replace("&amp;","&",$url); //Patch by @Frewuill

	$oauth['oauth_signature'] = $signature; // don't want to abandon all that work!
	ksort($oauth); // probably not necessary, but twitter's demo does it

	// also not necessary, but twitter's demo does this too
	function add_quotes($str) { return '"'.$str.'"'; }
	$oauth = array_map("add_quotes", $oauth);

	// this is the full value of the Authorization line
	$auth = "OAuth " . urldecode(http_build_query($oauth, '', ', '));

	// if you're doing post, you need to skip the GET building above
	// and instead supply query parameters to CURLOPT_POSTFIELDS
	$options = array( CURLOPT_HTTPHEADER => array("Authorization: $auth"),
	                  //CURLOPT_POSTFIELDS => $postfields,
	                  CURLOPT_HEADER => false,
	                  CURLOPT_URL => $url,
	                  CURLOPT_RETURNTRANSFER => true,
	                  CURLOPT_SSL_VERIFYPEER => false);

	// do our business
	$feed = curl_init();
	curl_setopt_array($feed, $options);
	$json = curl_exec($feed);
	curl_close($feed);

	return json_decode($json);
}

// Formating bytes
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 