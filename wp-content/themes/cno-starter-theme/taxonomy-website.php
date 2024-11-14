<?php
/**
 * Basic Archive Template
 *
 * @package ChoctawNation
 */

get_header();
?>
<div class="container-xxl docs-container d-lg-grid gap-3">
	<?php get_template_part( 'template-parts/aside', 'sidebar' ); ?>
	<main <?php post_class( 'd-md-grid order-1' ); ?>>
		<article class="container-xxl my-5">
			<?php
			get_template_part( 'template-parts/content', 'hero' );
			the_title( '<h1>', '</h1>' );
			the_content();
			?>
		</article>
		<?php get_footer(); ?>
	</main>
</div>
