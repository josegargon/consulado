<?php 

/*
	Template Name: Events
*/

get_header();

?>

	<div class="container container_12">

		<?php get_sidebar(); ?>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<?php 

			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			query_posts( array('post_type'=>'events', 'post_status'=>'publish', 'paged'=>$paged) );

			$per_page = get_option('posts_per_page');
			$all_posts = $wp_query->found_posts;
			$max_paged = ceil($all_posts/$per_page);


			if (have_posts()) : ?>

				<div class="gridholder grid_8">

					<?php while (have_posts()) : the_post(); ?>

						<!-- BEGIN POST BLOCK  -->
						<div id="post-<?php the_ID(); ?>" <?php post_class('post_block grid_4'); ?>>

							<?php

							if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) :
							
								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'block' );
								$thumb_url = $thumb['0'];

								?>
								<a href="<?php echo the_permalink(); ?>">
									<img src="<?php echo $thumb_url; ?>" />
								</a>

							<?php endif; ?>

							<div class="block">

								<div class="info">
									<time class="posted alignleft" datetime="<?php the_time(); ?>" pubdate> Posted on <?php the_time(); ?></time>
									<span class="alignright"> <?php comments_number( __('no comments','vz_front_terms'), __('1 comment','vz_front_terms'), __('% comments','vz_front_terms') ); ?> </span>
								</div>

								<div class="block-content">

									<a href="<?php the_permalink(); ?>"> <h1> <?php the_title(); ?> </h1> </a>
									<div class="description">
										<?php echo substr( str_replace('[...]', '', get_the_excerpt()) ,0,210); ?> 
									</div>
									<a href="<?php the_permalink(); ?>"> read more... </a>

								</div>

							</div>

						</div>
						<!-- END POST BLOCK  -->

					<?php endwhile; ?>


					<?php if($max_paged>1) : ?>
						<div class="grid_8 alignleft">

							<div class="block-pagination grid_8 alignright">
								<div class="by_number alignleft">
									<?php for($i=1;$i<=$max_paged;$i++) : ?>
										<a href="<?php echo get_pagenum_link( $i ); ?>" class="<?php echo($i==$paged) ? 'active' : '' ; ?> alignleft"> <?php echo $i; ?> </a>
									<?php endfor; ?>
								</div>

								<div class="by_arrows alignright">
									<?php previous_posts_link(__('Prev','vz_front_terms')); ?>
									<?php next_posts_link(__('Next','vz_front_terms')); ?> 							
								</div>

							</div>

						</div>
					<?php endif; ?>

				</div>

			<?php endif; ?>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>