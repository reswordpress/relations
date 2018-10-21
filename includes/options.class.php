<?php

class Relation_Options {

	/**
	 * The single instance of the class.
	 *
	 * @var Relation_Options
	 */
	protected static $_instance = null;

	/**
	 * Relation_Options constructor.
	 */
	public function __construct() {
		add_action('admin_init', array($this, 'admin_init'));
	}


	/**
	 * Main Class Instance.
	 *
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @static
	 * @return Relation_Options - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function admin_init(  ) {
		register_setting(
			'relations_group',
			'relations_options'
		);
		add_settings_section(
			'general_section',
			'',
			null,
			'relation_general_page'
		);
		add_settings_field(
			'default_template',
			__('Default template', 'relations'),
			array($this, 'default_template_callback'),
			'relation_general_page',
			'general_section'
		);
	}

	public function default_template_callback() {
		$templates = Relations_Helper::get_templates();
		$current_template = Relations_Helper::get_option('default_template');
		$options = '';
		foreach ( $templates as $template_id => $template_name ) {
			$options .= sprintf(
				'<option value="%d" %s>%s</option>',
				$template_id,
				selected($current_template, $template_id, false),
				$template_name
			);
		}
		printf(
			'<select name="relations_options[default_template]" id="default_template" class="relations_select">%s</select>',
			$options
		);

	}

}