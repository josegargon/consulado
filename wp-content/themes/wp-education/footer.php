	<footer>

		<div class="footer">

			<?php

			$footer_order = explode(',',get_option('vz_options_arch_footer','footer1,footer2,footer3,footer4'));

			$footer_grid = 0;

			if( is_active_sidebar('footer1') && !get_option("vz_options_arch_footer_footer1_disabled") ) { $footer_grid++; }
			if( is_active_sidebar('footer2') && !get_option("vz_options_arch_footer_footer2_disabled") ) { $footer_grid++; }
			if( is_active_sidebar('footer3') && !get_option("vz_options_arch_footer_footer3_disabled") ) { $footer_grid++; }
			if( is_active_sidebar('footer4') && !get_option("vz_options_arch_footer_footer4_disabled") ) { $footer_grid++; }

			if( $footer_grid > 0 ) : ?>
			<div class="footer_columns">

				<div class="container_12">

					<?php

						for($i = 0; $i <= 3; $i++) :

							if( is_active_sidebar($footer_order[$i]) && !get_option("vz_options_arch_footer_footer{$i}_disabled") ) {
								echo '<div class="grid_'.(12/$footer_grid).'">';
								dynamic_sidebar($footer_order[$i]);
								echo '</div>';
							}

						endfor;

					?>

				</div>

			</div>
			<?php endif; ?>

			<!-- FOOTER NAVIGATION BEGIN -->
			<?php  if ( has_nav_menu( 'footer_nav' ) ) : ?>
			<div class="footer_nav">

				<div class="container_12">

					<div class="grid_12 no_margin">

						<?php wp_nav_menu( array( 'theme_location' => 'footer_nav', 'depth' => 2, 'container' => '' ) ); ?>

					</div>

				</div>

			</div>
			<?php endif; ?>
			<!-- FOOTER NAVIGATION BEGIN -->

			<?php if( !get_option('vz_text_footer_copyright_disabled') ) : ?>
			<div class="footer_copyright">

				<div class="container_12">

					<div class="grid_12">

						<span class="alignleft"> <?php echo get_option('vz_text_footer_left','&copy;  All rights reserved '.date('Y')); ?> </span>
						<span class="alignright"> <?php echo get_option('vz_text_footer_right','theme by Vuzzu'); ?> </span>

					</div>

				</div>

			</div>
			<?php endif; ?>

		</div>

	</footer>

	<?php if( get_option('vz_options_google_analytics_enabled') ) : ?>
	<script type="text/javascript"> <?php echo str_replace("\'","'", str_replace('\"','"', get_option('vz_options_google_analytics_code') ) ); ?> </script>
	<?php endif ?>

	<?php wp_footer(); ?>
</body>

</html>