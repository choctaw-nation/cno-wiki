<?php
/**
 * Role Editor
 *
 * @package ChoctawNation
 */

namespace ChoctawNation\Admin;

/**
 * Role Editor
 */
class Role_Editor {
	/**
	 * Create custom roles and add capabilities for the Block Editor
	 */
	public function create_custom_roles() {
		$this->create_cno_roles_for_gutenberg();
		$this->add_custom_capabilities();
	}

	/**
	 * Add custom capabilities to roles
	 */
	private function add_custom_capabilities() {
		$capabilities = array(
			'can_unlock_blocks' => array( 'administrator', 'front-end' ),
			'edit_dev-notes'    => array( 'administrator', 'front-end' ),
		);
		foreach ( $capabilities as $capability => $roles ) {
			foreach ( $roles as $role ) {
				$role_obj = get_role( $role );

				if ( $role_obj && ! $role_obj->has_cap( $capability ) ) {
					$role_obj->add_cap( $capability );
				}
			}
		}
	}

	/**
	 * Create Front-End and Content roles with editor capabilities
	 */
	private function create_cno_roles_for_gutenberg() {
		$editor = get_role( 'editor' );
		if ( ! $editor ) {
			return;
		}

		$custom_roles = array( 'front-end', 'content-editor' );

		foreach ( $custom_roles as $role ) {
			if ( ! get_role( $role ) ) {
				add_role( $role, ucwords( str_replace( '-', ' ', $role ) ), $editor->capabilities );
			}
			$role_obj = get_role( $role );
			if ( $role_obj ) {
				$role_obj->add_cap( 'gform_full_access' );
				$role_obj->add_cap( 'edit_css' );
				$role_obj->add_cap( 'manage_options' );
				// Remove 'unfiltered_html' capability if present
				if ( 'content-editor' === $role ) {
					if ( $role_obj->has_cap( 'unfiltered_html' ) ) {
						$role_obj->remove_cap( 'unfiltered_html' );
					}
				}
			}
		}
	}

	/**
	 * Allow content editors to edit CSS.
	 *
	 * @param array  $caps    The user's capabilities.
	 * @param string $cap     The capability being checked.
	 * @param int    $user_id The user ID.
	 * @return array Modified capabilities.
	 */
	public function allow_edit_css( array $caps, string $cap, int $user_id ): array {
		if ( 'edit_css' !== $cap ) {
			return $caps;
		}

		$user = get_userdata( $user_id );

		if (
			$user &&
			in_array( 'content-editor', $user->roles, true )
		) {
			return array( 'edit_css' );
		}

		return $caps;
	}
}
