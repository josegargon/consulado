<?php $body_order = explode(',',get_option('vz_options_arch_body','sidebar,content')); ?>

<aside>
	
	<div class="sidebar grid_4 <?php echo ($body_order[0] == 'sidebar') ? 'alignleft' : 'alignright'; ?>">

		<?php

			if( is_front_page() ) {
				dynamic_sidebar('Home');
			} elseif( is_home() || is_page_template('template-blog.php') || is_category() ) {
				dynamic_sidebar('Blog');
			} elseif ( is_page() ) {
				dynamic_sidebar('Page');
			} elseif( is_single() ) {
				dynamic_sidebar('Post');
			} elseif( is_404() ) {
				dynamic_sidebar('404');
			} elseif( is_author() ) {

				// Fetching visited author role
				$the_author = $wp_query->get_queried_object();
				$visited_author = new WP_User( $the_author->ID ); 
				$visited_author_role = $visited_author->roles[0];

				vz_author_sidebar_filebox($the_author,$visited_author_role);
				vz_author_sidebar_feeds($the_author);

			} else {
				dynamic_sidebar('Home');
			}

		?>

	</div>

</aside>

