<?php

/****************************************
* Custom Widgets
****************************************/


######################## Sidebar ########################
# Side Navigation
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Side_Navigation" );' ) );
class VZ_Side_Navigation extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_side_nav', '(WPE) Side Navigation',
            array( 'description'    => 'Help visitors to navigate easily. (left/right)',
                   'classname'      => 'side_nav')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args );
        global $post;
        $title  = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $menu   = wp_get_nav_menu_object( $instance['vz_side_nav'] );

        if ( !$menu ) return;

        echo $before_widget;
        if ( !empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        echo '<ul>';

        $menu_items = wp_get_nav_menu_items( $menu ); 
        $sublinks_catched = null; $menu_html = '';
        foreach ( $menu_items as $key => $menu_item ) {
            if( $menu_item->url == get_permalink($post->ID) && $menu_item->title == get_the_title($post->ID) ) {
                $actualpage_ID = $menu_item->ID;
            }

            if( $menu_item->menu_item_parent == 0 ) {
                if($sublinks_catched) { $menu_html.= '</ul> <div class="clear"></div> </li>'; unset($sublinks_catched); }
                $menu_html.= '<li class="parent"> <a href="'.$menu_item->url.'"> '.$menu_item->title.' </a> </li>';
            } else {
                if( $menu_item->menu_item_parent == $actualpage_ID ) {
                    if(!$sublinks_catched) { $menu_html.= '<li><ul>'; }
                    $sublinks_catched = TRUE;
                    $menu_html.= '<li> <a href="'.$menu_item->url.'"> &#8226; '.$menu_item->title.' </a> </li>';
                }
            }         
        }

        echo $menu_html.'</ul><div class="clear"></div>';

        echo $after_widget;
    }

    # Adminend
    public function form( $instance ) {
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
        $title = (isset($instance['title'])) ? esc_attr($instance['title']) : '';
        
        if ( !$menus ) {
            echo '<p>'. sprintf( 'There is no menu. <a href="%s">Create a menu</a>.', admin_url('nav-menus.php') ) .'</p>';
            return;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('vz_side_nav'); ?>">Select Menu:</label>
            <select id="<?php echo $this->get_field_id('vz_side_nav'); ?>" name="<?php echo $this->get_field_name('vz_side_nav'); ?>">
            <?php
                foreach ( $menus as $menu ) {
                    $selected = $instance['vz_side_nav'] == $menu->term_id ? ' selected="selected"' : '';
                    echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
                }
            ?>
            </select>
        </p>
        <?php 
    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['vz_side_nav'] = $new_instance['vz_side_nav'];

        return $instance;
    }

}



