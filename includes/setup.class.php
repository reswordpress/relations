<?php

class Relations_Setup {
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
		// Register Templates Post Type
		add_action( 'init', array($this, 'register_templates') );
	}


	/**
	 * Main Class Instance.
	 *
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @static
	 * @return Relations_Setup - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function register_templates() {
		$labels = array(
			'name'                  => __( 'Templates', 'relations' ),
			'singular_name'         => __( 'Templates', 'relations' ),
			'menu_name'             => __( 'Templates', 'relations' ),
			'name_admin_bar'        => __( 'Template', 'relations' ),
			'archives'              => __( 'Template Archives', 'relations' ),
			'attributes'            => __( 'Template Attributes', 'relations' ),
			'parent_item_colon'     => __( 'Parent Template:', 'relations' ),
			'all_items'             => __( 'Templates', 'relations' ),
			'add_new_item'          => __( 'Add New Template', 'relations' ),
			'add_new'               => __( 'Add New', 'relations' ),
			'new_item'              => __( 'New Template', 'relations' ),
			'edit_item'             => __( 'Edit Template', 'relations' ),
			'update_item'           => __( 'Update Template', 'relations' ),
			'view_item'             => __( 'View Template', 'relations' ),
			'view_items'            => __( 'View Templates', 'relations' ),
			'search_items'          => __( 'Search Template', 'relations' ),
			'not_found'             => __( 'Not found', 'relations' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'relations' ),
			'featured_image'        => __( 'Featured Image', 'relations' ),
			'set_featured_image'    => __( 'Set featured image', 'relations' ),
			'remove_featured_image' => __( 'Remove featured image', 'relations' ),
			'use_featured_image'    => __( 'Use as featured image', 'relations' ),
			'insert_into_item'      => __( 'Insert into Template', 'relations' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Template', 'relations' ),
			'items_list'            => __( 'Templates list', 'relations' ),
			'items_list_navigation' => __( 'Templates list navigation', 'relations' ),
			'filter_items_list'     => __( 'Filter Templates list', 'relations' ),
		);
		$args = array(
			'label'                 => __( 'Templates', 'relations' ),
			'description'           => __( 'Relations Templates', 'relations' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => 'relations',
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'relations_template', $args );
	}
}