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
<div class="container-xxl docs-container position-relative">
	<?php get_template_part( 'template-parts/aside', 'sidebar' ); ?>
	<main <?php post_class( 'docs-main order-1 py-3' ); ?>>
		<article class="container-xxl docs-content">
			<?php
			get_template_part( 'template-parts/content', 'hero' );
			the_title( '<h1>', '</h1>' );
			the_content();
			?>
		</article>
		<?php
		get_template_part( 'template-parts/aside', 'table-of-contents' );
		get_footer();
		?>
	</main>
</div>