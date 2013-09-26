<?php 

/*
	Template Name: Home
*/

get_header(); ?>

	<div class="container container_12">

		<?php get_sidebar(); ?>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<?php if( !get_option('vz_options_home_slider_disabled') ) vz_home_slider(); // Loading v1 slider


			// Showing featured news
			if( !get_option('vz_options_home_featuredarticles_disabled') ) vz_home_featured();


			// Showing home news block
			if( !get_option('vz_options_home_newsblock_disabled') ) vz_home_news(); 

			// Showing home events block
			if( !get_option('vz_options_home_eventsblock_disabled') ) vz_home_events(); 

			// Showing new sidebar 
			dynamic_sidebar('Home-bottom');

			?>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>