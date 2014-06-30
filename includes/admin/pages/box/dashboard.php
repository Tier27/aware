<?php

/**
 * The dashboard admin page.
 *
 * @package     AWARE
 * @subpackage  The dashboard admin page.
 * @copyright   Copyright (c) 2013, Joshua B. Kornreich
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function awareShowDashboardPage() {
	/*
	 * Check whether user can view the Dashboard
	 */
	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( '<p id="error-page" style="-moz-background-clip:border;
				-moz-border-radius:11px;
				background:#FFFFFF none repeat scroll 0 0;
				border:1px solid #DFDFDF;
				color:#333333;
				display:block;
				font-size:12px;
				line-height:18px;
				margin:25px auto 20px;
				padding:1em 2em;
				text-align:center;
				width:700px">You do not have sufficient permissions to access this page.</p>' );
	}
	else {
		global $connections;

		add_meta_box( 
		    'client-update-metabox',
		    'Client Update Metabox',
		    'awareDashboardMeta',
		    'AWARE_dashboard',
		    'normal' );
		
		function AWAREDashboardMeta() {

			echo '<form action="admin-post.php" method="post">';
			echo '<input type="hidden" name="action" value="add_foobar">';

			echo "<select class='clients' name='to'>";
			$users = get_users();
			foreach( $users as $user ) :
				echo "<option value='$user->user_email'>$user->first_name</option>";
			endforeach;
			echo "</select>";
			echo "<textarea class='message' name='message'></textarea>";
			submit_button('Send Update', 'primary', 'submit', false);
			echo '</form>';

		}


		//$form = new cnFormObjects();
?>
		<div class="wrap">
			<?php echo get_screen_icon( 'connections' ); ?>

			<h2>AWARE : <?php _e( 'Dashboard', 'connections' ); ?></h2>

			<?php do_meta_boxes( 'aware_dashboard', 'normal' ); ?>

			<div id="dashboard-widgets-wrap">

				<div class="metabox-holder" id="dashboard-widgets">

					<div style="width: 49%;" class="postbox-container">
						<?php //do_meta_boxes( $connections->pageHook->dashboard, 'left', NULL ); ?>
					</div>

					<div style="width: 49%;" class="postbox-container">
						<?php do_meta_boxes( $connections->pageHook->dashboard, 'right', NULL ); ?>
					</div>

				</div>

			</div>

		</div>

		<?php
		$attr = array(
			'action' => '',
			'method' => 'get'
		);

		//$form->open( $attr );

		//wp_nonce_field( 'howto-metaboxes-general' );
		//wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		//wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );

		//$form->close();
?>


		<div class="clear"></div>

		<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			//postboxes.add_postbox_toggles('<?php echo $connections->pageHook->dashboard ?>');
		});
		//]]>
		</script>
	<?php
	}
}

?>
