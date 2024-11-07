<?php
/**
 * Simple Image Object
 *
 * @package ChoctawNation
 * @subpackage ACF
 * @since 0.2
 */

namespace ChoctawNation\ACF;

/** Generates a simple API for interacting with the ACF Image array and escapes its values */
class Image {
	/** The Image ID
	 *
	 * @var int $id
	 */
	public int $id;

	/** The img src url, escaped
	 *
	 * @var string $src
	 */
	public string $src;

	/** The srcset, escaped
	 *
	 * @var string $scrset;
	 */
	public string $srcset;

	/**
	 * The alt (user-input or title of image), escaped
	 *
	 * @var string $alt;
	 */
	public string $alt;

	/**
	 * Constructor
	 *
	 * @param array $image the acf image array
	 */
	public function __construct( array $image ) {
		$this->src    = esc_url( $image['url'] );
		$this->alt    = $this->generate_alt( $image );
		$this->srcset = wp_get_attachment_image_srcset( $image['ID'] );
		$this->id     = $image['ID'];
	}

	/** Sets the alt text to title if alt doesn't exist
	 *
	 * @param array $image the acf image array
	 */
	private function generate_alt( array $image ): string {
		if ( empty( $image['alt'] ) ) {
			return $image['title'];
		} else {
			return esc_attr( $image['alt'] );
		}
	}

	/**
	 * Generates the img element
	 *
	 * @param string $img_class the class to add
	 * @param bool   $lazy_load whether to lazy load the image. Default `true`.
	 * @return string the HTML
	 */
	public function get_the_image( string $img_class = '', bool $lazy_load = true ): string {
		$markup = "<img class='{$img_class}' src='{$this->src}' srcset='{$this->srcset}' alt='{$this->alt}'" . ( $lazy_load ? 'loading="lazy"' : '' ) . '/>';
		return $markup;
	}

	/**
	 * Echoes the Image element
	 *
	 * @param string $img_class the html class to give the image
	 * @param bool   $lazy_load whether to lazy load the image. Default `true`.
	 */
	public function the_image( string $img_class = '', bool $lazy_load = true ) {
		echo $this->get_the_image( $img_class, $lazy_load );
	}
}
