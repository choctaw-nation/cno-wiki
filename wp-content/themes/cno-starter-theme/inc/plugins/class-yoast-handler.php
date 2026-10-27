<?php
/**
 * Yoast Handler Class
 *
 * @package ChoctawNation
 */

namespace ChoctawNation\Plugins;

/**
 * Yoast_Handler Class
 */
class Yoast_Handler {
	/**
	 * Use the excerpt as the meta description if none is set.
	 * Alternatively, use the ACF brief description if available.
	 *
	 * @param string $description The current meta description.
	 */
	public function meta_description_handler( $description ): string {
		if ( ! is_singular( 'post' ) && ! is_page() ) {
			return $description;
		}
		$acf     = get_field( 'archive_content' ) ?? get_field( 'brief_description' );
		$excerpt = get_the_excerpt();
		if ( ! empty( $excerpt ) ) {
			return $excerpt;
		} elseif ( ! empty( $acf ) ) {
			return esc_textarea( $acf );
		}
		return $description;
	}
}
