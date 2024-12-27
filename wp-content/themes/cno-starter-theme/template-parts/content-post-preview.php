<?php
/**
 * A card-based post preview layout
 *
 * @package ChoctawNation
 */

$with_post_meta     = isset( $args['with_post_meta'] ) ? $args['with_post_meta'] : true;
$with_last_modified = isset( $args['with_last_modified'] ) ? $args['with_last_modified'] : true;
$button_text        = isset( $args['button_text'] ) ? $args['button_text'] : 'Read More';

?>
<article <?php post_class( 'card position-relative h-100 shadow-sm overflow-hidden' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<figure class="card-img-top ratio ratio-16x9 overflow-hidden">
		<?php
		the_post_thumbnail(
			'large',
			array(
				'class'   => 'w-100 object-fit-cover',
				'loading' => 'lazy',
			)
		);
		?>
	</figure>
	<?php endif; ?>
	<div class="card-body d-flex flex-column flex-wrap">
		<div class="card-head">
			<?php
			the_title( '<h2 class="card-title h3 fw-bold">', '</h2>' );
			get_template_part(
				'template-parts/content',
				'post-meta',
			);
			?>
		</div>
		<?php
		$short_excerpt = substr( get_the_excerpt(), 0, 240 );
		echo ! empty( $short_excerpt ) ? "<p class='fs-base mb-4 card-text'>{$short_excerpt}...</p>" : '';
		?>
		<a href="<?php the_permalink(); ?>" class="btn btn-primary mt-auto align-self-start <?php echo $with_post_meta ? '' : 'stretched-link'; ?>"><?php echo $button_text; ?></a>
	</div>
</article>