<?php 

/*
	Template Name: Contact
*/

get_header();

$body_order = explode(',',get_option('vz_options_arch_body','sidebar,content'));

?>

	<div class="container container_12">

		<?php if( !get_option('vz_options_ct_map_disabled') && get_option('vz_options_ct_lat_lon') ) :
			if( substr( get_option('vz_options_ct_lat_lon') ,0,7 ) != '<iframe' ) : ?>
				<div class="grid_12" style="margin-bottom:20px">
					<iframe width="940" scrolling="no" height="202" frameborder="0" 
					src="https://www.google.com/maps?split=0&amp;t=m&amp;q=<?php echo get_option('vz_options_ct_lat_lon'); ?>&amp;z=16&amp;output=embed&amp;iwloc=near" 
					marginwidth="0" marginheight="0"></iframe>
				</div>
			<?php else : ?>
				<div class="grid_12" style="margin-bottom:20px">
					<?php echo get_option('vz_options_ct_lat_lon'); ?>
				</div>
			<?php endif;
		endif; ?>

		<div class="sidebar grid_4 <?php echo ($body_order[0] == 'sidebar') ? 'alignleft' : 'alignright'; ?>">
			<div class="widget rounded_2 hardcoded_contact">
				<h1><?php echo get_option('vz_options_ct_side_title', __('Information','vz_front_terms') ); ?></h1>
				
				<div class="information">
					<span class="alignleft phone"></span>
					<label><?php echo get_option('vz_options_ct_phone_label', __('Phone','vz_front_terms') ); ?></label>
					<p> <?php echo get_option('vz_options_ct_phone'); ?> </p>
					<div class="clear"></div>
				</div>

				<div class="information">
					<span class="alignleft address"></span>
					<label><?php echo get_option('vz_options_ct_address_label', __('Office Address','vz_front_terms') ); ?></label>
					<p> <?php echo get_option('vz_options_ct_address'); ?> </p>
					<div class="clear"></div>
				</div>

				<div class="information">
					<span class="alignleft email"></span>
					<label><?php echo get_option('vz_options_ct_email_label', __('Email','vz_front_terms') ); ?></label>
					<p> <?php echo get_option('vz_options_ct_email'); ?> </p>
					<div class="clear"></div>
				</div>

				<div class="information">
					<span class="alignleft staff"></span>
					<label><?php echo get_option('vz_options_ct_staff_label', __('Public relations','vz_front_terms') ); ?></label>
					<p> <?php echo get_option('vz_options_ct_staff'); ?> </p>
					<div class="clear"></div>
				</div>

			</div>
		</div>

		<!-- BEGIN CONTENT  -->
		<div class="content alignleft">

			<?php if(have_posts()) : the_post(); ?>

				<div class="post rounded_2 alignleft grid_8">

					<h1 class="title"> <?php the_title(); ?> </h1>

					<div class="post-content contact-page"> 
						<?php the_content(); ?> 

						<form class="vzforms_ajax" id="vzform_">
							<div class="alignleft half">
								<label><?php _e('Full name','vz_front_terms'); ?>:</label> 
								<input type="text" onblur="this.placeholder = '<?php _e('Your name','vz_front_terms'); ?>'" onfocus="this.placeholder = '' " class="rounded_2 vzinput_req" placeholder="<?php _e('Your name','vz_front_terms'); ?>" name="fullname">
							</div>

							<div class="alignleft half">
								<label><?php _e('E-mail address','vz_front_terms'); ?>:</label> 
								<input type="email" onblur="this.placeholder = '<?php _e('Your email','vz_front_terms'); ?>'" onfocus="this.placeholder = '' " class="rounded_2 vzinput_req" placeholder="<?php _e('Your email','vz_front_terms'); ?>" name="emailaddress">
							</div>

							<label><?php _e('Subject','vz_front_terms'); ?>:</label> 
							<input type="text" onblur="this.placeholder = '<?php _e('Subject','vz_front_terms'); ?>'" onfocus="this.placeholder = '' " class="rounded_2" placeholder="<?php _e('Subject','vz_front_terms'); ?>" name="subject">
							<label><?php _e('Your message','vz_front_terms'); ?>:</label> 
							<textarea onblur="this.placeholder = '<?php _e('Your message','vz_front_terms'); ?>'" onfocus="this.placeholder = '' " class="rounded_2 vzinput_req" placeholder="<?php _e('Your message','vz_front_terms'); ?>" name="yourmessage"></textarea>

							<div class="clear"></div>
							<input type="submit" class="main" value="<?php _e('Send','vz_front_terms'); ?>">
							<input type="reset" class="second" value="<?php _e('Cancel','vz_front_terms'); ?>">
						</form>

						<div class="clear"></div>
					</div>

				</div>

			<?php endif; ?>

		</div>
		<!-- END CONTENT  -->

	</div>

<?php get_footer(); ?>