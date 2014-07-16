<?php

class AWARELoad {

	public function __construct() {

		$this->includes();
		$this->rewrite();
		$this->actions();
		global $templates;
		global $retrieve;
		$templates = new AWARETemplateParts();
		$retrieve = new AWARERetrieve();

	}

	private static function includes() {

		require_once AWARE_PATH . 'includes/class.template-parts.php'; 
		require_once AWARE_PATH . 'includes/class.admin-parts.php'; 
		require_once AWARE_PATH . 'includes/class.rewrite.php'; 
		require_once AWARE_PATH . 'includes/class.retrieve.php'; 
		require_once AWARE_PATH . 'includes/admin/class.menu.php'; 
		require_once AWARE_PATH . 'includes/admin/class.actions.php'; 
		require_once AWARE_PATH . 'includes/admin/class.classes.php'; 

	}

	public function rewrite() {
	
		$rewrite = new AWARERewrite();

	}

	public function actions() {

		add_action('init', array( __CLASS__, 'register_css' ));
		add_action('admin_print_styles', array( __CLASS__, 'do_css' ));
		add_action('admin_print_scripts', array( __CLASS__, 'do_scripts' ));
		add_action('wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ));
		add_action('wp_head', array( __CLASS__, 'print_ajaxurl' ));
		add_action('init', array( __CLASS__, 'conversations' ));
		//add_action('init', array( __CLASS__, 'roles' ));

	}

	public function register_css() {
		wp_register_style( 'aware-admin', AWARE_URL . "assets/css/admin.css", array(), '0.0.1' );
		wp_register_style( 'aware-admin-custom', AWARE_URL . "assets/css/custom-style.css", array(), '0.0.1' );
		wp_register_style( 'aware-foundation', AWARE_URL . "assets/css/foundation.css", array(), '0.0.1' );
		wp_register_style( 'aware-foundation-ui', AWARE_URL . "assets/css/ui.css", array(), '0.0.1' );
	}

	public function do_css() {
		wp_enqueue_style('aware-admin');
		wp_enqueue_style('aware-admin-custom');
		if( $_GET['page'] == 'aware_dashboard' ) :
			wp_enqueue_style('aware-foundation');
			wp_enqueue_style('aware-foundation-ui');
		endif;
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'aware-main', AWARE_URL . 'assets/js/aware.js', array( 'jquery' ) );
	}


	public function do_scripts() {
		wp_enqueue_script('editor');
		wp_enqueue_script( 'aware-main', AWARE_URL . 'assets/js/aware.js', array( 'jquery' ) );
		wp_enqueue_script( 'aware-foundation', AWARE_URL . 'assets/js/foundation.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'aware-foundation-accordion', AWARE_URL . 'assets/js/foundation/foundation.accordion.js', array( 'jquery' ) );
		wp_enqueue_script( 'aware-foundation-datepicker', AWARE_URL . 'assets/js/foundation/foundation-datepicker.js', array( 'jquery' ) );
		add_action( 'admin_head', 'wp_tiny_mce' );
	}

	public function print_ajaxurl() { ?>
		<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		</script>
	<?php }

	public function roles() {

		$roles[] = add_role(
		    'client',
		    __( 'Client' ),
		    array(
			'read'         => true,  // true allows this capability
			'edit_posts'   => true,
			'delete_posts' => false, // Use false to explicitly deny
		    )
		);

		$roles[] = add_role(
		    'manager',
		    __( 'Manager' ),
		    array(
			'read'         => true,  // true allows this capability
			'edit_posts'   => true,
			'delete_posts' => false, // Use false to explicitly deny
		    )
		);

		return $roles;

	}

	public function conversations() {
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

}

?>
