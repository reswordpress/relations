<?php

class Relations_Templates {
	/**
	 * The single instance of the class.
	 *
	 * @var Relations
	 */
	protected static $_instance = null;

	/**
	 * Relations_Setup constructor.
	 */
	public function __construct() {
		// Add meta box
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
	 * @return Relations_Templates - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function register_meta_box() {
		add_meta_box(
			'relations_template_settings',
			__('Settings', 'relations'),
			array($this, 'settings'),
			'relations_template',
			'normal',
			'high'
		);

		/*add_meta_box(
			'relations_template_shortcode',
			__('Shortcode', 'relations'),
			array($this, 'shortcode'),
			'relations_template',
			'side'
		);*/
	}

	public function settings( $post ) {
		// Setup number of posts field
		$posts_per_page_meta = get_post_meta($post->ID, '_relations_posts_per_page', true);
		$default_posts_per_page = apply_filters('relation_default_posts_per_page', 5);
		$current_posts_per_page = (isset($posts_per_page_meta) && !empty($posts_per_page_meta)) ? $posts_per_page_meta : $default_posts_per_page;

		// Setup position field
		$positions = Relations_Helper::get_positions();
		$position_meta = get_post_meta($post->ID, '_relations_position', true);
		$default_position = apply_filters('relation_default_position', 'bottom_content');
		$current_position = (isset($position_meta) && !empty($position_meta)) ? $position_meta : $default_position;

		// Setup position field
		$inject_paragraph_number_meta = get_post_meta($post->ID, '_relations_inject_paragraph_number', true);
		$default_inject_paragraph_number = apply_filters('relation_default_inject_paragraph_number', 2);
		$current_inject_paragraph_number = (isset($inject_paragraph_number_meta) && !empty($inject_paragraph_number_meta)) ? $inject_paragraph_number_meta : $default_inject_paragraph_number;

		// Setup content type field
		$content_types = Relations_Helper::get_content_types();
		$content_type_meta = get_post_meta($post->ID, '_relations_content_type', true);
		$default_content_type = apply_filters('relation_default_content_type', 'tags');
		$current_content_type = (isset($content_type_meta) && !empty($content_type_meta)) ? $content_type_meta : $default_content_type;

		// Setup custom posts field
		$custom_posts_meta = get_post_meta($post->ID, '_relations_custom_posts', true);
		$default_custom_posts = apply_filters('relation_default_custom_posts', array());
		$current_custom_posts = (isset($custom_posts_meta) && !empty($custom_posts_meta)) ? $custom_posts_meta : $default_custom_posts;

		// Setup display type field
		$display_types = Relations_Helper::get_display_types();
		$display_type_meta = get_post_meta($post->ID, '_relations_display_type', true);
		$default_display_type = apply_filters('relation_default_display_type', 'simple');
		$current_display_type = (isset($display_type_meta) && !empty($display_type_meta)) ? $display_type_meta : $default_display_type;

		// Setup order type field
		$order_types = Relations_Helper::get_order_types();
		$order_type_meta = get_post_meta($post->ID, '_relations_order_type', true);
		$default_order_type = apply_filters('relation_default_order_type', 'date');
		$current_order_type = (isset($order_type_meta) && !empty($order_type_meta)) ? $order_type_meta : $default_order_type;

		include RELATIONS_PATH . 'includes/view/metabox-main.php';
	}

	public function shortcode( $post ) {
		printf(
			'<p>%s</p>',
			__('You can use this shortcode to show related posts with this template', 'relations')
		);
		printf(
			'<code class="relations_code">[relations template_id="%d"]</code>',
			$post->ID
		);
		printf(
			'<h4>%s</h4>',
			__('Available attributes:', 'relations')
		);
		printf(
			'<p><code>%s</code> - %s</p>',
			'template_id',
			__('Id of relations templates', 'relations')
		);
		printf(
			'<p><code>%s</code> - %s</p>',
			'post_id',
			__('Id of post to get related (default is current post)', 'relations')
		);
		printf(
			'<p><code>%s</code> - %s</p>',
			'posts_number',
			__('Number of posts to show (Will override template setting)', 'relations')
		);

	}

	public function save_fields( $post_id ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( isset($_POST['posts_per_page']) && !empty($_POST['posts_per_page']) ) {
			update_post_meta( $post_id, '_relations_posts_per_page', intval($_POST['posts_per_page']) );
		}
		if ( isset($_POST['relations_position']) && !empty($_POST['relations_position']) ) {
			update_post_meta( $post_id, '_relations_position', sanitize_text_field($_POST['relations_position']) );
		}
		if ( isset($_POST['inject_paragraph_number']) && !empty($_POST['inject_paragraph_number']) ) {
			update_post_meta( $post_id, '_relations_inject_paragraph_number', sanitize_text_field($_POST['inject_paragraph_number']) );
		}
		if ( isset($_POST['content_type']) && !empty($_POST['content_type']) ) {
			update_post_meta( $post_id, '_relations_content_type', sanitize_text_field($_POST['content_type']) );
		}
		if ( isset($_POST['custom_posts']) && !empty($_POST['custom_posts']) && $_POST['content_type'] === 'custom') {
			$post_ids = array_map('intval', $_POST['custom_posts']);
			update_post_meta( $post_id, '_relations_custom_posts', $post_ids);
		}
		if ( isset($_POST['display_type']) && !empty($_POST['display_type']) ) {
			update_post_meta( $post_id, '_relations_display_type', sanitize_text_field($_POST['display_type']) );
		}
		if ( isset($_POST['custom_display_type']) && !empty($_POST['custom_display_type']) && $_POST['display_type'] === 'custom') {
			$templates = array_map('htmlspecialchars', $_POST['custom_display_type']);
			update_post_meta( $post_id, '_relations_custom_display_type', $templates );
		}
		if ( isset($_POST['order_type']) && !empty($_POST['order_type']) ) {
			update_post_meta( $post_id, '_relations_order_type', sanitize_text_field($_POST['order_type']) );
		}
	}


}