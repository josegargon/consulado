<?php

/****************************************
* Site elements
****************************************/



/*-----------------------------------------------------------------------------------*/
/*	SINGLE: Comment part
/*-----------------------------------------------------------------------------------*/
function vz_comments($comment, $args, $depth) { 
	$GLOBALS['comment'] = $comment;

	$reply_class = ($comment->comment_parent>0) ? 'reply' : '';

	$comment_author = NULL;
	if( get_user_by('email', $comment->comment_author_email) ) :
		$comment_author = get_user_by('email', $comment->comment_author_email);
	endif; ?>

	<div <?php comment_class($reply_class); ?> id="comment-<?php comment_ID(); ?>">

		<?php echo get_avatar( $comment->comment_author_email, 77 ); ?>

		<div class="comment-content alignright">

			<div class="info">
				<span class="title alignleft">
					<strong><?php echo $comment->comment_author; ?></strong><br/>
					<?php if($comment_author) :
						echo ucfirst($comment_author->roles[0]);
						if( is_string(get_user_meta( $comment_author->ID, 'vz_group' )) ) echo ' '.__('at','vz_front_terms').' '.ucfirst( get_user_meta( $comment_author->ID, 'vz_group' ) );
					endif; ?>
				</span>

				<span class="links alignright">
					<a href="#reply" class="reply_comment cid_<?php echo $comment->comment_ID; ?>"> <?php _e('Reply','vz_front_terms'); ?> </a> &#8226; <a href="#" class="report comment" id="comment_<?php echo $comment->comment_ID; ?>" onclick="return false"> <?php _e('Report','vz_front_terms'); ?> </a><br/>
					<time class="posted alignright" datetime="<?php comment_date( 'Y-m-d - g:i A'); ?>" pubdate><?php _e('Posted on','vz_front_terms'); ?>  <?php comment_date( 'd.m.Y - g:i A'); ?></time>
				</span>
			</div>

			<div class="description alignright"><?php echo $comment->comment_content; ?></div>

		</div>

		<div class="clear"></div>

	</div>

<?php 

}



// Show social icon short function
function vz_show_social_icon($name) {
	if(get_option("vz_options_social_{$name}_show")) : ?>
		<div class="box alignleft"> 
			<a href="<?php echo ($name=='rss') ? get_option('vz_options_social_rss',bloginfo('rss2_url')) : get_option("vz_options_social_{$name}"); ?>" class="<?php echo $name; ?>"> </a>
		</div>
	<?php endif;
}



/*-----------------------------------------------------------------------------------*/
/*	HOME: TOP Social part
/*-----------------------------------------------------------------------------------*/
function vz_show_social($_grid_size) { ?>
	<div class="head_social <?php echo $_grid_size; ?> alignleft">
		<span class="alignleft"> <?php _e('Social', 'vz_front_terms'); ?> </span>
		<?php 
			//Showing social icons
			vz_show_social_icon('fb'); 
			vz_show_social_icon('tw'); 
			vz_show_social_icon('gplus'); 
			vz_show_social_icon('lin'); 
			vz_show_social_icon('skype'); 
			vz_show_social_icon('yt'); 
			vz_show_social_icon('vimeo'); 
			vz_show_social_icon('igram'); 
			vz_show_social_icon('pin'); 
			vz_show_social_icon('dribbble'); 
			vz_show_social_icon('xing'); 
			vz_show_social_icon('tumblr'); 
			vz_show_social_icon('rss'); 

		
		if(get_option('vz_options_lang_links_enabled')) : ?>
		<span class="border alignleft"></span>
		<span class="langs alignleft"> <?php _e('Language', 'vz_front_terms'); ?>

			<?php 
				if(get_option('vz_options_lang_links_firstlang')) :
					echo '<a href="'.get_option('vz_options_lang_links_firstlang2').'">'.get_option('vz_options_lang_links_firstlang').'</a> ';
				endif;

				if(get_option('vz_options_lang_links_secondlang')) :
					echo '&#8226; <a href="'.get_option('vz_options_lang_links_secondlang2').'" class="no_margin">'.get_option('vz_options_lang_links_secondlang').'</a> ';
				endif;

				if(get_option('vz_options_lang_links_thirdlang')) :
					echo '&#8226;  <a href="'.get_option('vz_options_lang_links_thirdlang2').'" class="no_margin">'.get_option('vz_options_lang_links_thirdlang').'</a> ';
				endif;

				if(get_option('vz_options_lang_links_fourthlang')) :
					echo '&#8226;  <a href="'.get_option('vz_options_lang_links_fourthlang2').'" class="no_margin">'.get_option('vz_options_lang_links_fourthlang').'</a>';
				endif;
			?>

		</span>
		<?php endif;?>
	</div>
	<?php 

}



