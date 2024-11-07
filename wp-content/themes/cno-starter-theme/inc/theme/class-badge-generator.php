<?php
/**
 * Badge Generator
 *
 * @package ChoctawNation
 */

namespace ChoctawNation;

/**
 * Badge Generator
 */
class Badge_Generator {
	/**
	 * The badges for the current post
	 *
	 * @var array $badges
	 */
	private array $badges;

	/**
	 * Badge_Generator constructor.
	 */
	public function __construct() {
		$this->badges = array();
	}

	/**
	 * Get the badges for the current post
	 *
	 * @return array
	 */
	public function get_badges(): array {
		$this->badges = array_merge( $this->badges, $this->get_post_type_badge() );
		$this->badges = array_merge( $this->badges, $this->get_dev_note_badge( 'framework' ) );
		$this->badges = array_merge( $this->badges, $this->get_dev_note_badge( 'language' ) );
		return $this->badges;
	}

	/**
	 * Get the post type badge
	 */
	private function get_post_type_badge(): array {
		$post_type_map = array(
			'post'     => array(
				'label' => 'General',
				'color' => 'primary',
				'href'  => get_post_type_archive_link( 'post' ),
			),
			'dev-note' => array(
				'label' => 'Dev Note',
				'color' => 'secondary',
				'href'  => get_post_type_archive_link( 'dev-note' ),
			),
			'website'  => null,
		);
		return array( get_post_type() => $post_type_map[ get_post_type() ] );
	}

	/**
	 * Get the dev-note badge
	 *
	 * @param string $taxonomy The taxonomy to get the badge for
	 */
	private function get_dev_note_badge( string $taxonomy ): array {
		$terms = get_the_terms( get_the_ID(), $taxonomy );
		if ( ! $terms || is_wp_error( $terms ) ) {
			return array();
		}
		$terms_colors = array(
			'framework' => 'info',
			'language'  => 'warning',
		);
		$terms_badges = array();
		foreach ( $terms as $term ) {
			$terms_badges = array_merge(
				$terms_badges,
				array(
					$term->slug => array(
						'label' => $term->name,
						'color' => $terms_colors[ $taxonomy ],
						'href'  => get_term_link( $term ),
					),
				),
			);
		}
		return $terms_badges;
	}
}