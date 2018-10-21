<?php


class Relations_Generator {

	/**
	 * Id of related posts template
	 *
	 * @var integer
	 */
	private $template_id;


	/**
	 * Id of post to show it's related posts
	 *
	 * @var
	 */
	private $post_id;

	/**
	 * Number of posts
	 *
	 * @var integer
	 */
	private $posts_per_page;

	/**
	 * Position of related post
	 *
	 * @var integer
	 */
	private $positions;

	/**
	 * Content type of related posts
	 *
	 * @var integer
	 */
	private $content_type;

	/**
	 * Custom posts to show as related posts
	 *
	 * @var integer
	 */
	private $custom_posts;

	/**
	 * Display type of related posts
	 *
	 * @var integer
	 */
	private $display_type;

	/**
	 * Custom display structure of related posts
	 *
	 * @var integer
	 */
	private $custom_display_type;

	/**
	 * Order of related posts
	 *
	 * @var integer
	 */
	private $order_type;

	/**
	 * Main query
	 *
	 * @var WP_Query
	 */
	private $query;

	/**
	 * Relations_Generator constructor.
	 */
	public function __construct( $template_id, $post_id = false, $posts_per_page = false ) {
		if( $post_id === false ) {
			global $post;
			$post_id = $post->ID;
		}
		$this->template_id         = $template_id;
		$this->post_id             = $post_id;
		$this->posts_per_page      = $posts_per_page === false ? get_post_meta( $template_id, '_relations_posts_per_page', true ) : $posts_per_page;
		$this->positions           = get_post_meta( $template_id, '_relations_position', true );
		$this->content_type        = get_post_meta( $template_id, '_relations_content_type', true );
		$this->custom_posts        = get_post_meta( $template_id, '_relations_custom_posts', true );
		$this->display_type        = get_post_meta( $template_id, '_relations_display_type', true );
		$this->custom_display_type = get_post_meta( $template_id, '_relations_custom_display_type', true );
		$this->order_type          = get_post_meta( $template_id, '_relations_order_type', true );

		$this->query = new WP_Query( $this->generate_args() );

	}


	/**
	 * Generate WP_Query args based on template settings
	 */
	private function generate_args() {
		$args = array(
			'posts_per_page'      => $this->posts_per_page,
			'post_type'           => 'post',
			'orderby'             => $this->order_type,
			'ignore_sticky_posts' => 1,
			'post__not_in'        => array( $this->post_id ),
		);

		if ( $this->content_type !== 'custom' ) {
			$args['tax_query'] = array(
				'relation' => apply_filters( 'relations_tax_query_relation', 'AND', $this ),
			);
			if ( $this->content_type === 'tags' || $this->content_type === 'tags_categories' ) {
				$terms = wp_get_post_terms( $this->post_id, 'post_tag', array("fields" => "ids") );
				if ( is_array( $terms ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'post_tag',
						'terms'    => $terms,
					);
				}
			}
			if ( $this->content_type === 'categories' || $this->content_type === 'tags_categories' ) {
				$terms = wp_get_post_terms( $this->post_id, 'category', array("fields" => "ids") );
				if ( is_array( $terms ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'category',
						'terms'    => $terms,
					);
				}
			}
		}

		return apply_filters( 'relations_wp_query_args', $args, $this );

	}

	/**
	 * Generate output
	 */
	public function render() {
		echo $this->get_render();
	}
	/**
	 * Generate output
	 */
	public function get_render() {
		$output = '';
		if ( $this->query->have_posts() ) {
			if( $this->display_type !== 'custom' ) {
				$output .= relations_get_template_html($this->display_type.'/wrapper-start.php');
				while ( $this->query->have_posts() ) {
					$this->query->the_post();
					global $post;
					$output .= relations_get_template_html($this->display_type.'/content-related-post.php', array('post' => $post));
				}
				$output .= relations_get_template_html($this->display_type.'/wrapper-end.php');
				wp_reset_postdata();
			} else {
				$wrapper_start = htmlspecialchars_decode($this->custom_display_type['wrapper_start']);
				$wrapper_end = $this->custom_display_type['wrapper_end'];
				$body = $this->custom_display_type['body'];
				$output .= $wrapper_start;
				$tags = array(
					'{title}',
					'{permalink}',
					'{thumbnail}',
					'{excerpt}',
					'{date}',
				);
				while ( $this->query->have_posts() ) {
					$this->query->the_post();
					global $post;
					$tags_value = array(
						get_the_title(),
						get_permalink(),
						get_the_post_thumbnail_url(),
						get_the_excerpt(),
						get_the_date(),
					);
					$content = str_replace($tags, $tags_value, $body);
					$output .= htmlspecialchars_decode($content);
				}
				$output .= htmlspecialchars_decode($wrapper_end);
				wp_reset_postdata();
			}
		}
		return $output;
	}

}