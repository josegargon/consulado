<?php

	# This file handles all customizations made to color styles from theme options.

	function print_vz_style_option($option_name,$css_selector) {

		if( get_option( $option_name ) ) $the_option = get_option( $option_name );

		echo get_option( $option_name ) ? " $css_selector { font-size : {$the_option}px !important; } " : '';
	}

?>

<style type="text/css">

	.main,.second { color: #fff !important; } .third { color: #565656 !important; }

	<?php if( get_option('vz_styling_main_main') ) : ?>
		.container a,
		.container .widget.tip a,
		.container .user_info .profile .info h1,
		.sidebar .widget a,
		.sidebar .widget.subscribe .description span,
		.sidebar .widget.side_nav ul li ul li a:hover,
		.sidebar .widget.side_nav ul li ul li a.active,
		.sidebar .widget.login span,
		.sidebar .widget.login div.user h1,
		.sidebar .widget.login .nav li a:hover,
		.sidebar .widget.subscribed_posts ul li a:hover,
		.sidebar .widget.filebox li.folder,
		.sidebar .widget.filebox li.file a:hover,
		.content .feed .post a:hover,
		.content .feed .event span.place span,
		.content .post .post-head .info h1,
		.content .post .post-head .social a:hover,
		.content .new_comment form .comment-form-author label,
		.content .new_comment form .comment-form-email label,
		.content .new_comment form .comment-form-url label,
		.content .new_comment form .comment-form-comment label,
		.content .comments .comment span.title strong,
		.content .gallery_folder .info span.counter,
		.content .back_albums span.counter,
		.post_block .block .info span,
		.table .crow .lecture p.lect,
		.table .crow .lecture p.edit,
		.content .wall .post a.title:hover,
		.post_block .block .block-content h1:hover,
		.content .gallery_folder a.album_name:hover,
		.content .apply form .form-content .field p,
		.footer_nav div > ul > li > ul > li > a,
		.content .featured .featured_post span,
		.modal form .details p,
		.ui-tabs .ui-tabs-nav li.ui-tabs-active a,
		.ui-tabs .ui-tabs-nav li.ui-tabs-active a:hover,
		.content .vzforms_ajax label,
		.container .widget.widget_calendar table th,
		.sidebar .widget.side_nav a:hover,
		.sidebar .widget.widget_categories a:hover,
		.container .widget.popular > div ul li a:hover,
		.content .staff .about a:hover,
		.sidebar .widget.hardcoded_contact .information label { color: <?php echo get_option('vz_styling_main_main'); ?> !important; }

		.header,
		.main,
		.widget.apply,
		.container .user_info,
		.container .user_info .profile a.view,
		.sidebar .widget.path,
		.block-pagination a:hover,
		.block-pagination a.active,
		.footer_columns,
		.ui-tabs .ui-tabs-nav,
		.ui-accordion .ui-accordion-header,
		#lightboxOverlay, 
		.form-submit #submit,
		.container .widget.widget_tag_cloud .tagcloud a:hover,
		.content .staff .about .icons a:hover  { background-color: <?php echo get_option('vz_styling_main_main'); ?> !important; }
	<?php endif; ?>

	<?php if( get_option('vz_styling_main_gold') ) : ?>
		.head_content a:hover,
		.container .slider .caption .caption-content h1 a,
		.container .widget.apply .description,
		.sidebar .widget.path .current,
		.sidebar .widget.apply ul li,
		.sidebar .widget.login div.user h2,
		.sidebar .widget.login .nav li.logout a,
		.footer_columns .twitter li span,
		.footer_columns .twitter li span a,
		.footer_nav ul > li > ul > li > a:hover,
		.footer_copyright,
		.footer_copyright a { color: <?php echo get_option('vz_styling_main_gold'); ?> !important; }

		.head_content .head_social .box:hover,
		.head_content .main_nav,
		.sidebar .widget.subscribe,
		.mainmenu_mobile,
		.footer_columns .gallery a:hover { background-color: <?php echo get_option('vz_styling_main_gold'); ?> !important; }

		.footer { border-color: <?php echo get_option('vz_styling_main_gold'); ?> !important;  }
	<?php endif; ?>

	<?php if( get_option('vz_styling_main_font') ) : ?>
		.container { color: <?php echo get_option('vz_styling_main_font'); ?> !important; }
	<?php endif; ?>

	<?php if( get_option('vz_styling_main_link') ) : ?>
		.container a { color: <?php echo get_option('vz_styling_main_link'); ?> !important; }
	<?php endif; ?>

	<?php if( get_option('vz_styling_main_title') ) : ?>
		.container h1, 
		.container h2,
		.container h3,
		.container h4,
		.container h5 { color: <?php echo get_option('vz_styling_main_title'); ?> !important; }
	<?php endif; ?>

	<?php if( get_option('vz_options_home_slider_height') && get_option('vz_options_home_slider_height') != 327 ) : ?>
		.slider { height: <?php echo get_option('vz_options_home_slider_height'); ?>px !important; }
	<?php endif; ?>


	<?php 

		print_vz_style_option('vz_styling_sizing_font',	 '.container');
		print_vz_style_option('vz_styling_sizing_link',	 '.container a');
		print_vz_style_option('vz_styling_sizing_date',	 '.container .post_block .block .info');
		print_vz_style_option('vz_styling_sizing_h1',	 '.container h1');
		print_vz_style_option('vz_styling_sizing_h2',	 '.container h2');
		print_vz_style_option('vz_styling_sizing_h3',	 '.container h3');
		print_vz_style_option('vz_styling_sizing_h4',	 '.container h4');
		print_vz_style_option('vz_styling_sizing_h5',	 '.container h5');
		print_vz_style_option('vz_styling_sizing_h6',	 '.container h6');

	?>

	</style>
