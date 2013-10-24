<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="ltr">

<!-- Vuzzu theme -->

<head>

	<!-- Meta tags -->
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Title -->
	<title><?php bloginfo('name'); ?>  <?php wp_title(); ?></title>

	<!-- Pingbacks -->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<?php if( get_option('vz_options_look_fav_enabled') ) : $fav_ico = get_option('vz_options_look_favicon'); ?>
	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo( is_numeric($fav_ico) ? wp_get_attachment_url($fav_ico) : $fav_ico ); ?>" />
	<?php endif; ?>

	<!-- Starting wp_head -->
	<?php wp_head(); ?>

<!-- analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-39892578-3', 'consulado-ecuador.es');
  ga('send', 'pageview');

</script>

	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

</head>

<body <?php body_class(); ?>>

	<header>

		<div class="header">

			<?php $_grid_size = 2;
			$_hide_social = FALSE; $_hide_topmenu = FALSE; $_hide_logo = FALSE;
			if( get_option('vz_options_arch_header_social_disabled') ) { $_grid_size--; $_hide_social = TRUE; }
			if( get_option('vz_options_arch_header_topmenu_disabled') ) { $_grid_size--;$_hide_topmenu= TRUE; }
			if( get_option('vz_options_arch_header_logo_disabled') ) { $_hide_logo = TRUE; }
			echo ( ($_hide_social && $_hide_topmenu) || $_hide_logo ) ? '' : '<hr/>'; ?>

			<div class="head_content container_12">

				<?php $_grid_size = ( $_grid_size > 0 ) ? 'grid_'.(12/$_grid_size) : '';

				$_head_order = explode(',', get_option('vz_options_arch_header','social,topmenu') );

				foreach ($_head_order as $key => $show_part) {
					switch ($show_part) {
						case 'social':	if(!$_hide_social) vz_show_social($_grid_size);   break;
						case 'topmenu':	
							$tm_class = ($key==0) ? 'alignleft' : 'alignright';
							if(!$_hide_topmenu) vz_show_topmenu($_grid_size,$tm_class);  
						break;
					}
				}

				echo '<div class="clear"></div>';

				if( !$_hide_logo ) vz_show_logo(); ?>

				<!-- TOPNAV MOBILE BEGIN -->
				<?php $locs = get_nav_menu_locations(); $mmenu = wp_get_nav_menu_object( $locs['top_nav'] );
			 	$mmenu_items = ($mmenu) ? wp_get_nav_menu_items($mmenu->term_id) : null; ?>
			
				<div class="topmenu_mobile"> <?php _e('Navigation','vz_front_terms'); ?>

					<select style="display:none" class="topmenu_mobile" onChange="window.location.href = ''+ $v(this).val() + '';">

						<option> - <?php _e('Select','vz_front_terms'); ?> - </option>
						<?php if($mmenu_items) : foreach ( $mmenu_items as $mkey => $mmenu_item ) : ?>
							<option value="<?php echo $mmenu_item->url; ?>"> <?php echo $mmenu_item->title; ?> </option>
						<?php endforeach; endif; ?>

					</select>

				</div>
				<!-- TOPNAV MOBILE END -->

				<!-- HEADER MAIN NAVIGATION BEGIN -->
				<nav>

					<div class="clear"></div>

					<a href="#" class="mainmenu_mobile" style="display:none" onclick="$v(this).next().slideToggle();return false;"> 
						<span> <?php _e('Main navigation','vz_front_terms'); ?> </span>
						<span class="alignright"> </span>
					</a>

					<div class="main_nav grid_12" id="main_menu">

						<?php wp_nav_menu( array( 'theme_location' => 'main_nav', 'container' => false, 'items_wrap' => '<ul class="sf-menu" id="main_menu">%3$s</ul>', 'menu_class' => '', 'menu_id' => '', 'depth' => '3', 'fallback_cb' => false ) ); ?>

						<form method="get" role="search" id="searchform" action="<?php echo home_url('/'); ?>">

							<input type="text" name="s" id="s" class="search" value="<?php echo(strlen( get_search_query() )>0) ? get_search_query() : ''; ?>" placeholder="<?php _e('search now...','vz_front_terms'); ?>" />

						</form>

						<div class="clear"></div>

					</div>

				</nav>
				<!-- HEADER MAIN NAVIGATION END -->

			</div>



		</div>

	</header>

	<!-- MOBILE SEARCH -->
	<form method="get" role="search" id="searchform" class="mobile_search" action="<?php echo home_url('/'); ?>" style="display:none">

		<input type="text" name="s" id="s" class="rounded_1" value="<?php echo(strlen( get_search_query() )>0) ? get_search_query() : ''; ?>" placeholder="<?php _e('search now...','vz_front_terms'); ?>" />

	</form>
	<!-- MOBILE SEARCH END -->

<?php if(isset($_GET['unsubscribe'])) :

	$email = wp_kses_post($_GET['unsubscribe']);

    $checkemail = $wpdb->get_row("SELECT ID FROM wp_posts WHERE MD5(post_title) = '{$email}' AND post_status='private' AND post_type='vz_custom_newsletter'", 'ARRAY_N');

    if($checkemail) : 
    	$wpdb->query(" DELETE FROM wp_posts WHERE  MD5(post_title) = '{$email}' AND post_status='private' AND post_type='vz_custom_newsletter' "); ?>
    	<div class="container_12"> <div class="grid_12">
	    	<div class="shortcode rounded_2 message type_1" style="margin-bottom:0;margin-top:15px">
				<span class="alignleft"> <?php _e('Success','vz_front_terms'); ?> </span>
				<p class="alignleft"> <?php _e('You have unsubscribed successfully.','vz_front_terms'); ?> </p>
				<a class="close alignright" href="#" onclick="$v(this).parent().fadeOut('normal');return false"> x </a>
				<div class="clear"></div>
			</div>
		</div></div>
   	<?php endif;

endif; ?>