/*-----------------------------------------------------------------------------------*/
/*	HOME: TOP Menu part
/*-----------------------------------------------------------------------------------*/
function vz_show_topmenu($_grid_size,$_menu_class) { 
	if ( has_nav_menu('top_nav') ) : ?>
		<div class="top_nav <?php echo $_grid_size; ?> alignleft">
			<?php wp_nav_menu( array( 'theme_location' => 'top_nav', 'depth' => 1, 'container' => '', 'menu_class' => $_menu_class ) ); ?>
		</div>
	<?php endif;
}



/*-----------------------------------------------------------------------------------*/
/*	HOME: Logo
/*-----------------------------------------------------------------------------------*/
function vz_show_logo() { ?>
	<div class="grid_12">

		<div class="head_logo grid_4 <?php echo ( strlen(get_option('vz_options_look_ad_code'))>0 ) ? 'alignleft' : ''; ?>">
			<?php if( get_option('vz_options_look_logo') ) : $_logo = get_option('vz_options_look_logo'); ?>
			<a href="<?php echo site_url(); ?>">
				<img src="<?php echo is_numeric($_logo) ? wp_get_attachment_url( $_logo ) : $_logo; ?>"/>
			</a>
			<?php endif; ?>

			<?php if( !get_option('vz_text_header_htext_disabled') ) : ?>
				<h1> <?php echo get_option('vz_text_header_title', get_bloginfo('name') ); ?> </h1>
				<h4> <?php echo get_option('vz_text_header_slogan', get_bloginfo ('description') ); ?> </h4>
			<?php endif; ?>

			<div class="clear"></div>
		</div>

		<?php if( strlen(get_option('vz_options_look_ad_code'))>0 ) : ?>
			<div class="head_logo_ad grid_8"> <?php echo get_option('vz_options_look_ad_code'); ?> </div>
		<?php endif; ?>

	</div>

	<?php 
}



/*-----------------------------------------------------------------------------------*/
/*	HOME: Slider v1
/*-----------------------------------------------------------------------------------*/
function vz_home_slider() {

	$query_args = array( 'showposts' => get_option('vz_options_home_slider_num',3) , 'post_type' => 'post' );

	if( get_option('vz_options_home_slider_cat') ) { $query_args['cat'] = get_option('vz_options_home_slider_cat'); }

	$slider_posts = new WP_Query( $query_args );

	//Checking if any of posts has thumbnail
	if ( $slider_posts->have_posts() ) :
		while ( $slider_posts->have_posts() ) : $slider_posts->the_post();
			if( has_post_thumbnail() ) $there_is_a_thumbnail = TRUE;
		endwhile;
	endif;

	if ( $slider_posts->have_posts() && $there_is_a_thumbnail ) : $_cstat = get_option('vz_options_home_captions_disabled'); ?>

		<!-- BEGIN SLIDER  -->
		<div class="slider grid_8">

			<div class="sliding">

				<ul class="slides">

					<?php $_first_slide = null; $dsp = '';

					while ( $slider_posts->have_posts() ) : $slider_posts->the_post(); 

							if( !has_post_thumbnail() ) continue;

							if(!$_first_slide) { 
								$_first_slide['link'] = '<a href="'.get_permalink().'">'.substr(get_the_title(),0,40).'</a>';   
								$_first_slide['caption'] = substr(str_replace('[...]','',get_the_excerpt()),0,210); 
							} ?>

						<li <?php echo $dsp; ?>>

							<?php echo '<a href="'.get_permalink().'">'; ?>

								<?php if( has_post_thumbnail() ) : ?>
									<img src="<?php echo vz_resize( get_post_thumbnail_id(), 'vz_home_slider'); ?>" class="rounded_2" />
								<?php endif; ?>

							<?php echo '</a>'; ?>

							<div class="hidden caption-title"><?php echo '<a href="'.get_permalink().'">'.substr(get_the_title(),0,40); ?></a></div>
							<div class="hidden caption-text"><?php echo substr(str_replace('[...]','',get_the_excerpt()),0,200); ?></div>

						</li>

					<?php $dsp = 'style="display:none"'; endwhile; ?>

				</ul>

				<?php if( !get_option('vz_options_home_captions_disabled') ) : ?>
					<div class="caption rounded_2">

						<div class="caption-content alignleft">
							<h1> <?php echo $_first_slide['link']; ?> </h1>
							<p> <?php echo $_first_slide['caption']; unset($_first_slide); ?> </p>
						</div>

						<div class="arrows alignright">
							<a href="#" class="alignleft rounded_2 last" id="prev"> </a>
							<hr/>
							<a href="#" class="alignleft rounded_2 more" id="next"> </a>
						</div>

					</div>
				<?php endif; ?>

			</div>

		</div>
		<!-- END SLIDER  -->

	<?php

	endif;

}



