<?php
/**
 * The Post Meta template
 *
 * @package ChoctawNation
 */

$classes          = isset( $args['classes'] ) ? $args['classes'] : '';
$has_been_updated = get_the_modified_time( 'U' ) !== get_the_time( 'U' );
$author_id        = get_post_field( 'post_author', get_the_ID() );
$author           = get_the_author_meta( 'display_name', $author_id );
?>
<div class="<?php echo "post-meta fs-base {$classes}"; ?>">
	<p class="mb-0">
		By:
		<?php echo do_shortcode( '[publishpress_authors_box]' ); ?>
	</p>
	<p class="mb-2">
		<?php
		if ( $has_been_updated ) {
			echo 'Last Updated: ';
			if ( get_the_modified_time( 'Y-m-d' ) === date( 'Y-m-d' ) ) { // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				echo 'Today at ';
			}
			the_modified_time( 'F j, Y @ g:ia' );
		} else {
			echo 'Posted: ';
			the_time( 'F j, Y @ g:ia' );
		}
		?>
	</p>
	<?php get_template_part( 'template-parts/content', 'post-badges' ); ?>
</div>
