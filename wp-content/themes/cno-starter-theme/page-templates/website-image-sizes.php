<?php
/**
 * Template Name: Website Image Sizes
 * Template Post Type: website
 *
 * @package ChoctawNation
 */

get_header();
?>
<main <?php post_class( 'container my-5' ); ?>>
	<?php get_template_part( 'template-parts/single/nav', 'breadcrumbs' ); ?>
	<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained mt-5">
		<h2 class="wp-block-heading">Base Photo Requirements:</h2>
		<ul class="wp-block-list">
			<li>Rendered at 72dpi</li>
			<li>Must use .jpg or .webp file extensions</li>
			<li>Must be no larger than 5MB</li>
		</ul>
	</div>
	<?php the_content(); ?>
</main>
<?php
get_footer();