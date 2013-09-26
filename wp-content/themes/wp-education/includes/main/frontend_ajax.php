<?php

/****************************************
* Frontend Ajax
****************************************/



# Handling ajax requests for NON-PRIVILEGED USERS ##########################################
add_action('wp_ajax_nopriv_vz_frontajax', 'vz_frontajax');
function vz_frontajax() {
    global $wpdb;

    # Starting
    $sub_action    = wp_kses_post($_POST['sub_action']);
    unset($_POST['action']); unset($_POST['sub_action']);

    # Default  values
    $options = FALSE;

    # Switching between page actions
    switch ($sub_action) {

        // Subscribing
        case 'subscribe':
            $email = wp_kses_post($_POST['email']);

            $checkemail = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_title = '{$email}' AND post_status='private' AND post_type='vz_custom_newsletter'", 'ARRAY_N');

            if(!$checkemail) {
                $_the_post = array(
                  'post_title'    => $email,
                  'post_status'   => 'private',
                  'post_type'     => 'vz_custom_newsletter'
                );
                wp_insert_post( $_the_post );
                $message = __("You've subscribed successfully.",'vz_front_terms');
            } else {
                $message = __("You've already subscribed.",'vz_front_terms');
            }
        break;


        // Unsubscribing
        case 'unsubscribe':
            $email = wp_kses_post($_POST['email']);

            $checkemail = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_title = '{$email}' AND post_status='private' AND post_type='vz_custom_newsletter'", 'ARRAY_N');

            if(!$checkemail) {
                $message = __('Your email is not in subscribers list.','vz_front_terms');
            } else {
                $blog_title = get_bloginfo();
                $message = 'Hello, 

                this email helps you to unsubscribe from '.$blog_title.'.
                If you want to continue with unsubscription <a href="'.get_site_url().'?unsubscribe='.md5($email).'">click here</a>.';

                wp_mail( $email , $blog_title.' newsletter unsubscription', $message, 'From: '.get_option('admin_email') );
                $message = __("We've mailed you unsubscription instructions.",'vz_front_terms');
            }
        break;


        // Load more
        case 'load_more':
            $more_content = '';

            $post_id = wp_kses_post($_POST['post_id']);
            $type    = wp_kses_post($_POST['post_type']);
            $offset  = wp_kses_post($_POST['load_offset']) * 30;

            switch ($type) {
                case 'gallery':

                    $album_name = $wpdb->get_var( "SELECT post_name FROM $wpdb->posts WHERE ID = $post_id" );
                    $photos = get_posts( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'numberposts' => 30, 'offset' => ($offset) ) ); 
                    foreach($photos as $photo ) :
                        $more_content .= '<div class="grid_2 album_image">'.
                        '<a href="'.vz_resize( $photo->ID, 'vz_gallery_zoom').'" rel="lightbox['.$album_name.']" title="'.$photo->post_excerpt.'">'. 
                        '<img src="'.vz_resize( $photo->ID, 'vz_gallery_thumb').'" />'.
                        '</a></div>';
                    endforeach;

                break;

                case 'comments':

                    $comments = get_comments( array( 'status' => 'approve', 'post_id' => $post_id, 'parent' => 0, 'number' => 30, 'offset' => $offset ) );
                    foreach ($comments as $comment) : $showctr++;
                        $comment_class = 'odd';
                        $ccounter++; if ($ccounter % 2 == 0) { $comment_class = 'even'; }

                        $comment_author = NULL;
                        if( get_user_by('email', $comment->comment_author_email) ) :
                            $comment_author = get_user_by('email', $comment->comment_author_email);
                        endif;

                        $more_content .= 
                        '<div class="comment '.$comment_class.'">'.get_avatar( $comment->comment_author_email, 77 ).
                            '<div class="comment-content alignright">'.
                             '<div class="info">'.
                              '<span class="title alignleft">'.
                               '<strong>'.$comment->comment_author.'</strong><br/>';
                        if($comment_author) :
                            $more_content .= ucfirst($comment_author->roles[0]);
                            if( get_user_meta( $comment_author->ID, 'vz_group' ) ) $more_content .= ' '.__('at','vz_front_terms').' '.ucfirst( get_user_meta( $comment_author->ID, 'vz_group' ) );
                        endif;
                        $more_content .= '</span>'.
                              '<span class="links alignright">'.
                                '<a href="#reply" class="reply_comment cid_'.$comment->comment_ID.'">'.__('Reply','vz_front_terms').'</a> &#8226; <a href="#" class="report comment" id="comment_'.$comment->comment_ID.'">'.__('Report','vz_front_terms').'</a><br/>'.
                                 '<time class="posted alignright" datetime="'.comment_date( 'Y-m-d - g:i A').'" pubdate>'.__('Posted on','vz_front_terms').comment_date( ' d.m.Y - g:i A').'</time>'.
                              '</span>'.
                             '</div>'.
                             '<div class="description alignright">'.$comment->comment_content.'</div>'.
                            '</div><div class="clear"></div>'.
                        '</div>';

                        $comments_child = get_comments( array( 'status' => 'approve', 'post_id' => $post_id, 'parent' => $comment->comment_ID, 'orderby' => 'date', 'order' => 'ASC' ) );
                        foreach ($comments_child as $comment_child) :
                            $commentc_class = 'odd';
                            $ccounterc++; if ($ccounterc % 2 == 0) { $commentc_class = 'even'; }

                            $commentc_author = NULL;
                            if( get_user_by('email', $comment_child->comment_author_email) ) :
                                $commentc_author = get_user_by('email', $comment_child->comment_author_email);
                            endif;

                            $more_content .= 
                            '<div class="comment '.$commentc_class.' reply">'.get_avatar( $comment_child->comment_author_email, 77 ).
                                '<div class="comment-content alignright">'.
                                 '<div class="info">'.
                                  '<span class="title alignleft">'.
                                   '<strong>'.$comment_child->comment_author.'</strong><br/>';
                            if($commentc_author) :
                                $more_content .= ucfirst($commentc_author->roles[0]);
                                if( get_user_meta( $commentc_author->ID, 'vz_group' ) ) $more_content .= ' '.__('at','vz_front_terms').' '.ucfirst( get_user_meta( $commentc_author->ID, 'vz_group' ) );
                            endif;
                            $more_content .= '</span>'.
                                  '<span class="links alignright">'.
                                    '<a href="#reply" class="reply_comment cid_'.$comment->comment_ID.'">'.__('Reply','vz_front_terms').'</a> &#8226; <a href="#" class="report comment" id="comment_'.$comment_child->comment_ID.'">'.__('Report','vz_front_terms').'</a><br/>'.
                                     '<time class="posted alignright" datetime="'.comment_date( 'Y-m-d - g:i A').'" pubdate>'.__('Posted on','vz_front_terms').comment_date( ' d.m.Y - g:i A').'</time>'.
                                  '</span>'.
                                 '</div>'.
                                 '<div class="description alignright">'.$comment_child->comment_content.'</div>'.
                                '</div><div class="clear"></div>'.
                            '</div>';
                        endforeach;
                    endforeach;

                break;

                case 'posts':

                    $user_ID = get_current_user_id();
                    $author_id = $post_id;

                    $fauthors = $author_id;
                    if( is_user_logged_in() && $author_id == $user_ID ) {
                        $fauthors = get_user_meta($author_id, 'fauthors');
                        if($fauthors) { $fauthors = $author_id.",$fauthors"; }
                        $fposts = get_user_meta($author_id, 'fposts');
                        if($fposts) :
                            $fposts_query = " UNION SELECT *
                                FROM  $wpdb->posts
                                WHERE ID IN ($fposts)
                                AND   post_type = 'post'
                                AND   post_status = 'publish' $fposts_query";
                        endif;
                    }

                    //EXECUTING MAIN QUERY
                    $query_args = " SELECT *
                                    FROM  $wpdb->posts
                                    WHERE post_author IN ($fauthors)
                                    AND   post_type = 'post'
                                    AND   post_status = 'publish'
                                    $fposts_query
                                    ORDER BY post_date DESC
                                    LIMIT $offset,30 ";

                    $author_posts = $wpdb->get_results($query_args, OBJECT);

                    if($author_posts) : global $post;

                        foreach ($author_posts as $post) : setup_postdata( $post );

                            $cn = get_comments_number();
                            $cntext = __('no comments','vz_front_terms');
                            if($cn==1) {
                                $cntext = '<strong>1</strong>'.__(' comment','vz_front_terms');
                            } else {
                                $cntext = "<strong>$cn</strong>".__(" comments",'vz_front_terms');
                            }

                            $more_content .= '<div class="post">'.
                             '<div class="intro alignleft rounded_2">';

                            if ( has_post_thumbnail() ) :
                                $more_content .= '<a href="'.get_permalink().'"> <img src="'.vz_resize( get_post_thumbnail_id(), 'vz_blog_wall').'" class="rounded_2 alignleft" /> </a>';
                            endif;

                            $more_content .= "<p>$cntext</p>".get_the_time('l,').'<br/>'.get_the_time('d M Y').'<br/>'.get_the_time('g:i A').
                             '</div>'.
                             '<a href="'.get_permalink().'" class="title alignleft">'.get_the_title();
                                if( is_user_logged_in() ) :
                                    if(get_the_author_meta('ID') != $author_id) { $more_content .= '<br/><span class="alignleft">'.__('by ','vz_front_terms').get_the_author().'</span>'; }
                                endif;
                            $more_content .= '</a>'.
                             '<div class="excerpt alignleft">'.substr(get_the_excerpt(),0,190).'</div>'.
                            '</div>';

                        endforeach; 

                    endif;

                break;

            }

            echo $more_content;

            die();
        break;


        // Vuzzu form
        case 'vzform' :

            $form_id = wp_kses_post($_POST['form_id']); unset($_POST['form_id']);

            $mail_to = get_bloginfo('admin_email');
            if($form_id) {
                $the_post = get_post( $form_id );
                $mail_to = $the_post->post_excerpt;
            }

            $visitor_email = 'visitor@'.get_bloginfo();
            if(isset($_POST['email']) && strlen($_POST['email'])>0) {
                $visitor_email = $_POST['email'];
            }

            $message = "Send time: ".current_time('mysql').'\n\n';

            foreach ($_POST as $key => $value) {
                $message .= ucfirst($key).": $value";
                
            }

            wp_mail( $mail_to, 'Sent from: '.get_bloginfo() , $message, 'From: '.$visitor_email );

            echo do_shortcode('[vz_message] '.__('Your form has been submitted successfully.','vz_front_terms').' [/vz_message]');
            die();

        break;


    }


    # Displaying message
    echo '<span class="message">'.$message.'</span>';

    # Finishing
    die();
}



