<?php 

/*
	Template Name: Full Width
*/

get_header(); ?>

	<div class="container container_12">

		<!-- BEGIN CONTENT  -->
		<div class="content full alignleft">

			<!-- BEGIN PAGE  -->
			<div class="post top_rounded_2 alignright grid_12">

				<?php if(have_posts()) : the_post(); ?>

					<h1 class="title"> <?php the_title(); ?> </h1>

					<div class="post-content"> <?php the_content(); ?> </div>

				<?php endif; ?>

			</div>
			<!-- END PAGE  -->

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>