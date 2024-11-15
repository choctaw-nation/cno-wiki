<?php
/**
 * Offcanvas Navbar Nav
 *
 * @package ChoctawNation
 */

use ChoctawNation\Navwalker;
?>
<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#main-navbar" aria-controls="main-navbar" aria-label="Toggle pages navigation">
	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
		<path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
	</svg>
</button>
<div class="text-bg-primary offcanvas-lg offcanvas-end flex-grow-1" id="main-navbar" aria-labelledby="main-navbar-offcanvas-label" data-bs-scroll="true">
	<div class="offcanvas-header px-4 pb-0">
		<h5 class="offcanvas-title" id="main-navbar-offcanvas-label">CNO Wiki</h5>
		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#main-navbar"></button>
	</div>
	<div class="offcanvas-body flex-shrink-1 w-auto">
		<hr class="d-lg-none bg-white my-2" />
		<?php
		if ( has_nav_menu( 'primary_menu' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary_menu',
					'menu_class'     => 'navbar-nav header-nav fs-6 ms-0',
					'menu_id'        => 'main-menu',
					'walker'         => new Navwalker(),
				)
			);
		}
		?>
		<hr class="d-lg-none bg-white my-2" />
		<?php get_template_part( 'template-parts/header/content', 'color-mode-toggle' ); ?>
	</div>
</div>