# Handling ajax requests for PRIVILEGED USERS ##########################################
add_action('wp_ajax_vz_ufrontajax', 'vz_ufrontajax');
function vz_ufrontajax() {
	global $wpdb;
    $c_user_id = get_current_user_id( );
    $c_user = new WP_User( $c_user_id );

    # Starting
    $sub_action    = wp_kses_post($_POST['sub_action']);
    unset($_POST['action']); unset($_POST['sub_action']);

    # Default  values
    $options = FALSE;

    # Switching between page actions
    switch ($sub_action) {

        // Adding lecture
        case 'add_lecture':
            $name = wp_kses_post($_POST['lecture_name']);
            $date = wp_kses_post($_POST['lecture_date']);
            $time = wp_kses_post($_POST['starttime']).__(' to ','vz_front_terms').wp_kses_post($_POST['finishtime']);
            $hall = wp_kses_post($_POST['lecture_hall']);
            $group = wp_kses_post($_POST['lecture_group']);
            $repeat = wp_kses_post($_POST['lecture_repeat']);
            if(strlen($repeat)==0) { $repeat = 'once'; }

            $the_cal = array(
              'post_title'    => $name,
              'post_type'     => 'calendar',
              'ping_status'   => 'closed',
              'comment_status'=> 'closed',
              'post_date'     => date('Y-m-d H:i:s', strtotime($date))
            );

            if( $c_user->roles[0] == 'professor' ) {
                $the_cal['post_excerpt'] = $repeat;
                $the_cal['post_status'] = 'publish';
                $message = __('The lecture was added successfully.','vz_front_terms');
            } else {
                $the_cal['post_status'] = 'private';
                $message = __('The event was added successfully.','vz_front_terms');
            }

            wp_insert_post( $the_cal );
            $the_cal_id = $wpdb->insert_id;

            if( $hall ) add_post_meta($the_cal_id, 'hall', $hall);
            if( $time ) add_post_meta($the_cal_id, 'time', $time);
            if( $group ) add_post_meta($the_cal_id, 'group', $group);
            //if( $c_user->roles[0] == 'professor' ) add_post_meta($the_cal_id,'pgroup', get_user_meta( $c_user_id,'vz_group' ) );

        break;

        // Getting lecture
        case 'get_lecture':
            $the_cal     = get_post( wp_kses_post($_POST['the_cal']), OBJECT );
            $the_title   = $the_cal->post_title;
            $the_excerpt = ucfirst($the_cal->post_excerpt);
            $the_date    = date('d F Y', strtotime($the_cal->post_date) );
            $the_time    = get_post_meta($the_cal->ID, 'time');
            $the_hall    = get_post_meta($the_cal->ID, 'hall'); $the_hall = $the_hall[0];
            $the_group   = get_post_meta($the_cal->ID, 'group'); $the_group = ucfirst($the_group[0]);

            if($the_time) $the_time = explode(__(' to ','vz_front_terms'), $the_time[0]);

            $starttime   = $the_time[0];
            $finishtime  = $the_time[1];
            echo "$the_title||$the_date||$starttime||$finishtime||$the_hall||$the_group||$the_excerpt";  die();
        break;

        // Edit lecture
        case 'edit_lecture':
            $the_cal_id = wp_kses_post($_POST['lecture_id']);
            $name = wp_kses_post($_POST['lecture_name']);
            $date = wp_kses_post($_POST['lecture_date']);
            $time = wp_kses_post($_POST['starttime']).__(' to ','vz_front_terms').wp_kses_post($_POST['finishtime']);
            $hall = wp_kses_post($_POST['lecture_hall']);
            $group = wp_kses_post($_POST['lecture_group']);
            $repeat = wp_kses_post($_POST['lecture_repeat']);
            if(strlen($repeat)==0) { $repeat = 'once'; }

            $the_cal = array(
              'ID'            => $the_cal_id,
              'post_title'    => $name,
              'post_excerpt'  => $repeat,
              'post_type'     => 'calendar',
              'post_date'     => date('Y-m-d H:i:s', strtotime($date))
            );

            wp_update_post( $the_cal );

            if( $hall ) update_post_meta($the_cal_id, 'hall', $hall);
            if( $time ) update_post_meta($the_cal_id, 'time', $time);
            if( $group ) update_post_meta($the_cal_id, 'group', $group);

            if( $c_user->roles[0] == 'professor' ) {
                $message = __('The lecture was updated successfully.','vz_front_terms');
            } else {
                $message = __('The event was updated successfully.','vz_front_terms');
            }
        break;

        // Delete lecture
        case 'delete_lecture':
            $the_cal = wp_kses_post($_POST['lecture_id']);

            wp_delete_post( $the_cal, true );
            delete_post_meta( $post_id, 'hall');
            delete_post_meta( $post_id, 'time');
            delete_post_meta( $post_id, 'group');
            delete_post_meta( $post_id, 'pgroup');

            if( $c_user->roles[0] == 'professor' ) {
                $message = __('The lecture was deleted successfully.','vz_front_terms');
            } else {
                $message = __('The event was deleted successfully.','vz_front_terms');
            }
        break;

        // Follow post
        case 'follow_post':
            $the_post = wp_kses_post($_POST['the_post']);
            $following_posts = get_user_meta($c_user_id,'fposts');

            if(strlen($following_posts)>0) { $following_posts .= ",$the_post"; }
            else { $following_posts = "$the_post"; }
            update_user_meta( $c_user_id, 'fposts', $following_posts );

            die();
        break;

        // Unfollow post
        case 'unfollow_post':
            $the_post = wp_kses_post($_POST['the_post']);
            $following_posts = get_user_meta($c_user_id,'fposts');

            if($following_posts) {
                $f_posts = explode(',',$following_posts);
                if(in_array($the_post, $f_posts)) { $new_fposts = array_diff($f_posts, array($the_post)); }
                foreach ($new_fposts as $fpost) {
                    $new_following_posts .= "$fpost,";
                }
                $new_following_posts = substr($new_following_posts, 0, -1);
                update_user_meta( $c_user_id, 'fposts', $new_following_posts );
            }

            die();
        break;

        // Follow author
        case 'follow_author':
            $the_author = wp_kses_post($_POST['the_author']);
            $following_authors = get_user_meta($c_user_id,'fauthors');

            if(strlen($following_authors)>0) { $following_authors .= ",$the_author"; }
            else { $following_authors = "$the_author"; }
            update_user_meta( $c_user_id, 'fauthors', $following_authors );

            die();
        break;

        // Unfollow author
        case 'unfollow_author':
            $the_author = wp_kses_post($_POST['the_author']);
            $following_authors = get_user_meta($c_user_id,'fauthors');

            if($following_authors) {
                $f_authors = explode(',',$following_authors);
                if(in_array($the_author, $f_authors)) { $new_fauthors = array_diff($f_authors, array($the_author)); }
                foreach ($new_fauthors as $fauthor) {
                    $new_following_authors .= "$fauthor,";
                }
                $new_following_authors = substr($new_following_authors, 0, -1);
                update_user_meta( $c_user_id, 'fauthors', $new_following_authors );
            }

            die();
        break;

        // Report comment
        case 'report_comment':
            $the_comment = wp_kses_post($_POST['the_comment']);

            wp_set_comment_status( $the_comment, 'spam' );

            die();
        break;

        // Load more
        case 'load_more':
            $more_content = '';

            $post_id = wp_kses_post($_POST['post_id']);
            $type    = wp_kses_post($_POST['post_type']);
            $offset  = wp_kses_post($_POST['load_offset']) * 30;

            switch ($type) {
                case 'gallery':

                    $album_name = $wpdb->get_var( "SELECT post_name FROM $wpdb->posts WHERE ID = $post_id" );
                    $photos = get_posts( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'numberposts' => 30, 'offset' => ($offset) ) ); 
                    foreach($photos as $photo ) :
                        $more_content .= '<div class="grid_2 album_image">'.
                        '<a href="'.vz_resize( $photo->ID, 'vz_gallery_zoom').'" rel="lightbox['.$album_name.']" title="'.$photo->post_excerpt.'">'. 
                        '<img src="'.vz_resize( $photo->ID, 'vz_gallery_thumb').'" />'.
                        '</a></div>';
                    endforeach;

                break;

                case 'comments':

                    $comments = get_comments( array( 'status' => 'approve', 'post_id' => $post_id, 'parent' => 0, 'number' => 30, 'offset' => $offset ) );
                    foreach ($comments as $comment) : $showctr++;
                        $comment_class = 'odd';
                        $ccounter++; if ($ccounter % 2 == 0) { $comment_class = 'even'; }

                        $comment_author = NULL;
                        if( get_user_by('email', $comment->comment_author_email) ) :
                            $comment_author = get_user_by('email', $comment->comment_author_email);
                        endif;

                        $more_content .= 
                        '<div class="comment '.$comment_class.'">'.get_avatar( $comment->comment_author_email, 77 ).
                            '<div class="comment-content alignright">'.
                             '<div class="info">'.
                              '<span class="title alignleft">'.
                               '<strong>'.$comment->comment_author.'</strong><br/>';
                        if($comment_author) :
                            $more_content .= ucfirst($comment_author->roles[0]);
                            if( get_user_meta( $comment_author->ID, 'vz_group' ) ) $more_content .= ' '.__('at','vz_front_terms').' '.ucfirst( get_user_meta( $comment_author->ID, 'vz_group' ) );
                        endif;
                        $more_content .= '</span>'.
                              '<span class="links alignright">'.
                                '<a href="#reply" class="reply_comment cid_'.$comment->comment_ID.'">'.__('Reply','vz_front_terms').'</a> &#8226; <a href="#" class="report comment" id="comment_'.$comment->comment_ID.'">'.__('Report','vz_front_terms').'</a><br/>'.
                                 '<time class="posted alignright" datetime="'.comment_date( 'Y-m-d - g:i A').'" pubdate>'.__('Posted on','vz_front_terms').comment_date( ' d.m.Y - g:i A').'</time>'.
                              '</span>'.
                             '</div>'.
                             '<div class="description alignright">'.$comment->comment_content.'</div>'.
                            '</div><div class="clear"></div>'.
                        '</div>';

                        $comments_child = get_comments( array( 'status' => 'approve', 'post_id' => $post_id, 'parent' => $comment->comment_ID, 'orderby' => 'date', 'order' => 'ASC' ) );
                        foreach ($comments_child as $comment_child) :
                            $commentc_class = 'odd';
                            $ccounterc++; if ($ccounterc % 2 == 0) { $commentc_class = 'even'; }

                            $commentc_author = NULL;
                            if( get_user_by('email', $comment_child->comment_author_email) ) :
                                $commentc_author = get_user_by('email', $comment_child->comment_author_email);
                            endif;

                            $more_content .= 
                            '<div class="comment '.$commentc_class.' reply">'.get_avatar( $comment_child->comment_author_email, 77 ).
                                '<div class="comment-content alignright">'.
                                 '<div class="info">'.
                                  '<span class="title alignleft">'.
                                   '<strong>'.$comment_child->comment_author.'</strong><br/>';
                            if($commentc_author) :
                                $more_content .= ucfirst($commentc_author->roles[0]);
                                if( get_user_meta( $commentc_author->ID, 'vz_group' ) ) $more_content .= ' '.__('at','vz_front_terms').' '.ucfirst( get_user_meta( $commentc_author->ID, 'vz_group' ) );
                            endif;
                            $more_content .= '</span>'.
                                  '<span class="links alignright">'.
                                    '<a href="#reply" class="reply_comment cid_'.$comment->comment_ID.'">'.__('Reply','vz_front_terms').'</a> &#8226; <a href="#" class="report comment" id="comment_'.$comment_child->comment_ID.'">'.__('Report','vz_front_terms').'</a><br/>'.
                                     '<time class="posted alignright" datetime="'.comment_date( 'Y-m-d - g:i A').'" pubdate>'.__('Posted on','vz_front_terms').comment_date( ' d.m.Y - g:i A').'</time>'.
                                  '</span>'.
                                 '</div>'.
                                 '<div class="description alignright">'.$comment_child->comment_content.'</div>'.
                                '</div><div class="clear"></div>'.
                            '</div>';
                        endforeach;
                    endforeach;

                break;

                case 'posts':

                    $user_ID = get_current_user_id();
                    $author_id = $post_id;

                    $fauthors = $author_id;
                    if( is_user_logged_in() && $author_id == $user_ID ) {
                        $fauthors = get_user_meta($author_id, 'fauthors');
                        if($fauthors) { $fauthors = $author_id.",$fauthors"; }
                        $fposts = get_user_meta($author_id, 'fposts');
                        if($fposts) :
                            $fposts_query = " UNION SELECT *
                                FROM  $wpdb->posts
                                WHERE ID IN ($fposts)
                                AND   post_type = 'post'
                                AND   post_status = 'publish' $fposts_query";
                        endif;
                    }

                    //EXECUTING MAIN QUERY
                    $query_args = " SELECT *
                                    FROM  $wpdb->posts
                                    WHERE post_author IN ($fauthors)
                                    AND   post_type = 'post'
                                    AND   post_status = 'publish'
                                    $fposts_query
                                    ORDER BY post_date DESC
                                    LIMIT $offset,30 ";

                    $author_posts = $wpdb->get_results($query_args, OBJECT);

                    if($author_posts) : global $post;

                        foreach ($author_posts as $post) : setup_postdata( $post );

                            $cn = get_comments_number();
                            $cntext = __('no comments','vz_front_terms');
                            if($cn==1) {
                                $cntext = "<strong>1</strong>".__(' comment','vz_front_terms');
                            } else {
                                $cntext = "<strong>$cn</strong>".__(" comments",'vz_front_terms');
                            }

                            $more_content .= '<div class="post">'.
                             '<div class="intro alignleft rounded_2">';

                            if ( has_post_thumbnail() ) :
                                $more_content .= '<a href="'.get_permalink().'"> <img src="'.vz_resize( get_post_thumbnail_id(), 'vz_blog_wall').'" class="rounded_2 alignleft" /> </a>';
                            endif;

                            $more_content .= "<p>$cntext</p>".get_the_time('l,').'<br/>'.get_the_time('d M Y').'<br/>'.get_the_time('g:i A').
                             '</div>'.
                             '<a href="'.get_permalink().'" class="title alignleft">'.get_the_title();
                                if( is_user_logged_in() ) :
                                    if(get_the_author_meta('ID') != $author_id) { $more_content .= '<br/><span class="alignleft">'.__('by ','vz_front_terms').get_the_author().'</span>'; }
                                endif;
                            $more_content .= '</a>'.
                             '<div class="excerpt alignleft">'.substr(get_the_excerpt(),0,190).'</div>'.
                            '</div>';

                        endforeach; 

                    endif;

                break;

            }

            echo $more_content;

            die();
        break;

        case 'filebox_upload':
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');

            $attachment_id = media_handle_upload('thefile', 0);

            $file_folder = 'filebox';

            if( strlen($_POST['file_folder'])>0 ) {
                $file_folder = $_POST['file_folder'];
            } else {
                if( strlen($_POST['new_folder'])>0 ) {
                    $file_folder = $_POST['new_folder'];
                    $folders = get_user_meta(get_current_user_id(), 'vz_filebox_folders', true);
                    if($folders) :
                        $folders .= ",$file_folder";
                    else :
                        $folders = $file_folder;
                    endif;
                    update_user_meta( get_current_user_id(), 'vz_filebox_folders', $folders );
                }
            }

            wp_update_post( array('ID' => $attachment_id, 'post_excerpt' => $file_folder) );

            $success = "<span class='message'>".__("File was uploaded successfully.",'vz_front_terms').'</span>';

            echo '<script language="javascript" type="text/javascript">window.top.window.filebox_success("'.$success.'");</script>';
            die();
        break;

        case 'filebox_delete':

            $attachment_id  = wp_kses_post( $_POST['file_id'] );
            $the_attachment = get_post($attachment_id);

            if( $the_attachment->post_author == get_current_user_id() ) :
                wp_delete_attachment( $attachment_id );
                $success = "<span class='message'>".__("File was deleted successfully.",'vz_front_terms').'</span>';
                echo '<script language="javascript" type="text/javascript">window.top.window.fileboxdelete_success("'.$success.'");</script>';
                die();
            endif;

        break;

        case 'vzform' :

            $form_id = wp_kses_post($_POST['form_id']); unset($_POST['form_id']);

            $mail_to = get_bloginfo('admin_email');
            if($form_id) {
                $the_post = get_post( $form_id );
                $mail_to = $the_post->post_excerpt;
            }

            $visitor_email = 'visitor@'.get_bloginfo();
            if(isset($_POST['email']) && strlen($_POST['email'])>0) {
                $visitor_email = $_POST['email'];
            }

            $message = "Send time: ".current_time('mysql').'\n\n';

            foreach ($_POST as $key => $value) {
                $message .= ucfirst($key).": $value";
                
            }

            wp_mail( $mail_to, 'Sent from: '.get_bloginfo() , $message, 'From: '.$visitor_email );

            echo do_shortcode('[vz_message] '.__('Your form has been submitted successfully.','vz_front_terms').' [/vz_message]');
            die();

        break;

    }



    # Displaying message
    echo '<span class="message">'.$message.'</span>';

    # Finishing
    die();

}