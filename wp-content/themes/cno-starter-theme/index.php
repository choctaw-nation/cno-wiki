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
<?php if ( is_search() ) : ?>
	<?php if ( have_posts() ) : ?>
<main class="container my-5">
	<h1 class="mb-5">Showing Results for: “<?php echo get_search_query(); ?>”</h1>
	<ul class="list-unstyled row row-cols-1 row-cols-md-auto align-items-stretch gx-0 gap-4" id="search-results">
		<?php
		while ( have_posts() ) {
			the_post();
			echo '<li class="col-lg-3 flex-grow-1">';
			get_template_part(
				'template-parts/content',
				'post-preview',
			);
			echo '</li>';
		}
		?>
	</ul>
</main>
	<?php else : ?>
<p><?php esc_html_e( 'No posts found.', 'cno-starter-theme' ); ?></p>
	<?php endif; ?>
<?php else : ?>
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
endif;
get_footer();
