<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package ChoctawNation
 */

get_header();
?>
<main id="main" class="site-main container py-5 mt-5">
	<section class="error-404 not-found">
		<div class="page-404">
			<h1 class="mb-3">404</h1>
			<p class="alert alert-info mb-4">
				<?php esc_html_e( 'Page not found.', 'cno' ); ?>
			</p>
			<a class="btn btn-outline-primary" href="<?php echo esc_url( home_url() ); ?>" role="button">
				<?php esc_html_e( 'Back Home &raquo;', 'cno' ); ?>
			</a>
		</div>
	</section>
</main>
<?php
get_footer();
