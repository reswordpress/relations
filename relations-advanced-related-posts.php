<?php
/*
Plugin Name: Relations lite
Plugin URI: https://wordpress.org/plugins/relations/
Description: An advanced related posts plugin for WordPress
Version: 1.0.0
Author: theKoder
Author URI: https://thekoder.com/
Text Domain: relations
Domain Path: /languages/
*/

define( 'RELATIONS_URL', plugin_dir_url( __FILE__ ) );
define( 'RELATIONS_PATH', plugin_dir_path( __FILE__ ) );
define( 'RELATIONS_FILE_NAME', plugin_basename( __FILE__ ) );


function relations_activation() {
	require_once RELATIONS_PATH . 'includes/activator.class.php';
	Relations_Activator::activate();
}

register_activation_hook( __FILE__, 'relations_activation' );

function relations_deactivation() {
	require_once RELATIONS_PATH . 'includes/deactivator.class.php';
	Relations_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'relations_deactivation' );

require RELATIONS_PATH . 'includes/main.class.php';
function run_relations() {
	return Relations::get_instance();
}

run_relations();