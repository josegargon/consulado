<?php

/****************************************
* Events post type
****************************************/



# Registering the events post type which will save events
add_action( 'init', 'register_event_posts' );
function register_event_posts() {
    $labels = array(
        'name'               => _x( 'Events', 'post type general name','vz_terms' ),
        'singular_name'      => _x( 'Event', 'post type singular name','vz_terms' ),
        'add_new'            => _x( 'Add New', 'event','vz_terms' ),
        'add_new_item'       => __( 'Add New Event','vz_terms' ),
        'edit_item'          => __( 'Edit Event','vz_terms' ),
        'new_item'           => __( 'New Event','vz_terms' ),
        'all_items'          => __( 'All Events','vz_terms' ),
        'view_item'          => __( 'View Event','vz_terms' ),
        'search_items'       => __( 'Search Events','vz_terms' ),
        'not_found'          => __( 'No events found','vz_terms' ),
        'not_found_in_trash' => __( 'No events found in the Trash','vz_terms' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Events'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our events and events specific data',
        'query_var'        => true,
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title','editor' ),
        'has_archive'   => true,
        'hierarchical'  => true,
        'publicly_queryable'   => true,
        'rewrite'       => array("slug" => get_option('vz_options_slugs_events','event') ),
        'capability_type'   => 'events',
        'capabilities' => array(
            'publish_posts'         => 'publish_events',
            'edit_posts'            => 'edit_events',
            'edit_others_posts'     => 'edit_others_events',
            'delete_posts'          => 'delete_events',
            'delete_others_posts'   => 'delete_others_events',
            'read_private_posts'    => 'read_private_events',
            'edit_post'             => 'edit_event',
            'delete_post'           => 'delete_event',
            'read_post'             => 'read_event',
            'edit_page'             => 'edit_event',
        ),
    );
    register_post_type( 'events', $args );
    flush_rewrite_rules();
    
    add_action( 'save_post', 'save_event_details' );
}

/* Adding metabox */
add_action( 'add_meta_boxes', 'add_event_metabox' );
function add_event_metabox() {
    add_meta_box('wpe_events', 'Event details', 'wpe_event_details', 'events', 'side');
}


function wpe_event_details() {
    global $post;
    $meta = get_post_meta($post->ID);
    $vzdate = (isset($meta['vz_date'])) ? $meta['vz_date'][0] : '';
    $vzloc = (isset($meta['vz_location'])) ? $meta['vz_location'][0] : ''; ?>
    <div>

        <p>
            <label for="datepicker">Date:</label> <br/>
            <input type="text" style="width:100%" id="datepicker" name="vz_date" value="<?php echo $vzdate; ?>" />
        </p>

        <p>
            <label for="location">Location:</label> <br/>
            <input type="text" style="width:100%" id="location" name="vz_location" value="<?php echo $vzloc; ?>" />
        </p>

        <div class="clear"></div> 

    </div>

    <?php
}

function save_event_details($post_id) {
 
    if (isset($_POST['post_type']) && $_POST['post_type'] != 'events') 
    {
        return $post_id;
    }

    if(isset($_POST['vz_date'])) 
    {
        update_post_meta( $post_id, 'vz_date', $_POST['vz_date']);
    }

    if(isset($_POST['vz_location'])) 
    {
    update_post_meta( $post_id, 'vz_location', $_POST['vz_location']);
    }
}



/* Adding admin role capability to access fully events. */
add_action( 'admin_init', 'add_events_caps');
function add_events_caps() {
    $role_object = get_role('administrator');
    $role_object->add_cap('publish_events');
    $role_object->add_cap('edit_events');
    $role_object->add_cap('edit_others_events');
    $role_object->add_cap('delete_events');
    $role_object->add_cap('delete_others_events');
    $role_object->add_cap('edit_event');
    $role_object->add_cap('delete_event');
    $role_object->add_cap('read_event');
}