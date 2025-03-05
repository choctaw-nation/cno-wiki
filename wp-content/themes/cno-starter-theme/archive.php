<?php
/**
 * Basic Archive Template
 *
 * @package ChoctawNation
 */

get_header();
?>
<main class="d-flex flex-column row-gap-5 my-5">
	<header class="archive-header text-center container">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<h1>
					<?php echo the_archive_title(); ?>
				</h1>
				<?php
				$description = get_the_archive_description();
				if ( $description ) {
					echo $description;
				}
				?>
			</div>
		</div>
	</header>
	<section class="search-form py-5">
		<div class="container">
			<div class="d-flex flex-column row-gap-3 my-5">
				<div class="row row-cols-auto align-items-center gx-0 gap-2">
					<?php
					$shortcodes = array( 'Search', 'Submit' );
					foreach ( $shortcodes as $shortcode ) {
						echo do_shortcode( '[searchandfilter field="' . $shortcode . '"]' );
					}
					?>
				</div>
				<div class="row row-cols-auto align-items-baseline gx-0 gap-2">
					<?php
					$filters = array( 'Languages', 'Frameworks' );
					foreach ( $filters as $filter ) {
						echo do_shortcode( "[searchandfilter field='(Website Taxonomy) {$filter}']" );
					}
					?>
				</div>
			</div>
		</div>
	</section>
	<div class="container">
		<?php if ( have_posts() ) : ?>
		<ul class="list-unstyled row row-cols-1 row-cols-md-auto align-items-stretch gx-0 gap-4 mb-5" id="search-results">
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
		<?php else : ?>
		<p><?php esc_html_e( 'No posts found.', 'cno-starter-theme' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
