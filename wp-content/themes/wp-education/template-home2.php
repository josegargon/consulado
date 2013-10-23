<?php 

/*
	Template Name: Home2
*/

get_header(); ?>

	<div class="container container_12">
		<?php //if( !get_option('vz_options_home_slider_disabled') ) vz_home_slider_wide(); // Loading v2 slider ?>
		<div class="hidden-phone" style="overflow: hidden; margin-left: 10px; margin-bottom: 20px;position:relative;">
			<img class="rounded_2" style="width:100%; display:block;" src="/wp-content/uploads/2013/10/Bandera-940x330.jpg">
			<h1 style="position:absolute; bottom:0;left:0px;padding:10px;background:#000;opacity:0.9;color: #F9D84D;font-family: 'RobotoBold';font-size: 18px !important;display:block;width:100%;">Bienvenidos a la web del Consulado de Ecuador de Sevilla</h1>
		</div>

		<?php dynamic_sidebar('Home2-center'); ?>

		<div class="clear"> </div>

		<?php get_sidebar(); ?>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<?php

			// Showing featured news
			//vz_home_featured();

			// Showing home news block
			vz_home_news(); 

			// Showing home events block
			vz_home_events(); 

			?>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>
