<?php get_header(); ?>

	<div class="container container_12">

		<?php get_sidebar(); ?>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<!-- BEGIN PAGE  -->
			<div class="post rounded_2 alignright grid_8">

				<?php if(have_posts()) : the_post(); ?>

					<h1 class="title"> <?php the_title(); ?> </h1>

					<div class="post-content"> <?php the_content(); ?> </div>

					<?php wp_link_pages(); ?>

				<?php endif; ?>

			</div>
			<!-- END PAGE  -->

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>