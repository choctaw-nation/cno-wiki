<?php
/**
 * Hide By Role Block Output
 *
 * @package ChoctawNation
 */

$user_roles            = 0 === wp_get_current_user() ? null : (array) wp_get_current_user()->roles;
$user_has_allowed_role = is_array( $user_roles ) && array_intersect( $user_roles, $attributes['allowedRoles'] );

$is_public           = in_array( 'all', $attributes['allowedRoles'], true );
$for_logged_in_users = in_array( 'logged-in', $attributes['allowedRoles'], true );
$is_visible          = $is_public || ( $for_logged_in_users && is_user_logged_in() ) || $user_has_allowed_role;
if ( $is_visible ) {
	echo $content;
}