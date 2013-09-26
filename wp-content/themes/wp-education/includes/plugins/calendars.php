<?php

/****************************************
* Calendars custom post type
****************************************/



# Registering the newsletter post type which will save subscribers
add_action( 'init', 'register_vz_custom_calendars' );
function register_vz_custom_calendars() {

    $args = array(
        'label'              => 'Calendar',
        'labels'             => array('name' => _x('Calendar', 'Post type general name','vz_terms')),
        'supports'           => array('title'),
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => false,
        'show_in_menu'       => false,
        'show_in_nav_menus'  => false,
        'query_var'          => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false
    );

    register_post_type( 'calendar', $args );
    flush_rewrite_rules();

}

