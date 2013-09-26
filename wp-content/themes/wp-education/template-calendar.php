<?php 

/*
	Template Name: Calendar
*/

	get_header();

	// Main vars
	$c_user_id    = get_current_user_id( );
	$c_user_group = get_user_meta( $c_user_id, 'vz_group' );
	$send_lost_page = null; 
	$days_in_week = ''; $months_in_week = ''; $years_in_week = '';

	$mon_content = ''; $tue_content = ''; $wed_content = ''; $thu_content = '';	$fri_content = ''; $sat_content = '';

	// Checking if user can use calendar
	if( !$current_user_role || ( $current_user_role != 'student' && $current_user_role != 'professor' ) ) $send_lost_page = TRUE;
	if( $current_user_role == 'professor' && get_option('vz_plugins_extra_profcal_disabled') ) $send_lost_page = TRUE;
	if( $current_user_role == 'student' && get_option('vz_plugins_extra_studcal_disabled') ) $send_lost_page = TRUE;

	// If any of conditions above was catched we will send visitor to 404 page
	if( $send_lost_page ) :
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 ); exit();
	endif;

	// Fining last week of year to prevent irrational pagination
	$lastweekofyear = date('W', strtotime( date('Y-12-31') ) );
	if($lastweekofyear=='01') { $lastweekofyear = date('W', strtotime( date('Y-12-24') ) ); }

	// Getting and setting the_week variable by _GET
	$the_week = (int)date('W');
	if( isset($_GET['week']) ) {
		$the_week = wp_kses_post( $_GET['week'] );

	}

	// Building pagination links
	$prevlink = '?week='.($the_week-1);
	$nextlink = '?week='.($the_week+1);

	// Preventing irrational pagination
	if( $the_week < 0 ) { $prevlink = ''; }
	if( $the_week == $lastweekofyear ) { $nextlink = ''; }
	if( $the_week < 10) { $the_week = "0{$the_week}"; }

	//Getting weekDates from custom function which returns d-m-Y format of actual weeks 6 days
	$wdates = weekDates( $the_week, date('Y') ); 		// Array containing week dates
	$display_range = $wdates[1].__(' to ','vz_front_terms').$wdates[6];  // String format to display week days range

	//Appending all weekdays into query condition variables
	foreach ($wdates as $wdate) {
		$days_in_week   .= date('d',strtotime($wdate)).',';
		$months_in_week .= date('m',strtotime($wdate)).',';
		$years_in_week  .= date('Y',strtotime($wdate)).',';
	}

	//Removing last ,(coma)
	$days_in_week   = substr($days_in_week, 0, -1);
	$months_in_week = substr($months_in_week, 0, -1);
	$years_in_week  = substr($years_in_week, 0, -1);

