<?php 

/*
	Template Name: Courses
*/

get_header(); ?>

	<div class="container container_12">

		<div class="table grid_12">

			<h1 class="title top_rounded_2"> <?php the_title(); ?> </h1>

			<table>
				<thead>
					<tr>
						<td class="nr"> <?php _e('Nr.','vz_front_terms'); ?> </td>
						<td class="lecture"> <?php _e('Lecture','vz_front_terms'); ?> </td>
						<td class="prof"> <?php _e('Professor','vz_front_terms'); ?> </td>
						<td class="time"> <?php _e('Time','vz_front_terms'); ?> </td>
						<td class="hall"> <?php _e('Class','vz_front_terms'); ?> </td>
					</tr>
				</thead>
				<tbody>

					<?php

					$posts = query_posts('post_type=courses&orderby=ID&order=asc');

					if ( have_posts() ) : ?>

						<?php while (have_posts()) : the_post(); $pstctr++; ?>

							<tr>
								<td> <?php echo $pstctr; ?> </td> 
								<td> <a href="<?php the_permalink();?>"> <?php the_title(); ?> </a> </td> 
								<td> <?php $prof = get_post_meta($post->ID, 'prof'); echo $prof[0]; ?> </td> 
								<td> <?php $stime = get_post_meta($post->ID, 'starttime'); echo $stime[0]; ?> <?php _e('to','vz_front_terms'); ?> <?php $ftime = get_post_meta($post->ID, 'finishtime'); echo $ftime[0]; ?> </td> 
								<td> <?php $clss = get_post_meta($post->ID, 'class'); echo $clss[0]; ?> </td>
							</tr>

						<?php endwhile; ?>

					<?php else : ?>

						<tr>
							<td colspan="5"> <?php _e('There are no courses yet.', 'vz_front_terms'); ?> </td>
						</tr>

					<?php endif; ?>

				</tbody>
			</table>

		</div>

	</div>

<?php get_footer(); ?>