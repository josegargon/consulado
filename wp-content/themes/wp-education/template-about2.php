<?php 

/*
	Template Name: About2
*/

get_header();

?>

	<div class="container container_12">

		<?php if(have_posts()) : the_post(); ?>

			<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?>
				<div class="grid_12 about-top">
					<img src="<?php echo vz_resize( get_post_thumbnail_id(get_the_ID()), 'vz_about_head'); ?>" class="rounded_2" />
				</div>
			<?php endif; ?>

			<?php get_sidebar(); ?>

			<!-- BEGIN CONTENT  -->
			<div class="content alignleft">

					<div class="post rounded_2 alignleft grid_8">

						<h1 class="title"> <?php the_title(); ?> </h1>

						<div class="post-content"> <?php the_content(); ?> </div>

						<?php wp_link_pages(); ?>			

					</div>

				<?php $query_args = array( 'post_type' => 'staff', 'order' => 'asc' );

				$staff_posts = new WP_Query( $query_args );

				if ( $staff_posts->have_posts() ) : 

					while ( $staff_posts->have_posts() ) : $staff_posts->the_post();
						$position = get_post_meta( get_the_ID(), 'vz_staffposition', true);
						$facebook = get_post_meta( get_the_ID(), 'vz_stafffacebook', true);
						$twitter  = get_post_meta( get_the_ID(), 'vz_stafftwitter', true);

					 ?>

						<div class="staff grid_8 rounded_2 alignleft">

							<div class="about">
								<div class="alignleft">
									<a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a><br/>
									<span> <?php echo $position; ?> </span>
								</div>
								<div class="icons alignright">
									<?php 
										echo ( strlen($facebook)>0 ) ? '<a href="'.$facebook.'" class="fb alignleft"></a>' : '';
										echo ( strlen($twitter)>0 ) ? '<a href="'.$twitter.'" class="tw alignleft"></a>' : '';
									?>
								</div>

								<div class="clear"></div>
							</div>

							<div class="description2 alignleft"> 
								<?php if ( has_post_thumbnail() ) : ?>
									<a href="<?php the_permalink(); ?>" class="sub-pose alignleft"> 
										<img src="<?php echo vz_resize( get_post_thumbnail_id(get_the_ID()), 'vz_about_staff'); ?>" class="rounded_2 alignleft" />
									</a>
								<?php endif; ?>

								<?php echo substr(str_replace('[...]', '', get_the_excerpt()),0,600); ?> 

							</div>

						</div>

					<?php endwhile;

				endif; ?>

			</div>
			<!-- END CONTENT  -->

		<?php endif; ?>

	</div>

<?php get_footer(); ?>