?>

	<div class="container container_12">

		<?php vz_general_author_head( $c_user_id, $c_user_id ); ?>

		<!-- BEGIN CALENDAR TABLE  -->
		<div class="table grid_12">

			<h1 class="title top_rounded_2 center_text"> 
				<?php _e('Your schedule', 'vz_front_terms'); ?> 

				<?php if( $current_user_role == 'professor' ) : ?>
					<a href="#" class="grey_nav alignright rounded_2" id="add_event"> <?php _e('+ Add lecture', 'vz_front_terms'); ?>  </a>
				<?php else : ?>
					<a href="#" class="grey_nav alignright rounded_2" id="add_event"> <?php _e('+ New event', 'vz_front_terms'); ?>  </a>
				<?php endif; ?>
			</h1>

			<div class="nav">

				<a href="<?php echo $prevlink; ?>" class="grey_nav alignleft rounded_2"> Prev. week </a>

				<strong id="actual_week"> <?php echo _e('Week','vz_front_terms'); echo " $the_week"; ?>, </strong> <?php echo $display_range; ?>

				<a href="<?php echo $nextlink; ?>" class="grey_nav alignright rounded_2"> Next week </a>

			</div>

			<table>
				<thead>
					<tr>
						<td class="monday">   <?php _e('Monday','vz_front_terms'); ?> </td>
						<td class="tuesday">  <?php _e('Tuesday','vz_front_terms'); ?> </td>
						<td class="wednesday"> <?php _e('Wednesday','vz_front_terms'); ?> </td>
						<td class="thursday"> <?php _e('Thursday','vz_front_terms'); ?> </td>
						<td class="friday">   <?php _e('Friday','vz_front_terms'); ?> </td>
						<td class="saturday"> <?php _e('Saturday','vz_front_terms'); ?> </td>
					</tr>
				</thead>
				<tbody>

					<tr>

						<?php 
							// Setting repeat cycle filters
							$filters = array('weekly','monthly','yearly','once');

							// BUILDING QUERIES
							foreach ($filters as $filter) {

								$meta_sel = ''; $group_limit = '';

								// Proffessors can see all schedules
								if( $current_user_role == 'student' ) {
									$group_limit = "$wpdb->posts.ID = $wpdb->postmeta.post_id
													AND   $wpdb->postmeta.meta_key = 'group' 
													AND   $wpdb->postmeta.meta_value = '$c_user_group' AND";
									$meta_sel = ", $wpdb->postmeta";
								}
								
								// Main query without any repeat cycle condition
								$query_args = " SELECT $wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->posts.post_author, $wpdb->posts.post_date
											    FROM  $wpdb->posts $meta_sel
											    WHERE $group_limit $wpdb->posts.post_type = 'calendar' 
												AND   $wpdb->posts.post_status IN ('publish','future') ";

								// Appending repeat cycles condition
								switch ($filter) {
									case 'weekly' :
										$query_args.= " AND   $wpdb->posts.post_excerpt = '$filter' ";
									break;

									case 'monthly':
										$query_args.= " AND   $wpdb->posts.post_excerpt = '$filter' ";
										$query_args.= " AND   DAYOFMONTH($wpdb->posts.post_date) IN ($days_in_week) ";
									break;

									case 'yearly':
										$query_args.= " AND   $wpdb->posts.post_excerpt = '$filter' ";
										$query_args.= " AND   DAYOFMONTH($wpdb->posts.post_date) IN ($days_in_week) "; 
										$query_args.= " AND   MONTH($wpdb->posts.post_date) IN ($months_in_week) "; 
									break;

									case 'once': 
										$query_args.= " AND   $wpdb->posts.post_excerpt = '$filter' ";
										$query_args.= " AND   DAYOFMONTH($wpdb->posts.post_date) IN ($days_in_week) "; 
										$query_args.= " AND   MONTH($wpdb->posts.post_date) IN ($months_in_week) "; 
										$query_args.= " AND   YEAR($wpdb->posts.post_date) IN ($years_in_week) "; 
									break;
								}

								// Appending order query line
								$query_args.= " ORDER BY $wpdb->posts.post_date ASC ";

								// Inserting  query into queries array which will be executed on line: 147
								$queries[$filter] = $wpdb->get_results($query_args, OBJECT);
							}

							// If User is student, we add students extra personal schedule records
							if( $current_user_role == 'student' ) {
								$queries['student'] = $wpdb->get_results(" SELECT ID,post_title,post_date,post_author
												 FROM  $wpdb->posts 
												 WHERE post_type = 'calendar'
												 AND   post_status = 'private'
												 AND   post_author = $c_user_id
												 AND   DAYOFMONTH(post_date) IN ($days_in_week)
												 AND   MONTH(post_date) IN ($months_in_week)
												 AND   YEAR(post_date) IN ($years_in_week)
												 ORDER BY post_date ASC ", OBJECT);
							}

							// EXECUTING QUERIES
							foreach ($queries as $calendar_posts) {
								
								// Building export data
								foreach ( $calendar_posts as $cpost ) :

									// Prepairing content
									$the_title = str_replace('Private:', '', $cpost->post_title);
									$time	   = get_post_meta( $cpost->ID, 'time', true);
									$hall	   = get_post_meta( $cpost->ID, 'hall', true);
									$group	   = get_post_meta( $cpost->ID, 'group', true);

									if( $current_user_role == 'student' ) {
										$prof_info = get_userdata($cpost->post_author);
										$group .= '<br/>'.$prof_info->display_name;
									}

									// Collecting data with html
									$the_calendar = "<div class='lecture rounded_2'>";
									if($time) $the_calendar .= "<p class='time'> $time </p>";
									$the_calendar.= "<p class='lect'> $the_title </p>";
									if($hall) $the_calendar .= "<p class='hall'> $hall </p>";
									if($group) $the_calendar .= "<p class='group rounded_2'> $group </p>";
									if($c_user_id == $cpost->post_author) $the_calendar .= "<p class='edit'> <a href='#' class='edit_cal main' id='cal_".$cpost->ID."'> ".__('Edit','vz_front_terms')." </a> <a href='#' class='delete_cal second' id='cal_".$cpost->ID."'> ".__('Delete','vz_front_terms')." </a> </p>";

									$the_calendar.= "<div class='clear'></div> </div> <div class='clear'></div>";

									// Appending content to certain day of week by day number
									switch ( date( 'N', strtotime( $cpost->post_date ) ) ) {
										case 1: $mon_content .= $the_calendar; break;
										case 2: $tue_content .= $the_calendar; break;
										case 3: $wed_content .= $the_calendar; break;
										case 4: $thu_content .= $the_calendar; break;
										case 5: $fri_content .= $the_calendar; break;
										case 6: $sat_content .= $the_calendar; break;
									}

								endforeach;

							}

						?>

						<td class="crow"> <?php if($mon_content) echo $mon_content; ?> </td>
						<td class="crow"> <?php if($tue_content) echo $tue_content; ?> </td>
						<td class="crow"> <?php if($wed_content) echo $wed_content; ?> </td>
						<td class="crow"> <?php if($thu_content) echo $thu_content; ?> </td>
						<td class="crow"> <?php if($fri_content) echo $fri_content; ?> </td>
						<td class="crow"> <?php if($sat_content) echo $sat_content; ?> </td>

					</tr>

				</tbody>
			</table>

			<!-- CALENDAR MODALS -->
			<?php if( $current_user_role == 'professor') : ?>

				<div class="modal add rounded_2" id="add_modal" style="display:none">

					<form class="vz_uajax" id="add_lecture">

						<h1> <?php _e('Add new lecture','vz_front_terms'); ?> <span id="modal_process" class="alignright"></span> </h1>

						<div class="details">
							<p> <?php _e('Lecture name','vz_front_terms'); ?> </p>
							<input type="text" name="lecture_name" class="rounded_2" id="lecture_name" />

							<p> <?php _e('Lecture date','vz_front_terms'); ?>: </p>
							<input type="text" name="lecture_date" class="rounded_2" id="lecture_date" />
							
							<p> <?php _e('Lecture hall/room','vz_front_terms'); ?>: </p>
							<input type="text" name="lecture_hall" class="rounded_2" />

							<p class="half alignleft"> <?php _e('Lecture start time','vz_front_terms'); ?>: </p> 
							<p class="half alignright"> <?php _e('Lecture end time','vz_front_terms'); ?>: </p>
							<input type="text" name="starttime" class="rounded_2 half alignleft" id="timepicker_start" /> 
							<input type="text" name="finishtime" class="rounded_2 half alignright" id="timepicker_end" />

							<div class="clear"></div>
							<p> <?php _e('Student group','vz_front_terms'); ?>: </p>
							<select name="lecture_group" class="selectbox rounded_2">
							<?php 
								echo '<option value="">'.__('None','vz_front_terms').'</option>';
								if( get_option("vz_groups") ) {
	                                $groups = get_option("vz_groups");
	                                $groups = array_reverse($groups);
	                                foreach ($groups as $group) {
	                                    echo "<option value='$group'> $group </option>";
	                                }
	                            } 
	                        ?>
							</select>

							<p> <?php _e('Repeat lecture','vz_front_terms'); ?>: </p>
							<select name="lecture_repeat" class="selectbox rounded_2">
								<option value=""><?php _e('None','vz_front_terms'); ?></option>
								<option value="weekly"><?php _e('Weekly','vz_front_terms'); ?></option>
								<option value="monthly"><?php _e('Monthly','vz_front_terms'); ?></option>
								<option value="yearly"><?php _e('Yearly','vz_front_terms'); ?></option>
							</select>

							<div class="clear"></div>
							<input class="main" type="submit" value="<?php _e('Add lecture','vz_front_terms'); ?>" />
							<input class="second close alignright" type="reset" value="<?php _e('Cancel','vz_front_terms'); ?>" />

							<div class="clear"></div>
						</div>

					</form>

				</div>

				<div class="modal edit rounded_2" id="edit_modal" style="display:none">

					<form class="vz_uajax" id="edit_lecture">

						<h1> <?php _e('Edit lecture','vz_front_terms'); ?> <span id="modal_process" class="alignright"></span> </h1>
						<input type="hidden" name="lecture_id" id="lecture_id" value="" />

						<div class="details">
							<p> Lecture name </p>
							<input type="text" name="lecture_name" class="rounded_2" id="lecture_name2" />

							<p> Lecture date: </p>
							<input type="text" name="lecture_date" class="rounded_2" id="lecture_date2" />
							
							<p> Lecture hall/room: </p>
							<input type="text" name="lecture_hall" class="rounded_2" id="lecture_hall" />

							<p class="half alignleft"> Lecture start time: </p> 
							<p class="half alignright"> Lecture end time: </p>
							<input type="text" name="starttime" class="rounded_2 half alignleft" id="timepicker_start2" /> 
							<input type="text" name="finishtime" class="rounded_2 half alignright" id="timepicker_end2" />

							<div class="clear"></div>
							<p> Student group: </p>
							<select name="lecture_group" id="lecture_group" class="selectbox rounded_2">
							<?php 
								echo '<option value="">'.__('None','vz_front_terms').'</option>';
								if( get_option("vz_groups") ) {
	                                $groups = get_option("vz_groups");
	                                $groups = array_reverse($groups);
	                                foreach ($groups as $group) {
	                                    echo "<option value='$group'> $group </option>";
	                                }
	                            } 
	                        ?>
							</select>

							<p> Repeat lecture: </p>
							<select name="lecture_repeat" id="lecture_repeat" class="selectbox rounded_2">
								<option value=""><?php _e('None','vz_front_terms'); ?></option>
								<option value="weekly"><?php _e('Weekly','vz_front_terms'); ?></option>
								<option value="monthly"><?php _e('Monthly','vz_front_terms'); ?></option>
								<option value="yearly"><?php _e('Yearly','vz_front_terms'); ?></option>
							</select>

							<div class="clear"></div>
							<input class="main" type="submit" value="<?php _e('Edit lecture','vz_front_terms'); ?>" />
							<input class="second close alignright" type="reset" value="<?php _e('Cancel','vz_front_terms'); ?>" />

							<div class="clear"></div>
						</div>

					</form>

				</div>

				<div class="modal delete rounded_2" style="display:none">
					<form class="vz_uajax" id="delete_lecture">
						<h1> <?php _e('Delete lecture with id:','vz_front_terms'); ?><strong id="del_id"></strong> </h1>
						<input type="hidden" name="lecture_id" id="lecture_id" value="" />

						<div class="details">
							<input class="main" type="submit" value="<?php _e('Confirm','vz_front_terms'); ?>" />
							<input class="second close alignright" type="reset" value="<?php _e('Cancel','vz_front_terms'); ?>" />
							<div class="clear"></div>
						</div>
					</form>
				</div>
				
			<?php else : ?>

				<div class="modal add rounded_2" id="add_modal" style="display:none">

					<form class="vz_uajax" id="add_lecture">

						<h1> <?php _e('Add new event','vz_front_terms'); ?> <span id="modal_process" class="alignright"></span> </h1>

						<div class="details">
							<p> <?php _e('Event name','vz_front_terms'); ?> </p>
							<input type="text" name="lecture_name" class="rounded_2" id="lecture_name" />

							<p> <?php _e('Event date','vz_front_terms'); ?>: </p>
							<input type="text" name="lecture_date" class="rounded_2" id="lecture_date" />

							<p class="half alignleft"> <?php _e('Event start time','vz_front_terms'); ?>: </p> 
							<p class="half alignright"> <?php _e('Event end time','vz_front_terms'); ?>: </p>
							<input type="text" name="starttime" class="rounded_2 half alignleft" id="timepicker_start" /> 
							<input type="text" name="finishtime" class="rounded_2 half alignright" id="timepicker_end" />

							<div class="clear"></div>
							<input class="main" type="submit" value="<?php _e('Add event','vz_front_terms'); ?>" />
							<input class="second close alignright" type="reset" value="<?php _e('Cancel','vz_front_terms'); ?>" />

							<div class="clear"></div>
						</div>

					</form>

				</div>

				<div class="modal edit rounded_2" id="edit_modal" style="display:none">

					<form class="vz_uajax" id="edit_lecture">

						<h1> <?php _e('Edit event','vz_front_terms'); ?> <span id="modal_process" class="alignright"></span> </h1>
						<input type="hidden" name="lecture_id" id="lecture_id" value="" />

						<div class="details">
							<p> <?php _e('Event name','vz_front_terms'); ?> </p>
							<input type="text" name="lecture_name" class="rounded_2" id="lecture_name2" />

							<p> <?php _e('Event date','vz_front_terms'); ?>: </p>
							<input type="text" name="lecture_date" class="rounded_2" id="lecture_date2" />
							
							<p class="half alignleft"> <?php _e('Event start time','vz_front_terms'); ?>: </p> 
							<p class="half alignright"> <?php _e('Event end time','vz_front_terms'); ?>: </p>
							<input type="text" name="starttime" class="rounded_2 half alignleft" id="timepicker_start2" /> 
							<input type="text" name="finishtime" class="rounded_2 half alignright" id="timepicker_end2" />

							<div class="clear"></div>
							<input class="main" type="submit" value="<?php _e('Edit event','vz_front_terms'); ?>" />
							<input class="second close alignright" type="reset" value="<?php _e('Cancel','vz_front_terms'); ?>" />

							<div class="clear"></div>
						</div>

					</form>

				</div>

				<div class="modal delete rounded_2" style="display:none">
					<form class="vz_uajax" id="delete_lecture">
						<h1> <?php _e('Delete event with id:','vz_front_terms'); ?><strong id="del_id"></strong> </h1>
						<input type="hidden" name="lecture_id" id="lecture_id" value="" />

						<div class="details">
							<input class="main" type="submit" value="<?php _e('Confirm','vz_front_terms'); ?>" />
							<input class="second close alignright" type="reset" value="<?php _e('Cancel','vz_front_terms'); ?>" />
							<div class="clear"></div>
						</div>
					</form>
				</div>

			<?php endif; ?>
			<!-- END CALENDAR MODALS -->

		</div>
		<!-- END CALENDAR TABLE  -->

	</div>

<?php get_footer(); ?>