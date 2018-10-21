<?php

class Relations_Public {

	/**
	 * Id of related posts template
	 *
	 * @var integer
	 */
	private $template_id;

	/**
	 * Position of related posts
	 *
	 * @var string
	 */
	private $position;


	/**
	 * The single instance of the class.
	 *
	 * @var Relations
	 */
	protected static $_instance = null;

	/**
	 * Relations_Public constructor.
	 */
	public function __construct() {
		$this->template_id = Relations_Helper::get_option( 'default_template' );
		$this->position    = get_post_meta( $this->template_id, '_relations_position', true );

		if ( $this->position === 'bottom_content' || $this->position === 'top_content' || $this->position === 'inline' ) {
			add_filter( 'the_content', array( $this, 'filter_the_content' ) );
		}

		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));


	}


	/**
	 * Main Class Instance.
	 *
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @static
	 * @return Relations_Public - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function filter_the_content( $content ) {
		global $post;
		$generator = new Relations_Generator($this->template_id, $post->ID);

		if ( $this->position === 'bottom_content' ) {
			$content .= $generator->get_render();
		} elseif ( $this->position === 'top_content' ) {
			$content = $generator->get_render() . $content;
		} elseif ( $this->position === 'inline' ) {
			$paragraph_id = get_post_meta( $this->template_id, '_relations_inject_paragraph_number', true );
			$closing_p = '</p>';
			$paragraphs = explode( $closing_p, $content );
			foreach ($paragraphs as $index => $paragraph) {
				// Only add closing tag to non-empty paragraphs
				if ( trim( $paragraph ) ) {
					// Adding closing markup now, rather than at implode, means insertion
					// is outside of the paragraph markup, and not just inside of it.
					$paragraphs[$index] .= $closing_p;
				}
				// + 1 allows for considering the first paragraph as #1, not #0.
				if ( $paragraph_id == $index + 1 ) {
					$paragraphs[$index] .= $generator->get_render();
				}
			}
			$content = implode( '', $paragraphs );
		}

		return $content;
	}


	public function enqueue_scripts( $hook ) {

		wp_enqueue_style( 'relations-public-style', RELATIONS_URL . 'assets/public/css/relations.css', array(), Relations::get_version(), $media = 'all' );

		wp_enqueue_script( 'relations-public-js', RELATIONS_URL . 'assets/public/js/relations.js', array( 'jquery' ), Relations::get_version(), true );

	}
}
