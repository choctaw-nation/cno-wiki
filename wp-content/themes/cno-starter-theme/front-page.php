<?php
/**
 * Homepage Template
 *
 * @package ChoctawNation
 */

get_header();
?>
<div class="container-xxl docs-container d-lg-grid gap-3">
	<?php get_template_part( 'template-parts/aside', 'sidebar' ); ?>
	<main <?php post_class( 'container-xxl my-5 d-flex flex-column row-gap-5' ); ?>>
		<section>
			<?php the_content(); ?>
		</section>
		<?php
				$recent_posts = new WP_Query(
					array(
						'post_type'      => array( 'dev-note', 'post' ),
						'posts_per_page' => 6,
						'orderby'        => 'modified',
						'order'          => 'DESC',
					)
				);
				?>
		<?php if ( $recent_posts->have_posts() ) : ?>
		<section class="row gap-3 gx-0">
			<div class="col-12 text-center">
				<h2>Recent Posts</h2>
			</div>
			<?php
			while ( $recent_posts->have_posts() ) {
				$recent_posts->the_post();
				echo '<div class="col-lg-4 flex-grow-1">';
				get_template_part(
					'template-parts/content',
					'post-preview',
					array(
						'with_post_meta' => true,
					)
				);
				echo '</div>';
			}
			?>
		</section>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
		<?php get_footer(); ?>
	</main>
</div>
