<?php
class Relations_Admin {

	/**
	 * The single instance of the class.
	 *
	 * @var Relations
	 */
	protected static $_instance = null;

	/**
	 * Relations_Admin constructor.
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'add_menus'), 9);
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Search Posts
		add_action( 'wp_ajax_relations_search_posts', array($this, 'search_posts') );
		add_action( 'wp_ajax_nopriv_relations_search_posts', array($this, 'search_posts') );

		// Add meta boxes
		add_action('add_meta_boxes', array($this, 'register_meta_box'));

		// Save fields
		add_action('save_post', array($this, 'save_fields'));

	}

	/**
	 * Main Class Instance.
	 *
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @static
	 * @return Relations_Admin - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function enqueue_scripts( $hook ) {

		wp_enqueue_style( 'relations-admin-style', RELATIONS_URL . 'assets/admin/css/relations.css', array(), Relations::get_version(), $media = 'all' );
		wp_enqueue_style( 'select2-style', RELATIONS_URL . 'assets/admin/css/select2.min.css', array(), '3.4.8', $media = 'all' );

		wp_enqueue_script( 'relations-admin-js', RELATIONS_URL . 'assets/admin/js/relations.js', array( 'jquery' ), Relations::get_version(), true );
		wp_enqueue_script( 'select2-relations', RELATIONS_URL . 'assets/admin/js/select2.full.min.js', array( 'jquery' ), '4.0.5', true );
		wp_localize_script( 'relations-admin-js', 'relations_ajax', array(
				'url'           => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'ajax-nonce' ),
				'error'         => __( 'Something goes wrong. please try again', 'relations' ),
				'typeThreeChar' => __( 'Enter at lest 3 characters to start search', 'relations' ),
			) );


	}

	public function add_menus() {
		add_menu_page(
			__('Related Posts', 'relations'),
			__('Related Posts', 'relations'),
			'manage_options',
			'relations',
			array($this, 'main_menu_content'),
			'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAVCAYAAABG1c6oAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB5klEQVQ4jZ2Vv2tTURTHv+fclxo1FYwgONhBl/4BGRycRHAtOHZw1VTq0N2tGEHooI6CtTpIFwcnFzvYbqXiIogdXLSIlZgmkr53z/m6aEwk5r3ku93vPefD+cHlSiTn4X4ZgGA8OYD2252dpVqtlvXcaPZkTNDfXMb5LMuu9HuJqHJSIBDakqDS7+jksOFK+g8kZ9MCs5wCPopINuyuByQp0f2qFqi6qXofQHMkUEQIYDkPlqeBllOzugBH85IOVZ9VRPZygVR98683TMeBVqEKj4i8y4PlaWApDtxFgaV0gDsnRPZHAkWEJFeKVDH9e8N0vwDVlwDQ7XbPl8vl3YGWReRLESAAZGa3Sb6fEtlMzW6Q3DuMsTbRSzGzZVF+KIWwlsZ4TVX3JYTPKnJ2LCBJMfIeVbcDwnMHrnuMW3SfUWAmUV0vDCSp0X1FgdcBeOHudQXWG43GLgAPwIaIfEI0Wy0AC9HsIclLJIOZ3Wy1WqdIqpG3DsjTf2LFyAW4z6pqZwTwnJk9SJJk0+H1A+jTV0Brzn2xpLomIt96QAD4Sla83T72P2ApTbvVavWnu9dVdXUD6Fx0X0xUH4vI96JjG1CMca5DniGZZGZLzWbz5LC4wv+IkQtmloYg0wp9JCI/hsX9AsKS7XJnueh5AAAAAElFTkSuQmCC',
			6
		);
		add_submenu_page(
			'relations',
			__('Related posts settings', 'relations'),
			__('Settings', 'relations'),
			'manage_options',
			'relations',
			array($this, 'main_menu_content')
		);
	}

	public function main_menu_content() {
		include RELATIONS_PATH . 'includes/view/menu-settings.php';
	}

	public function register_meta_box() {
		add_meta_box(
			'relations_template_selector',
			__('Related Posts', 'relations'),
			array($this, 'post_template_selector_metabox'),
			'post',
			'side'
		);
	}

	public function post_template_selector_metabox( $post ) {
		$templates = Relations_Helper::get_templates();
		$current_template = get_post_meta($post->ID, '_relations_template', true);
		$options = sprintf(
			'<option value="%s">%s</option>',
			'default',
			__('Default template', 'relations')
		);
		foreach ( $templates as $template_id => $template_name ) {
			$options .= sprintf(
				'<option value="%d" %s>%s</option>',
				$template_id,
				selected($current_template, $template_id, false),
				$template_name
			);
		}
		printf(
			'<label for="relations_template">%s</label>',
			__('Related posts template:', 'relations')
		);
		printf(
			'<select name="relations_template" id="relations_template" class="relations_select">%s</select>',
			$options
		);
	}


	public function save_fields( $post_id ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( isset($_POST['relations_template']) && !empty($_POST['relations_template']) && $_POST['relations_template'] !== 'default') {
			update_post_meta( $post_id, '_relations_template', intval($_POST['relations_template']) );
		}
	}

	public function search_posts() {
		$text = sanitize_text_field( $_GET['q'] );
		global $wpdb;
		$table_name = $wpdb->posts;
		$id         = $text;
		$text       = '%' . $text . '%';

		$query        = "SELECT * FROM $table_name WHERE (post_title LIKE '%s' OR ID = '%d') AND post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC";
		$query_result = $wpdb->get_results( $wpdb->prepare( $query, $text, $id ), 'OBJECT' );
		$data         = array();
		if ( count( $query_result ) > 0 ) {
			foreach ( $query_result as $key => $post ) {
				$data[] = array( 'id' => $post->ID, 'text' => '#' . $post->ID . ' ' . $post->post_title );
			}
		} else {
			$data[] = array( 'id' => '0', 'text' => __( 'No Posts Found!', 'relations' ) );
		}
		wp_send_json( $data );
	}

}