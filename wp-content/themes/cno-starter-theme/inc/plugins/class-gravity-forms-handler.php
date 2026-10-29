<?php
/**
 * Gravity Forms Handler
 *
 * @package ChoctawNation
 * @subpackage Gravity Forms
 */

namespace ChoctawNation\Plugins;

/**
 * Gravity Forms Handler
 */
class Gravity_Forms_Handler {
	/**
	 * Add Bootstrap classes to Gravity Forms buttons.
	 *
	 * DEV NOTE: you probably don't need this function anymore, but it's left here for legacy support and reference.
	 * You'll probably want to use the block controls or the `_gforms-overrides.scss` partial
	 *
	 * @see https://docs.gravityforms.com/gform_submit_button/#h-5-append-custom-css-classes-to-the-button
	 *
	 * @param string $button The button HTML.
	 * @return string The modified button HTML.
	 */
	public function handle_additional_gform_button_classes( string $button ): string {
		$fragment = \WP_HTML_Processor::create_fragment( $button );
		$fragment->next_token();
		$fragment->add_class( 'btn' );
		$fragment->add_class( 'btn-primary' );

		return $fragment->get_updated_html();
	}

	/**
	 * Dequeue Gravity Forms Recaptcha Scripts where possible.
	 * The blocks should enqueue it when needed.
	 */
	public function dequeue_recaptcha_scripts() {
		if ( is_admin() ) {
			return;
		}
		global $post;
		if ( is_home() || is_front_page() || is_archive() || ( $post instanceof \WP_Post && ! has_block( 'gravityforms/form', $post ) ) ) {
			wp_dequeue_script( 'gforms_recaptcha_recaptcha' );
			wp_dequeue_script( 'gforms_recaptcha_frontend' );
		}
	}

	/**
	 * Set default Gravity Forms styles based on the theme's JSON configuration.
	 *
	 * @see https://docs.gravityforms.com/gform_default_styles/
	 *
	 * @param array|string|false $styles The current Gravity Forms styles.
	 * @return array|string The modified Gravity Forms styles.
	 */
	public function set_default_styles( array|string|false $styles ): string|array {
		$theme_json      = json_decode( file_get_contents( get_template_directory() . '/theme.json' ), JSON_OBJECT_AS_ARRAY );
		$global_settings = wp_get_global_settings();
		if ( ! $theme_json ) {
			return is_array( $styles ) ? $styles : array();
		}

		$theme_colors  = $global_settings['color']['palette']['theme'];
		$primary_color = array_find(
			$theme_colors,
			fn ( $item ) => isset( $item['slug'] ) && 'primary' === $item['slug'],
		);

		if ( ! is_array( $styles ) ) {
			$styles = array();
		}
		$styles['theme']                = 'orbital';
		$styles['inputSize']            = 'lg';
		$styles['inputBorderRadius']    = '0';
		$styles['inputBorderColor']     = $primary_color['color'] ?? 'black';
		$styles['inputBackgroundColor'] = 'white';
		$styles['inputColor']           = 'black';
		$styles['descriptionFontSize']  = 'var(--wp--preset--font-size--root)';
		$styles['descriptionColor']     = 'black';
		$styles['labelFontSize']        = 'var(--wp--preset--font-size--root)';
		$styles['labelColor']           = 'black';

		if ( isset( $primary_color['color'] ) ) {
			$styles['buttonPrimaryBackgroundColor'] = $primary_color['color'];
			$styles['inputPrimaryColor']            = $primary_color['color'];
		}
		return $styles;
	}
}