# Why us
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Why_us" );' ) );
class VZ_Why_us extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_why_us',
            '(WPE) Why us',
            array( 'description'    => 'Help visitors to decide why us.. (left/right)',
                   'classname'      => 'apply')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args );
        $title = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $desc = (isset($instance['description'])) ? apply_filters( 'widget_title', $instance['description'] ) : '';
        $lists = (isset($instance['listitems'])) ? $instance['listitems'] : null;
        $linkname = (isset($instance['alinkname'])) ? $instance['alinkname'] : '';
        $link = (isset($instance['applyformlink'])) ? $instance['applyformlink'] : '#';

        echo $before_widget;
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title; ?>
            <div class="description rounded_2">

                <?php echo $desc; ?>

                <ul>
                    <?php
                        if($lists) :
                            for($i=0;$i<=$lists;$i++) :
                                echo '<li>'.$instance['listitem'.$i].'</li>';
                            endfor;
                        endif;
                    ?>
                </ul>

            </div>

            <a href="<?php echo $link; ?>" class="button_apply"> <?php echo $linkname; ?> </a>

        <?php echo $after_widget;
    }

    #Adminend
    public function form( $instance ) {
        $title = (isset($instance['title'])) ? esc_attr( $instance['title'] ) : '';
        $desc = (isset($instance['description'])) ? esc_attr( $instance['description'] ) : '';
        $lists = (isset($instance['listitems'])) ? $instance['listitems'] : 0;
        $linkname = (isset($instance['alinkname'])) ? $instance['alinkname'] : ''; 
        $link = (isset($instance['applyformlink'])) ? $instance['applyformlink'] : '#'; ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>">Description:</label> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" ><?php echo $desc; ?></textarea>
        </p>

        <?php $litemvalue = array();
            for($i=0; $i<=$lists; $i++) : $item = 'listitem'.$i; 
                $litemvalue[$i] = isset($instance[$item]) ? esc_attr($instance[$item]) : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id($item); ?>">List item:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id($item); ?>" name="<?php echo $this->get_field_name('listitem'); ?>[]" type="text" value="<?php echo $litemvalue[$i]; ?>" />
        </p>
        <?php endfor; ?>
        <div> </div>
        <a href="#" id="clonefield"> Add list item </a>

        <p>
            <label for="<?php echo $this->get_field_id('alinkname'); ?>">Apply link name:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('alinkname'); ?>" name="<?php echo $this->get_field_name('alinkname'); ?>" type="text" value="<?php echo $linkname; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('applyformlink'); ?>">Apply form link:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('applyformlink'); ?>" name="<?php echo $this->get_field_name('applyformlink'); ?>" type="text" value="<?php echo $link; ?>" />
        </p>
        <?php

    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']          = strip_tags( $new_instance['title'] );
        $instance['description']    = strip_tags( $new_instance['description'] );
        $instance['applyformlink']  = strip_tags( $new_instance['applyformlink'] );
        $instance['alinkname']      = strip_tags( $new_instance['alinkname'] );

        $listitems = 0;
        foreach ($new_instance['listitem'] as $key => $value) {
            
            if(strlen($value)>0) {
                $instance['listitem'.$key] = strip_tags( $value );
                $listitems++;
            }
        }

        $instance['listitems']      = strip_tags( $listitems-1 );

        return $instance;
    }

}



# Why us Wide
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Why_us_wide" );' ) );
class VZ_Why_us_wide extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_why_us_wide',
            '(WPE) Why us wide',
            array( 'description'    => 'Help visitors to decide why us.. (home2-center)',
                   'classname'      => 'apply wide grid_12')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args );
        $title = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $desc = (isset($instance['desc'])) ? substr($instance['desc'],0,85) : '';
        $linkname = (isset($instance['alinkname'])) ? $instance['alinkname'] : '';
        $link = (isset($instance['applyformlink'])) ? $instance['applyformlink'] : '#';

        echo $before_widget; ?>
        <div class="description rounded_2 alignleft">
            <h1> <?php echo $title; ?> </h1>
            <?php echo $desc; ?>
        </div>

        <a href="<?php echo $link; ?>" class="button_apply alignright rounded_2"> <?php echo $linkname; ?> </a>
        <?php echo $after_widget;
    }

    #Adminend
    public function form( $instance ) { 
        $title = (isset($instance['title'])) ? esc_attr( $instance['title'] ) : '';
        $desc = (isset($instance['desc'])) ? substr( esc_attr($instance['desc']),0,85) : '';
        $linkname = (isset($instance['alinkname'])) ? $instance['alinkname'] : '';
        $link = (isset($instance['applyformlink'])) ? $instance['applyformlink'] : '#'; ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('desc'); ?>">Description (85 chars max):</label> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>" ><?php echo $desc; ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('alinkname'); ?>">Apply link name:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('alinkname'); ?>" name="<?php echo $this->get_field_name('alinkname'); ?>" type="text" value="<?php echo $linkname; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'applyformlink' ); ?>">Apply form link</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'applyformlink' ); ?>" name="<?php echo $this->get_field_name( 'applyformlink' ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" />
        </p>
        <?php 
    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['desc'] = strip_tags( $new_instance['desc'] );
        $instance['applyformlink'] = strip_tags( $new_instance['applyformlink'] );
        $instance['alinkname'] = strip_tags( $new_instance['alinkname'] );

        return $instance;
    }

}



