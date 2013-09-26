<?php

/****************************************
* Books post type
****************************************/



# Registering the books post type which will save books
add_action( 'init', 'register_book_posts' );
function register_book_posts() {
    $labels = array(
        'name'               => _x( 'Books', 'post type general name','vz_terms' ),
        'singular_name'      => _x( 'Book', 'post type singular name','vz_terms' ),
        'add_new'            => _x( 'Add New', 'book','vz_terms' ),
        'add_new_item'       => __( 'Add New Book','vz_terms','vz_terms' ),
        'edit_item'          => __( 'Edit Book','vz_terms' ),
        'new_item'           => __( 'New Book','vz_terms' ),
        'all_items'          => __( 'All Books','vz_terms' ),
        'view_item'          => __( 'View Book','vz_terms' ),
        'search_items'       => __( 'Search Books','vz_terms' ),
        'not_found'          => __( 'No books found','vz_terms' ),
        'not_found_in_trash' => __( 'No books found in the Trash','vz_terms' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Books'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our books and books specific data',
        'query_var'     => true,
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title', 'editor' ),
        'has_archive'   => false,
        'hierarchical'  => false,
        'taxonomies'    => array('folder'),
        'publicly_queryable'   => true,
        'rewrite'       => array("slug" => get_option('vz_options_slugs_book','book') , 'with_front' => FALSE ),
        'capability_type'   => 'books',
        'capabilities' => array(
            'publish_posts'         => 'publish_books',
            'edit_posts'            => 'edit_books',
            'edit_others_posts'     => 'edit_others_books',
            'delete_posts'          => 'delete_books',
            'delete_others_posts'   => 'delete_others_books',
            'read_private_posts'    => 'read_private_books',
            'edit_post'             => 'edit_book',
            'delete_post'           => 'delete_book',
            'read_post'             => 'read_book',
            'edit_page'             => 'edit_book',
        ),
    );
    register_post_type( 'books', $args );
    flush_rewrite_rules();

    add_action( 'save_post', 'save_book_details' );
}

# Registering books genres
add_action( 'init', 'create_genre_taxonomies', 0 );
function create_genre_taxonomies() {
  $labels = array(
    'name' => _x( 'Genres', 'taxonomy general name','vz_terms' ),
    'singular_name' => _x( 'Genre', 'taxonomy singular name','vz_terms' ),
    'search_items' =>  __( 'Search Genres','vz_terms' ),
    'popular_items' => __( 'Popular Genres','vz_terms' ),
    'all_items' => __( 'All Genres','vz_terms' ),
    'parent_item' => __( 'Parent Genres','vz_terms' ),
    'parent_item_colon' => __( 'Parent Genre:','vz_terms' ),
    'edit_item' => __( 'Edit Genre','vz_terms' ),
    'update_item' => __( 'Update Genre','vz_terms' ),
    'add_new_item' => __( 'Add New Genre','vz_terms' ),
    'new_item_name' => __( 'New Genre Name','vz_terms' ),
  );
  register_taxonomy('genres',array('books'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'genre' , 'with_front' => FALSE ),
  ));
}

/* Adding metabox */
add_action( 'add_meta_boxes', 'add_book_metabox' );
function add_book_metabox() {
    add_meta_box('wpe_book', 'Book details', 'wpe_book_details', 'books', 'normal');
}

function wpe_book_details() {
    global $post;
    $meta = get_post_meta($post->ID);
    $vz_book_left = (isset($meta['vz_book_left'])) ? $meta['vz_book_left'][0] : '';
    $vz_book_back = (isset($meta['vz_book_back'])) ? $meta['vz_book_back'][0] : ''; 
    $vz_book_color = (isset($meta['vz_book_color'])) ? $meta['vz_book_color'][0] : ''; 
    $vz_dl_text = (isset($meta['vz_dl_text'])) ? $meta['vz_dl_text'][0] : ''; 
    $vz_dl_url = (isset($meta['vz_dl_url'])) ? $meta['vz_dl_url'][0] : ''; 
    $vz_book_color = (isset($meta['vz_book_color'])) ? $meta['vz_book_color'][0] : null; 

    ?>
    <div>

        <p>
            <label>Book left:</label> <br/>
            <input type="text" style="width:100%" name="vz_book_left" value="<?php echo $vz_book_left; ?>" />
        </p>

        <p>
            <label>Book back:</label> <br/>
            <textarea class="widefat" name="vz_book_back" ><?php echo $vz_book_back; ?></textarea>
        </p>

        <p>
            <label>Book download button text:</label> <br/>
            <input type="text" style="width:100%" name="vz_dl_text" value="<?php echo $vz_dl_text; ?>" />
        </p>

        <p>
            <label>Book download button url:</label> <br/>
            <input type="text" style="width:100%" name="vz_dl_url" value="<?php echo $vz_dl_url; ?>" />
        </p>

        <div style="width: 160px;margin-bottom:35px" class="wppick">
            <label>Book color:</label> <br/>
            <input type="text" style="width:160px;height:28px;position: absolute;<?php echo ($vz_book_color) ? "background: $vz_book_color" : ''; ?>" name="vz_book_color" value="<?php echo $vz_book_color; ?>" class="wp-cpick" />
            <div id="picker" style="display:none"></div>
        </div>

        <div class="clear"></div> 

    </div>

    <?php
}

function save_book_details($post_id) {
 
    if (isset($_POST['post_type']) && $_POST['post_type'] != 'books') 
    {
        return $post_id;
    }

    if(isset($_POST['vz_book_left'])) 
    {
        update_post_meta( $post_id, 'vz_book_left', $_POST['vz_book_left']);
    }

    if(isset($_POST['vz_book_back'])) 
    {
        update_post_meta( $post_id, 'vz_book_back', $_POST['vz_book_back']);
    }

    if(isset($_POST['vz_dl_text'])) 
    {
        update_post_meta( $post_id, 'vz_dl_text', $_POST['vz_dl_text']);
    }

    if(isset($_POST['vz_dl_url'])) 
    {
        update_post_meta( $post_id, 'vz_dl_url', $_POST['vz_dl_url']);
    }

    if(isset($_POST['vz_book_color'])) 
    {
        update_post_meta( $post_id, 'vz_book_color', $_POST['vz_book_color']);
    }
}


/* Adding admin role capability to access fully events. */
add_action( 'admin_init', 'add_books_caps');
function add_books_caps() {
    $role_object = get_role('administrator');
    $role_object->add_cap('publish_books');
    $role_object->add_cap('edit_books');
    $role_object->add_cap('edit_others_books');
    $role_object->add_cap('delete_books');
    $role_object->add_cap('delete_others_books');
    $role_object->add_cap('edit_book');
    $role_object->add_cap('delete_book');
    $role_object->add_cap('read_book');
}