<?php 

/*
	Template Name: Single Gallery
*/

get_header(); 

if(have_posts()) : the_post(); 
	$photos = get_children( array( 'post_parent' => get_the_ID(), 'post_type' => 'attachment' ) );
    $count = count( $photos );
    $album_name = $post->post_name;
    $album_title = $post->post_title;
endif; ?>

	<div class="container container_12">

		<!-- BEGIN CONTENT  -->
		<div class="content full alignleft">

			<div class="back_albums grid_12"> 
				<h1 class="single-template"><?php echo $album_title; ?></h1>
				<span class="counter alignright"> <?php echo "<strong> $count </strong> "; echo($count>1) ? _e('Photos','vz_front_terms') : _e('Photo','vz_front_terms'); ?> </span>
			</div>
			<input type="hidden" id="album_title" value="<?php echo $album_title; ?>" />


			<?php foreach($photos as $photo ) : ?>

				<div class="grid_2 album_image">
					<a href="<?php echo vz_resize( $photo->ID, 'vz_gallery_zoom'); ?>" rel="lightbox[<?php echo $album_name; ?>]" title="<?php echo $photo->post_excerpt; ?>"> <img src="<?php echo vz_resize( $photo->ID, 'vz_gallery_thumb'); ?>" /> </a>
				</div>

			<?php endforeach; ?>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>