# Login
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Login" );' ) );
class VZ_Login extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_login',
            '(WPE) Login',
            array( 'description'    => 'Let users log in, and use their panel.. (left/right)',
                   'classname'      => 'login')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args ); global $wp;
        $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

        $title = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $desc = (isset($instance['description'])) ? $instance['description'] : ''; 

        echo $before_widget; ?>

        <?php if( !is_user_logged_in() ) : ?>

        <a href="#" class="tab_link active"> <?php echo $instance['title']; ?> </a>
        <a href="<?php echo site_url('/wp-login.php?action=register'); ?>" class="tab_link"> <?php _e('Register','vz_front_terms') ?> </a>

        <div class="clear"> </div>

        <form name="loginform" id="loginform" action="<?php home_url(); ?>/wp-login.php" method="post">

            <div class="description"><?php echo $desc; ?></div>

            <span> <?php _e('Member username','vz_front_terms'); ?>: </span>
            <input type="text" name="log" id="user_login" class="field user" />

            <div class="clear"></div>

            <span> <?php _e('Password','vz_front_terms'); ?>: </span>
            <input type="password" name="pwd" id="user_pass" class="field pass" />

            <input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Login your account','vz_front_terms'); ?>" class="main" />
            <a href="<?php echo wp_lostpassword_url( $current_url ); ?>" class="second"> <?php _e('Recover','vz_front_terms'); ?> </a>

            <input type="hidden" name="redirect_to" value="<?php echo $current_url; ?>" />
            <input type="hidden" name="testcookie" value="1" />

            <div class="clear"></div>

        </form>

        <?php else : 
                $user_ID = get_current_user_id();
                $the_logged_user = new WP_User( $user_ID ); 

                $cal_page = get_posts(array(
                    'post_type'  => 'page',
                    'meta_key'   => '_wp_page_template',
                    'meta_value' => 'template-calendar.php'
                ));

                $calendar_link = get_permalink( $cal_page[0]->ID ); 

                $luser = $the_logged_user->roles[0]; ?>

            <div class="widget rounded_2 login">

                <a href="#" class="tab_link active"> <?php _e('Control Panel','vz_front_terms'); ?> </a>

                <div class="clear"> </div>

                <div class="user">

                    <?php echo get_avatar( $the_logged_user->user_email, 50 ); ?>

                    <h1> <?php echo $the_logged_user->display_name; ?> </h1>
                    <h2> <?php echo ucfirst($the_logged_user->roles[0]); ?> </h2>

                    <div class="clear"> </div>

                </div>

                <br class="clear"/>

                <ul class="nav">

                    <li class="user">        <a href="<?php echo get_author_posts_url($user_ID); ?>" title="Wall"> <?php _e('Go to your wall','vz_front_terms'); ?> </a> </li>
                    <?php if(current_user_can('publish_posts')) : ?>
                    <li class="newarticle">  <a href="<?php echo home_url(); ?>/wp-admin/post-new.php" title="New Article"> <?php _e('Write an article','vz_front_terms'); ?> </a> </li>
                    <?php endif; ?>
                    <?php if( $luser == 'professor' || $luser == 'student' ) : ?>
                    <li class="schedule">    <a href="<?php echo $calendar_link; ?>" title="Schedule"> <?php _e('View your schedule','vz_front_terms'); ?> </a> </li>
                    <?php endif; ?>
                    <li class="settings">    <a href="<?php echo admin_url( 'profile.php' ); ?>" title="Profile"> <?php _e('Edit settings','vz_front_terms'); ?> </a> </li>
                    <li class="logout">      <a href="<?php echo wp_logout_url( $current_url ); ?>" title="Logout"> <?php _e('Logout','vz_front_terms'); ?> </a> </li>

                </ul>


            </div>

        <?php endif;
        echo $after_widget;
    }

    #Adminend
    public function form( $instance ) {
        $title = (isset($instance['title'])) ? esc_attr( $instance['title'] ) : '';
        $desc = (isset($instance['description'])) ? $instance['description'] : ''; 
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>">Description:</label> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" ><?php echo $desc; ?></textarea>
        </p>
        <?php 
    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );

        return $instance;
    }

}



