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
		$this->badges = array_merge( $this->badges, $this->get_category_badge() );
		$this->badges = array_merge( $this->badges, $this->get_dev_note_badge( 'framework' ) );
		$this->badges = array_merge( $this->badges, $this->get_dev_note_badge( 'language' ) );
		$this->badges = array_merge( $this->badges, $this->get_tag_badges() );
		return $this->badges;
	}

	/**
	 * Get the category badge
	 */
	private function get_category_badge(): array {
		$categories = get_the_category();
		if ( ! $categories || is_wp_error( $categories ) ) {
			return array();
		}
		$category_badges = array();
		foreach ( $categories as $category ) {
			$category_badges = array_merge(
				$category_badges,
				array(
					$category->slug => array(
						'label' => $category->name,
						'color' => 'primary',
						'href'  => get_category_link( $category ),
					),
				),
			);
		}
		return $category_badges;
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

	private function get_tag_badges(): array {
		$tags = get_the_tags();
		if ( ! $tags || is_wp_error( $tags ) ) {
			return array();
		}
		$tag_badges = array();
		foreach ( $tags as $tag ) {
			$tag_badges = array_merge(
				$tag_badges,
				array(
					$tag->slug => array(
						'label' => $tag->name,
						'color' => 'outline-primary',
						'href'  => get_tag_link( $tag ),
					),
				),
			);
		}
		return $tag_badges;
	}
}
