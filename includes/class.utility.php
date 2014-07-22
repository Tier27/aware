<?php

function aware_the_title() {
	$title = str_replace( 'Private:', '', get_the_title());
	echo $title;
}

//add_filter( 'login_redirect', 'aware_client_login_redirect', 10, 3 );
function aware_client_login_redirect() {
}

function soi_login_redirect( $redirect_to, $request, $user  ) {
	return ( is_array( $user->roles ) && ( in_array( 'client', $user->roles ) || true ) ) ? site_url('/client') : admin_url();
} 
add_filter( 'login_redirect', 'soi_login_redirect', 10, 3 );


function aware_email_from_id( $ID ) {
        global $wpdb;
        return $wpdb->get_var("SELECT user_email FROM $wpdb->users WHERE ID=$ID");
}

//add_action( 'show_user_profile', 'aware_add_manager_field' );
//add_action( 'edit_user_profile', 'aware_add_manager_field' );

function aware_add_manager_field( $user ) { 

	global $retrieve; ?>

	<table class="form-table">

		<tr>
			<th><label for="twitter">Manager</label></th>

			<td>
				<?php $managers = $retrieve->managers(); ?>
				<?php $this_manager = get_usermeta( $user->ID, 'aware_manager' ); ?>
				<select name="aware-manager" data-user="<?php echo $user->ID; ?>">
					<option value="0">No manager</option>
					<?php foreach( $managers as $manager ) : ?>
					<option value="<?php echo $manager->ID; ?>" <?php if( $manager->ID == $this_manager ) echo "selected=\"selected\""; ?>><?php echo $manager->display_name ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

	</table>
<?php }

//add_action( 'personal_options_update', 'aware_save_manager_field' );
//add_action( 'edit_user_profile_update', 'aware_save_manager_field' );

function aware_save_manager_field( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'aware_manager', $_POST['aware-manager'] );
}


function soft_is_admin() {
	if( current_user_can( 'manage_options' ) ) return true;
}

?>
