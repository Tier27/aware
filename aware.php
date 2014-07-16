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

class AWARELoad {

	public function __construct() {

		$this->defineConstants();
		$this->includes();
		global $templates;
		$templates = new AWARETemplateParts();

	}

	private static function defineConstants() {
		define( 'AWARE_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
		define( 'AWARE_BASE_NAME', plugin_basename( __FILE__ ) );
		define( 'AWARE_PATH', plugin_dir_path( __FILE__ ) );
		define( 'AWARE_URL', plugin_dir_url( __FILE__ ) );
	}

	private static function includes() {

		require_once AWARE_PATH . 'includes/class.template-parts.php'; 
		require_once AWARE_PATH . 'includes/class.admin-parts.php'; 
		require_once AWARE_PATH . 'includes/class.rewrite.php'; 
		require_once AWARE_PATH . 'includes/admin/class.menu.php'; 
		require_once AWARE_PATH . 'includes/admin/class.actions.php'; 
		require_once AWARE_PATH . 'includes/admin/class.classes.php'; 

	}

}

$AWARELoad = new AWARELoad();


add_action('init', 'register_css' );
function register_css()
{
    wp_register_style( 'aware-admin', AWARE_URL . "assets/css/admin.css", array(), '0.0.1' );
    wp_register_style( 'aware-admin-custom', AWARE_URL . "assets/css/custom-style.css", array(), '0.0.1' );
    wp_register_style( 'aware-foundation', AWARE_URL . "assets/css/foundation.css", array(), '0.0.1' );
    wp_register_style( 'aware-foundation-ui', AWARE_URL . "assets/css/ui.css", array(), '0.0.1' );
}

add_action('admin_print_styles', 'do_css' );
function do_css()
{
    wp_enqueue_style('aware-admin');
    wp_enqueue_style('aware-admin-custom');
    if( $_GET['page'] == 'aware_dashboard' ) :
	wp_enqueue_style('aware-foundation');
	wp_enqueue_style('aware-foundation-ui');
    endif;
}

add_action('admin_print_scripts', 'do_jslibs' );
function do_jslibs()
{
    wp_enqueue_script('editor');
    wp_enqueue_script( 'aware-main', AWARE_URL . 'assets/js/aware.js', array( 'jquery' ) );
    wp_enqueue_script( 'aware-foundation', AWARE_URL . 'assets/js/foundation.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'aware-foundation-accordion', AWARE_URL . 'assets/js/foundation/foundation.accordion.js', array( 'jquery' ) );
    wp_enqueue_script( 'aware-foundation-datepicker', AWARE_URL . 'assets/js/foundation/foundation-datepicker.js', array( 'jquery' ) );
    add_action( 'admin_head', 'wp_tiny_mce' );
}

add_action('wp_enqueue_scripts', 'aware_enqueue_scripts');
function aware_enqueue_scripts() {
    wp_enqueue_script( 'aware-main', AWARE_URL . 'assets/js/aware.js', array( 'jquery' ) );
}
add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

add_action( 'init', 'create_posttype' );
function create_posttype() {
	register_post_type( 'acme_product',
		array(
			'labels' => array(
				'name' => __( 'Products' ),
				'singular_name' => __( 'Product' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'products'),
		)
	);
}

function aware_action_wp_enqueue_scripts() {
	wp_enqueue_style('aware-foundation');
}
//add_action( 'wp_enqueue_scripts', 'aware_action_wp_enqueue_scripts' );

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

function client_get_threads ( $args = array() ) {

	$basis = array(
		'post_type'	=> 'conversation',
		'post_status'	=> 'private',
	);
	$args = array_merge($basis, $args);
	return get_posts( $args );

}

function client_get_updates ( $args = array() ) {

	$basis = array(
		'post_type'	=> 'update',
		'post_status'	=> 'private',
		'meta_key'	=> 'client',
		'meta_value'	=> $args['client'],
	);
	$args = array_merge($basis, $args);
	return get_posts( $args );

}

function admin_get_clients() {

	$args = array( 'role' => 'client' );
	if( get_option( 'aware_development_mode' ) == 1 ) $args['role'] = '';
	return get_users( $args );

}

function admin_get_managers() {

	$args = array( 'role' => 'manager' );
	return get_users( $args );

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

function admin_get_events ( $args = array() ) {

	$basis = array(
		'post_type'	=> 'event',
	);
	$args = array_merge($basis, $args);
	return get_posts( $args );

}


function admin_get_projects ( $args = array() ) {

	$basis = array(
		'post_type'	=> 'project',
	);
	
	//We don't want to break the basis by passing the wrong data type into the merge
	if( is_array( $args ) )
		$args = array_merge($basis, $args);
	else
		$args = $basis;

	return get_posts( $args );

}

function aware_get_ai1ec_events() {

	global $wpdb;
	return $wpdb->get_results("SELECT * FROM wp_ai1ec_events e JOIN wp_posts p ON e.post_id = p.ID");

}

function aware_get_pm_projects() {

	global $wpdb;
	return $wpdb->get_results("SELECT * FROM wp_posts p JOIN wp_postmeta pm ON p.ID = pm.post_id WHERE p.post_type='project'");

}

function aware_add_client_role() {

        $result = add_role(
            'client',
            __( 'Client' ),
            array(
                'read'         => true,  // true allows this capability
                'edit_posts'   => true,
                'delete_posts' => false, // Use false to explicitly deny
            )
        );

}

add_action( 'init', 'aware_add_manager_role' );
function aware_add_manager_role() {

        $result = add_role(
            'manager',
            __( 'Manager' ),
            array(
                'read'         => true,  // true allows this capability
                'edit_posts'   => true,
                'delete_posts' => false, // Use false to explicitly deny
            )
        );

}


add_action( 'init', 'aware_conversation_init' );
function aware_conversation_init() {
	$labels = array(
		'name'               => _x( 'Conversations', 'post type general name', 'aware' ),
		'singular_name'      => _x( 'Conversation', 'post type singular name', 'aware' ),
		'menu_name'          => _x( 'Conversations', 'admin menu', 'aware' ),
		'name_admin_bar'     => _x( 'Conversation', 'add new on admin bar', 'aware' ),
		'add_new'            => _x( 'Add New', 'book', 'aware' ),
		'add_new_item'       => __( 'Add New Conversation', 'aware' ),
		'new_item'           => __( 'New Conversation', 'aware' ),
		'edit_item'          => __( 'Edit Conversation', 'aware' ),
		'view_item'          => __( 'View Conversation', 'aware' ),
		'all_items'          => __( 'All Conversations', 'aware' ),
		'search_items'       => __( 'Search Conversations', 'aware' ),
		'parent_item_colon'  => __( 'Parent Conversations:', 'aware' ),
		'not_found'          => __( 'No conversations found.', 'aware' ),
		'not_found_in_trash' => __( 'No conversations found in Trash.', 'aware' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'conversation' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'conversation', $args );
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
} // end soi_login_redirect
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

function aware_add_manager_field( $user ) { ?>

	<table class="form-table">

		<tr>
			<th><label for="twitter">Manager</label></th>

			<td>
				<?php $managers = admin_get_managers(); ?>
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

	update_user_meta( $user_id, 'projects', $_POST['projects'] );

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

//Move this
function add_project_to_client( $project_id, $client_id ) {
	$projects = get_post_meta( $client_id, 'projects', true );
	if( !in_array($project_id, $projects) ) :
		$projects[] = $project_id;
		update_post_meta( $client_id, 'projects', $projects );
	endif;
}

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


/***************************
**** Rewrite ***************
***************************/
add_action( 'init', 'aware_rewrites_init' );
function aware_rewrites_init(){
	add_rewrite_rule(
        	'client/([0-9]+)/?$',
	        'index.php?pagename=client&client_id=$matches[1]',
	        'top' 
	);
	add_rewrite_rule(
		'^client/([^/]*)/?$',
	        'index.php?pagename=client&aware_type=$matches[1]',
		'top'
	);
	//flush_rewrite_rules(false);
}


add_filter( 'query_vars', 'aware_query_vars' );
function aware_query_vars( $query_vars ){
	$query_vars[] = 'client_id';
	$query_vars[] = 'aware_type';
	return $query_vars;
}


/***************************
**** End Rewrite ***************
***************************/
