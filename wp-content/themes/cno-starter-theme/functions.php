<?php
/**
 * Theme Functions
 *
 * Should be pretty quiet in here besides requiring the appropriate files. Like style.css, this should really only be used for quick fixes with notes to refactor later.
 *
 * @package ChoctawNation
 */

use ChoctawNation\Theme_Init;

/** Get the theme init class */
require_once get_template_directory() . '/inc/theme/class-theme-init.php';
new Theme_Init( 'nation' );


/**
 * Add a filter to modify the speculation rules configuration.
 *
 * This filter sets the mode to 'prerender' and eagerness to 'moderate'.
 *
 * @link https://make.wordpress.org/core/2025/03/06/speculative-loading-in-6-8/
 *
 * @param array $config The current speculation rules configuration.
 * @return array Modified speculation rules configuration.
 */
add_filter(
	'wp_speculation_rules_configuration',
	function ( $config ) {
		if ( is_array( $config ) ) {
			$config['mode']      = 'prerender';
			$config['eagerness'] = 'moderate';
		}
		return $config;
	}
);