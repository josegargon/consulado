<?php

/**********************************************
* Theme Options Ajax
***********************************************/



// Global options array
$vz_options = array();


/* Default function for handling data in any case where we want to interrupt 
*  further actions and just execute setting values function.
*/
function default_case() {
	global $vz_options;

	foreach ($_POST as $option => $new_value) {
		$vz_options[$option] = $new_value;
	}

}



# Handling ajax requests ############################################################################
add_action('wp_ajax_vz_tho_ajax', 'vz_tho_ajax');
function vz_tho_ajax() {
	global $wpdb;
	global $wp_rewrite;
	global $vz_options;


	# Starting
	$page    = wp_kses_post($_POST['page']);
	unset($_POST['page'], $_POST['action']);


	# Default  values
	$vz_options = FALSE;
	$message = __('Changes have been saved successfully.', 'vz_terms');


	# Switching between page actions
	switch ($page) {


		/***** PLUGINS *****/
		### NEWSLETTER
		// Removing subscriber
		case 'vz_plugins_newsletter_remove_subscriber':
			$the_id = wp_kses_post($_POST['the_id']);

			wp_delete_post( $the_id, TRUE );

			$message = 'Removed';
		break;


		// Listing subscribers
		case 'vz_plugins_newsletter_get_subscribers':
			$offset = wp_kses_post($_POST['offset']) * 10;
			$loop = new WP_Query( array( 'post_type' => 'vz_custom_newsletter', 'showposts' => 10, 'offset' => $offset ) ); 

			while ( $loop->have_posts() ) : $loop->the_post();
				echo '<li> '.get_the_title().' <a href="#" id="'.get_the_ID().'" class="delete alignright red">X</a> </li>';
			endwhile;

			$message = FALSE;
		break;


		// Mailing subscribers
		case 'vz_plugins_newsletter':

			$query_args = array( 'post_type' => 'vz_custom_newsletter' );
			$subscribers = new WP_Query( $query_args );
			if ( $subscribers->have_posts() ) :

				while ( $subscribers->have_posts() ) : $subscribers->the_post();
					wp_mail( $subscribers->post_title, $_POST['subject'], $_POST['message'], 'From: '.$_POST['email'] );
				endwhile;

				$message = __('E-mails have been sent successfully.', 'vz_terms');
			else :
				$message = __('There is no subscriber to mail.', 'vz_terms');
			endif;

		break;


		### FORMS
		// Removing form
		case 'vz_plugins_forms_remove_form':
			$the_id = wp_kses_post($_POST['the_id']);

			wp_delete_post( $the_id, TRUE );
			$wpdb->query(" DELETE FROM $wpdb->postmeta WHERE post_id = '$the_id' ");

			$message = 'Removed';
		break;


		// Listing forms
		case 'vz_plugins_forms_get_form':
			$the_id     = wp_kses_post($_POST['the_id']);
			$the_post   = get_post( $the_id, OBJECT );
			$form_name  = $the_post->post_title;
			$form_email = $the_post->post_excerpt;
			$elements   = get_post_meta($the_id, 'vz_custom_form_elements');
			$elements   = $elements[0];

			echo $form_name.'|'.$form_email.'|';

			foreach($elements as $element) {
				echo '<li>';

				$tsel = ''; $esel = ''; $tasel = ''; $csel = ''; $req_check = '';

				if($element['required']=='yes') { $req_check = 'selected="selected"'; }

				switch($element['type']) {
					case 'text':     $tsel = 'selected="selected"'; break;
					case 'email':    $esel = 'selected="selected"'; break;
					case 'textarea': $tasel= 'selected="selected"'; break;
					case 'checkbox': $csel = 'selected="selected"'; break;
				}

				echo '<div> <input type="text" name="fieldname[]" value="'.$element['fieldname'].'" /> </div>'.
					 '<div> <input type="text" name="placeholder[]" value="'.$element['placeholder'].'" /> </div>'.
					 '<div>
						<select name="type[]" style="height:25px">
							<option value="text" '.$tsel.'>'.__('Text', 'vz_terms').'</option> 
							<option value="email" '.$esel.'>'.__('Email', 'vz_terms').'</option>
							<option value="textarea" '.$tasel.'>'.__('Text Area', 'vz_terms').'</option>
							<option value="checkbox" '.$csel.'>'.__('Checkbox', 'vz_terms').'</option>
						</select>
					  </div>'.
					 '<div> <select name="required[]" style="height:25px">
								<option value="">'.__('No', 'vz_terms').'</option>
								<option value="yes" '.$req_check.'>'.__('Yes', 'vz_terms').'</option> 
							</select> </div>'.
					 '</li>';
			}

			$message = FALSE;
		break;


		// Listing forms
		case 'vz_plugins_forms_get_forms':
			$offset = wp_kses_post($_POST['offset']) * 10;
			$loop = new WP_Query( array( 'post_type' => 'vz_custom_forms', 'showposts' => 10, 'offset' => $offset ) ); 

			while ( $loop->have_posts() ) : $loop->the_post();
				echo '<li> <span class="name">'.get_the_title().'</span> <span>[vzform id="'.get_the_ID().'"]</span>  <a href="#" id="'.get_the_ID().'" class="delete alignright red">x</a> <a href="#" id="'.get_the_ID().'" class="edit alignright">Edit</a> </li>';
			endwhile;

			$message = FALSE;
		break;


		// Saving form
		case 'vz_plugins_forms':
			if($_POST['form_id']>0) {
				$actual_form = array(
				  'post_title'    => wp_strip_all_tags( $_POST['formname'] ),
				  'post_content'  => wp_strip_all_tags( $_POST['submitname'] ),
				  'post_excerpt'  => wp_strip_all_tags( $_POST['formemail'] ),
				  'ID'            => wp_strip_all_tags( $_POST['form_id'] ) );
				wp_update_post( $actual_form );

				foreach($_POST['fieldname'] as $ix => $value) {
					$elements[$ix] = array(
						'fieldname'     => wp_strip_all_tags( $value ),
						'placeholder'   => wp_strip_all_tags( $_POST['placeholder'][$ix] ),
						'type'          => wp_strip_all_tags( $_POST['type'][$ix] ),
						'required'      => wp_strip_all_tags( $_POST['required'][$ix] ) 
					);
				}

				update_post_meta(wp_strip_all_tags( $_POST['form_id'] ), 'vz_custom_form_elements', $elements);
			} else {
				$new_form = array(
				  'post_title'    => wp_strip_all_tags( $_POST['formname'] ),
				  'post_content'  => wp_strip_all_tags( $_POST['submitname'] ),
				  'post_excerpt'  => wp_strip_all_tags( $_POST['formemail'] ),
				  'ping_status'   => 'closed',
				  'comment_status'=> 'closed',
				  'post_status'   => 'private',
				  'post_type'     => 'vz_custom_forms' );
				wp_insert_post( $new_form );

				foreach($_POST['fieldname'] as $ix => $value) {
					$elements[$ix] = array(
						'fieldname'     => wp_strip_all_tags( $value ),
						'placeholder'   => wp_strip_all_tags( $_POST['placeholder'][$ix] ),
						'type'          => wp_strip_all_tags( $_POST['type'][$ix] ),
						'required'      => wp_strip_all_tags( $_POST['required'][$ix] ) 
					);
				}
				
				add_post_meta( $wpdb->insert_id , 'vz_custom_form_elements', $elements, true);
			}

			$message = __('Form have been saved successfully.', 'vz_terms');           
		break;
		/***** PLUGINS *****/


		// Setting Architecture options
		case 'vz_options_arch':
			foreach (array_keys($_POST) as $position) : $new_value = '';

				//Setting containers elements by order
				foreach ($_POST[$position] as $container) {
					$con_att    = explode(':', $container);
					$con_name   = $con_att[0];
					$con_status = $con_att[1];

					if($con_name!='logo') $new_value .= "{$con_name},";

					$vz_options[ "{$position}_{$con_name}_disabled" ] = $con_status;
				}

				$vz_options[$position] = substr($new_value, 0, -1);

			endforeach;
		break;


		// Adding new group
		case 'vz_plugins_extra_add_group':
			$name = ucfirst( wp_kses_post($_POST['name']) );

			$actual_roles = array($name);
			if( get_option("vz_groups") ) {

				$actual_roles = get_option("vz_groups");

				if ( in_array($name, $actual_roles) ) {
					echo '<p>Group already exists!</p>|'; die();
				} else {
					array_push($actual_roles,$name);
				}

			}

			update_option( "vz_groups", $actual_roles );

			echo '<p>Group have been added successfully. Refresh to see. </p> <img src="'.VZ_THEMEOPTIONS_INC.'/images/tick.png" class="ok" />|ok';

			die();
		break;


		// Getting groups
		case 'vz_plugins_extra_get_groups':

			echo '<option value="">'.__('None','vz_terms').'</option>';
			if( get_option("vz_groups") ) :
				$actual_roles = array_reverse( get_option("vz_groups") );
				foreach ( $actual_roles as $arole ) {
					echo "<option value='$arole'> $arole </option>";
				}

			endif;

			die();
		break;


		// Edit group
		case 'vz_plugins_extra_edit_group':
			$a_name = ucfirst( wp_kses_post($_POST['name']) );
			$a_group = wp_kses_post($_POST['group']);

			if( get_option("vz_groups") ) :
				$actual_roles = get_option("vz_groups");
				$new_roles = str_replace($a_group, $a_name, $actual_roles);
				update_option( "vz_groups", $new_roles );
				$message = 'Group was edited successfully. Refresh to see.';
			endif;

		break;


		// Delete group
		case 'vz_plugins_extra_delete_group':
			if( strlen($_POST['group'])>0 ) :
				
				$a_group = wp_kses_post($_POST['group']);

				if( get_option("vz_groups") ) :

					$actual_roles = get_option("vz_groups");
					$new_roles = array_diff( $actual_roles, array($a_group) );
					update_option( "vz_groups", $new_roles );
					$message = 'Group was deleted successfully. Refresh to see.';
				
				endif;

			endif;

		break;


		// Checking if custom roles are allowed to publish posts
		case 'vz_plugins_extra':

			$prof = get_role( 'professor' );
			$stud = get_role( 'student' );

			if( $_POST['profpost_disabled'] == 'disabled' ) {
				$prof->remove_cap( 'professor', 'publish_posts' );
			} else {
				if( !$prof->has_cap('publish_posts') ) $prof->add_cap( 'professor', 'publish_posts' );
			}

			if( $_POST['studpost_disabled'] == 'disabled' ) {
				$stud->remove_cap( 'student', 'publish_posts' );
			} else {
				if( !$stud->has_cap('publish_posts') ) $stud->add_cap( 'student', 'publish_posts' );
			}

			unset($_POST['profpost_disabled']);
			unset($_POST['studpost_disabled']);

			default_case();

		break;


		// Adding extra show checkboxes
		case 'vz_options_social':

			$vz_options['rss_show']    = $_POST['rss_show'];
			$vz_options['fb_show']     = $_POST['fb_show'];
			$vz_options['tw_show']     = $_POST['tw_show'];
			$vz_options['gplus_show']  = $_POST['gplus_show'];
			$vz_options['lin_show']    = $_POST['lin_show'];
			$vz_options['skype_show']  = $_POST['skype_show'];
			$vz_options['yt_show']     = $_POST['yt_show'];
			$vz_options['vimeo_show']  = $_POST['vimeo_show'];
			$vz_options['igram_show']  = $_POST['igram_show'];
			$vz_options['pin_show']    = $_POST['pin_show'];
			$vz_options['xing_show']   = $_POST['xing_show'];
			$vz_options['dribbble_show'] = $_POST['dribbble_show'];
			$vz_options['tumblr_show'] = $_POST['tumblr_show'];

			default_case();

		break;


		// Setting default options
		default:
			default_case();
		break;
	}


	# Updating options
	if($vz_options) :
		foreach ($vz_options as $option => $new_value) : $the_option_name = "{$page}_{$option}";

			if(strlen($new_value)>0 ) {

				update_option( $the_option_name, wp_kses_post($new_value) );
			} else {
				delete_option($the_option_name);
			}

		endforeach;
	endif;



	# Displaying message
	if($page == 'vz_styling_main' || $page == 'vz_plugins_extra') { $message.= ' Refresh to see.'; }
	if($message) echo '<p>'.$message.'</p> <img src="'.VZ_THEMEOPTIONS_INC.'/images/tick.png" class="ok" />';



	# Finishing
	die();
}