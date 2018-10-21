<?php
/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/relations/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/relations-advanced-related-posts/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param 	string 	$template_name			Template to load.
 * @param 	string 	$string $template_path	Path to templates.
 * @param 	string	$default_path			Default path to template files.
 * @return 	string 							Path to the template file.
 */
function relations_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	// Set variable to search in relations-advanced-related-posts folder of theme.
	if ( ! $template_path ) :
		$template_path = 'relations-advanced-related-posts/';
	endif;
	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = RELATIONS_PATH . 'templates/'; // Path to the template folder
	endif;
	// Search template file in theme folder.
	$template = locate_template( array(
		$template_path . $template_name,
		// $template_name
	) );
	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;
	return apply_filters( 'relations_locate_template', $template, $template_name, $template_path, $default_path );
}

/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see relations_locate_template()
 *
 * @param string 	$template_name			Template to load.
 * @param array 	$args					Args passed for the template file.
 * @param string 	$string $template_path	Path to templates.
 * @param string	$default_path			Default path to template files.
 */
function relations_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;
	$template_file = relations_locate_template( $template_name, $tempate_path, $default_path );
	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;
	include $template_file;
}


/**
 * Like relations_get_template, but returns the HTML instead of outputting.
 *
 * @see wc_get_template
 * @since 2.5.0
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 *
 * @return string
 */
function relations_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	relations_get_template( $template_name, $args, $template_path, $default_path );
	return ob_get_clean();
}