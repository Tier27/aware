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
	$args = array_merge($basis, $args);
	return get_posts( $args );

}


/************************************************
********Template Parts **************************
************************************************/

function aware_event_part() { ?>
      <?php //$events = aware_get_ai1ec_events(); ?>
      <?php $events = admin_get_events(); ?>
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
	      <?php aware_accordion_part_form( $datum ); ?>
	    </div>
	  </dd>
<?php
	endforeach;
?>
	  <dd class="accordion-navigation">
	    <a href="#client-new"><i class="fa fa-plus"></i> Add new</a>
	    <div id="client-new" class="content">
	      <?php aware_accordion_part_form( null, 'add' ); ?>
	    </div>
	  </dd>
          </dl>
        </div>
<?php
}

function aware_accordion_part_projects() {
	$projects = admin_get_projects();
?>

      <div class="large-12 medium-12 small-12 columns panel section">
        <h3><i class="fa fa-space-shuttle"></i> Projects</h3><hr>
        <dl class="accordion" data-accordion>
<?php
	foreach( $projects as $project ) :
?>
  <dd class="accordion-navigation">
    <a href="#project-<?php echo $project->ID; ?>"><?php echo get_avatar( $project->ID ); ?> <?php echo $project->post_title; ?></a>
    <div id="project-<?php echo $project->ID; ?>" class="content">
	<?php aware_accordion_part_project_form( $project ); ?>
    </div>
  </dd>
<?php
	endforeach;
?>
  <dd class="accordion-navigation">
    <a href="#project-new"><i class="fa fa-plus"></i> Add new</a>
    <div id="project-new" class="content">
      <?php aware_accordion_part_project_form( null, 'add' ); ?>
    </div>
  </dd>
        </dl>
      </div>
<?php
}

function aware_accordion_part_events() {
	$events = admin_get_events();
?>

      <div class="large-12 medium-12 small-12 columns panel section">
        <h3><i class="fa fa-space-shuttle"></i> Events</h3><hr>
        <dl class="accordion" data-accordion>
<?php
	foreach( $events as $event ) :
?>
  <dd class="accordion-navigation">
    <a href="#event-<?php echo $event->ID; ?>"><?php echo get_avatar( $event->ID ); ?> <?php echo $event->post_title; ?></a>
    <div id="event-<?php echo $event->ID; ?>" class="content">
	<?php aware_accordion_part_event_form( $event ); ?>
    </div>
  </dd>
<?php
	endforeach;
?>
  <dd class="accordion-navigation">
    <a href="#event-new"><i class="fa fa-plus"></i> Add new</a>
    <div id="event-new" class="content">
      <?php aware_accordion_part_event_form( null, 'add' ); ?>
    </div>
  </dd>
        </dl>
      </div>
<?php
}

function aware_accordion_part_form( $obj = NULL, $action = 'update' ) { ?>
<?php if( $obj != NULL ) $meta = get_user_meta( $obj->ID ); ?>
<form>
  <div class="row">
    <div class="large-6 columns">
      <label>First name
        <input type="text" name="first-name" value="<?php echo $meta["first_name"][0]; ?>"/>
      </label>
    </div>
    <div class="large-6 columns">
      <label>Last name
        <input type="text" name="last-name" value="<?php echo $meta["last_name"][0]; ?>"/>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-4 columns">
      <label>Email
        <input type="text" name="email" value="<?php echo $obj->user_email; ?>" />
      </label>
    </div>
    <div class="large-4 columns">
      <label>Password
        <input type="password" name="password" />
      </label>
    </div>
    <div class="large-4 columns">
      <label>Client ID
        <input type="text" name="client-id" value="<?php echo $meta["client-id"][0]; ?>"/>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <?php $managers = admin_get_managers(); ?>
      <label>Manager
        <select name="manager">
	  <option value="0">No manager</option>
	  <?php foreach( $managers as $manager ) : ?>
	  <option value="<?php echo $manager->ID; ?>" <?php if( $manager->ID == $meta["manager"][0] ) echo "selected=\"selected\""; ?>><?php echo $manager->display_name ?></option>
	  <?php endforeach; ?>
        </select>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label>Projects</label>
      <?php $projects = admin_get_projects(); ?>
      <?php foreach( $projects as $project ) : ?>
      <input type="checkbox" name="projects[]" value="<?php echo $project->ID; ?>" <?php if( in_array( $project->ID, unserialize($meta["projects"][0])) ) echo "checked=\"checked\""; ?>><label for="checkbox1"><?php echo $project->post_title; ?></label>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label>Notes (private)
        <textarea name="notes" placeholder="Notes about your client"><?php echo $meta["notes"][0]; ?></textarea>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <input name="ID" value="<?php echo $obj->ID; ?>" class="hidden">
      <input name="action" value="admin_<?php echo $action; ?>_client" class="hidden">
      <input name="aware-<?php echo $action; ?>-client" class="button radius tiny" value="<?php echo ucfirst($action); ?> client">
      <?php if( $action == 'update' ) : ?><input name="aware-delete-client" class="red button radius tiny" value="Delete client"><?php endif; ?>
      <?php if( $action == 'update' ) : ?>
	<input name="aware-view-client-dashboard" class="orange button radius tiny" value="View Client Dashboard">
      <?php endif; ?>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns response hidden">
      The client has been updated.
    </div>
  </div>
</form>
<?php }

