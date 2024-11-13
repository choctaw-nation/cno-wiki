<?php
/**
 * Template part for displaying a loop of recent posts.
 *
 * @package ChoctawNation
 */

use ChoctawNation\Asset_Loader;
use ChoctawNation\Enqueue_Type;

$post_type_title    = $args['title'];
$post_type_type     = is_string( $args['type'] ) ? array( $args['type'] ) : $args['type'];
$no_posts_message   = isset( $args['no_posts_message'] ) ? $args['no_posts_message'] : 'No posts found.';
$with_last_modified = isset( $args['with_last_modified'] ) ? $args['with_last_modified'] : true;
$read_more          = isset( $args['read_more'] ) ? $args['read_more'] : 'Read More';
$as_swiper          = isset( $args['as_swiper'] ) ? $args['as_swiper'] : false;
if ( $as_swiper ) {
	new Asset_Loader( 'post-swiper', Enqueue_Type::both, 'modules' );
}
?>
<div class="container d-flex flex-column align-items-stretch row-gap-4">
	<div class="row">
		<div class="col text-center">
			<h2>
				<?php echo $post_type_title; ?>
			</h2>
		</div>
	</div>
	<?php
	$recent_posts = new WP_Query(
		array(
			'post_type'      => $post_type_type,
			'posts_per_page' => 12,
			'orderby'        => 'modified',
			'order'          => 'DESC',
			'post_parent'    => 0, // Only include top-level posts
		)
	);
	?>
	<?php if ( $recent_posts->have_posts() ) : ?>
	<?php if ( $as_swiper ) : ?>
	<div class="row position-relative" style="--swiper-navigation-color:var(--bs-primary);">
		<div class="col-1 position-relative">
			<div class="swiper-button-prev"></div>
		</div>
		<div class="col-10 swiper">
			<div class="swiper-wrapper">
				<?php while ( $recent_posts->have_posts() ) : ?>
				<?php $recent_posts->the_post(); ?>
				<div class="swiper-slide">
					<?php
						get_template_part(
							'template-parts/content',
							'post-preview',
							array(
								'button_text' => $read_more,
							)
						);
					?>
				</div>
				<?php endwhile; ?>
			</div>
		</div>
		<div class="col-1 position-relative">
			<div class="swiper-button-next"></div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col"><a href="" class="btn btn-outline-primary">View All</a></div>
	</div>
	<?php else : ?>
	<ul class="list-unstyled row row-cols-1 row-cols-md-auto align-items-stretch gx-0 gap-4 mb-5">
		<?php
		while ( $recent_posts->have_posts() ) {
			$recent_posts->the_post();
			echo '<li class="col-lg-3 flex-grow-1">';
			get_template_part(
				'template-parts/content',
				'post-preview',
				array(
					'with_last_modified' => $with_last_modified,
					'button_text'        => $read_more,
				)
			);
			echo '</li>';
		}
		?>
	</ul>
	<?php endif; ?>
	<?php else : ?>
	<p><?php echo $no_posts_message; ?></p>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</div>