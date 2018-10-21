<?php
class Relations_Helper {

	/**
	 * Get available positions for related posts
	 *
	 * @return array
	 */
	public static function get_positions() {
		$positions = array(
			'bottom_content' => __( 'Bottom Content', 'relations' ),
			'top_content'    => __( 'Top Content', 'relations' ),
			'inline'         => __( 'Inline', 'relations' ),
			// 'popup'          => __( 'PopUp', 'relations' ),
			// 'sticky_top'     => __( 'Top Sticky', 'relations' ),
			// 'sticky_bottom'  => __( 'Bottom Sticky', 'relations' ),
			// 'sticky_left'    => __( 'Left Sticky', 'relations' ),
			// 'sticky_right'   => __( 'Right Sticky', 'relations' ),
			//'custom'         => __( 'Shortcode (Available in pro version)', 'relations' ),
		);

		return apply_filters( 'relations_positions', $positions );
	}


	/**
	 * Get available content types for related posts
	 *
	 * @return array
	 */
	public static function get_content_types() {

		$content_types = array(
			'tags'            => __( 'Tags', 'relations' ),
			'categories'      => __( 'Categories', 'relations' ),
			'tags_categories' => __( 'Tags & Categories', 'relations' ),
			//'custom'          => __( 'Custom (Available in pro version)', 'relations' ),
			// TODO: Add with filter in future!
			// 'yoast_seo'       => __( 'Yoast Seo focus keywords', 'relations' ),
		);

		return apply_filters( 'relations_content_types', $content_types );
	}

	/**
	 * Get available content types for related posts
	 *
	 * @return array
	 */
	public static function get_display_types() {

		$display_types = array(
			'simple'   => __( 'Simple (List of links)', 'relations' ),
			'advanced' => __( 'Advanced (Post with thumbnail)', 'relations' ),
			//'custom'   => __( 'Custom (Available in pro version)', 'relations' ),
		);

		return apply_filters( 'relations_display_types', $display_types );
	}

	/**
	 * Get available order types for related posts
	 *
	 * @return array
	 */
	public static function get_order_types() {

		$order_types = array(
			'date' => __( 'Date', 'relations' ),
			'rand' => __( 'Random', 'relations' ),
		);

		return apply_filters( 'relations_order_types', $order_types );
	}

	public static function get_templates() {
		$args     = array(
			'posts_per_page' => - 1,
			'post_type'      => 'relations_template',
			'post_status'    => 'publish',
		);
		$my_query = new WP_Query( $args );
		$templates = array();
		while ( $my_query->have_posts() ):
			$my_query->the_post();
			$templates[get_the_ID()] = get_the_title();
		endwhile;
		wp_reset_postdata();
		return $templates;
	}


	public static function get_option( $option_name = false ) {
		$options        = get_option( 'relations_options' );
		$sorted_options = array();

		$sorted_options['default_template']  = ( isset( $options['default_template'] ) && ! empty( $options['default_template'] ) ) ? $options['default_template'] : '';


		if ( ! empty( $option_name ) ) {
			return $sorted_options[ $option_name ];
		} else {
			return $options;
		}
	}
}