function aware_accordion_part_project_form( $project = NULL, $action = 'update' ) { ?>
<form>
  <div class="row">
    <div class="large-6 columns">
      <label>Name
        <input type="text" name="name" value="<?php echo $project->post_title; ?>"/>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <?php $clients = admin_get_clients(); ?>
      <label>Client
        <select name="client">
	  <option value="0">No client</option>
	  <?php $this_client = get_post_meta( $project->ID, 'client', true ); ?>
	  <?php foreach( $clients as $client ) : ?>
	  <option value="<?php echo $client->ID; ?>" <?php if( $client->ID == $this_client ) echo "selected=\"selected\""; ?>><?php echo $client->display_name ?></option>
	  <?php endforeach; ?>
        </select>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label>Notes (private)
        <textarea name="notes" placeholder="Notes about your project"><?php echo get_post_meta( $project->ID, 'notes', true ); ?></textarea>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <input name="ID" value="<?php echo $project->ID; ?>" class="hidden">
      <input name="action" value="admin_<?php echo $action; ?>_project" class="hidden">
      <input name="aware-<?php echo $action; ?>-project" class="button radius tiny" value="<?php echo ucfirst($action); ?> project">
      <?php if( $action == 'update' ) : ?><input name="aware-delete-project" class="red button radius tiny" value="Delete project"><?php endif; ?>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns response hidden">
      The project has been updated.
    </div>
  </div>
</form>
<?php }

function aware_accordion_part_event_form( $event = NULL, $action = 'update' ) { ?>
<?php
//To handle the mess that is time conversion
$time = ( $time = get_post_meta( $event->ID, 'start_time', true ) ) ? $time : time(); 
$date = date('m/d/Y', $time);
$hour = date('g', $time);
$minute = date('i', $time);
$time_suffix = date('A', $time);
?>
<form>
  <div class="row">
    <div class="large-12 columns">
      <label>Name
        <input type="text" name="name" value="<?php echo $event->post_title; ?>"/>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">
      <label>Start Date
        <input type="text" class="fdatepicker" name="start-date" value="<?php echo $date; ?>"/>
      </label>
    </div>
    <div class="large-2 columns">
      <label>&nbsp;
        <select class="foundation" name="start-hour">
	    <?php for( $i=1; $i<=12; $i++ ) : ?><option value=<?php echo $i; ?> <?php if( $i == $hour ) echo "selected=\"selected\""; ?>><?php echo $i; ?></option><?php endfor; ?>
        </select>
      </label>
    </div>
    <div class="large-2 columns">
      <label>&nbsp;
        <select class="foundation" name="start-minute">
	    <?php for( $i=0; $i<60; $i++ ) : ?><option value=<?php echo $i; ?> <?php if( $i == $minute ) echo "selected=\"selected\""; ?>><?php if( $i < 10 ) echo '0'; echo $i; ?></option><?php endfor; ?>
        </select>
      </label>
    </div>
    <div class="large-2 columns">
      <label>&nbsp;
        <select class="foundation" name="start-suffix">
		<option value="AM" <?php if( 'AM' == $time_suffix ) echo "selected=\"selected\""; ?>>AM</option>
		<option value="PM" <?php if( 'PM' == $time_suffix ) echo "selected=\"selected\""; ?>>PM</option>
        </select>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <?php $clients = admin_get_clients(); ?>
      <label>Client
        <select name="client">
	  <option value="0">No client</option>
	  <?php $this_client = get_post_meta( $event->ID, 'client', true ); ?>
	  <?php foreach( $clients as $client ) : ?>
	  <option value="<?php echo $client->ID; ?>" <?php if( $client->ID == $this_client ) echo "selected=\"selected\""; ?>><?php echo $client->display_name ?></option>
	  <?php endforeach; ?>
        </select>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label>Projects</label>
      <?php $projects = admin_get_projects(); ?>
      <?php foreach( $projects as $project ) : ?>
      <input type="checkbox" name="projects[]" value="<?php echo $project->ID; ?>" <?php if( in_array( $project->ID, get_post_meta( $event->ID, 'projects', true )) ) echo "checked=\"checked\""; ?>><label for="checkbox1"><?php echo $project->post_title; ?></label>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label>Notes (private)
        <textarea name="notes" placeholder="Notes about your event"><?php echo get_post_meta( $event->ID, 'notes', true ); ?></textarea>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <input name="ID" value="<?php echo $event->ID; ?>" class="hidden">
      <input name="action" value="admin_<?php echo $action; ?>_event" class="hidden">
      <input name="aware-<?php echo $action; ?>-event" class="button radius tiny" value="<?php echo ucfirst($action); ?> event">
      <?php if( $action == 'update' ) : ?><input name="aware-delete-event" class="red button radius tiny" value="Delete event"><?php endif; ?>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns response hidden">
      The event has been updated.
    </div>
  </div>
</form>
<?php }


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
	update_user_meta( $user_id, 'projects', $_POST['projects'] );
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

