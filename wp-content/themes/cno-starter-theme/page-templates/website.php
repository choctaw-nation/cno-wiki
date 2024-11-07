<?php
/**
 * Template Name: Website Documentation
 * Template Post Type: website
 * Uses a new header and totally different layout for showing docs with a fixed navbar on the left.
 *
 * @package ChoctawNation
 */

get_header( 'docs' );
?>
<main <?php post_class( 'container my-5' ); ?>>
	<?php get_template_part( 'template-parts/single/nav', 'breadcrumbs' ); ?>
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="row my-5">
		<div class="col ratio ratio-16x9">
			<?php
				the_post_thumbnail(
					'full',
					array( 'class' => 'w-100 object-fit-cover' )
				);
			?>
		</div>
	</div>
	<?php endif; ?>
	<div class="row justify-content-center">
		<div class="col-lg-10">
			<?php the_title( '<h1>', '</h1>' ); ?>
			<?php
			$children = wp_list_pages(
				array(
					'title_li'    => '',
					'child_of'    => get_the_ID(),
					'echo'        => 0,
					'post_type'   => 'website',
					'post_status' => 'publish',
				)
			);

			if ( $children ) :
				?>
			<nav class="table-of-contents">
				<h2>Table of Contents</h2>
				<ul>
					<?php echo $children; ?>
				</ul>
			</nav>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();