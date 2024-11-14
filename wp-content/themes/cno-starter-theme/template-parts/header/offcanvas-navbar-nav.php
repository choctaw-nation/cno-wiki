<?php
/**
 * Offcanvas Navbar Nav
 *
 * @package ChoctawNation
 */

use ChoctawNation\Navwalker;

if ( has_nav_menu( 'primary_menu' ) ) {
	wp_nav_menu(
		array(
			'theme_location'  => 'primary_menu',
			'menu_class'      => 'navbar-nav header-nav fs-6 ms-0',
			'menu_id'         => 'main-menu',
			'container'       => 'div',
			'container_class' => 'offcanvas-body flex-shrink-1 w-auto',
			'walker'          => new Navwalker(),
		)
	);
}
