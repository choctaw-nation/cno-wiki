<?php
/**
 * The Websites Navigation (Sidebar)
 *
 * @package ChoctawNation
 */

?>
<nav class="offcanvas-body">
	<ul class="sidebar-nav accordion accordion-flush list-unstyled rounded-0 m-0 ps-0 flex-grow-1">
		<?php foreach ( $args as $website ) : ?>
			<?php
			$args = array(
				'post_type' => array( 'post', 'image-spec' ),
				'tax_query' => array(
					array(
						'taxonomy' => $website->taxonomy,
						'field'    => 'slug',
						'terms'    => $website->slug,
					),
				),
			);
			if ( cno_user_is_developer() ) {
				array_push( $args['post_type'], 'dev-note' );
			}
			$related_posts = get_posts( $args );
			?>
			<?php if ( ! empty( $related_posts ) ) : ?>
		<li class="accordion-item">
			<h4 class="accordion-header">
				<button class="accordion-button collapsed px-2 py-1" data-bs-toggle="collapse" data-bs-target="<?php echo '#' . $website->slug; ?>" aria-expanded="false"
						aria-controls="<?php echo '#' . $website->slug; ?>">
					<?php echo $website->name; ?>
				</button>
			</h4>
				<?php
				echo "<ul class='accordion-collapse collapse list-unstyled list-group list-group-flush ms-2' id='{$website->slug}'>";
				foreach ( $related_posts as $related_post ) {
					echo '<li><a class="fs-base list-group-item list-group-item-action" href="' . get_permalink( $related_post->ID ) . '">' . esc_html( $related_post->post_title ) . '</a></li>';
				}
				echo '</ul>';
				?>
		</li>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</nav>