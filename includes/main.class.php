<?php
class Relations {

	/**
	 * The single instance of the class.
	 *
	 * @var Relations
	 */
	protected static $_instance = null;

	/**
	 * Plugin name
	 *
	 * @var string
	 */
	private $name;
	/**
	 * Plugin author
	 *
	 * @var string
	 */
	private $author;

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	private static $version = '1.0.0';

	/**
	 * Plugin description
	 *
	 * @var string
	 */
	private $description;

	/**
	 * Available notification types
	 *
	 * @var array
	 */
	public static $notification_types;


	public function __construct() {
		$this->load_dependencies();
		$this->set_locale();

		Relations_Admin::get_instance();
		Relations_Setup::get_instance();
		Relations_Templates::get_instance();
		Relations_Public::get_instance();
		Relation_Options::get_instance();
	}

	/**
	 * Main Class Instance.
	 *
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @static
	 * @return Relations - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function set_plugin_info(  ) {
		$this->name        = __('Relations', 'relations');
		$this->description = __('An advanced related posts plugin for WordPress', 'relations');
		$this->author      = __('theKoder', 'relations');
	}

	private function load_dependencies() {
		require_once RELATIONS_PATH . 'includes/i18n.php';
		require_once RELATIONS_PATH . 'includes/functions.php';
		require_once RELATIONS_PATH . 'includes/helper.class.php';
		require_once RELATIONS_PATH . 'includes/generator.class.php';
		require_once RELATIONS_PATH . 'includes/admin.class.php';
		require_once RELATIONS_PATH . 'includes/public.class.php';
		require_once RELATIONS_PATH . 'includes/options.class.php';
		require_once RELATIONS_PATH . 'includes/setup.class.php';
		require_once RELATIONS_PATH . 'includes/templates.class.php';
	}

	// TODO: Remove this method
	public function set_options() {
		new WPAN_Options();
	}

	private function set_locale() {
		$plugin_i18n = new Relations_i18n();
		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );
	}

	/**
	 * @return string
	 */
	public static function get_version() {
		return self::$version;
	}

}