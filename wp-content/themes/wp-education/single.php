<?php get_header(); ?>

	<div class="container container_12">

		<?php get_sidebar(); ?>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<?php if(have_posts()) : the_post(); ?>

				<!-- BEGIN POST  -->
				<div class="post top_rounded_2 alignright grid_8" id="post-<?php the_ID(); ?>">

					<?php if( get_post_type( $post->ID ) == 'post' ) : $author = new WP_User( get_the_author_meta('ID') ); ?>

					<div class="post-head">

						<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?>
						<img src="<?php echo vz_resize( get_post_thumbnail_id(), 'vz_block_bloghead'); ?>" />
						<?php endif; ?>

						<?php /* if( !get_option('vz_options_btemp_author_disabled') ) : ?>
							<div class="bypostauthor rounded_2 alignright">

								<div class="info alignleft">

									<div class="social alignright">

										<a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>" rel="author"> Profile </a>

										<?php if( get_the_author_meta('linkedin', get_the_author_meta( 'ID' ) ) ) : ?>
										<a href="<?php the_author_meta('linkedin', get_the_author_meta( 'ID' ) ); ?>"> <?php _e('LinkedIn','vz_front_terms'); ?> </a>
										<?php endif; ?>

										<?php if( get_the_author_meta('twitter', get_the_author_meta( 'ID' ) ) ) : ?>
										<a href="<?php the_author_meta('twitter', get_the_author_meta( 'ID' ) ); ?>"> <?php _e('Twitter','vz_front_terms'); ?> </a>
										<?php endif; ?>

									</div>

									<?php echo get_avatar( $author->ID, 50 ); ?>

									<a class="alignleft" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"> <h1 class="alignleft"> <?php the_author(); ?> </h1> </a><br/><br/>
									<h2 class="alignleft"> <?php echo ucfirst($author->roles[0]); ?> </h2>

								</div>							

							</div>
						<?php endif; */ ?>

					</div>

					<?php endif; ?>

					<div class="clear"></div>

					<h1 class="title"> <?php the_title(); ?> </h1>

					<div class="post-content">

						<?php the_content(); ?>

						<?php wp_link_pages(); ?> <br/>

						<?php 
						if( !get_option('vz_options_btemp_cat_disabled') ) : 
							$cats = wp_get_post_categories( $post->ID ); 
							if( count($cats)>0 ) :
								$all_cats = count($cats); $cc = 0;
								_e('Category: ','vz_front_terms');
								foreach ($cats as $cat) { $cc++;
									$clink = get_category_link( $cat );
									$cname = get_the_category_by_id( $cat );
									echo "<a href='{$clink}'> {$cname} </a>";
									echo ($all_cats!=$cc) ? ',' : '';
								}
							endif;
						endif; ?>

						<p><?php if( !get_option('vz_options_btemp_tag_disabled') ) the_tags(); ?></p>

					</div>

					<?php if( get_post_type( $post->ID ) == 'post' || get_post_type( $post->ID ) == 'events' )	: ?>

					<div class="post-footer">
						

						<span class="alignleft">
							<?php _e('Found useful?','vz_front_terms'); ?> 
						</span>
						
						<?php 

						if ( is_user_logged_in() && get_current_user_id() != $author->ID ) { 
								
							$fauthors = get_user_meta(get_current_user_id(), 'fauthors');
							$fposts = get_user_meta( get_current_user_id(), 'fposts');
							$fclass = 'follow';
							$fterm = __('Follow','vz_front_terms');

							if(is_string($fauthors)) :
								$fauthors = explode(',', $fauthors);
								if( in_array($author->ID, $fauthors ) ) {
									$fterm = __('Unfollow','vz_front_terms'); $fclass = 'unfollow';
								}
							endif;

							if(is_string($fposts)) :
								$fposts = explode(',', $fposts);
								if( in_array(get_the_ID(), $fposts) ) { 
									$fterm = __('Unfollow','vz_front_terms'); $fclass = 'unfollow'; 
								}
							endif;

							echo '<a class="main '.$fclass.'" id="post_'.get_the_ID().'"> '.$fterm.' </a> ';

						} 

						?>

						<strong class="alignleft"> <?php _e('Share it','vz_front_terms'); ?> </strong> 

						<?php vz_generate_social_sharing(); ?>

						<div class="clear"></div>

					</div>

					<?php endif; ?>

				</div>
				<!-- END POST  -->

				

				<?php comments_template(); ?> 

			<?php endif; ?>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>