<?php 

/*
	Template Name: Books
*/

get_header(); 

if(have_posts()) : the_post(); 
	$books = get_children( array( 'post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'application/pdf' ) );
    $count = count( $books );
    $page_title = $post->post_title;
endif; ?>

	<div class="container container_12">

		<!-- BEGIN CONTENT  -->
		<div class="content full alignleft">

			<div class="back_albums grid_12"> 
				<h1 class="single-template"><?php echo $page_title; ?></h1>
				<span class="counter alignright"> <?php echo "<strong> $count </strong> "; echo($count>1) ? _e('Books','vz_front_terms') : _e('Book','vz_front_terms'); ?> </span>
			</div>

			<div class="grid_12">
				
				<ul id="bk-list" class="bk-list">

					<?php if($count>0) : foreach($books as $book ) :
						$content = get_post_field('post_content', $book->ID);
						$caption = get_post_field('post_excerpt', $book->ID);
						$date = get_post_field('post_date', $book->ID);
						$file_size = formatBytes(filesize( get_attached_file( $book->ID ) ),2); ?>

						<li>
							<div class="bk-book bk-bookdefault">
								<div class="bk-front">
									<div class="bk-cover">
										<span><?php echo $book->post_title; ?></span>
									</div>
									<div class="bk-cover-back"></div>
								</div>
								<div class="bk-page">
									<div class="bk-content bk-content-current">
										<?php echo $content; ?>
										<a href="<?php echo wp_get_attachment_url( $book->ID ); ?>">Download here</a>
									</div>
								</div>
								<div class="bk-back">
									<p>
										<?php _e('File size','vz_front_terms'); echo ": $file_size"; ?> <br/>
										<?php _e('Upload date','vz_front_terms'); echo ": $date"; ?>
									</p>
								</div>
								<div class="bk-left">
									<p><a href="#" class="turn"> </a><?php echo $caption; ?></p>
								</div>
							</div>
						</li>

					<?php endforeach; endif; ?>

				</ul>

				<div class="clear"></div>
			</div>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>