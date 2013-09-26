<?php 

$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
$cfield = '<p class="comment-form-comment"><label for="comment">' . __( 'Comment', 'vz_front_terms' ) . '</label><br /><textarea id="comment" name="comment" aria-required="true"></textarea></p><div class="clear"></div>';
$cfield = ( !is_user_logged_in() ) ? "</div>$cfield" : $cfield;

$comments_args = array(
	'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published. ' ) . ( $req ? __( 'Fields with * mark are mandatory.' ) : '' ) . '</p><div class="inputs alignleft">',
    'comment_field' => $cfield
);

comment_form($comments_args);

if ( have_comments() ) : 
$all_comments = wp_count_comments( get_the_ID() );
$max_offset = ceil($all_comments->approved/30);
$showctr = 0;  ?>

	<!-- BEGIN COMMENTS  -->
	<div class="comments alignright grid_8 rounded_2">

		<h1 class="title"><?php _e('Comments','vz_front_terms'); ?></h1>

		<div id="more_content">

			<input type="hidden" id="item_id" value="<?php echo get_the_ID(); ?>" />
			<input type="hidden" id="item_offset" value="1" />
			<input type="hidden" id="max_offset" value="<?php echo $max_offset; ?>" />

			<?php wp_list_comments('per_page=30&callback=vz_comments'); ?>		

		</div>

		<?php if($all_comments->approved>30) : ?>
			<div class="block-pagination">
				<div class="alignleft">
					<span> <?php _e('Showing','vz_front_terms'); ?> <span id="count-showing"><?php echo $showctr; ?></span> <?php _e('of','vz_front_terms'); ?> <span id="all_items"><?php echo $all_comments->approved; ?></span> </span>
				</div>

				<div class="alignright">
					<a href="#" class="comments" id="load_more"> <?php _e('Load more...','vz_front_terms'); ?> </a>
				</div>
			</div>
		<?php endif; ?>

	</div>
	<!-- END COMMENTS  -->


<?php endif; ?>