<?php get_header(); ?>

	<div class="container container_12">

		<?php get_sidebar(); ?>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<!-- BEGIN PAGE  -->
			<div class="post top_rounded_2 alignright grid_8">

				<h1 class="title"> <?php echo get_option('vz_text_404_title', __('404 - Page not found, don not worry!','vz_front_terms') ) ?> </h1>

				<div class="post-content"> 
					<?php echo get_option('vz_text_404_description', __('It seems that the page you are looking for has moved or no longer exists.<br/> In the meantime, please use our navigation to reach the content you need.','vz_front_terms') ) ?>
				</div>

			</div>
			<!-- END PAGE  -->

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>