# Subscribers
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Subscribe" );' ) );
class VZ_Subscribe extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_subscribe',
            '(WPE) Subscribe',
            array( 'description'    => 'Get subscribers, spread words.. (left/right)',
                   'classname'      => 'subscribe')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args );
        $title = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $desc = (isset($instance['description'])) ? $instance['description'] : ''; 

        echo $before_widget;
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title; ?>

        <div class="description"> <?php echo $desc; ?> </div>

        <form id="subscribe" class="vz_ajax">

            <input type="email" name="email" value="testing@mail.org" onfocus="if (this.value==this.defaultValue) this.value = ''" onblur="if (this.value=='') this.value = this.defaultValue" class="field" id="subscribe_email" />

            <input type="submit" value="unsubscribe" class="second" id="unsubscribe_button" />
            <input type="submit" value="i agree, subscribe me" class="main" id="subscribe_button" />

            <div class="clear"></div>

        </form>

        <?php echo $after_widget;
    }

    # Adminend
    public function form( $instance ) {
        $title = (isset($instance['title'])) ? esc_attr( $instance['title'] ) : '';
        $desc = (isset($instance['description'])) ? $instance['description'] : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>">Description:</label> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" ><?php echo $desc; ?></textarea>
        </p>
        <?php 
    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']       = strip_tags( $new_instance['title'] );
        $instance['description'] = $new_instance['description'];

        return $instance;
    }

}



# Info Link
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Info_Link" );' ) );
class VZ_Info_Link extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_infolink',
            '(WPE) Info Link',
            array( 'description'    => 'Shortcut your main links.. (left/right)',
                   'classname'      => 'infolink')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args );
        $title = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $desc = (isset($instance['desc'])) ? $instance['desc'] : ''; 

        echo $before_widget; ?>
        <a href="<?php echo $instance['link']; ?>">
                
            <div class="icon alignleft">
                <img src="<?php echo VZ_THEME_PATH; ?>/includes/images/<?php echo $instance['icon']; ?>" />
            </div>

            <h1> <?php echo $title; ?> </h1>

            <div class="description"> <?php echo $desc; ?> </div>

        </a>
        <?php echo $after_widget;
    }

    #Adminend
    public function form( $instance ) {
        $icc = 'checked="checked"'; $ics = '';
        if(isset($instance['icon']) && $instance['icon']=='icon-scholarship.png') { $icc = ''; $ics = 'checked="checked"'; }
        $title = (isset($instance['title'])) ? esc_attr( $instance['title'] ) : '';
        $desc = (isset($instance['desc'])) ? $instance['desc'] : ''; 
        $link = (isset($instance['link'])) ? $instance['link'] : ''; 
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('desc'); ?>">Description:</label> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>" ><?php echo $desc; ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'link' ); ?>">Link</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $link; ?>" />
        </p>
        <p>
            <label>Icon</label> 
            <label id="campus"> 
                <img src="<?php echo VZ_THEME_PATH; ?>/includes/images/icon-campus.png" width="32" height="27" />
                <input type="radio" id="campus" name="<?php echo $this->get_field_name( 'icon' ); ?>" value="icon-campus.png" <?php echo $icc; ?> />
            </label>

            <label id="scholarship"> 
                <img src="<?php echo VZ_THEME_PATH; ?>/includes/images/icon-scholarship.png" width="32" height="27" />
                <input type="radio" id="scholarship" name="<?php echo $this->get_field_name( 'icon' ); ?>" value="icon-scholarship.png" <?php echo $ics; ?> />
            </label>
        </p>
        <?php 
    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['desc'] = strip_tags( $new_instance['desc'] );
        $instance['link'] = strip_tags( $new_instance['link'] );
        $instance['icon'] = strip_tags( $new_instance['icon'] );

        return $instance;
    }

}



