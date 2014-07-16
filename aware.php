<?php
/**
 * Plugin Name: AWARE
 * Plugin URI: http://aware.plugins.tier27.com/
 * Description: A superb system to manage clients
 * Version: 0.0.1
 * Author: Joshua Kornreich
 * Author URI: http://joshua.tier27.com/
 * Text Domain: aware_plugin
 * Domain Path: languages
 *
 * Copyright 2014  Joshua B. Kornreich  ( email : joshua@tier27.com )
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AWARe; if not, see <http://www.gnu.org/licenses/>.
 *
 * @package AWARE
 * @category Core
 * @author Joshua B. Kornreich
 * @version 0.0.1
 */

define( 'AWARE_PATH', plugin_dir_path( __FILE__ ) );
define( 'AWARE_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
define( 'AWARE_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'AWARE_PATH', plugin_dir_path( __FILE__ ) );
define( 'AWARE_URL', plugin_dir_url( __FILE__ ) );

require_once AWARE_PATH . 'includes/class.load.php'; 

$AWARELoad = new AWARELoad();

/*****************************/

add_action( 'wp_ajax_client_create_thread', 'client_create_thread' );
function client_create_thread() {

	$post = array(
		'post_content'	=> $_POST['content'],
		'post_title'	=> $_POST['title'],
		'post_name'	=> sanitize_title($_POST['title']),
		'post_type'	=> 'conversation',
		'post_status'	=> 'private',
	);
	$ID = wp_insert_post( $post );
	echo json_encode( array( 'action' => 'redirect', 'location' => get_the_permalink( $ID ) ) );

	die(); // this is required to return a proper result
}

add_action( 'wp_ajax_admin_create_update', 'admin_create_update' );
function admin_create_update() {

	$post = array(
		'post_content'	=> $_POST['content'],
		'post_title'	=> $_POST['title'],
		'post_name'	=> sanitize_title($_POST['title']),
		'post_type'	=> 'update',
		'post_status'	=> 'private',
	);
	$ID = wp_insert_post( $post );
        $email = aware_email_from_id( $_POST['client'] );

        $message = 'You have a new update on your project. Click <a href="' . admin_url() . '">here</a> to view this update.';
	//echo $message;
        wp_mail( $email, 'New update from AWARE', $message );
	update_post_meta( $ID, 'client', $_POST['client'] );
	$_POST['response'] = 'Something fresh.';
	echo json_encode( $_POST );

	die(); // this is required to return a proper result
}

function aware_the_title() {
	$title = str_replace( 'Private:', '', get_the_title());
	echo $title;
}

add_filter( 'login_redirect', 'aware_client_login_redirect', 10, 3 );
function aware_client_login_redirect() {
}

function soi_login_redirect( $redirect_to, $request, $user  ) {
	return ( is_array( $user->roles ) && in_array( 'client', $user->roles ) ) ? site_url('/client') : admin_url();
} 
add_filter( 'login_redirect', 'soi_login_redirect', 10, 3 );


function aware_email_from_id( $ID ) {
        global $wpdb;
        return $wpdb->get_var("SELECT user_email FROM $wpdb->users WHERE ID=$ID");
}

add_action( 'wp_ajax_admin_update_settings', 'admin_update_settings' );

function admin_update_settings() {
	print_r( $_POST );
	if( !empty( $_POST['aware-administrative-email'] ) ) update_option('aware_administrative_email', $_POST['aware-administrative-email']);
	if( !empty( $_POST['aware-client-administrative-email'] ) ) update_option('aware_client_administrative_email', $_POST['aware-client-administrative-email']);
	update_option('aware_development_mode', $_POST['aware-development-mode']);
	die();
}

add_action( 'show_user_profile', 'aware_add_manager_field' );
add_action( 'edit_user_profile', 'aware_add_manager_field' );

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

add_action( 'personal_options_update', 'aware_save_manager_field' );
add_action( 'edit_user_profile_update', 'aware_save_manager_field' );

function aware_save_manager_field( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'aware_manager', $_POST['aware-manager'] );
}

add_action( 'wp_ajax_admin_update_client', 'admin_update_client' );
function admin_update_client() {
	$args = array( 
		'ID' => $_POST['ID'], 
		'first_name' => $_POST['first-name'],
		'last_name' => $_POST['last-name'],
		'user_email' => $_POST['email'],
	);
	if( $_POST['password'] != '' ) $args['user_pass'] = $_POST['password'];
	$user_id = wp_update_user( $args );
	update_user_meta( $user_id, 'manager', $_POST['manager'] );

	$previous_project_array = get_user_meta( $user_id, 'projects', true );
	//Remove previous projects that are no longer associated
	foreach( $previous_project_array as $previous_project ) :
		if( !in_array($previous_project, $_POST['projects'] ) ) :
			remove_client_from_project( $user_id, $project_id );
		endif;
	endforeach;

	//update_user_meta( $user_id, 'projects', $_POST['projects'] );
	delete_user_meta( $user_id, 'projects' );
	foreach( $_POST['projects'] as $project ) add_user_meta( $user_id, 'projects', $project );

	//Move this 
	foreach( $_POST['projects'] as $project_id ) add_client_to_project( $user_id, $project_id );

	update_user_meta( $user_id, 'client-id', $_POST['client-id'] );
	update_user_meta( $user_id, 'notes', $_POST['notes'] );
	die();
}

add_action( 'wp_ajax_admin_add_client', 'admin_add_client' );

function admin_add_client() {
	$args = array(
		'user_login' => $_POST['email'],
		'first_name' => $_POST['first-name'],
		'last_name' => $_POST['last-name'],
		'user_email' => $_POST['email'],
		'user_pass' => $_POST['password'],
		'role' => 'client',
	);
	$user_id = wp_insert_user( $args );
	update_user_meta( $user_id, 'manager', $_POST['manager'] );
	update_user_meta( $user_id, 'projects', $_POST['projects'] );

	//Move this 
	foreach( $_POST['projects'] as $project_id ) add_client_to_project( $user_id, $project_id );

	update_user_meta( $user_id, 'client-id', $_POST['client-id'] );
	update_user_meta( $user_id, 'notes', $_POST['notes'] );
	$response = array(
		"ID" => $user_id,
		"text" => "The client has been created",
	);
	echo json_encode($response);
	die();
}

add_action( 'wp_ajax_admin_delete_client', 'admin_delete_client' );
function admin_delete_client() {
	if( isset( $_POST['ID'] ) && !empty( $_POST['ID'] ) ) wp_delete_user( $_POST['ID'] );
}

add_action( 'wp_ajax_admin_update_project', 'admin_update_project' );
function admin_update_project() {
	$args = array( 
		'ID' => $_POST['ID'], 
		'post_title' => $_POST['name'],
		'post_name' => sanitize_title($_POST['name']),
	);
	$post_id = wp_update_post( $args );
	update_post_meta( $post_id, 'notes', $_POST['notes'] );
	update_post_meta( $post_id, 'client', $_POST['client'] );
	update_post_meta( $post_id, 'clients', $_POST['clients'] );

	//Move this 
	foreach( $_POST['clients'] as $client ) add_project_to_client( $project_id, $project_id );

	echo $post_id;
	die();
}

add_action( 'wp_ajax_admin_add_project', 'admin_add_project' );
function admin_add_project() {
	$args = array( 
		'post_title' => $_POST['name'],
		'post_name' => sanitize_title($_POST['name']),
		'post_type' => 'project',
		'post_status' => 'publish'
	);
	$post_id = wp_insert_post( $args );
	update_post_meta( $post_id, 'notes', $_POST['notes'] );
	echo $post_id;
	die();
}


add_action( 'wp_ajax_admin_delete_project', 'admin_delete_project' );
function admin_delete_project() {
	if( isset( $_POST['ID'] ) && !empty( $_POST['ID'] ) ) wp_delete_post( $_POST['ID'] );
}

add_action( 'wp_ajax_admin_update_event', 'admin_update_event' );

function admin_update_event() {
	$args = array( 
		'ID' => $_POST['ID'], 
		'post_title' => $_POST['name'],
		'post_name' => sanitize_title($_POST['name']),
	);
	$post_id = wp_update_post( $args );
	$time = $_POST['start-date'] . ' ' . $_POST['start-hour'] . ':' . $_POST['start-minute'] . ':00' . $_POST['start-suffix'];
	update_post_meta( $post_id, 'start_time', strtotime($time) );
	update_post_meta( $post_id, 'notes', $_POST['notes'] );
	update_post_meta( $post_id, 'client', $_POST['client'] );
	update_post_meta( $post_id, 'projects', $_POST['projects'] );
	//echo $post_id;
	die();
}

add_action( 'wp_ajax_admin_add_event', 'admin_add_event' );

function admin_add_event() {
	$args = array( 
		'post_title' => $_POST['name'],
		'post_name' => sanitize_title($_POST['name']),
		'post_type' => 'event',
		'post_status' => 'publish'
	);
	$post_id = wp_insert_post( $args );
	$time = $_POST['start-date'] . ' ' . $_POST['start-hour'] . ':' . $_POST['start-minute'] . ':00' . $_POST['start-suffix'];
	update_post_meta( $post_id, 'start_time', strtotime($time) );
	update_post_meta( $post_id, 'notes', $_POST['notes'] );
	update_post_meta( $post_id, 'client', $_POST['client'] );
	update_post_meta( $post_id, 'projects', $_POST['projects'] );
	echo $post_id;
	die();
}

add_action( 'wp_ajax_admin_delete_event', 'admin_delete_event' );

function admin_delete_event() {
	if( isset( $_POST['ID'] ) && !empty( $_POST['ID'] ) ) wp_delete_post( $_POST['ID'] );
}

function soft_is_admin() {
	if( current_user_can( 'manage_options' ) ) return true;
}

//Move this
function add_client_to_project( $client_id, $project_id ) {
	$clients = get_post_meta( $project_id, 'clients', true );
	if( !is_array( $clients ) || !in_array($client_id, $clients) ) :
		$clients[] = $client_id;
		update_post_meta( $project_id, 'clients', $clients );
	endif;
}

//This doesn't do anything
function remove_client_from_project( $client_id, $project_id ) {
	$clients = get_post_meta( $project_id, 'clients', true );
	if( in_array($client_id, $clients) ) :
		//$clients[] = $client_id;
		update_post_meta( $project_id, 'clients', $clients );
	endif;
}

//Move this
function add_project_to_client( $project_id, $client_id ) {
	$projects = get_post_meta( $client_id, 'projects', true );
	if( !in_array($project_id, $projects) ) :
		$projects[] = $project_id;
		update_post_meta( $client_id, 'projects', $projects );
	endif;
}
