<?php

/****************************************
* Courses post type
****************************************/



# Registering the courses post type which will save courses
add_action( 'init', 'register_course_posts' );
function register_course_posts() {
    $labels = array(
        'name'               => _x( 'Courses', 'post type general name','vz_terms' ),
        'singular_name'      => _x( 'Course', 'post type singular name','vz_terms' ),
        'add_new'            => _x( 'Add New', 'course','vz_terms' ),
        'add_new_item'       => __( 'Add New Course','vz_terms' ),
        'edit_item'          => __( 'Edit Course','vz_terms' ),
        'new_item'           => __( 'New Course','vz_terms' ),
        'all_items'          => __( 'All Courses','vz_terms' ),
        'view_item'          => __( 'View Course','vz_terms' ),
        'search_items'       => __( 'Search Courses','vz_terms' ),
        'not_found'          => __( 'No courses found','vz_terms' ),
        'not_found_in_trash' => __( 'No courses found in the Trash','vz_terms' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Courses'
    );

    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our courses and courses specific data',
        'query_var'     => true,
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title','editor' ),
        'has_archive'   => false,
        'hierarchical'  => false,
        'publicly_queryable'   => true,
        'rewrite'       => array("slug" => get_option('vz_options_slugs_courses','course') ),
        'capability_type'   => 'course',
        'capabilities' => array(
            'publish_posts'         => 'publish_courses',
            'edit_posts'            => 'edit_courses',
            'edit_others_posts'     => 'edit_others_courses',
            'delete_posts'          => 'delete_courses',
            'delete_others_posts'   => 'delete_others_courses',
            'read_private_posts'    => 'read_private_courses',
            'edit_post'             => 'edit_course',
            'delete_post'           => 'delete_course',
            'read_post'             => 'read_course',
            'edit_page'             => 'edit_course',
        ),
    );
    register_post_type( 'courses', $args );
    flush_rewrite_rules();
    
    add_action( 'save_post', 'save_course_details' );
}

/* Adding metabox */
add_action( 'add_meta_boxes', 'add_courses_metabox' );
function add_courses_metabox() {
    add_meta_box('wpe_courses', 'Course details', 'wpe_course_details', 'courses', 'side');
}


function wpe_course_details() {
    global $post;
    $meta = get_post_meta($post->ID);
    $prof = (isset($meta['prof'])) ? $meta['prof'][0] : '';
    $stime = (isset($meta['starttime'])) ? $meta['starttime'][0] : '';
    $ftime = (isset($meta['finishtime'])) ? $meta['finishtime'][0] : '';
    $hall = (isset($meta['class'])) ? $meta['class'][0] : ''; ?>
    <div>

        <p>
            <label for="prof">Professor:</label>
            <input id="prof" class="widefat" name="prof" type="text" value="<?php echo $prof; ?>" />
        </p>

        <p>
            <label for="timepicker_start">Start time:</label> <br/>
            <input type="text" style="width:100%" id="timepicker_start" name="starttime" value="<?php echo $stime; ?>" />
        </p>

        <p>
            <label for="timepicker_end">Finish time:</label> <br/>
            <input type="text" style="width:100%" id="timepicker_end" name="finishtime" value="<?php echo $ftime; ?>" />
        </p>

        <p>
            <label for="class">Hall/Room:</label> 
            <input id="class" name="class" class="widefat" type="text" value="<?php echo $hall; ?>" />
        </p>

        <div class="clear"></div> 

    </div>

    <?php
}

function save_course_details($post_id) {
 
    if (isset($_POST['post_type']) && $_POST['post_type'] != 'courses') 
    {
        return $post_id;
    }

    if(isset($_POST['prof'])) 
    {
        update_post_meta($post_id,'prof',$_POST['prof']);
    }

    if(isset($_POST['class'])) 
    {
        update_post_meta($post_id,'class',$_POST['class']);
    }

    if(isset($_POST['starttime'])) 
    {
        update_post_meta($post_id,'starttime',$_POST['starttime']);
    }

    if(isset($_POST['finishtime'])) 
    {
        update_post_meta($post_id,'finishtime',$_POST['finishtime']);
    }
}



/* Adding admin role capability to access fully courses. */
add_action( 'admin_init', 'add_courses_caps');
function add_courses_caps() {
    $role_object = get_role('administrator');
    $role_object->add_cap('publish_courses');
    $role_object->add_cap('edit_courses');
    $role_object->add_cap('edit_others_courses');
    $role_object->add_cap('delete_courses');
    $role_object->add_cap('delete_others_courses');
    $role_object->add_cap('edit_course');
    $role_object->add_cap('delete_course');
    $role_object->add_cap('read_course');
}