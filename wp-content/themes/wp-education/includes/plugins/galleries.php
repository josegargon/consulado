<?php

/****************************************
* Galleries post type
****************************************/



# Registering the galleries post type which will save galleries
add_action( 'init', 'register_gallery_posts' );
function register_gallery_posts() {
    $labels = array(
        'name'               => _x( 'Galleries', 'post type general name','vz_terms' ),
        'singular_name'      => _x( 'Gallery', 'post type singular name','vz_terms' ),
        'add_new'            => _x( 'Add New', 'gallery','vz_terms' ),
        'add_new_item'       => __( 'Add New Gallery','vz_terms','vz_terms' ),
        'edit_item'          => __( 'Edit Gallery','vz_terms' ),
        'new_item'           => __( 'New Gallery','vz_terms' ),
        'all_items'          => __( 'All Galleries','vz_terms' ),
        'view_item'          => __( 'View Gallery','vz_terms' ),
        'search_items'       => __( 'Search Galleries','vz_terms' ),
        'not_found'          => __( 'No galleries found','vz_terms' ),
        'not_found_in_trash' => __( 'No galleries found in the Trash','vz_terms' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Galleries'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our galleries and galleries specific data',
        'query_var'     => true,
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'   => false,
        'hierarchical'  => false,
        'taxonomies'    => array('folder'),
        'publicly_queryable'   => true,
        'rewrite'       => array("slug" => get_option('vz_options_slugs_gal','gal') , 'with_front' => FALSE ),
        'capability_type'   => 'galleries',
        'capabilities' => array(
            'publish_posts'         => 'publish_galleries',
            'edit_posts'            => 'edit_galleries',
            'edit_others_posts'     => 'edit_others_galleries',
            'delete_posts'          => 'delete_galleries',
            'delete_others_posts'   => 'delete_others_galleries',
            'read_private_posts'    => 'read_private_galleries',
            'edit_post'             => 'edit_gallery',
            'delete_post'           => 'delete_gallery',
            'read_post'             => 'read_gallery',
            'edit_page'             => 'edit_gallery',
        ),
    );
    register_post_type( 'galleries', $args );
    flush_rewrite_rules();
}

# Registering gallery folders
add_action( 'init', 'create_folder_taxonomies', 0 );
function create_folder_taxonomies() {
  $labels = array(
    'name' => _x( 'Folders', 'taxonomy general name','vz_terms' ),
    'singular_name' => _x( 'Folder', 'taxonomy singular name','vz_terms' ),
    'search_items' =>  __( 'Search Folders','vz_terms' ),
    'popular_items' => __( 'Popular Folders','vz_terms' ),
    'all_items' => __( 'All Folders','vz_terms' ),
    'parent_item' => __( 'Parent Folders','vz_terms' ),
    'parent_item_colon' => __( 'Parent Folder:','vz_terms' ),
    'edit_item' => __( 'Edit Folder','vz_terms' ),
    'update_item' => __( 'Update Folder','vz_terms' ),
    'add_new_item' => __( 'Add New Folder','vz_terms' ),
    'new_item_name' => __( 'New Folder Name','vz_terms' ),
  );
  register_taxonomy('folders',array('galleries'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'folder' , 'with_front' => FALSE ),
  ));
}


/* Adding admin role capability to access fully events. */
add_action( 'admin_init', 'add_galleries_caps');
function add_galleries_caps() {
    $role_object = get_role('administrator');
    $role_object->add_cap('publish_galleries');
    $role_object->add_cap('edit_galleries');
    $role_object->add_cap('edit_others_galleries');
    $role_object->add_cap('delete_galleries');
    $role_object->add_cap('delete_others_galleries');
    $role_object->add_cap('edit_gallery');
    $role_object->add_cap('delete_gallery');
    $role_object->add_cap('read_gallery');
}