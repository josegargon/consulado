<?php get_header(); 

	// Getting visited author and logged user info
	$the_author  = $wp_query->get_queried_object();
	$_the_author = new WP_User( $the_author->ID );
	$author_role = $_the_author->roles[0];
	$user_ID 	 = get_current_user_id();
	
	$content_grid = 'grid_8';
	if( !get_option('vz_plugins_extra_blogging') && !get_option('vz_plugins_extra_file_sharing') ) $content_grid = 'grid_12';
	if( get_option('vz_plugins_extra_blogging') && !get_option('vz_plugins_extra_file_sharing') && !is_user_logged_in() ) $content_grid = 'grid_12';
	if( $author_role != 'professor' && $author_role != 'student' && $the_author->ID!=$user_ID ) $content_grid = 'grid_12'; ?>

	<div class="container container_12">

		<?php

		//Generating author header from site_elements.php
		vz_general_author_head( $the_author, $user_ID );

		// Geting authors following authors including himself
		if( is_user_logged_in() && $the_author->ID == $user_ID ) {
			$fauthors = get_user_meta($the_author->ID, 'fauthors');
			if($fauthors) { $fauthors = $the_author->ID.",$fauthors"; }
			else { $fauthors = $the_author->ID; }

			//Getting following posts of author
			$fposts = get_user_meta($the_author->ID, 'fposts');
			if($fposts) :
				$fposts_query = " UNION SELECT *
				    FROM  $wpdb->posts
				    WHERE ID IN ($fposts)
					AND   post_type = 'post'
					AND   post_status = 'publish' $fposts_query";
				$fposts_count = " UNION SELECT COUNT(*) as fposts
				    FROM  $wpdb->posts
				    WHERE ID IN ($fposts)
					AND   post_type = 'post'
					AND   post_status = 'publish' $fposts_query";
			endif;
		} else {
			$fauthors = $the_author->ID;
		}

		//COUNTING TOTAL POSTS OF AUTHOR AND FOLLOWING
		$aposts_count = " SELECT COUNT(*) as aposts
			    FROM  $wpdb->posts
			    WHERE post_author IN ($fauthors)
				AND   post_type = 'post'
				AND   post_status = 'publish'
				$fposts_count
				ORDER BY post_date DESC ";
		$count_aposts = $wpdb->get_results($aposts_count, OBJECT); $count_aposts = $count_aposts[0];
		$all_posts    = $count_aposts->aposts + $count_aposts->fposts;
		$max_offset   = ceil($all_posts/30);

		//EXECUTING MAIN QUERY
		$query_args = " SELECT *
					    FROM  $wpdb->posts
					    WHERE post_author IN ($fauthors)
						AND   post_type = 'post'
						AND   post_status = 'publish'
						$fposts_query
						ORDER BY post_date DESC
						LIMIT 0,30 ";
		$author_posts = $wpdb->get_results($query_args, OBJECT);

		$showctr = 0;

		get_sidebar(); ?>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<div class="wall <?php echo $content_grid; ?>">

				<h1 class="title"> 
					<?php if( !get_option('vz_plugins_extra_blogging') && !get_option('vz_plugins_extra_calendar') ) :
						echo __('Posts by ','vz_front_terms').$the_author->display_name;
					else :
						echo( is_user_logged_in() && $the_author->ID == $user_ID ) ? __('Your posts and the following','vz_front_terms') : __('Posts by ','vz_front_terms').$the_author->display_name;
					endif; ?>
				</h1>

				<div id="more_content">

					<input type="hidden" id="item_id" value="<?php echo $the_author->ID; ?>" />
					<input type="hidden" id="item_offset" value="1" />
					<input type="hidden" id="max_offset" value="<?php echo $max_offset; ?>" />

					<?php

					if($author_posts) : global $post;

						foreach ($author_posts as $post) : setup_postdata( $post ); $showctr++; ?>

							<div class="post">

								<div class="intro alignleft rounded_2">

									<?php if ( has_post_thumbnail() ) : ?>
										<a href="<?php the_permalink(); ?>"> <img src="<?php echo vz_resize( get_post_thumbnail_id(), 'vz_blog_wall'); ?>" class="rounded_2 alignleft" /> </a>
									<?php endif; ?>

									<p> <?php comments_number( __('no comments','vz_front_terms'), __('<strong>1</strong> comment','vz_front_terms'), __('<strong>%</strong> comments','vz_front_terms') ); ?>  </p>

									<?php the_time('l,'); echo '<br/>'; the_time('d M Y'); echo '<br/>'; the_time('g:i A'); ?>

								</div>

								<a href="<?php the_permalink(); ?>" class="title alignleft"> 
									<?php the_title();
									if( is_user_logged_in() ) :
										if(get_the_author_meta('ID') != $the_author->ID) { echo '<br/><span class="alignleft">'.__('by ','vz_front_terms').get_the_author().'</span>'; }
									endif; ?>
								</a> <br/>

								<div class="excerpt alignleft"> <?php echo substr(get_the_excerpt(),0,190); ?> </div>

							</div>

						<?php endforeach; 

					endif;

					if($the_author->ID == $user_ID && !$fposts && !$author_posts) {
						echo '<p style="padding:20px">';
							_e('You did not post anything yet.', 'vz_front_terms');
						echo '</p>';
					}

					if( !is_user_logged_in() || $the_author->ID != $user_ID) {
						if(!$author_posts) :
						echo '<p style="padding:20px">';
							_e('The author did not post anything yet.', 'vz_front_terms');
						echo '</p>';
						endif;
					}
						
					?>

				</div>

				<?php if( $all_posts > 0) : ?>
					<div class="block-pagination">
						<div class="alignleft">
							<span> <?php _e('Showing','vz_front_terms'); ?> <span id="count-showing"><?php echo $showctr; ?></span> <?php _e('of','vz_front_terms'); ?> <span id="all_items"><?php echo $all_posts; ?></span> </span>
						</div>

						<div class="alignright">
							<a href="#" class="posts" id="load_more"> <?php _e('Load more...','vz_front_terms'); ?> </a>
						</div>
					</div>
				<?php endif; ?>

			</div>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>