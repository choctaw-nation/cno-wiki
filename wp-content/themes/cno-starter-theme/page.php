<?php
/**
 * Standard Page Output with default Hero section
 *
 * @package ChoctawNation
 */

get_header();
?>
<div class="container-xxl docs-container d-lg-grid gap-3">
	<?php get_template_part( 'template-parts/aside', 'sidebar' ); ?>
	<main <?php post_class(); ?>>
		<article class="container-xxl my-5">
			<?php the_content(); ?>
		</article>
		<?php get_footer(); ?>
	</main>
</div>