/*-----------------------------------------------------------------------------------*/
/*	HOME: Slider v2
/*-----------------------------------------------------------------------------------*/
function vz_home_slider_wide() { 	

	$query_args = array( 'showposts' => get_option('vz_options_home_slider_num',3) , 'post_type' => 'post' );

	if( get_option('vz_options_home_slider_cat') ) { $query_args['cat'] = get_option('vz_options_home_slider_cat'); }

	$slider_posts = new WP_Query( $query_args );

	//Checking if any of posts has thumbnail
	if ( $slider_posts->have_posts() ) :
		while ( $slider_posts->have_posts() ) : $slider_posts->the_post();
			if( has_post_thumbnail() ) $there_is_a_thumbnail = TRUE;
		endwhile;
	endif;

	if ( $slider_posts->have_posts() && $there_is_a_thumbnail ) : $_cstat = get_option('vz_options_home_captions_disabled'); ?>

		<!-- BEGIN SLIDER  -->
		<div class="slider wide alignright grid_12">

			<div class="sliding">

				<ul class="slides">

					<?php $_first_slide = null; $dsp = '';

					while ( $slider_posts->have_posts() ) : $slider_posts->the_post();

						if( !has_post_thumbnail() ) continue;

						if(!$_first_slide) { 
							$_first_slide['link'] = '<a href="'.get_permalink().'">'.substr(get_the_title(),0,40).'</a>';   
							$_first_slide['caption'] = substr(str_replace('[...]','',get_the_excerpt()),0,210); 
						} ?>

						<li <?php echo $dsp; ?>>

							<?php echo '<a href="'.get_permalink().'">'; ?>

								<?php if( has_post_thumbnail() ) : ?>
									<img src="<?php echo vz_resize( get_post_thumbnail_id(), 'vz_home_slider_wide'); ?>" class="rounded_2" />
								<?php endif; ?>

							<?php echo '</a>'; ?>

							<div class="hidden caption-title"><?php echo '<a href="'.get_permalink().'">'.substr(get_the_title(),0,40); ?></a></div>
							<div class="hidden caption-text"><?php echo substr(str_replace('[...]','',get_the_excerpt()),0,210); ?></div>

						</li>

					<?php $dsp = 'style="display:none"'; endwhile; ?>

				</ul>


				<?php if( !get_option('vz_options_home_captions_disabled') ) : ?>
					<div class="caption rounded_2">

						<div class="caption-content alignleft">
							<h1> <?php echo $_first_slide['link']; ?> </h1>
							<p> <?php echo $_first_slide['caption']; unset($_first_slide); ?> </p>
						</div>

						<div class="arrows alignright">
							<a href="#" class="alignleft rounded_2 last" id="prev"> </a>
							<hr/>
							<a href="#" class="alignleft rounded_2 more" id="next"> </a>
						</div>

					</div>
				<?php endif; ?>

			</div>

		</div>
		<!-- END SLIDER  -->

	<?php

	endif;

}



/*-----------------------------------------------------------------------------------*/
/*	HOME: Featured posts
/*-----------------------------------------------------------------------------------*/
function vz_home_featured() {
	$query_args = array( 'showposts' => get_option('vz_options_home_featured_num',2) , 'post_type' => 'post' );

	if( get_option('vz_options_home_featured_cat') ) { $query_args['cat'] = get_option('vz_options_home_featured_cat'); }

	$featured_posts = new WP_Query( $query_args );

	if ( $featured_posts->have_posts() ) : ?>

		<div class="featured grid_8 rounded_2">

			<h2 class="title"> 
				<?php _e('Featured posts','vz_front_terms'); ?>

				<div class="arrows alignright">
					<a href="#" class="alignleft rounded_2 last" id="prev"> </a>
					<a href="#" class="alignleft rounded_2 more" id="next"> </a>
				</div>

			</h2>

			<?php $counter=0;

			while ( $featured_posts->have_posts() ) : $featured_posts->the_post(); $counter++; ?>

			<div class="featured_post <?php echo ($counter>1) ? 'hidden' : 'display'; ?>" <?php echo ($counter>1) ? 'style="display:none"' : ''; ?>>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="featured_thumb rounded_2 alignleft">
						<a href="<?php the_permalink(); ?>"> <?php the_post_thumbnail('featured'); ?> </a>
					</div>
				<?php endif; ?>

				<span> <?php the_title(); ?> </span>

				<div class="description"> <?php echo substr(str_replace('[...]', '', get_the_excerpt()),0,320); ?> </div>

				<a class="main" href="<?php the_permalink(); ?>"> <?php _e('Full article', 'vz_front_terms') ?> </a>
				<a class="third" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"> <?php _e('Author\'s profile', 'vz_front_terms') ?> </a>

			</div>

			<?php endwhile; ?>

		</div>

	<?php 	

	endif;

}