# Tip widget
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Tip_Widget" );' ) );
class VZ_Tip_Widget extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_tipwidget',
            '(WPE) Tip Widget',
            array( 'description'    => 'Shortcut your tip links.. (home2-center)',
                   'classname'      => 'tip grid_4')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args );
        $title = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $desc = (isset($instance['desc'])) ? $instance['desc'] : ''; 
        $link = (isset($instance['link'])) ? $instance['link'] : ''; 

        echo $before_widget; ?>
        <div class="description">
                <h1 class="rounded_2"> <?php echo $title; ?> </h1>
                <?php echo $desc; ?>
        </div>

        <a href="<?php echo $link; ?>" class="alignleft"> <?php _e('Read more','vz_front_terms'); ?> </a>
        <?php echo $after_widget;
    }

    #Adminend
    public function form( $instance ) { 
        $title = (isset($instance['title'])) ? esc_attr( $instance['title'] ) : '';
        $desc = (isset($instance['desc'])) ? $instance['desc'] : ''; 
        $link = (isset($instance['link'])) ? $instance['link'] : '';  ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('desc'); ?>">Description:</label> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>" ><?php echo $desc; ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'link' ); ?>">Link:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $link; ?>" />
        </p>
        <?php 
    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['desc'] = strip_tags( $new_instance['desc'] );
        $instance['link'] = strip_tags( $new_instance['link'] );

        return $instance;
    }

}



# Path
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Path" );' ) );
class VZ_Path extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_path',
            '(WPE) Path',
            array( 'description'    => 'Let users know their path while surfing... (left/right)',
                   'classname'      => 'path')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        global $post;
        extract($args);
        echo $before_widget; 


        if ( !is_home() ) { echo '<a href="'.home_url().'" class="current">'.__('Home','vz_front_terms').'</a> <span class="current"> / </span>'; }
        if ( is_page() && !$post->post_parent ) { echo '<a href="#">'.get_the_title().'</a>'; }
        if ( is_page() && $post->post_parent ) { 

            $parent_id  = $post->post_parent;  
            $paths = array();  
            while($parent_id) {  
                $page = get_page($parent_id);  
                array_push($paths, '<a href="'.get_permalink($page->ID).'" class="current">'.get_the_title($page->ID).'</a>');
                $parent_id  = $page->post_parent;  
            }

            foreach (array_reverse($paths) as $path) {
                echo $path.'<span class="current"> / </span>';
            }

            echo '<a href="#">'.get_the_title().'</a>'; 
        }
        if ( is_single() ) { echo '<a href="#">'.get_the_title().'</a>'; }


        echo $after_widget;
    }


}



