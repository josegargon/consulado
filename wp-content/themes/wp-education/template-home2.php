<?php 

/*
	Template Name: Home2
*/

get_header(); ?>

	<div class="container container_12">

		<?php if( !get_option('vz_options_home_slider_disabled') ) vz_home_slider_wide(); // Loading v2 slider ?>

		<?php dynamic_sidebar('Home2-center'); ?>

		<div class="clear"> </div>

		<?php get_sidebar(); ?>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<?php

			// Showing featured news
			vz_home_featured();

			// Showing home news block
			vz_home_news(); 

			// Showing home events block
			vz_home_events(); 

			?>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>