/*-----------------------------------------------------------------------------------*/
/*	HOME: News block
/*-----------------------------------------------------------------------------------*/
function vz_home_news() {
	$query_args = array( 'showposts' => get_option('vz_options_home_newsblock_num',4) , 'post_type' => 'post' );

	if( get_option('vz_options_home_newsblock_cat') ) { $query_args['cat'] = get_option('vz_options_home_newsblock_cat'); }

	$block_posts = new WP_Query( $query_args );

	if ( $block_posts->have_posts() ) : ?>

		<div class="feed grid_4 rounded_2">

			<h2 class="title"> <?php _e('News','vz_front_terms'); ?> </h2>

			<?php $count_posts = 0;

			while ( $block_posts->have_posts() ) : $block_posts->the_post(); $count_posts++; ?>

			<div class="post <?php echo ( $count_posts == get_option('vz_options_home_newsblock_num',4) ) ? 'no_border' : ''; ?>">

				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>"> <?php the_post_thumbnail(); ?> </a>
				<?php endif; ?>

				<div class="info">
					<span class="date alignleft"> <?php the_time('d.m.Y - g:i A'); ?> </span>
					<span class="comments alignright"> <?php comments_number('', '1', '%'); ?> </span>

					<a href="<?php the_permalink(); ?>" class="alignleft title"> <?php the_title(); ?></a>
				</div>

				<div class="clear"></div>
			</div>

			<?php endwhile; ?>

		</div>

	<?php 	

	endif;

}



/*-----------------------------------------------------------------------------------*/
/*	HOME: Events block
/*-----------------------------------------------------------------------------------*/
function vz_home_events() {
	$query_args = array( 'showposts' => get_option('vz_options_home_eventsblock_num',4) , 'post_type' => 'events' );

	$block_events = new WP_Query( $query_args );

	if ( $block_events->have_posts() ) : ?>

		<div class="feed grid_4 rounded_2">

			<h2 class="title"> <?php _e('Events','vz_front_terms'); ?> </h2>

			<?php $count_posts = 0;

			while ( $block_events->have_posts() ) : $block_events->the_post(); $count_posts++;
				  $vz_date = get_post_meta( get_the_ID(), 'vz_date' ); $vz_date = explode(' ',$vz_date[0]);
				  $vz_location = get_post_meta( get_the_ID(), 'vz_location' ); ?>

			<div class="event <?php echo ( $count_posts == get_option('vz_options_home_eventsblock_num',4) ) ? 'no_border' : ''; ?>">

				<div class="date rounded_2 alignleft"> <span> <?php echo $vz_date[0]; ?> </span> <?php echo $vz_date[1]; ?> </div>
				<div class="info alignleft">
					<a class="title alignleft" href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a>
					<span class="place alignleft"> <?php _e('at','vz_front_terms'); ?> <span> <?php echo $vz_location[0]; ?> </span> </span>
				</div>
				<div class="clear"></div>

			</div>

			<?php endwhile; ?>

		</div>

	<?php 	

	endif;

}



