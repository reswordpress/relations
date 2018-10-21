<?php

class Relations_Activator {

	public static function activate() {
		/*
		 * TODO:
		 * 1. Create simple and advanced templates
		 * 2. Set simple template ID as default template option
		 */

		// Create simple template
		$simple_template = array(
			'post_title'  => __( 'Simple template', 'relations' ),
			'post_status' => 'publish',
			'post_type'   => 'relations_template',
		);
		$simple_template_id = wp_insert_post( $simple_template );
		update_post_meta( $simple_template_id, '_relations_posts_per_page', 5 );
		update_post_meta( $simple_template_id, '_relations_position', 'bottom_content' );
		update_post_meta( $simple_template_id, '_relations_content_type', 'tags' );
		update_post_meta( $simple_template_id, '_relations_display_type', 'simple' );
		update_post_meta( $simple_template_id, '_relations_order_type', 'date' );

		// Create advanced template
		$advanced_template = array(
			'post_title'  => __( 'Advanced template', 'relations' ),
			'post_status' => 'publish',
			'post_type'   => 'relations_template',
		);
		$advanced_template_id = wp_insert_post( $advanced_template );
		update_post_meta( $advanced_template_id, '_relations_posts_per_page', 5 );
		update_post_meta( $advanced_template_id, '_relations_position', 'inline' );
		update_post_meta( $advanced_template_id, '_relations_inject_paragraph_number', 2 );
		update_post_meta( $advanced_template_id, '_relations_content_type', 'tags' );
		update_post_meta( $advanced_template_id, '_relations_display_type', 'advanced' );
		update_post_meta( $advanced_template_id, '_relations_order_type', 'date' );

		// Set default template
		$options = array(
			'default_template'  => $simple_template_id,
		);
		update_option('relations_options', $options);

	}

}