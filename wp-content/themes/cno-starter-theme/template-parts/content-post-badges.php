<?php
/**
 * Displays the Proper Badges for a Post
 *
 * @package ChoctawNation
 */

use ChoctawNation\Badge_Generator;

$badge_generator = new Badge_Generator();
$badges          = $badge_generator->get_badges();
?>
<div class="badges-container d-flex flex-wrap gap-2 mb-3">
	<?php foreach ( $badges as $key => $args ) : ?>
		<?php
		if ( is_null( $args ) ) {
			continue;
		}
		?>
	<a href="<?php echo $args['href']; ?>" class="btn btn-<?php echo esc_attr( $args['color'] ); ?> btn-sm rounded-pill">
		<?php echo esc_html( $args['label'] ); ?>
	</a>
	<?php endforeach; ?>
</div>
