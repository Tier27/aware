<?php

/**
 * The role capability admin page.
 *
 * @package     Connections
 * @subpackage  The role capability admin page.
 * @copyright   Copyright (c) 2013, Steven A. Zahm
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       unknown
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function awareShowRolesPage() {
	$role = new \AWARERole();
	$role->add( 'editor', 'aware_change_roles' );
	$role->speak();
	print_r( array('b'=>'o', 'o'=>'b') );
	/*
	 * Check whether user can edit roles
	 */
	if ( false && !current_user_can( 'aware_change_roles' ) ) {
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
				width:700px">' . __( 'You do not have sufficient permissions to access this page.', 'aware' ) . '</p>' );
	}
	else {
		global $aware, $wp_roles;

		$form = new cnFormObjects();

?>
		<div class="wrap aw-roles">

			<h2>Aware : <?php _e( 'Roles &amp; Capabilities', 'aware' ); ?></h2>

			<?php
		$attr = array(
			'action' => '',
			'method' => 'post',
		);

		$form->open( $attr );
		$form->tokenField( 'update_role_settings' );
?>

			<div id="poststuff" class="metabox-holder has-right-sidebar">

				<div class="inner-sidebar" id="side-info-column">
					<div id="submitdiv" class="postbox">
						<h3 class="hndle" style="cursor: auto;">
							<span><?php _e( 'Save Changes or Reset', 'connections' ); ?></span>
						</h3>

						<div class="inside">

							<div id="minor-publishing">
								<label for="reset_all_roles">
									<input type="checkbox" id="reset_all_roles" name="reset_all" value="true">
									<?php _e( 'Reset All Role Capabilities', 'connections' ); ?>
								</label>
							</div>

							<div id="major-publishing-actions">
								<div id="publishing-action">
									<input type="hidden" name="aw-action" value="update_role_capabilities"/>
									<input class="button-primary" type="submit" value="<?php _e( 'Update', 'connections' ); ?>" name="save" />
								</div>
								<div class="clear"></div>
							</div>
						</div>

					</div>
				</div>

				<div class="has-sidebar" id="post-body">
					<div class="has-sidebar-content" id="post-body-content">
						<?php
		$editable_roles = get_editable_roles();

		foreach ( $editable_roles as $role => $details ) {
			$name = translate_user_role( $details['name'] );

			// the admininistrator should always have all capabilities
			if ( $role == 'administrator' ) continue;

			$capabilies = AWARERole::capabilities();

			echo '<div class="postbox">';

			echo '<h3 class="hndle" style="cursor: auto;"><span>' , $name , '</span></h3>';

			echo '<div class="inside">';

			foreach ( $capabilies as $capability => $capabilityName ) {

				echo '<span style="display: block;"><label for="' . $role . '_' . $capability . '">';
				echo '<input type="hidden" name="roles[' . $role . '][capabilities][' . $capability . ']" value="false" />';
				echo '<input type="checkbox" id="' . $role . '_' . $capability . '" name="roles[' . $role . '][capabilities][' . $capability . ']" value="true" ';

				if ( AWARERole::hasCapability( $role, $capability ) ) echo 'CHECKED ';
				// the admininistrator should always have all capabilities
				if ( $role == 'administrator' ) echo 'DISABLED ';
				echo '/> ' . $capabilityName . '</label></span>' . "\n";

			}

			echo '<span style="display: block;"><label for="' . $role . '_reset_capabilities">';
			echo '<input type="checkbox" id="' . $role . '_reset_capabilities" name="reset[' . $role . ']" value="' . $name . '" /> ';
			echo sprintf( __( 'Reset %s Capabilities', 'aware' ) , $name ) . '</label></span>' . "\n";

			echo '</div>';
			echo '</div>';
		}
?>
					</div>
				</div>
			</div>

			<?php $form->close(); ?>


		</div>
		<div class="clear"></div>

	<?php }
}
?>
