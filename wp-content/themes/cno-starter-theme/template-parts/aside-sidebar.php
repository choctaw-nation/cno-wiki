<?php
/**
 * The Main Sidebar
 *
 * @package ChoctawNation
 */

$websites = get_terms(
	array(
		'taxonomy'   => 'website',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

if ( is_wp_error( $websites ) || empty( $websites ) ) {
	return;
}
?>
<aside class="docs-sidebar border-1 border-end overflow-y-auto d-block">
	<div class="offcanvas-lg offcanvas-start pt-lg-3" tabindex="-1" id="sidebar-nav" aria-labelledby="sidebarOffcanvasLabel">
		<div class="offcanvas-header border-bottom">
			<h5 class="offcanvas-title" id="sidebarOffcanvasLabel">Browse Docs</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#sidebar-nav"></button>
		</div>
		<?php get_template_part( 'template-parts/nav', 'websites', $websites ); ?>
	</div>
</aside>