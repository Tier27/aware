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

	}

	private static function defineConstants() {
		define( 'AWARE_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
		define( 'AWARE_BASE_NAME', plugin_basename( __FILE__ ) );
		define( 'AWARE_PATH', plugin_dir_path( __FILE__ ) );
		define( 'AWARE_URL', plugin_dir_url( __FILE__ ) );
	}

	private static function includes() {

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
}

add_action('admin_print_styles', 'do_css' );
function do_css()
{
    wp_enqueue_style('aware-admin');
    wp_enqueue_style('aware-admin-custom');
    if( $_GET['page'] == 'aware_dashboard' ) wp_enqueue_style('aware-foundation');
}

add_action('admin_print_scripts', 'do_jslibs' );
function do_jslibs()
{
    wp_enqueue_script('editor');
    wp_enqueue_script( 'aware-main', AWARE_URL . 'assets/js/aware.js', array( 'jquery' ) );
    wp_enqueue_script( 'aware-foundation', AWARE_URL . 'assets/js/foundation.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'aware-foundation-accordion', AWARE_URL . 'assets/js/foundation/foundation.accordion.js', array( 'jquery' ) );
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
		'post_type'	=> 'ai1ec_event',
	);
	$args = array_merge($basis, $args);
	return get_posts( $args );

}


function admin_get_projects ( $args = array() ) {

	$basis = array(
		'post_type'	=> 'project',
	);
	$args = array_merge($basis, $args);
	return get_posts( $args );

}


/************************************************
********Template Parts **************************
************************************************/

function aware_event_part() { ?>
      <?php $events = aware_get_ai1ec_events(); ?>
      <div class="large-12 medium-12 small-12 columns panel section">
        <h3><i class="fa fa-calendar"></i> Calendar</h3><hr>
	<?php foreach( $events as $event ) : ?>
        <?php $clients = get_post_meta($event->ID, 'client'); ?>
        <div class="large-12 panel columns section">
          <div class="large-3 columns">
            <h5><a href="post.php?post=<?php echo $event->ID; ?>&action=edit"><?php echo $event->post_title; ?></a></h5>
          </div>
          <div class="large-3 columns">
            <h5><a href="#"><?php echo implode( ', ', $clients ); ?></a></h5>
          </div>
          <div class="large-3 columns">
            <h5><a href="#"><?php echo date('m/d/Y', $event->start); ?></a></h5>
          </div>
          <div class="large-3 columns">
            <h5><a href="#"><?php echo date('g:i a', $event->end); ?></a></h5>
          </div>
        </div>
	<?php endforeach; ?>
        <a href="#" class="right">Go To Calendar »</a>
      </div>
<?php } 

function aware_project_part() { ?>
      <?php $projects = CPM_Project::getInstance()->get_projects(); ?>
      <div class="large-12 medium-12 small-12 columns panel section">
        <h3><i class="fa fa-space-shuttle"></i> Projects</h3><hr>
	<?php foreach( $projects as $project ) : ?>
        <div class="row">
          <div class="large-2 column">
            <img src="http://placehold.it/50x50&text=[img]">
          </div>
          <div class="large-4 columns">
            <h5><a href="admin.php?page=cpm_projects&tab=project&action=single&pid=<?php echo $project->ID; ?>"><?php echo $project->post_title; ?></a></h5>
          </div>
          <div class="large-4 columns">
            <h5><a href="#"><?php foreach( $project->users as $user ) echo "<li>" . $user['name'] . "</li>"; ?></a></h5>
          </div>
        </div><hr>
	<?php endforeach; ?>
        <a href="#" class="right">Go To Projects »</a>
      </div>
<?php }

function aware_communication_part() { ?>
      <?php $threads = client_get_threads(); ?>
      <div class="large-12 small-12 columns panel section">
        <h3><i class="fa fa-comments"></i> Communication</h3><hr>
        <?php foreach( $threads as $thread ) : ?>
        <div class="panel communication-widget">
          <div class="communication-widget-avatar">
          </div>
          <div class="communicaiton-widget-description">
              <p><?php echo $thread->post_title; ?></p>
          </div>
        </div>
         <?php endforeach; ?>
      </div>
<?php } 

function aware_client_part() { ?>
      <?php $clients = admin_get_clients(); ?>
      <div class="large-12 medium-12 small-12 columns panel section">
        <h3><i class="fa fa-space-shuttle"></i> Clients</h3><hr>
        <?php foreach( $clients as $client ) : ?>
        <div class="row">
          <div class="large-1 column">
           <?php echo get_avatar( $client->ID ); ?>
          </div>
          <div class="large-9 columns">
            <h5><a href="user-edit.php?user_id=<?php echo $client->ID; ?>"><?php echo $client->display_name; ?></a></h5>
          </div>
         </div><hr>
         <?php endforeach; ?>
       </div>
<?php }

function aware_accordion_part( $dt ) {
	switch( $dt ) {
		case 'client' :
			$data = admin_get_clients();
			break;
	}
?>

      <div class="large-12 medium-12 small-12 columns panel section">
        <h3><i class="fa fa-space-shuttle"></i> Clients</h3><hr>
        <dl class="accordion" data-accordion>
<?php
	foreach( $data as $datum ) :
?>
  <dd class="accordion-navigation">
    <a href="#client-<?php echo $datum->ID; ?>"><?php echo get_avatar( $datum->ID ); ?> <?php echo $datum->display_name; ?></a>
    <div id="client-<?php echo $datum->ID; ?>" class="content">
	<form>
		<input name="name">
	</form>
    </div>
  </dd>
<?php
	endforeach;
?>
  <dd class="accordion-navigation">
    <a href="#client-new"><i class="fa fa-plus"></i> Add new</a>
    <div id="client-new" class="content">
      Panel 1. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
    </div>
  </dd>
        </dl>
      </div>
<?php
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
