<?php
/**
 * Basic Header Template
 *
 * @package ChoctawNation
 */

?>

<!DOCTYPE html>
<html lang="<?php bloginfo( 'language' ); ?>">

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<header class="text-bg-primary z-3" id="site-header">
		<div class="container-xxl">
			<nav class="navbar navbar-expand-lg justify-content-lg-between py-2 gap-3">
				<div class="navbar-container flex-lg-grow-1 flex-shrink-1 w-auto d-flex flex-wrap flex-lg-nowrap align-items-center">
					<div class="position-relative d-flex py-2 align-items-center gap-2">
						<img src="<?php echo get_template_directory_uri() . '/img/the-great-seal-white.svg'; ?>" alt="The Great Seal of the Choctaw Nation" class='d-inline-block logo' />
						<a class="stretched-link navbar-brand fw-bold text-white fs-5" href="<?php echo esc_url( site_url() ); ?>">
							Home
						</a>
					</div>
					<?php get_template_part( 'template-parts/header/offcanvas', 'navbar-nav' ); ?>
				</div>
				<?php get_search_form(); ?>
				<button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar-nav" aria-controls="sidebar-nav" aria-expanded="false"
						aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<?php get_template_part( 'template-parts/header/content', 'color-mode-toggle' ); ?>
			</nav>
		</div>
	</header>
