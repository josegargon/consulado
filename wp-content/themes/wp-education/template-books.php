<?php 

/*
	Template Name: Multiple Books
*/

get_header();

$post_type = 'post';

$taxonomies = get_object_taxonomies( (object) array( 'post_type' => 'books' ) ); ?>

	<div class="container container_12">

		<div class="content full alignleft">

			<?php

			$count_posts = wp_count_posts( 'books' );
			query_posts( 'post_type=books' );

			if (have_posts()) : ?>

				<div class="filter_books grid_12"> 
					<h1 class="single-template"><?php echo the_title(); ?></h1>
					<?php $genres = get_terms("genres");
					if( count($genres)>0 ) : ?>
					<ul class="filters alignright">
						<li><?php _e('Genre:','vz_front_terms'); ?></li>
						<?php 
						echo '<li class="filter" data-filter="all"><a href="#" onclick="return false;">'.__('All','vz_front_terms').'</a></li>';
						foreach ($genres as $genre) :
							echo '<li class="filter" data-filter="'.$genre->slug.'"><a href="#" onclick="return false;">'.$genre->name.'</a></li>';
						endforeach; ?> 
					</ul>
					<?php endif; ?>
				</div>

				<div class="grid_12">

					<ul id="bk-list" class="bk-list mixitup">

						<?php while (have_posts()) : the_post(); 
							$left_ct = get_post_meta( get_the_ID(), 'vz_book_left', true);
							$back_ct = get_post_meta( get_the_ID(), 'vz_book_back', true);
							$dl_text = get_post_meta( get_the_ID(), 'vz_dl_text', true);
							$dl_url = get_post_meta( get_the_ID(), 'vz_dl_url', true);
							$book_color = get_post_meta( get_the_ID(), 'vz_book_color', true); 
							$p_genres = wp_get_post_terms( get_the_ID(), 'genres');
							$post_genres = '';
							foreach ($p_genres as $p_genre) :
							 	$post_genres .= $p_genre->slug.' ';
							endforeach; $post_genres = substr($post_genres, 0, -1); ?>

							<li class="mix <?php echo($post_genres) ? $post_genres : ''; ?> mix_all" >
								<div class="bk-book bk-bookdefault">
									<div class="bk-front" <?php echo ($book_color) ? 'style="background-color: '.$book_color.'"' : ''; ?>>
										<div class="bk-cover">
											<span><?php the_title(); ?></span>
										</div>
										<div class="bk-cover-back" <?php echo ($book_color) ? 'style="background-color: '.$book_color.'"' : ''; ?>></div>
									</div>
									<div class="bk-page">
										<div class="bk-content bk-content-current">
											<?php the_content(); ?>
											<a href="<?php if($dl_url) echo $dl_url; ?>"><?php if($dl_text) echo $dl_text; ?></a>
										</div>
									</div>
									<div class="bk-back" <?php echo ($book_color) ? 'style="background-color: '.$book_color.'"' : ''; ?>>
										<p>
											<?php if($back_ct) echo $back_ct; ?>
										</p>
									</div>
									<div class="bk-left" <?php echo ($book_color) ? 'style="background-color: '.$book_color.'"' : ''; ?>>
										<p><a href="#" class="turn"> </a> <?php if($left_ct) echo $left_ct; ?></p>
									</div>
								</div>
							</li>	

						<?php endwhile; ?>

					</ul>

				</div>

			<?php else : _e('There is no book post yet.','vz_front_terms'); endif; ?>

		</div>

	</div>

<?php get_footer(); ?>