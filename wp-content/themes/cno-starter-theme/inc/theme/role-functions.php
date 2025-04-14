<?php
/**
 * Global Role functions specific to the dev needs of this site.
 * Enhance / Extend WordPress functions
 *
 * @package ChoctawNation
 */

use ChoctawNation\Capability_Handler;

/**
 * Get the capability manager.
 *
 * @return Capability_Handler
 */
function cno_get_caps_manager(): Capability_Handler {
	static $cno_caps_manager = null;
	if ( null === $cno_caps_manager ) {
		$cno_caps_manager = new Capability_Handler();
	}
	return $cno_caps_manager;
}

/**
 * Check if the user is a developer.
 *
 * @return bool
 */
function cno_user_is_developer(): bool {
	return cno_get_caps_manager()->user_is_developer();
}
