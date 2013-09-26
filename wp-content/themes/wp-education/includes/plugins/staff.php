<?php

/****************************************
* Staff post type
****************************************/



# Registering the staff post type which will save staff
add_action( 'init', 'register_staff_posts' );
function register_staff_posts() {
    $labels = array(
        'name'               => _x( 'Staff', 'post type general name','vz_terms' ),
        'singular_name'      => _x( 'Staff', 'post type singular name','vz_terms' ),
        'add_new'            => _x( 'Add New', 'event','vz_terms' ),
        'add_new_item'       => __( 'Add New Staff','vz_terms' ),
        'edit_item'          => __( 'Edit Staff','vz_terms' ),
        'new_item'           => __( 'New Staff','vz_terms' ),
        'all_items'          => __( 'All Staff','vz_terms' ),
        'view_item'          => __( 'View Staff','vz_terms' ),
        'search_items'       => __( 'Search Staff','vz_terms' ),
        'not_found'          => __( 'No staff member found','vz_terms' ),
        'not_found_in_trash' => __( 'No staff member found in the Trash','vz_terms' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Staff'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our staff and staffs specific data',
        'query_var'     => true,
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title','editor', 'thumbnail' ),
        'has_archive'   => true,
        'hierarchical'  => true,
        'publicly_queryable'   => true,
        'rewrite'       => array("slug" => get_option('vz_options_slugs_staff','staff') ),
        'capability_type'   => 'staff',
        'capabilities' => array(
            'publish_posts'         => 'publish_staffs',
            'edit_posts'            => 'edit_staffs',
            'edit_others_posts'     => 'edit_others_staffs',
            'delete_posts'          => 'delete_staffs',
            'delete_others_posts'   => 'delete_others_staffs',
            'read_private_posts'    => 'read_private_staffs',
            'edit_post'             => 'edit_staff',
            'delete_post'           => 'delete_staff',
            'read_post'             => 'read_staff',
            'edit_page'             => 'edit_staff',
        ),
    );
    register_post_type( 'staff', $args );
    flush_rewrite_rules();
    
    add_action( 'save_post', 'save_staff_details' );
}

/* Adding metabox */
add_action( 'add_meta_boxes', 'add_staff_metabox' );
function add_staff_metabox() {
    add_meta_box('wpe_staff', 'Staff details', 'wpe_staff_details', 'staff', 'side');
}


function wpe_staff_details() {
    global $post;
    $meta = get_post_meta($post->ID);
    $vzstaffposition = (isset($meta['vz_staffposition'])) ? $meta['vz_staffposition'][0] : '';
    $vz_stafffacebook = (isset($meta['vz_stafffacebook'])) ? $meta['vz_stafffacebook'][0] : '';
    $vz_stafftwitter = (isset($meta['vz_stafftwitter'])) ? $meta['vz_stafftwitter'][0] : ''; ?>
    <div>

        <p>
            <label for="position">Position:</label> <br/>
            <input type="text" style="width:100%" id="position" name="vz_staffposition" value="<?php echo $vzstaffposition; ?>" />
        </p>

        <p>
            <label for="location">Facebook:</label> <br/>
            <input type="text" style="width:100%" id="location" name="vz_stafffacebook" value="<?php echo $vz_stafffacebook; ?>" />
        </p>

        <p>
            <label for="location">Twitter:</label> <br/>
            <input type="text" style="width:100%" id="location" name="vz_stafftwitter" value="<?php echo $vz_stafftwitter; ?>" />
        </p>

        <div class="clear"></div> 

    </div>

    <?php
}

function save_staff_details($post_id) {
 
    if (isset($_POST['post_type']) && $_POST['post_type'] != 'staff') 
    {
        return $post_id;
    }

    if(isset($_POST['vz_staffposition'])) 
    {
        update_post_meta( $post_id, 'vz_staffposition', $_POST['vz_staffposition']);
    }

    if(isset($_POST['vz_stafffacebook'])) 
    {
    update_post_meta( $post_id, 'vz_stafffacebook', $_POST['vz_stafffacebook']);
    }

    if(isset($_POST['vz_stafftwitter'])) 
    {
    update_post_meta( $post_id, 'vz_stafftwitter', $_POST['vz_stafftwitter']);
    }
}



/* Adding admin role capability to access fully staff. */
add_action( 'admin_init', 'add_staff_caps');
function add_staff_caps() {
    $role_object = get_role('administrator');
    $role_object->add_cap('publish_staffs');
    $role_object->add_cap('edit_staffs');
    $role_object->add_cap('edit_others_staffs');
    $role_object->add_cap('delete_staffs');
    $role_object->add_cap('delete_others_staffs');
    $role_object->add_cap('edit_staff');
    $role_object->add_cap('delete_staff');
    $role_object->add_cap('read_staff');
}