# Popular posts
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Popular" );' ) );
class VZ_Popular extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_popular',
            '(WPE) Popular posts',
            array( 'description'    => 'Let users know most popular and most commented posts... (left/right)',
                   'classname'      => 'popular')
        );
    }

    # Adminend
    public function form( $instance ) { 
        $pop_num = (isset($instance['pop_num'])) ? esc_attr($instance['pop_num']) : 5;
        $com_num = (isset($instance['com_num'])) ? esc_attr($instance['com_num']) : 5; ?>
        <p>
            <label for="post">Most popular posts number:</label> <br/>
            <select id="post" name="<?php echo $this->get_field_name( 'pop_num' ); ?>">
                <?php for($i=3;$i<=10;$i++) : ?>
                <option value="<?php echo $i; ?>" <?php if($pop_num == $i ) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </p>
        <p>
            <label for="com">Most commented posts number:</label> <br/>
            <select id="com" name="<?php echo $this->get_field_name( 'com_num' ); ?>">
                <?php for($i=3;$i<=10;$i++) : ?>
                <option value="<?php echo $i; ?>" <?php if($com_num == $i ) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </p>
        <?php 
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract($args);
        $pop_num = (isset($instance['pop_num'])) ? esc_attr($instance['pop_num']) : 5;
        $com_num = (isset($instance['com_num'])) ? esc_attr($instance['com_num']) : 5;

        echo $before_widget; ?>

        <div class="tab-links">
            <a href="#" class="popular active"> <?php _e('Popular'); ?> </a>
            <a href="#" class="commented"> <?php _e('Most commented'); ?> </a>
        </div>

        <div class="clear"></div>

        <div id="popular" class="display">
            <?php $popularpost  = new WP_Query( array( 'posts_per_page' => $pop_num, 'meta_key' => 'post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC'  ) );

            if ( $popularpost->have_posts() ) : ?>
            <ul>
                <?php while ( $popularpost->have_posts() ) : $popularpost->the_post(); ?>
                    <li>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>"> 
                            <?php if( has_post_thumbnail() ) : ?>
                                <img src="<?php echo vz_resize( get_post_thumbnail_id(), 'vz_widget_popular'); ?>" class="rounded_2" />
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    <a class="post" href="<?php the_permalink(); ?>"> <?php echo substr(get_the_title(),0,60); ?> </a>
                    <div class="clear"></div> </li>
                <?php endwhile; ?>
            </ul>
            <?php endif; ?>
        </div>

        <div id="commented" class="hidden">
            <?php $popularpost  = new WP_Query( array( 'posts_per_page' => $com_num, 'orderby' => 'comment_count', 'order' => 'DESC'  ) );

            if ( $popularpost->have_posts() ) : ?>
            <ul>
                <?php while ( $popularpost->have_posts() ) : $popularpost->the_post(); ?>
                    <li>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>"> 
                            <?php if( has_post_thumbnail() ) : ?>
                                <img src="<?php echo vz_resize( get_post_thumbnail_id(), 'vz_widget_popular'); ?>" class="rounded_2" />
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    <a class="post" href="<?php the_permalink(); ?>"> <?php echo substr(get_the_title(),0,60); ?> </a>
                    <div class="clear"></div> </li>
                <?php endwhile; ?>
            </ul>
            <?php endif; ?>
        </div>

        <?php

        echo $after_widget;
    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance['pop_num'] = strip_tags( $new_instance['pop_num'] );
        $instance['com_num'] = strip_tags( $new_instance['com_num'] );
        return $instance;
    }


}



