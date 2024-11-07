<?php
/**
 * Standard Page Output with default Hero section
 *
 * @package ChoctawNation
 */

get_header();
?>

<main <?php post_class(); ?>>
	<article class="container my-5">
		<?php the_content(); ?>
	</article>
</main>
<?php
get_footer();
