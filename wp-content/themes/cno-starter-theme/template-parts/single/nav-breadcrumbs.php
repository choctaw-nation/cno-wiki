<?php
/**
 * Breadcrumb Navs for Single Templates
 *
 * @package ChoctawNation
 */

$breadcrumbs   = array();
$breadcrumbs[] = array(
	'title' => 'Home',
	'link'  => home_url(),
);

if ( is_single() ) {
	if ( get_post_type() !== 'post' ) {
		$breadcrumbs[] = array(
			'link'  => get_post_type_archive_link( get_post_type() ),
			'title' => get_post_type_object( get_post_type() )->label,
		);
	} else {
		$categories = get_the_category();
		if ( $categories ) {
			foreach ( $categories as $category ) {
				$breadcrumbs[] = array(
					'title' => $category->name,
					'link'  => get_category_link( $category ),
				);
			}
		}
	}
}
$websites = get_the_terms( get_the_ID(), 'website' );
if ( $websites && ! is_wp_error( $websites ) ) {
	foreach ( $websites as $website ) {
		$breadcrumbs[] = array(
			'title' => $website->name,
			'link'  => get_term_link( $website ),
		);
	}
}
$ancestors = get_post_ancestors( get_the_ID() );
if ( $ancestors ) {
	$ancestors = array_reverse( $ancestors );
	foreach ( $ancestors as $ancestor ) {
		$breadcrumbs[] = array(
			'title' => get_the_title( $ancestor ),
			'link'  => get_the_permalink( $ancestor ),
		);
	}
}
$breadcrumbs[] = array(
	'title' => get_the_title(),
);
?>
<nav class="breadcrumb fw-bold my-3" style="--bs-breadcrumb-divider:'&gt;'">
	<ol class="list-unstyled m-0 d-flex flex-wrap">
		<?php foreach ( $breadcrumbs as $index => $breadcrumb ) : ?>
			<?php
			$is_current = count( $breadcrumbs ) - 1 === $index;
			$li_class   = 'breadcrumb-item' . ( $is_current ? ' fw-bold' : '' );
			?>
		<li class="<?php echo $li_class; ?>" <?php echo $is_current ? 'aria-current="page"' : ''; ?>>
			<?php if ( $is_current ) : ?>
				<?php echo esc_html( $breadcrumb['title'] ); ?>
			<?php else : ?>
			<a href="<?php echo esc_url( $breadcrumb['link'] ); ?>" class="text-decoration-none">
				<?php echo esc_html( $breadcrumb['title'] ); ?>
			</a>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ol>
</nav>