######################## Footer ########################
# Latest gallery photos
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Latest_Gallery_Photos" );' ) );
class VZ_Latest_Gallery_Photos extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_gallery',
            '(WPE) Latest Gallery Photos',
            array( 'description'    => 'Showing last photos uploaded into gallery page. (footer)',
                   'classname'      => 'gallery')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args ); global $thumb_sizes;
        $title  = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';

        echo $before_widget;
        if ( !empty( $title ) ) {
            echo $before_title . $title . $after_title;
        } 

        $gallery_photos = 8; if($instance['gallery_photos']>0) { $gallery_photos = $instance['gallery_photos']; }

        $query_args = array( 'post_type' => 'galleries', 'orderby' => 'rand', 'taxonomy' => 'folders' );

        if( $instance['gallery_folder']>0 ) { $query_args['tax_query'] = array( array( 'taxonomy' => 'folders','terms' => $instance['gallery_folder'] , 'field' => 'term_id') ); }

        $gallery_posts = new WP_Query( $query_args );
        $_gp_ctr = 0;
        while ( $gallery_posts->have_posts() ) : $gallery_posts->the_post();

            $photos = get_children( array( 'post_parent' => get_the_ID(), 'post_type' => 'attachment' ) );
            foreach($photos as $photo ) : $_gp_ctr++; ?>
                <a href="<?php the_permalink(); ?>"> <img src="<?php echo vz_resize( $photo->ID, 'vz_gallery_mini'); ?>" /> </a>
            <?php if( $_gp_ctr >= $gallery_photos ) break; endforeach;

            if( $_gp_ctr >= $gallery_photos ) break; // Breaking if photo limit achieved

        endwhile;      

        echo $after_widget;
    }

    # Adminend
    public function form( $instance ) { 
        $title = (isset($instance['title'])) ? esc_attr($instance['title']) : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label> 
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="post">Gallery folder:</label> 
            <select id="post" name="<?php echo $this->get_field_name( 'gallery_folder' ); ?>">
                <option value="">Random</option>
                <?php $taxonomies = get_terms('folders');

                foreach ($taxonomies as $tax) {
                    echo '<option value="'.$tax->term_id.'" ';
                    if(isset($instance['gallery_folder']) && $instance['gallery_folder'] == $tax->term_id ) echo 'selected="selected"';
                    echo '>'.$tax->name.'</option>';
                }

                ?>
            </select>
        </p>
        <p>
            <label for="post">Number of max. photos:</label> 
            <select id="post" name="<?php echo $this->get_field_name( 'gallery_photos' ); ?>">
                <?php for($i=4;$i<=20;$i=$i+4) : ?>
                <option value="<?php echo $i; ?>" <?php if(isset($instance['gallery_photos']) && $instance['gallery_photos'] == $i ) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </p>
        <?php 
    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['gallery_folder'] = strip_tags( $new_instance['gallery_folder'] );
        $instance['gallery_photos'] = strip_tags( $new_instance['gallery_photos'] );

        return $instance;
    }

}



# Latest twitter updates
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Twitter_Updates" );' ) );
class VZ_Twitter_Updates extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_twitter',
            '(WPE) Twitter Updates',
            array( 'description'    => 'Showing latest twitter updates. (footer)',
                   'classname'      => 'twitter')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args );
        $title  = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $tw_username  = apply_filters( 'widget_title', $instance['tw_username'] );
        $tw_number  = apply_filters( 'widget_title', $instance['tw_number'] );

        echo $before_widget;
        if ( !empty( $title ) ) {
            echo $before_title . $title . $after_title;
        } 

        if(strlen($tw_username)>0) :

            $tweets = fetch_tweets($tw_username, $tw_number);
        endif; ?>

        <ul>
            <?php if(is_array($tweets)) : foreach ($tweets as $tweet) : ?>
            <li> <span> <a href="https://twitter.com/<?php echo $tw_username ?>" target="_blank"> @<?php echo $tw_username; ?>: </a> </span> <?php echo $tweet->text; ?> </li>
            <?php endforeach; endif; ?>
        </ul>

        <?php echo $after_widget;
    }

    # Adminend
    public function form( $instance ) {
        $title  = (isset($instance['title'])) ? esc_attr( $instance['title'] ) : '';
        $tw_username  = (isset($instance['tw_username'])) ? $instance['tw_username'] : '';
        $tw_number  = (isset($instance['tw_username'])) ? $instance['tw_number'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tw_username'); ?>">Twitter username:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('tw_username'); ?>" name="<?php echo $this->get_field_name('tw_username'); ?>" type="text" value="<?php echo $tw_username; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tw_number'); ?>">Number of tweets:</label> 
            <select class="widefat" id="<?php echo $this->get_field_id('tw_number'); ?>" name="<?php echo $this->get_field_name('tw_number'); ?>" >
                <?php for($i=1;$i<=10;$i++) : ?>
                    <option <?php echo (esc_attr($tw_number)==$i) ? 'selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        <?php 
    }

    # Update 
    public function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['tw_username'] = strip_tags( $new_instance['tw_username'] );
        $instance['tw_number'] = strip_tags( $new_instance['tw_number'] );

        return $instance;
    }

}



