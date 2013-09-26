<?php 

/*
	Template Name: Blog
*/

get_header();

?>

	<div class="container container_12">

		<?php get_sidebar(); ?>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<div class="gridholder grid_8">

			<?php 

			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			$per_page = get_option('vz_options_btemp_num',5);

			if($paged==1) :

				$sticky = get_option('sticky_posts');
				$sticky_posts = new WP_Query(array(
				    'post__in' => $sticky,
				    'ignore_sticky_posts' => 1,
				    'orderby' => 'ID',
				    'posts_per_page' => get_option('vz_options_btemp_sticky',5)
				));

				if(count($sticky)>0) :
					while ($sticky_posts->have_posts()) : $sticky_posts->the_post(); 
						$the_date = get_the_date(get_option('date_format'));
						$the_time = get_the_time(get_option('time_format')); ?>
					    <!-- BEGIN POST BLOCK  -->
						<div id="post-<?php the_ID(); ?>" <?php post_class('post_block grid_8'); ?>>

							<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?>
								<a href="<?php echo the_permalink(); ?>">
									<img src="<?php echo vz_resize( get_post_thumbnail_id(get_the_ID()), 'vz_block_bloghead'); ?>" />
								</a>
							<?php endif; ?>

							<div class="block">

								<div class="info">
									<time class="posted alignleft" datetime="<?php echo $the_date.' - '.$the_time; ?>" pubdate> Posted on <?php echo $the_date; ?></time>
									<span class="alignright"> <?php comments_number( __('no comments','vz_front_terms'), __('1 comment','vz_front_terms'), __('% comments','vz_front_terms') ); ?> </span>
								</div>

								<div class="block-content">

									<a href="<?php the_permalink(); ?>"> <h1> <?php the_title(); ?> </h1> </a>
									<div class="description">
										<?php echo substr( str_replace('[...]', '', get_the_excerpt()) ,0,210); ?> 
									</div>

								</div>

							</div>

						</div>
						<!-- END POST BLOCK  -->
					<?php endwhile; wp_reset_query();

				endif;

			endif;

			$all_other_posts = array(
			    'post__not_in' => get_option('sticky_posts'),
			    'post_status'=>'publish',
			    'paged'=>$paged,
			    'posts_per_page'=>$per_page
			);

			query_posts($all_other_posts);

			$all_posts = $wp_query->found_posts;
			$max_paged = ceil($all_posts/$per_page);

			if (have_posts()) : ?>

				<?php while (have_posts()) : the_post(); 
						$the_date = get_the_date(get_option('date_format'));
						$the_time = get_the_time(get_option('time_format')); ?>

					<!-- BEGIN POST BLOCK  -->
					<div id="post-<?php the_ID(); ?>" <?php post_class('post_block grid_8'); ?>>

						<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?>
							<a href="<?php echo the_permalink(); ?>">
								<img src="<?php echo vz_resize( get_post_thumbnail_id(get_the_ID()), 'vz_block_bloghead'); ?>" />
							</a>
						<?php endif; ?>

						<div class="block">

							<div class="info">
								<time class="posted alignleft" datetime="<?php echo $the_date.' - '.$the_time; ?>" pubdate> Posted on <?php echo $the_date; ?> </time>
								<span class="alignright"> <?php comments_number( __('no comments','vz_front_terms'), __('1 comment','vz_front_terms'), __('% comments','vz_front_terms') ); ?> </span>
							</div>

							<div class="block-content">

								<a href="<?php the_permalink(); ?>"> <h1> <?php the_title(); ?> </h1> </a>
								<div class="description">
									<?php echo substr( get_the_excerpt('210') ,0,210); ?>...
								</div>

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

			<?php endif; ?>

			</div>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>