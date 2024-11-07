<?php
/**
 * Basic Archive Template
 *
 * @package ChoctawNation
 */

get_header();
?>
<main class="container my-5">
	<h1 class="text-center mb-4"><?php echo substr( get_the_archive_title(), 10 ); ?></h1>
	<?php if ( have_posts() ) : ?>
	<ul class="list-unstyled row row-cols-1 row-cols-md-auto align-items-stretch gx-0 gap-4 mb-5">
		<?php
		while ( have_posts() ) {
			the_post();
			$children = wp_list_pages(
				array(
					'title_li'    => '',
					'child_of'    => get_the_ID(),
					'echo'        => 0,
					'post_type'   => get_post_type(),
					'post_status' => 'publish',
				)
			);
			if ( ! $children ) {
				continue;
			}
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
</main>
<?php
get_footer();