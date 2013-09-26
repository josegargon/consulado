<?php

/****************************************
* Theme Options
****************************************/


# Allowing iframes on wp_kses
$allowedposttags["iframe"] = array( "src" => array(), "height" => array(), "width" => array() );


# Getting listed on admin menu instantly
add_action("admin_menu", "admin_theme_menu");
function admin_theme_menu() {
    add_menu_page('Theme Management', VZ_THEME_NAME, 'administrator', plugin_basename(__FILE__), 'show_theme_options');
}



# Defining menu pages
$vz_theme_options_menu = array(  
    __('General', 'vz_terms') => array(
        __('Architecture',      'vz_terms') => 'vz_options_arch',
        __('Homepage',          'vz_terms') => 'vz_options_home',
        __('Blog',              'vz_terms') => 'vz_options_btemp',
        __('Contact',           'vz_terms') => 'vz_options_ct',
        __('Logo and Body',     'vz_terms') => 'vz_options_look',
        __('Social',            'vz_terms') => 'vz_options_social',
        __('Language links',    'vz_terms') => 'vz_options_lang_links',
        __('Slugs',             'vz_terms') => 'vz_options_slugs',
        __('Google Analytics',  'vz_terms') => 'vz_options_google_analytics', ),

    __('Text editing', 'vz_terms') => array(
        __('Header',            'vz_terms') =>'vz_text_header',
        __('Footer',            'vz_terms') =>'vz_text_footer', 
        __('Manual translation','vz_terms') =>'vz_text_mtranslate', 
        ),

    __('Styling', 'vz_terms') => array(
        __('Presets',           'vz_terms') => 'vz_styling_presets',
        __('Sizing',            'vz_terms') => 'vz_styling_sizing',
        __('Main colors',       'vz_terms') => 'vz_styling_main', ),

    __('Theme Plugins', 'vz_terms') => array(
        __('Contact form builder','vz_terms')=>'vz_plugins_forms',
        __('Newsletter',        'vz_terms') => 'vz_plugins_newsletter',
        __('Extra',             'vz_terms') => 'vz_plugins_extra', ),
);

if( get_option('vz_styling_main_advanced_enabled') ) :

    $vz_theme_options_menu[__('Styling', 'vz_terms')][__('Header','vz_terms')]  = 'vz_styling_header';
    $vz_theme_options_menu[__('Styling', 'vz_terms')][__('Sidebar','vz_terms')] = 'vz_styling_sidebar';
    $vz_theme_options_menu[__('Styling', 'vz_terms')][__('Content','vz_terms')] = 'vz_styling_content';
    $vz_theme_options_menu[__('Styling', 'vz_terms')][__('Buttons','vz_terms')] = 'vz_styling_buttons';
    $vz_theme_options_menu[__('Styling', 'vz_terms')][__('Footer','vz_terms')]  = 'vz_styling_footer';

endif;

# Generating view
function show_theme_options() {

    global $vz_theme_options_menu;  //Line: 17 ~ 44
    global $vz_theme_options_pages; //Look for theme_options_pages.php file

    ?>

    <div id="theme-options">
        <div class="top"> <h1> Theme <span class="regular">Options</span> </h1> </div>
        <div class="clear"></div>

        <?php show_theme_options_menu($vz_theme_options_menu); ?>

        <div class="right">
            <?php pages_generator($vz_theme_options_pages); ?>
        </div>

    </div>

    <?php
    
}



# Generating menu of theme options
function show_theme_options_menu($nav_items) {

    ?>

    <div class="left">

        <div class="first-column">
            <div class="label"> <h1>Main</h1> </div>
            <ul class="nav">
                <?php
                    $active_class = ' class="active"';
                    foreach(array_keys($nav_items) as $main_nav) {
                        $main_nav_link = str_replace(" ", "_", $main_nav);

                        echo '<li><a href="#'.strtolower($main_nav_link).'" '.$active_class.'>'.$main_nav.'</a></li>';
                        $active_class = '';
                    }
                ?>
            </ul>
            <div class="clear"></div>
        </div>

        <div class="second-column">
            <div class="label"> <h1>Options</h1> </div>
            <?php
                $active_nav = 'show';               
                foreach($nav_items as $_mainnav => $_subnav ) {
                    $main_nav_link = strtolower(str_replace(" ", "_", $_mainnav));
                    echo '<ul class="nav '.$active_nav.'" id="'.$main_nav_link.'">';
                    foreach($_subnav as $subnav => $subnav_id) {
                        echo '<li><a href="#" id="'.$subnav_id.'">'.$subnav.'</a></li>';
                        $active_nav = 'hide';
                    }
                    echo '</ul>';
                }
            ?>
            <div class="clear"></div>
        </div>

        <div class="clear"></div>

        <p class="copyright"> &copy; <?php echo date('Y ').VZ_THEMEOPTIONS_COPYRIGHT; ?> </p>

    </div>

    <?php

}