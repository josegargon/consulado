<?php

/****************************************
* Shortcodes
****************************************/

# Defining temporary global variables
$GLOBALS['vz_lists'] = array();
$GLOBALS['vz_tabs']  = array();
$GLOBALS['vz_accs']  = array();

# Adding lists shortcode
add_shortcode( 'vz_lists', 'show_vz_lists' );
function show_vz_lists( $atts, $content ) {
	extract( shortcode_atts( array( 'type' => 'plus'), $atts ) );

	do_shortcode( $content );

	if( count($GLOBALS['vz_lists'])>0 ) :

		$shortcode_content = '<ul class="shortcode list '.$type.'">';

			foreach ($GLOBALS['vz_lists'] as $list_item) {
				$shortcode_content .= '<li>'. $list_item . '</li>';
			}

		$shortcode_content .= '</ul>';

	endif;

	$GLOBALS['vz_lists'] = array(); // Reseting global lists variable

	return $shortcode_content;
}

# Adding list shortcode
add_shortcode( 'vz_list', 'manage_vz_list_item' );
function manage_vz_list_item( $atts, $content ) {
	array_push( $GLOBALS['vz_lists'], $content ); // Assigning to global variable
}


# Adding tabs shortcode
add_shortcode( 'vz_tabs', 'show_vz_tabs' );
function show_vz_tabs( $atts, $content ) {
	extract( shortcode_atts( array( 'type' => 'plus'), $atts ) );

	do_shortcode( $content );

	if( count($GLOBALS['vz_tabs'])>0 ) :

		$shortcode_content = '<div class="clear"></div>';

		$shortcode_content .= '<div class="vz_tabs">';

			$shortcode_content .= '<ul>';

				foreach ($GLOBALS['vz_tabs'] as $tab) {
					$tabctr++;

					$shortcode_content .= '<li> <a href="#vz_tabs-'.$tabctr.'">'. $tab['title'] .'</a> </li>';
				}

			$shortcode_content .= '</ul>';

			foreach ($GLOBALS['vz_tabs'] as $tab) {
				$tabctr2++;

				$shortcode_content .= '<div id="vz_tabs-'.$tabctr2.'"> '. $tab['content'] .'</div>';
			}

		$shortcode_content .= '</div>';

	endif;

	$GLOBALS['vz_tabs'] = array(); // Reseting global tabs variable

	return $shortcode_content;
}

# Adding tabs shortcode
add_shortcode( 'vz_tab', 'manage_vz_tab_item' );
function manage_vz_tab_item( $atts, $content ) {
	extract( shortcode_atts( array( 'title' => 'Tab'), $atts ) );
	array_push( $GLOBALS['vz_tabs'], array('title' => $title, 'content' => $content ) );
}


# Adding accordion shortcode
add_shortcode( 'vz_accordions', 'show_vz_accordions' );
function show_vz_accordions( $atts, $content ) {
	extract( shortcode_atts( array( 'type' => 'plus'), $atts ) );

	do_shortcode( $content );

	if( count($GLOBALS['vz_accs'])>0 ) :

		$shortcode_content = '<div class="clear"></div>';

		$shortcode_content .= '<div class="vz_accordion">';

			foreach ($GLOBALS['vz_accs'] as $accs) {
				$shortcode_content .= '<h3>'. $accs['title'] .'</h3>';
				$shortcode_content .= '<div>'. $accs['content'] .'</div>';
			}

		$shortcode_content .= '</div>';

	endif;

	$GLOBALS['vz_accs'] = array(); // Reseting global accs variable

	return $shortcode_content;
}


# Adding accordion shortcode
add_shortcode( 'vz_accordion', 'manage_vz_accordion' );
function manage_vz_accordion( $atts, $content ) {
	extract( shortcode_atts( array( 'title' => 'Accordion'), $atts ) );
	array_push( $GLOBALS['vz_accs'], array('title' => $title, 'content' => $content ) );
}


# Adding blockquote shortcode
add_shortcode( 'vz_blockquote', 'manage_vz_blockquote' );
function manage_vz_blockquote( $atts, $content ) {
	extract( shortcode_atts( array( 'author' => ''), $atts ) );
	$shortcode_content = '<div class="shortcode blockquote">
		<div class="blockquote-content"> '.$content.' </div>
		<div class="author"> '.$author.' </div>
		<div class="clear"></div>
	</div>';

	return $shortcode_content;
}


