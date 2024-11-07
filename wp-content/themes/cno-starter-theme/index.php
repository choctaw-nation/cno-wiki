<?php
/**
 * The primary archive page.
 *
 * Included for historical reasons. This file is not used in the theme.
 *
 * @package ChoctawNation
 */

get_header();
?>
<article class="<?php echo join( ' ', get_post_class( 'container my-5' ) ); ?>">
	<div class="row">
		<div class="col">
			<?php the_post_thumbnail( 'full' ); ?>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php the_title( '<h1>', '</h1>' ); ?>
		</div>
		</row>
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<?php the_content(); ?>
			</div>
		</div>
</article>
<?php
get_footer();
