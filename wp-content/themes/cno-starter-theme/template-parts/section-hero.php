<?php
/**
 * The Hero Section
 *
 * @package ChoctawNation
 */

use ChoctawNation\ACF\Hero;

$hero = new Hero( $args['id'], get_field( 'hero' ) );
?>

<section class="hero py-5 position-relative">
	<?php
	if ( $hero->has_background_image ) {
		$hero->the_image( 'position-absolute top-0 w-100 h-100 z-n1 mx-auto' );
	}
	?>
	<div class="container position-relative z-1">
		<div class="row">
			<div class="col">
				<h1 class="text-white">
					<?php $hero->the_headline(); ?>
				</h1>
				<?php
				if ( $hero->has_subheadline() ) {
					echo '<p class="fs-5">' . $hero->get_the_subheadline() . '</p>';
				}
				if ( $hero->has_cta ) {
					$hero->the_cta( 'btn btn-primary' );
				}
				?>
			</div>
		</div>
	</div>
</section>