# Adding message shortcode
add_shortcode( 'vz_message', 'show_vz_message' );
function show_vz_message( $atts, $content ) {
	extract( shortcode_atts( array( 'type' => 1, 'label' => null), $atts ) );

	if(!$label) :
		switch ($type) {
			case 1: $label = __('Success','vz_front_terms'); break;
			case 2: $label = __('Fail','vz_front_terms'); break;
			case 3: $label = __('Warning','vz_front_terms'); break;
			case 4: $label = __('Info','vz_front_terms'); break;
		}
	endif;

	$shortcode_content = '<div class="shortcode rounded_2 message type_'.$type.'">
		<span class="alignleft"> '.$label.' </span>
		<p class="alignleft"> '.$content.' </p>
		<a class="close alignright" href="#" onclick="$v(this).parent().fadeOut(\'normal\');return false"> x </a>
		<div class="clear"></div>
	</div>';

	return $shortcode_content;
}


# Adding heading shortcode
add_shortcode( 'vz_heading', 'show_vz_heading' );
function show_vz_heading( $atts, $content ) {
	extract( shortcode_atts( array( 'size' => 1 ), $atts ) );

	$shortcode_content = '<div class="shortcode heading">';
	$shortcode_content .= "<h{$size}>{$content}</h{$size}>";
	$shortcode_content .= '</div>';

	return $shortcode_content;
}


# Adding vz_forms shortcode
add_shortcode( 'vzform', 'show_vzform' );
function show_vzform( $atts ) {
	extract( shortcode_atts( array( 'id' => 1 ), $atts ) );

	$the_post = get_post( $id );
	$submitname = ( strlen($the_post->post_content)>0 ) ? $the_post->post_content : __('Submit', 'vz_front_terms');

	if( get_post_type( $id ) != 'vz_custom_forms' ) return;

	$form_elements = get_post_meta( $id, 'vz_custom_form_elements', true);

	$shortcode_content = '<div class="clear"></div><form class="vzforms_ajax" id="vzform_'.$id.'">';

	foreach ($form_elements as $form_element) {

		$shortcode_content .= build_input( $form_element['fieldname'], $form_element['placeholder'], $form_element['type'], $form_element['required'] );
		
	}

	$shortcode_content .= '<div class="clear"></div>';
	$shortcode_content .= '<input type="submit" value="'.$submitname.'" class="main" />';
	$shortcode_content .= '<input type="reset" value="'.__('Cancel', 'vz_front_terms').'" class="second" />';

	$shortcode_content .= '</form> <div class="clear"></div>';



	return $shortcode_content;
}

# Function for building inputs
function build_input( $fieldname, $placeholder, $type, $required = null ) {

	$input_content = "<label>$fieldname:</label> ";
	$req_ct = ($required) ? ' class="rounded_2 vzinput_req" ' : ' class="rounded_2" ' ;
	$fieldname = strtolower( preg_replace("/[^A-Za-z0-9]/", "", $fieldname) );

	switch ($type) {

		case 'text':

			$input_content .= '<input type="text" name="'.$fieldname.'" placeholder="'.$placeholder.'" '.$req_ct.' onfocus="this.placeholder = \'\' " onblur="this.placeholder = \''.$placeholder.'\'" />';

		break;

		case 'email':

			$input_content .= '<input type="email" name="'.$fieldname.'" placeholder="'.$placeholder.'" '.$req_ct.' onfocus="this.placeholder = \'\' " onblur="this.placeholder = \''.$placeholder.'\'" />';

		break;

		case 'textarea':

			$input_content .= '<textarea name="'.$fieldname.'" placeholder="'.$placeholder.'" '.$req_ct.' onfocus="this.placeholder = \'\' " onblur="this.placeholder = \''.$placeholder.'\'"></textarea>';

		break;

		case 'checkbox':

			$input_content .= '<input type="checkbox" name="'.$fieldname.'" value="yes" />';

		break;

	}

	return $input_content;

}