/*-----------------------------------------------------------------------------------*/
/*	AUTHOR: General Heading
/*-----------------------------------------------------------------------------------*/
function vz_general_author_head(&$the_author,$user_ID) {

	if( is_numeric($the_author) ) {
		$the_author2 = get_userdata($the_author);
	} else {
		$the_author2 = $the_author;
	}

	$author_user = new WP_User( $the_author2->ID );
	$author_role = $author_user->roles[0];
	global $wpdb, $wp;

	# Skiping if visted author role is not professor and is not student
	if( $author_role != 'professor' && $author_role != 'student' ) return;

	# Skiping heading if blogging and calendar, both are disabled
	if( !get_option('vz_plugins_extra_blogging') && !get_option('vz_plugins_extra_calendar') ) return; ?>

	<div class="user_info grid_12 rounded_2">

		<div class="profile alignleft rounded_2">

			<?php echo get_avatar( $the_author2->user_email, 50 ); 
			$the_group = (is_string(get_user_meta( $author_user->ID, 'vz_group' ))) ?  get_user_meta( $author_user->ID, 'vz_group' ) : '';	?>
			<div class="info alignleft">
				<h1> <?php echo $the_author2->display_name; ?></h1>
				<p> <?php echo ucfirst($the_group); ?> </p>
				<a href="#"> <?php echo $the_author2->user_email; ?> </a>
			</div>

			<?php if( is_user_logged_in() && $the_author2->ID == $user_ID ) : ?>
				<a href="<?php echo admin_url( 'profile.php' ); ?>" class="view alignleft rounded_2"> <?php _e('Edit profile','vz_front_terms'); ?> </a><br/>
				<a href="<?php echo wp_logout_url( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) ); ?>" class="view alignleft rounded_2"> <?php _e('Logout','vz_front_terms'); ?> </a>
			<?php else : 
				if( is_user_logged_in() && get_option('vz_plugins_extra_blogging') ) {
					$fterm = __('Follow','vz_front_terms'); 
					$fclass = 'follow';
					$fauthors = get_user_meta( get_current_user_id(), 'fauthors', true);
					$fauthors = explode(',', $fauthors);
					if( in_array($the_author2->ID, $fauthors) ) { $fterm = __('Unfollow','vz_front_terms'); $fclass = 'unfollow'; } ?> 
						<a href="#" class="view alignleft rounded_2 author <?php echo $fclass; ?>" id="author_<?php echo $the_author2->ID; ?>"> <?php echo $fterm; ?> </a><br/>
			<?php }

			endif; ?>

		</div>

		<?php if( get_option('vz_plugins_extra_calendar') ) : 
			$cal_page = get_posts(array(
                'post_type'  => 'page',
                'meta_key'   => '_wp_page_template',
                'meta_value' => 'template-calendar.php'
            ));

            $calendar_link = get_permalink( $cal_page[0]->ID ); ?>

			<div class="schedule alignleft rounded_2">

				<?php if( is_user_logged_in() ): ?>
				<a href="<?php echo $calendar_link; ?>" class="view alignleft rounded_2">
					<img src="<?php echo VZ_THEME_PATH; ?>/includes/images/icon_schedule.png"/>
					<?php _e('Schedule','vz_front_terms') ?>
				</a>
				<?php endif; ?>

				<div class="date alignleft">

					<div class="day alignleft">

						<?php

						$twodays = array( date('d') => (date('N', strtotime('today'))+1), 
										  date('d', strtotime('tomorrow')) => (date('N', strtotime('tomorrow'))+1) );

						$author_group = is_string(get_user_meta( $author_user->ID, 'vz_group')) ? get_user_meta( $author_user->ID, 'vz_group') : '';
						$post_group   = 'group';
						$post_order   = "ORDER BY $wpdb->posts.post_date ASC";

						$professor_lectures = '';
						if( $author_user->roles[0] == 'professor' ) {
							$professor_lectures = " AND $wpdb->posts.post_author = '".$the_author2->ID."'  ";
						}

						$filters = array('weekly','monthly','yearly','once');
						foreach ($filters as $filter) {
							$select_query[$filter] = " SELECT $wpdb->posts.ID, $wpdb->posts.post_title
							    FROM  $wpdb->posts, $wpdb->postmeta
							    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
								AND   $wpdb->postmeta.meta_key = '$post_group'
								AND   $wpdb->postmeta.meta_value = '$author_group' $professor_lectures
								AND   $wpdb->posts.post_type = 'calendar'
								AND   $wpdb->posts.post_excerpt = '$filter'
								AND   $wpdb->posts.post_status IN ('publish','future') ";
						}

						foreach ($twodays as $day => $dayinweek) {
							$week_condition = " AND DAYOFWEEK($wpdb->posts.post_date) = $dayinweek "; 
							$month_condition =" AND DAYOFMONTH($wpdb->posts.post_date) = $day "; 
							$year_condition = " AND DAYOFMONTH($wpdb->posts.post_date) = $day "; 
							$year_condition.= " AND MONTH($wpdb->posts.post_date) = $day "; 
							$once_condition = " AND DAYOFMONTH($wpdb->posts.post_date) = $day "; 
							$once_condition.= " AND MONTH($wpdb->posts.post_date) = ".date('m')." "; 
							$once_condition.= " AND YEAR($wpdb->posts.post_date) = ".date('Y')." "; 
				
							if($day==date('d')) :
								$today_events = $wpdb->get_results(" (".$select_query['weekly']." $week_condition $post_order) UNION 
									(".$select_query['monthly']." $month_condition $post_order) UNION
									(".$select_query['yearly']." $year_condition $post_order) UNION
									(".$select_query['once']." $once_condition $post_order) ", OBJECT);
							else :
								$tomorrow_events = $wpdb->get_results(" (".$select_query['weekly']." $week_condition $post_order) UNION 
									(".$select_query['monthly']." $month_condition $post_order) UNION
									(".$select_query['yearly']." $year_condition $post_order) UNION
									(".$select_query['once']." $once_condition $post_order) ", OBJECT);
							endif;
						}

						?>

						<a href="#" class="alignleft rounded_2">
							<?php _e('Today','vz_front_terms'); echo ($today_events) ? ' ('.count($today_events).')' : ''; ?>
						</a>

						<?php if(!$today_events) : ?>

							<span> <?php _e('You have some free time today.','vz_front_terms') ?> </span>

						<?php else : 

							if( count($today_events) == 1 ) :
								foreach ($today_events as $tevent) {
									$tevent_time = get_post_meta($tevent->ID, 'time', true);
									$tevent_hall = get_post_meta($tevent->ID, 'hall', true);
									echo $tevent_time.' - '.$tevent->post_title.', '.$tevent_hall;
								}
							else : ?>
								<ol class="ticker alignleft">
									<?php
									foreach ($today_events as $tevent) { $dsp = null;
										$tevent_time = get_post_meta($tevent->ID, 'time', true);
										$tevent_hall = get_post_meta($tevent->ID, 'hall', true);
										echo '<li '.$dsp.'>'.$tevent_time.' - '.$tevent->post_title.', '.$tevent_hall.'</li>';
										$dsp = 'style="display:none"';
									}
									?>
								</ol>
							<?php endif; 

						endif; ?>

					</div>

					<div class="clear"></div>

					<div class="day alignleft no_margin">

						<a href="#" class="alignleft rounded_2">
							<?php _e('Tomorrow','vz_front_terms'); echo ($tomorrow_events) ? ' ('.count($tomorrow_events).')' : ''; ?>
						</a>

						<?php if(!$tomorrow_events) : ?>

							<span> <?php _e('You have some free time tomorrow.','vz_front_terms') ?> </span>

						<?php else : 

							if( count($tomorrow_events) == 1 ) :
								foreach ($tomorrow_events as $trevent) {
									$trevent_time = get_post_meta($trevent->ID, 'time', true);
									$trevent_hall = get_post_meta($trevent->ID, 'hall', true);
									echo $trevent_time.' - '.$trevent->post_title.', '.$trevent_hall;
								}
							else : ?>
								<ol class="ticker alignleft">
									<?php
									foreach ($tomorrow_events as $trevent) { $dsp = null;
										$trevent_time = get_post_meta($trevent->ID, 'time', true);
										$trevent_hall = get_post_meta($trevent->ID, 'hall', true);
										echo '<li '.$dsp.'>'.$trevent_time.' - '.$trevent->post_title.', '.$trevent_hall.'</li>';
										$dsp = 'style="display:none"';
									}
									?>
								</ol>
							<?php endif; 

						endif; ?>

					</div>

				</div>

			</div>

		<?php endif; ?>

	</div>

	<?php

}



/*-----------------------------------------------------------------------------------*/
/*	SINGLE POST: Social sharing function
/*-----------------------------------------------------------------------------------*/
function vz_generate_social_sharing() { ?>

	<div class="shares alignleft">

		<?php if( !get_option('vz_options_social_fb_disabled') ) : ?>
			<iframe src="//www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;send=false&amp;layout=button_count&amp;width=83&amp;show_faces=true&amp;font=trebuchet+ms&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=294793147291639" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:83px; height:21px;" allowTransparency="true"></iframe>
		<?php endif; ?>

		<?php if( !get_option('vz_options_social_tw_disabled') ) : ?>
		<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
		<a href="http://twitter.com/share" class="twitter-share-button"
		data-url="<?php the_permalink(); ?>" data-via="wpbeginner"
		data-text="<?php the_title(); ?>" data-related="syedbalkhi:Founder of WPBeginner"
		data-count="horizontal">Tweet</a>
		<?php endif; ?>

		<?php if( !get_option('vz_options_social_gg_disabled') ) : ?>
		<div class="g-plusone"></div>
		<script type="text/javascript">
		  (function() { var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		    po.src = 'https://apis.google.com/js/plusone.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>
		<?php endif; ?>

		<?php if( !get_option('vz_options_social_li_disabled') ) : ?>
		<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
		<script type="IN/Share" data-url="<?php the_permalink(); ?>" data-counter="right"></script>
		<?php endif; ?>

		<div class="clear"></div>

	</div>


<?php 

}



/*-----------------------------------------------------------------------------------*/
/*	AUTHOR SIDEBAR: File box
/*-----------------------------------------------------------------------------------*/
function vz_author_sidebar_filebox($the_author,$the_author_role) {

	# Checking if file sharing is enabled
	if( !get_option('vz_plugins_extra_file_sharing') ) return;

	# If visited author role is not professor or student skip filebox
	if( $the_author_role != 'professor' && $the_author_role != 'student' ) return;

	# If filebox is disabled for visited author role skip filebox
	if( $the_author_role == 'professor' && get_option('vz_plugins_extra_proffile_disabled') ) return;
	if( $the_author_role == 'student'   && !get_option('vz_plugins_extra_studfile_enabled') ) return;

	# Getting logged user id
	$user_ID = get_current_user_id();
	global $wpdb; 

	?>

	<!-- BEGIN FILEBOX  -->
	<div class="widget rounded_2 filebox">

		<h1> <?php _e('Filebox','vz_front_terms'); ?>
			 <?php if( is_user_logged_in() && $the_author->ID == $user_ID ) : $showdelete = TRUE; ?>
		 		<a href="#" class="rounded_2 alignright grey_nav" id="upload_filebox"> <?php _e('Upload','vz_front_terms'); ?> </a>
		 	 <?php endif; ?>
		</h1>

		<ul>
			<?php

			# Fetching files of author
			$filebox_files = $wpdb->get_results(" SELECT ID,post_title,guid,post_mime_type
				FROM  $wpdb->posts WHERE post_type = 'attachment'
				AND   post_author = ".$the_author->ID." AND post_excerpt = 'filebox'
				ORDER BY post_date DESC ", OBJECT);

			# Listing author files without folders
			if ( count($filebox_files) > 0 ) :

				# For each file we show the html with certain icon based on file type
				foreach ($filebox_files as $file) : 
					$icon = '';
					$the_icon = explode('/',$file->post_mime_type);
					switch ($the_icon[0]) {
						case 'application': $icon = 'office'; break;
						case 'image':		$icon = 'media'; break;
						case 'video':		$icon = 'media'; break;
						case 'audio':		$icon = 'media'; break;
						case 'text':		$icon = 'text'; break;
					} ?>
					<li class="file <?php echo $icon; ?>"> 
						<a href="<?php echo $file->guid; ?>" target="_blank"> 
							<?php echo ucfirst($file->post_title); ?> 
						</a> 
						<?php echo($showdelete) ? '<a href="#" class="filebox delete" id="file_'.$file->ID.'"> </a>' : ''; ?>
					</li>
			<?php endforeach;

			endif;

			# Listing author files by folders
			if( get_user_meta($the_author->ID, 'vz_filebox_folders', true) ) :

				$folders = get_user_meta($the_author->ID, 'vz_filebox_folders', true);
				$folders = explode(',', $folders);

				if( count($folders) > 0 ) :

					foreach ($folders as $folder) {

						echo '<li class="folder">'.$folder.'</li>';

						$filebox_files = $wpdb->get_results(" SELECT ID,post_title,guid,post_mime_type
							FROM  $wpdb->posts WHERE post_type = 'attachment'
							AND   post_author = ".$the_author->ID." AND post_excerpt = '$folder'
							ORDER BY post_date DESC ", OBJECT);

						if ( count($filebox_files) > 0 ) :

							foreach ($filebox_files as $file) : 
								$icon = '';
								$the_icon = explode('/',$file->post_mime_type);
								switch ($the_icon[0]) {
									case 'application': $icon = 'office'; break;
									case 'image':		$icon = 'media'; break;
									case 'video':		$icon = 'media'; break;
									case 'audio':		$icon = 'media'; break;
									case 'text':		$icon = 'text'; break;
								} ?>
								<li class="file <?php echo $icon; ?>"> 
									<a href="<?php echo $file->guid; ?>" target="_blank"> 
										<?php echo ucfirst($file->post_title); ?> 
									</a> 
									<?php echo($showdelete) ? '<a href="#" class="filebox delete" id="file_'.$file->ID.'"> </a>' : ''; ?>
								</li>
						<?php endforeach;

						endif;
						
					}

				endif;

			endif;

			?>

		</ul>

		<!-- MODAL BOX  -->
		<div class="modal upload rounded_2" id="upload_modal" style="display:none">

			<form id="fbox_upload" method="post" enctype="multipart/form-data" target="upload_target" action="<?php echo admin_url('admin-ajax.php'); ?>">
				<input type="hidden" name="action" value="vz_ufrontajax" />
				<input type="hidden" name="sub_action" value="filebox_upload" />

				<h1> <?php _e('Upload file','vz_front_terms'); ?> <span id="modal_process" class="alignright"></span> </h1>

				<div class="details">
					<p> <?php _e('Existing Folder','vz_front_terms'); ?> </p>
					<select name="file_folder" class="selectbox rounded_2">
					<?php 
						echo '<option value="">'.__('None','vz_front_terms').'</option>';
						if( count($folders) > 0 ) {
	                        foreach ($folders as $folder) {
	                            echo "<option value='$folder'> $folder </option>";
	                        }
	                    } 
	                ?>
					</select>
					<p> <?php _e('New Folder','vz_front_terms'); ?> </p>
					<input type="text" name="new_folder" class="rounded_2" id="new_folder" />
					<div class="clear"></div>
					<p> <?php _e('File','vz_front_terms'); ?> </p>
					<input type="button" class="third" value="<?php _e('Browse','vz_front_terms'); ?>" onclick="$v(this).next().click();" />
					<input type="file" name="thefile" id="filebox-file" style="border:0;width:1px;height:1px;background:white" />
					<span id="sfile"></span><br/><br/>

					<div class="clear"></div>

					<input class="main" type="submit" value="<?php _e('Upload','vz_front_terms'); ?>" />
					<input class="second close alignright" type="reset" value="<?php _e('Cancel','vz_front_terms'); ?>" />

					<div class="clear"></div>
				</div>

				<iframe id="upload_target" name="upload_target" src="#" style="width:0px;height:0px;border:0px solid #fff;display:block"></iframe>

			</form>

		</div>

		<!-- MODAL BOX  -->
		<div class="modal fdelete rounded_2" style="display:none">
			<form class="vz_uajax" id="filebox_delete">
				<h1> <?php _e('Delete file with id:','vz_front_terms'); ?><strong id="del_id"></strong> </h1>
				<input type="hidden" name="file_id" id="file_id" value="" />

				<div class="details">
					<input class="main" type="submit" value="<?php _e('Confirm','vz_front_terms'); ?>" />
					<input class="second close alignright" type="reset" value="<?php _e('Cancel','vz_front_terms'); ?>" />
					<div class="clear"></div>
				</div>
			</form>
		</div>

	</div>
	<!-- END FILEBOX  -->

	<?php

}



/*-----------------------------------------------------------------------------------*/
/*	AUTHOR SIDEBAR:	Feeds
/*-----------------------------------------------------------------------------------*/
function vz_author_sidebar_feeds($the_author) { 
	if( !get_option('vz_plugins_extra_blogging') ) return;

	$user_ID = get_current_user_id();

	if($the_author->ID == $user_ID) : 
		$f_authors = get_user_meta($user_ID, 'fauthors', true); 
		$f_posts   = get_user_meta($user_ID, 'fposts', true); 

		if($f_authors) $f_authors = explode(',', $f_authors); ?>

		<div class="widget rounded_2 subscribed_posts">

			<h1> <?php _e('Following authors','vz_front_terms'); ?> </h1>

			<ul>

				<?php

				// Listing following authors
				if(!$f_authors) { echo '<li> '.__('You did not follow any author yet.','vz_front_terms').'</li>'; }
				else {
					foreach ($f_authors as $f_author) {
						$author_info = get_userdata($f_author);

						echo '<li> <a href="'.get_author_posts_url($f_author).'">'.$author_info->display_name.'</a> <a href="#" class="delete author single unfollow" id="author_'.$f_author.'"> </a></li>';
					}
				}

				?>

			</ul>

		</div>

		<?php if($f_posts) $f_posts = explode(',', $f_posts); ?>

		<div class="widget rounded_2 subscribed_posts">

			<h1> <?php _e('Following posts','vz_front_terms'); ?> </h1>

			<ul>

				<?php

				// Listing following posts
				if(!$f_posts) { echo '<li> '.__('You did not follow any post yet.','vz_front_terms').'</li>'; }
				else {
					foreach ($f_posts as $f_post) {
						$fpost = get_post($f_post); 
						echo '<li> <a href="'.get_permalink($fpost->ID).'">'.substr($fpost->post_title,0,38).'...</a> <a href="#" class="delete post single unfollow" id="post_'.$fpost->ID.'"> </a></li>';
					}
				}

				?>

			</ul>

		</div>

	<?php 

	endif;

}