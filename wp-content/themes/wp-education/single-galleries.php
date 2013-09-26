<?php get_header();

	if(have_posts()) : the_post();

   		$photos = get_posts( array( 'post_parent' => get_the_ID(), 'post_type' => 'attachment', 'numberposts' => 30 ) );
   		$all_photos = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_parent = ".get_the_ID()." " );
	    $max_offset = ceil($all_photos/30);
	    $album_name = $post->post_name;
	    $album_title = $post->post_title;

	endif;

	$albums_page = get_pages( array('meta_key' => '_wp_page_template','meta_value' => 'template-galleries.php' ));
	$albums_page = $albums_page[0];
	$showctr = 0;

	?>

	<div class="container container_12">

		<!-- BEGIN CONTENT  -->
		<div class="content full alignleft">

			<div class="back_albums grid_12"> 
				<a href="<?php echo get_page_link( $albums_page->ID ); ?>"> <?php _e('Back to albums','vz_front_terms'); ?> </a>
				<span class="counter alignright"> <?php echo "<strong> $all_photos </strong> "; echo($all_photos>1) ? _e('Photos','vz_front_terms') : _e('Photo','vz_front_terms'); ?> </span>
			</div>
			<input type="hidden" id="album_title" value="<?php echo $album_title; ?>" />
			<input type="hidden" id="max_offset" value="<?php echo $max_offset; ?>" />

			<input type="hidden" id="item_id" value="<?php echo get_the_ID(); ?>" />
			<input type="hidden" id="item_offset" value="1" />

			<div id="more_content">

				<?php foreach($photos as $photo ) : $showctr++; ?>

					<div class="grid_2 album_image">
						<a href="<?php echo vz_resize( $photo->ID, 'vz_gallery_zoom'); ?>" rel="lightbox[<?php echo $album_name; ?>]" title="<?php echo $photo->post_excerpt; ?>"> <img src="<?php echo vz_resize( $photo->ID, 'vz_gallery_thumb'); ?>" /> </a>
					</div>

				<?php endforeach; ?>

			</div>

			<?php if($all_photos>30) : ?>
				<div class="block-pagination grid_12 alignright">
					<div class="counting alignleft">
						<span> <?php _e('Showing','vz_front_terms'); ?> <span id="count-showing"><?php echo $showctr; ?></span> <?php _e('of','vz_front_terms'); ?> <span id="all_items"><?php echo $all_photos; ?></span> </span>
					</div>

					<div class="by_arrows alignright">				
						<a href="#" class="gallery" id="load_more"> <?php _e('Load more...','vz_front_terms'); ?> </a>
					</div>
				</div>
			<?php endif; ?>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>