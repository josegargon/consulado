<?php

	# This file handles all customizations made to color styles from theme options.
	function print_vz_style_option($option_name,$css_selector,$css_property) {
		$the_option = null;

		if( get_option( $option_name ) ) $the_option = get_option( $option_name );

		switch ($css_property) {
			case 1: $property_print = 'background-color'; 	break;
			case 2: $property_print = 'color'; 				break;
			case 3: $property_print = 'border-color';		break;
			case 4: $property_print = 'font-size'; $the_option.='px'; break;
		}

		echo get_option( $option_name ) ? " $css_selector { $property_print : $the_option !important; } " : '';
	}

	# OTHER
	$slider_height 			= get_option('vz_options_home_slider_height',327);

?>

<style type="text/css">

	.main,.second { color: #fff !important; } .third { color: #565656 !important; }
	
	<?php if( get_option('vz_options_look_bg_img') ) : $body_bg_img = get_option('vz_options_look_bg_img'); ?>
		body { background-image: url('<?php echo is_numeric($body_bg_img) ? wp_get_attachment_url($body_bg_img) : $body_bg_img; ?>'); background-repeat: <?php echo get_option( 'vz_options_look_bg_img_repeat', 'no-repeat' ); ?>; }
	<?php endif; ?>

	<?php

		# BODY
		print_vz_style_option('vz_options_look_bg_color',				'body',	1);
		
		# HEADER
		print_vz_style_option('vz_styling_header_bg',	 				'.header,#lightboxOverlay', 1);
		print_vz_style_option('vz_styling_header_sp',					'.header hr, .head_content .head_social span.border', 1);
		print_vz_style_option('vz_styling_header_font',					'.head_content', 2);
		print_vz_style_option('vz_styling_header_slogan',				'.head_content h4',	2);
		print_vz_style_option('vz_styling_header_link',					'.head_content a', 2);
		print_vz_style_option('vz_styling_header_link_hover',			'.head_content a:hover', 2);
		print_vz_style_option('vz_styling_header_social_bg',			'.head_content .head_social .box', 1);
		print_vz_style_option('vz_styling_header_social_hover_bg',		'.head_content .head_social .box:hover', 1);
		print_vz_style_option('vz_styling_header_main_nav_link',		'.head_content .main_nav > ul > li > a', 2);
		print_vz_style_option('vz_styling_header_main_nav_bg',			'.head_content .main_nav', 1);
		print_vz_style_option('vz_styling_header_main_nav_border',		'.head_content .main_nav', 3);
		print_vz_style_option('vz_styling_header_main_nav_hover_bg',	'.head_content .main_nav > ul > li > a:hover, .head_content .main_nav > ul > li.current-menu-item', 1);
		print_vz_style_option('vz_styling_header_main_nav_hover_bg',	'.head_content .main_nav > ul > li', 3);
		print_vz_style_option('vz_styling_header_main_nav_link_hover',	'.head_content .main_nav > ul > li > a:hover', 2);
		print_vz_style_option('vz_styling_header_main_nav_link_active',	'.head_content .main_nav > ul > li.current-menu-item > a', 2);

		# SIDEBAR Widgets
		print_vz_style_option('vz_styling_sidebar_widget_bg',	 		'.sidebar .widget', 1);
		print_vz_style_option('vz_styling_sidebar_widget_font',	 		'.sidebar .widget', 2);
		print_vz_style_option('vz_styling_sidebar_widget_link',	 		'.sidebar .widget a', 2);
		print_vz_style_option('vz_styling_sidebar_widget_h1',	 		'.sidebar .widget h1', 2);
		print_vz_style_option('vz_styling_sidebar_widget_why_bg',	 	'.sidebar .widget.apply', 1);
		print_vz_style_option('vz_styling_sidebar_widget_why_h1',	 	'.sidebar .widget.apply h1', 2);
		print_vz_style_option('vz_styling_sidebar_widget_why_font',	 	'.sidebar .widget.apply', 2);
		print_vz_style_option('vz_styling_sidebar_widget_why_link',	 	'.sidebar .widget.apply a', 2);
		print_vz_style_option('vz_styling_sidebar_widget_why_dborder',	'.sidebar .widget.apply ul li', 3);
		print_vz_style_option('vz_styling_sidebar_widget_path_bg',	 	'.sidebar .widget.path', 1);
		print_vz_style_option('vz_styling_sidebar_widget_path_link',	'.sidebar .widget.path a', 2);
		print_vz_style_option('vz_styling_sidebar_widget_path_link_hover','.sidebar .widget.path a:hover', 2);
		print_vz_style_option('vz_styling_sidebar_widget_sub_bg',	 	'.sidebar .widget.subscribe', 1);
		print_vz_style_option('vz_styling_sidebar_widget_sub_font',	 	'.sidebar .widget.subscribe', 2);
		print_vz_style_option('vz_styling_sidebar_widget_sub_h1',	 	'.sidebar .widget.subscribe h1', 2);
		print_vz_style_option('vz_styling_sidebar_widget_sub_link',	 	'.sidebar .widget.subscribe a', 2);

		# CONTENT
		print_vz_style_option('vz_styling_sizing_font',	 				'.container', 4);
		print_vz_style_option('vz_styling_sizing_link',	 				'.container a', 4);
		print_vz_style_option('vz_styling_sizing_date',	 				'.container .post_block .block .info', 4);
		print_vz_style_option('vz_styling_sizing_h1',	 				'.container h1', 4);
		print_vz_style_option('vz_styling_sizing_h2',	 				'.container h2', 4);
		print_vz_style_option('vz_styling_sizing_h3',	 				'.container h3', 4);
		print_vz_style_option('vz_styling_sizing_h4',	 				'.container h4', 4);
		print_vz_style_option('vz_styling_sizing_h5',	 				'.container h5', 4);
		print_vz_style_option('vz_styling_sizing_h6',	 				'.container h6', 4);
		print_vz_style_option('vz_styling_content_font',	 			'.container', 2);
		print_vz_style_option('vz_styling_content_link',	 			'.container a', 2);
		print_vz_style_option('vz_styling_content_link_hover',	 		'.container a:hover', 2);
		print_vz_style_option('vz_styling_content_h1',	 				'.container h1,.container h2,.container h3,.container h4,.container h5', 2);
		print_vz_style_option('vz_styling_content_table_h_bg',	 		'.table h1.title', 1);
		print_vz_style_option('vz_styling_content_table_h_font',	 	'.table h1.title', 2);
		print_vz_style_option('vz_styling_content_table_font',	 		'.table td', 2);
		print_vz_style_option('vz_styling_content_list_font',	 		'.content ul,.content ol', 2);
		print_vz_style_option('vz_styling_content_list_link',	 		'.content ul a,.content ol a', 2);
		print_vz_style_option('vz_styling_content_tab_h_bg',	 		'.ui-tabs .ui-tabs-nav', 1);
		print_vz_style_option('vz_styling_content_tab_h_font',	 		'.ui-tabs .ui-tabs-nav li a,.ui-tabs .ui-tabs-nav li a:hover', 2);
		print_vz_style_option('vz_styling_content_tab_ah_font',	 		'.ui-tabs .ui-tabs-nav li.ui-tabs-active a, .ui-tabs .ui-tabs-nav li.ui-tabs-active a:hover', 2);
		print_vz_style_option('vz_styling_content_ac_h_bg',	 			'.ui-accordion .ui-accordion-header', 1);
		print_vz_style_option('vz_styling_content_ac_h_font',	 		'.ui-accordion .ui-accordion-header', 2);
		print_vz_style_option('vz_styling_content_ac_ah_font',	 		'.ui-accordion-header-active', 2);

		# BUTTONS
		# - main
		print_vz_style_option('vz_styling_buttons_main_button_font',	'a.main,input.main,a.main:hover,input.main:hover', 2);
		print_vz_style_option('vz_styling_buttons_main_button_bg',		'a.main,input.main', 1);
		print_vz_style_option('vz_styling_buttons_main_button_bghover',	'a.main:hover,input.main:hover', 1);
		print_vz_style_option('vz_styling_buttons_main_button_border',	'a.main,input.main', 3);
		# - second
		print_vz_style_option('vz_styling_buttons_second_button_font',	'a.second,input.second,a.second:hover,input.second:hover', 2);
		print_vz_style_option('vz_styling_buttons_second_button_bg',	'a.second,input.second', 1);
		print_vz_style_option('vz_styling_buttons_second_button_bghover','a.second:hover,input.second:hover', 1);
		print_vz_style_option('vz_styling_buttons_second_button_border','a.second,input.second', 3);
		# - third
		print_vz_style_option('vz_styling_buttons_third_button_font',	'a.third,input.third,a.third:hover,input.third:hover', 2);
		print_vz_style_option('vz_styling_buttons_third_button_bg',		'a.third,input.third', 1);
		print_vz_style_option('vz_styling_buttons_third_button_bghover','a.third:hover,input.third:hover', 1);
		print_vz_style_option('vz_styling_buttons_third_button_border',	'a.third,input.third', 3);
		# - pagination
		print_vz_style_option('vz_styling_buttons_pag_main_font',	'.block-pagination a', 2);
		print_vz_style_option('vz_styling_buttons_pag_main_font_h',	'.block-pagination a:hover,.block-pagination a.active', 2);
		print_vz_style_option('vz_styling_buttons_pag_main_bg',		'.block-pagination a', 1);
		print_vz_style_option('vz_styling_buttons_pag_main_bg_h',	'.block-pagination a:hover,.block-pagination a.active', 1);
		print_vz_style_option('vz_styling_buttons_pag_main_border',	'.block-pagination a', 3);
		print_vz_style_option('vz_styling_buttons_pag_main_border_h','.block-pagination a:hover,.block-pagination a.active', 3);

		# FOOTER
		print_vz_style_option('vz_styling_footer_bg',	 				'.footer', 1);
		print_vz_style_option('vz_styling_footer_top_border',	 		'.footer', 3);
		print_vz_style_option('vz_styling_footer_copyright',	 		'.footer .footer_copyright, .footer .footer_copyright a', 2);
		print_vz_style_option('vz_styling_footer_widgets_bg',	 		'.footer_columns', 1);
		print_vz_style_option('vz_styling_footer_widgets_bottom_border','.footer_columns', 3);
		print_vz_style_option('vz_styling_footer_nav_bottom_border',	'.footer .footer_nav', 3);
		print_vz_style_option('vz_styling_footer_nav_parent',			'.footer .footer_nav > ul > li > a', 2);
		print_vz_style_option('vz_styling_footer_nav_children',			'.footer .footer_nav ul.sub-menu li a', 2);
		print_vz_style_option('vz_styling_footer_nav_children_hover',	'.footer .footer_nav ul.sub-menu li a:hover', 2);

	?>

	.slider { height: <?php echo $slider_height; ?>px !important; }

	</style>
