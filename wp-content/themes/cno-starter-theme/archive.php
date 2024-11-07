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
	<ul class="list-unstyled row row-cols-1 row-cols-md-auto align-items-stretch gx-0 gap-4 mb-5">
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
<?php
get_footer();