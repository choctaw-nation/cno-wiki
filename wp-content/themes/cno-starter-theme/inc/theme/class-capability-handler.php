<?php
/**
 * Capability Handler
 *
 * @package ChoctawNation
 */

namespace ChoctawNation;

/**
 * Capability Handler
 */
class Capability_Handler {
	/**
	 * The custom capabilities
	 *
	 * @var array $custom_capabilities
	 */
	private array $custom_capabilities;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->custom_capabilities = array( 'developer' => array( 'edit_dev-notes' ) );
	}

	/**
	 * Check if user is a developer
	 */
	public function user_is_developer() {
		return is_user_logged_in() && current_user_can( $this->custom_capabilities['developer'][0] );
	}
}
