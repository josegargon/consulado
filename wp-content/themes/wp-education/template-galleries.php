<?php 

/*
	Template Name: Multiple Gallery
*/

get_header();

?>

	<div class="container container_12">

		<div class="content full alignleft">

			<?php

			$count_posts = wp_count_posts('galleries');

			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

			query_posts('posts_per_page=6&post_type=galleries&paged='.$paged);
			$max_paged = ceil($count_posts->publish/6);

			if (have_posts()) : ?>

				<?php while (have_posts()) : the_post(); 
					  $attachments = get_children( array( 'post_parent' => get_the_ID() ) );
					  $count = count( $attachments ); ?>

					<div class="gallery_folder grid_4">

						<?php if ( has_post_thumbnail() ) :  ?>
			                <a href="<?php the_permalink(); ?>"> <img src="<?php echo vz_resize( get_post_thumbnail_id(), 'vz_gallery_album'); ?>" /></a>
			            <?php elseif($count>0) : $last_attach_ID = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_parent = ".get_the_ID()." AND post_type='attachment' ORDER BY ID DESC LIMIT 1"); ?>
			            	<a href="<?php the_permalink(); ?>"> <img src="<?php echo vz_resize( $last_attach_ID, 'vz_gallery_album'); ?>" /></a>
			        	<?php endif; ?>

						<div class="info">
							<span class="folder alignleft"> 
								<?php $folders = get_the_terms( get_the_ID(), 'folders'); $ctr = 0;
									foreach ( $folders as $folder) {
										$ctr++; echo $folder->name; if( $ctr!=count($folders) ) echo ',';
									}
								?> 
							</span>
							<span class="counter alignright"> 
								<?php echo "$count "; echo($count>1) ? _e('Photos','vz_front_terms') : _e('Photo','vz_front_terms'); ?>
							</span>
						</div>

						<a href="<?php the_permalink(); ?>" class="album_name"> <?php the_title(); ?> </a>

					</div>

				<?php endwhile; ?>

			<?php else : _e('There is no gallery post yet.','vz_front_terms'); endif; ?>

		</div>

		<?php if($max_paged>1) : ?>

			<div class="block-pagination grid_12 alignright">
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

		<?php endif; ?>

	</div>

<?php get_footer(); ?>