<?php
/**
 * The fallback single type.
 *
 * @package ChoctawNation
 */

get_header();
?>
<main class="docs-main py-3">
	<article <?php post_class( 'container-xxl my-5 docs-content' ); ?>>
		<?php get_template_part( 'template-parts/single/nav', 'breadcrumbs' ); ?>
		<?php if ( has_post_thumbnail() ) : ?>
		<div class="row">
			<div class="col">
				<?php the_post_thumbnail( 'full' ); ?>
			</div>
		</div>
		<?php endif; ?>
		<?php
		the_title( '<h1>', '</h1>' );
		get_template_part( 'template-parts/content', 'post-meta', array( 'classes' => 'justify-content-start gap-3' ) );
		the_content();
		?>
	</article>
	<?php
		get_template_part( 'template-parts/aside', 'table-of-contents' );
		get_footer();
	?>
</main>
