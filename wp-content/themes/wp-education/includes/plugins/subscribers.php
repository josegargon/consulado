<?php

/****************************************
* Subscribers plugin
****************************************/



# Registering the newsletter post type which will save subscribers
add_action( 'init', 'register_vz_custom_newsletter' );
function register_vz_custom_newsletter() {

    $args = array(
        'label'              => 'Subscribers',
        'labels'             => array('name' => _x('Subscribers', 'Post type general name','vz_terms')),
        'supports'           => array('title'),
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => false,
        'show_in_menu'       => false,
        'show_in_nav_menus'  => false,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false
    );

    register_post_type( 'vz_custom_newsletter', $args );
}



# Actual subscribers (Page generator element)
function generate_subscribers($arguments) {
    global $wpdb;
    $totalSubscribers = $wpdb->get_row(" SELECT COUNT(*) as total FROM $wpdb->posts WHERE $wpdb->posts.post_type = 'vz_custom_newsletter' ",ARRAY_A);
    $totalSubscribers = $totalSubscribers['total'];

    ?>

    <div class="content">

        <ul class="subscribers_list">

        </ul>

        <input type="hidden" id="total" value="<?php echo $totalSubscribers; ?>" />
        <input type="hidden" id="offset" value="0" />

        <a href="#" class="prev"> Prev </a>
        <a href="#" class="next"> Next </a>

        <div class="clear"></div>
    </div>

    <?php

}