<?php

/****************************************
* Theme Options Functions
****************************************/



# Printing js vars for javascript
add_action('admin_head', 'add_js_vars');
function add_js_vars() {

    ?>
    <script type="text/javascript"> var dirtoinc = "<?php echo VZ_THEMEOPTIONS_INC; ?>"; </script>
    <?php

}



# Adding style of theme options panel
add_action('admin_enqueue_scripts', 'add_wpe_css');
function add_wpe_css() {
    wp_enqueue_style('thickbox');

    wp_register_style('wpe-options', VZ_THEMEOPTIONS_INC.'/css/style.css', false, "1.0", "all");
    wp_register_style('jquery-ui', VZ_THEMEOPTIONS_INC.'/css/jqueryui.css', false, "1.0", "all");
    wp_enqueue_style('wpe-options');
    wp_enqueue_style('jquery-ui');
}



# Adding js of theme options panel
add_action('admin_enqueue_scripts', 'add_wpe_js');
function add_wpe_js() {
    wp_register_script('wpe-options', VZ_THEMEOPTIONS_INC."/js/theme_options.js", false, "1.0");   
    wp_enqueue_script('wpe-options');

    wp_register_script('wpe-farbtastic', VZ_THEMEOPTIONS_INC."/js/farbtastic.js", false, "1.2");   
    wp_enqueue_script('wpe-farbtastic');

    wp_register_script('jquery-selectbox', VZ_THEMEOPTIONS_INC.'/js/selectbox.js',false,"0.2");
    wp_register_script('jquery-ui-timepicker', VZ_THEMEOPTIONS_INC.'/js/timepicker.js',false,"1.2");

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-selectbox');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('jquery-ui-timepicker');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
}



# If logo is set changing the logo of admin
if( get_option('vz_options_look_logo') ) :
    add_action('login_head', 'admin_custom_login_logo');
    function admin_custom_login_logo() {
        $logo_url = ( is_numeric(get_option('vz_options_look_logo')) ? wp_get_attachment_url(get_option('vz_options_look_logo')) : get_option('vz_options_look_logo') );
        echo '<style type="text/css">
            h1 a { background: url('.$logo_url.') no-repeat top center !important; height: 100px !important; }
        </style>';
    }
endif;


# Listing user groups
add_filter('manage_users_columns', 'vz_user_group_column');
function vz_user_group_column($columns) {
    $columns['vz_group'] = 'Group';
    return $columns;
}
 
# Showing user groups
add_action('manage_users_custom_column',  'vz_show_user_group', 10, 3);
function vz_show_user_group($value, $column_name, $user_id) {
    if ( 'vz_group' == $column_name ) {
        if( get_user_meta( $user_id, 'vz_group' ) ) {
            return get_user_meta( $user_id, 'vz_group' );
        } else {
            return __('--','vz_terms');
        }
    }
}



# Showing user group field
add_action( 'show_user_profile', 'vz_show_user_group_field' );
add_action( 'edit_user_profile', 'vz_show_user_group_field' );
function vz_show_user_group_field( $user ) {

    if ( current_user_can('professor') ) { $role = 'professor'; }
    if ( current_user_can('student') ) { $role = 'student'; }
    if($role == 'student' || $role == 'professor') : ?>

    <h3>Select your group</h3>

    <table class="form-table">

        <tr>
            <th><label for="vz_group">Group</label></th>

            <td>
                <select name="vz_group" id="vz_group">
                    <?php 
                        if( get_option("vz_groups") ) {
                            $groups = get_option("vz_groups");
                            $groups = array_reverse($groups);
                            foreach ($groups as $group) {
                                echo "<option value='$group'";
                                echo ($group==get_user_meta( get_current_user_id(), 'vz_group' )) ? 'selected="selected"' : '';
                                echo "> $group </option>";
                            }
                        }
                    ?>
                </select>
                <span class="description">Please select your group.</span>
            </td>
        </tr>

    </table>
<?php endif;

}



# Updating user group field
add_action( 'personal_options_update', 'vz_update_user_group' );
add_action( 'edit_user_profile_update', 'vz_update_user_group' );
function vz_update_user_group( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) return false;
    update_user_meta( $user_id, 'vz_group', $_POST['vz_group'] );
}



# Shortcodes Editor button and javascript
add_action('init', 'shortcodes_addbuttons');
function shortcodes_addbuttons() {
    add_filter("mce_external_plugins", "shortcodes_tinymce_plugin");
    add_filter('mce_buttons', 'register_shortcodes_button');
}

function register_shortcodes_button($buttons) {
   array_push($buttons, "separator", "shortcodes");
   return $buttons;
}

function shortcodes_tinymce_plugin($plugin_array) {
   $plugin_array['shortcodes'] = VZ_THEME_PATH.'/admin/includes/js/script_editor.js';
   return $plugin_array;
}