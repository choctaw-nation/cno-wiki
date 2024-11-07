<?php
/**
 * The fallback single type.
 *
 * @package ChoctawNation
 */

get_header();
?>
<div class="container my-5">
	<?php get_template_part( 'template-parts/single/nav', 'breadcrumbs' ); ?>
</div>
<article <?php post_class( 'my-5' ); ?>>
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
get_footer();