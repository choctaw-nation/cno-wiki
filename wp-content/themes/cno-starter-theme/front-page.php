<?php
/**
 * Homepage Template
 *
 * @package ChoctawNation
 */

get_header();
$body_classes = array(
	'd-flex',
	'flex-column',
	'align-items-stretch',
	'row-gap-5',
	'mb-5',
);
?>
<main <?php post_class( $body_classes ); ?>>
	<section id="welcome" class="text-bg-light py-5">
		<div class="container">
			<div class="row">
				<div class="col text-center">
					<h1 class="display-1">Halito!</h1>
					<p class="fs-5">Welcome to the Wiki for the Choctaw Web & Digital Team.</p>
				</div>
			</div>
		</div>
	</section>
	<section id="websites">
		<?php
		get_template_part(
			'template-parts/content',
			'recent-posts',
			array(
				'title'            => 'Websites',
				'type'             => 'website',
				'no_posts_message' => 'No websites found.',
				'with_post_meta'   => false,
				'read_more'        => 'View Website Docs',
			)
		);
		?>
	</section>
	<section>
		<?php
		get_template_part(
			'template-parts/content',
			'recent-posts',
			array(
				'title'     => 'Recent Posts',
				'type'      => array( 'post', 'dev-note' ),
				'as_swiper' => true,
			)
		);
		?>
	</section>
</main>
<?php
get_footer();