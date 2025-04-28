<?php
/**
 * Basic Archive Template
 *
 * @package ChoctawNation
 */

use ChoctawNation\Asset_Loader;
use ChoctawNation\Enqueue_Type;

new Asset_Loader( 'toc-scroll', Enqueue_Type::script, 'modules' );
get_header();
?>
<?php if ( have_posts() ) : ?>
<div class="container-xxl d-lg-grid docs-container position-relative">
	<?php get_template_part( 'template-parts/aside', 'sidebar' ); ?>
	<main <?php post_class( 'docs-main d-grid order-1 py-3' ); ?>>
		<article class="container-xxl docs-content">
			<div class="row">
				<div class="col">
					<?php the_archive_title( '<h1>', '</h1>' ); ?>
				</div>
			</div>
			<div class="row row-cols-auto row-cols-lg-2 row-gap-4">
				<?php
				while ( have_posts() ) {
					the_post();
					echo '<div class="col">';
					get_template_part(
						'template-parts/content',
						'post-preview',
						array(
							'with_post_meta'     => false,
							'with_last_modified' => true,
							'button_text'        => 'Read More',
						)
					);
					echo '</div>';
				}
				?>
			</div>
		</article>
		<?php get_footer(); ?>
	</main>
</div>
<?php
endif;