<?php
/**
 * The Sidebar
 *
 * @package ChoctawNation
 */

use ChoctawNation\Navwalker;

?>
<aside class="sidebar border-1 border-end overflow-y-auto d-block">
	<div class="offcanvas-lg offcanvas-start pt-lg-3" tabindex="-1" id="sidebar-nav" aria-labelledby="sidebarOffcanvasLabel">
		<div class="offcanvas-header border-bottom">
			<h5 class="offcanvas-title" id="sidebarOffcanvasLabel">Browse Docs</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#sidebar-nav"></button>
		</div>
		<?php
		if ( has_nav_menu( 'sidebar_menu' ) ) {
			wp_nav_menu(
				array(
					'theme_location'  => 'sidebar_menu',
					'menu_class'      => 'sidebar-nav list-unstyled m-0 p-0',
					'container'       => 'div',
					'container_class' => 'offcanvas-body',
					'walker'          => new Navwalker(),
				)
			);
		}
		?>
	</div>
</aside>