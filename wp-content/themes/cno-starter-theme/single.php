<?php
/**
 * The fallback single type.
 *
 * @package ChoctawNation
 */

use ChoctawNation\Asset_Loader;
use ChoctawNation\Enqueue_Type;

new Asset_Loader( 'toc-scroll', Enqueue_Type::script, 'modules' );
get_header();
?>
<div class="container-xxl docs-container d-lg-grid gap-3">
	<?php get_template_part( 'template-parts/aside', 'sidebar' ); ?>
	<main class="docs-main my-3 d-grid">
		<div <?php post_class( 'container-fluid docs-content overflow-hidden' ); ?>>
			<?php get_template_part( 'template-parts/single/nav', 'breadcrumbs' ); ?>
			<?php if ( has_post_thumbnail() ) : ?>
			<div class="row">
				<div class="col">
					<?php
					the_post_thumbnail(
						'full',
						array(
							'class'           => 'w-100 h-100 object-fit-contain',
							'loading'         => 'eager',
							'data-spai-eager' => true,
						)
					);
					?>
				</div>
			</div>
			<?php endif; ?>
			<article>
				<?php
				the_title( '<h1>', '</h1>' );
				get_template_part(
					'template-parts/content',
					'post-meta',
					array( 'classes' => 'justify-content-start gap-3' )
				);
				the_content();
				?>
			</article>
		</div>
		<?php
		get_template_part( 'template-parts/aside', 'table-of-contents' );
		get_footer();
		?>
	</main>
</div>