# Contact info
add_action( 'widgets_init', create_function( '', 'register_widget( "VZ_Contact_Info" );' ) );
class VZ_Contact_Info extends WP_Widget {

    # Constructing
    public function __construct() {
        parent::__construct(
            'vz_contact',
            '(WPE) Contact Info',
            array( 'description'    => 'Let visitors find contact info shortly. (footer)',
                   'classname'      => 'contact')
        );
    }

    # Frontend
    public function widget( $args, $instance ) {
        extract( $args );
        $title  = (isset($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $address  = (isset($instance['address'])) ? $instance['address'] : '';

        $phones = $instance['phoneitems'];
        $emails = $instance['emailitems'];

        echo $before_widget;
        if ( !empty( $title ) ) {
            echo $before_title . $title . $after_title;
        } ?>

        <ul> 
            <?php
                echo '<li style="padding-left:0"';
                echo (strlen($address)>=50) ? ' class="long"' : '';
                echo '> <span class="address alignleft"></span> '.$address.' </li>';

                for($i=0;$i<=$phones;$i++) :
                    echo '<li class="phone">'.$instance['phoneitem'.$i].'</li>';
                endfor;

                for($i=0;$i<=$emails;$i++) :
                    echo '<li class="email">'.$instance['emailitem'.$i].'</li>';
                endfor;
            ?>
        </ul>

        <?php echo $after_widget;
    }

    # Adminend
    public function form( $instance ) { 
        $title  = (isset($instance['title'])) ? esc_attr($instance['title']) : '';
        $address  = (isset($instance['address'])) ? esc_attr($instance['address']) : '';
        $phoneitems  = (isset($instance['phoneitems'])) ? $instance['phoneitems'] : 0;
        $emailitems  = (isset($instance['emailitems'])) ? $instance['emailitems'] : 0;   ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>">Address:</label> 
            <textarea class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" ><?php echo $address; ?></textarea>
        </p>
        
        <?php for($i=0; $i<=$phoneitems; $i++) : $item = 'phoneitem'.$i; 
            $ivalue = (isset($instance[$item])) ? esc_attr($instance[$item]) : '';?>
        <p>
            <label for="<?php echo $this->get_field_id('phoneitem'.$i); ?>">Phone:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('phoneitem'.$i); ?>" name="<?php echo $this->get_field_name('phoneitem'); ?>[]" type="text" value="<?php echo $ivalue; ?>" />
        </p>
        <?php endfor; ?>
        <div> </div>
        <a href="#" id="clonefield"> Add phone </a><br/><br/>

        <?php for($i=0; $i<=$emailitems; $i++) : $item = 'emailitem'.$i;
            $ivalue = (isset($instance[$item])) ? esc_attr($instance[$item]) : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id($item); ?>">Email:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id($item); ?>" name="<?php echo $this->get_field_name('emailitem'); ?>[]" type="text" value="<?php echo $ivalue; ?>" />
        </p>
        <?php endfor; ?>
        <div> </div>
        <a href="#" id="clonefield"> Add email </a>

        <?php 
    }

    # Update
    public function update( $new_instance, $old_instance ) {

        $instance = array();
        $instance['title']          = strip_tags( $new_instance['title'] );
        $instance['address']        = $new_instance['address'];

        $phoneitems = 0; $emailitems = 0;

        foreach ($new_instance['phoneitem'] as $key => $value) {
            if(strlen($value)>0) {
                $instance['phoneitem'.$key] = strip_tags( $value );
                $phoneitems++;
            }
        }

        foreach ($new_instance['emailitem'] as $key => $value) {
            if(strlen($value)>0) {
                $instance['emailitem'.$key] = strip_tags( $value );
                $emailitems++;
            }
        }

        $instance['phoneitems']     = strip_tags( $phoneitems-1 );
        $instance['emailitems']     = strip_tags( $emailitems-1 );

        return $instance;
    }

}