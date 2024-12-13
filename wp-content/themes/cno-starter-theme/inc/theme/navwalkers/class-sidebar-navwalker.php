<?php
/**
 * Sidebar Navwalker
 *
 * Edits the output of wp_nav_menu()
 * <div class='menu-container'>
 *   <ul> // start_lvl()
 *     <li>
 *       <a href="">
 *           <span> // start_el() // </span>
 *       </a>
 *     </li> // end_el()
 *   </ul>
 * </div> // end_lvl()
 *
 * @package ChoctawNation
 */

namespace ChoctawNation;

/**
 * Creates the Docs Sidebar
 */
class Sidebar_Navwalker extends \Walker_Nav_Menu {
	/** The current nav item
	 *
	 * @var WP_Post $current_item
	 */
	protected $current_item;

	/**
	 * Depth of menu item. Used for padding.
	 *
	 * @var int $depth
	 */
	protected int $depth;

	/**
	 * The array of wp_nav_menu() arguments as an object.
	 *
	 * @var ?\stdClass $args
	 */
	protected ?\stdClass $args;

	/**
	 * Optional. ID of the current menu item. Default 0.
	 *
	 * @var int $id
	 */
	protected int $id;

	/**
	 * The current page ID
	 *
	 * @var int $current_page_id
	 */
	private int $current_page_id;

	/**
	 * Starts the Element Output (inside the `li`)
	 *
	 * @param string   $output       Used to append additional content (passed by reference).
	 * @param WP_Post  $data_object  Menu item data object.
	 * @param int      $depth        Depth of menu item. Used for padding.
	 * @param stdClass $args         An object of wp_nav_menu() arguments.
	 * @param int      $id           Optional. ID of the current menu item. Default 0.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = \null, $id = 0 ) {
		$this->current_page_id = get_the_ID();
		$this->current_item    = $data_object;
		$this->depth           = $depth;
		$this->args            = $args;
		$this->id              = $id;

		$output .= $this->get_the_li_element();
		$output .= $this->get_the_anchor_element();
		$output .= $this->get_the_posts();
	}

	/** Generate the Opening `li` tag
	 *
	 * @return string the HTML
	 */
	protected function get_the_li_element(): string {
		$indent        = ( $this->depth ) ? str_repeat( "\t", $this->depth ) : '';
		$li_attributes = '';
		$class_names   = $this->set_the_li_classes();
		$html_id       = $this->set_the_li_id();
		return $indent . '<li' . $html_id . $class_names . $li_attributes . '>';
	}

	/**
	 * Handles the setting of the element's classes and returns an HTML string
	 *
	 * @return string the class names
	 */
	protected function set_the_li_classes(): string {
		$classes   = empty( $this->current_item->classes ) ? array() : (array) $this->current_item->classes;
		$classes[] = ( $this->current_item->current || $this->current_item->current_item_ancestor ) ? 'active' : '';
		$classes[] = "nav-item nav-item-{$this->current_item->ID} list-group-item p-0 border-0 rounded-0 position-relative";

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $this->current_item, $this->args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		return $class_names;
	}

	/**
	 * Handles the id generation
	 *
	 * @return string the id
	 */
	protected function set_the_li_id(): string {
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $this->current_item->ID, $this->current_item, $this->args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		return $id;
	}

	/**
	 * Generates the initial anchor tag, or button if the link is a #
	 *
	 * @return string the anchor
	 */
	protected function get_the_anchor_element(): string {
		$attributes = $this->get_the_anchor_attributes();

		$title = apply_filters( 'the_title', $this->current_item->title, $this->current_item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $this->current_item, $this->args, $this->depth );

		$item_output  = $this->args->before;
		$item_output .= "<a {$attributes}>";
		$item_output .= $this->args->link_before . $title . $this->args->link_after;
		$item_output .= '</a>';
		$item_output .= $this->args->after;
		$item_output  = apply_filters( 'walker_nav_menu_start_el', $item_output, $this->current_item, $this->depth, $this->args );
		return $item_output;
	}

	/** Builds the anchor attributes */
	protected function get_the_anchor_attributes(): string {
		$current_classes = array(
			'current-menu-parent',
			'current-dev-note-parent',
			'current-dev-note-ancestor',
		);
		$active_class    = array( 'nav-link' );
		foreach ( $current_classes as $class ) {
			if ( in_array( $class, $this->current_item->classes, true ) ) {
				$active_class = array_merge(
					$active_class,
					array(
						'active',
						'border-bottom',
						'border-4',
						'border-primary-subtle',
						'text-primary-subtle',
						'p-2',
						'fw-bold',
					)
				);
				break;
			}
		}

		$attributes = array(
			'title'  => $this->current_item->attr_title,
			'target' => $this->current_item->target,
			'rel'    => $this->current_item->xfn,
			'href'   => $this->current_item->url,
			'class'  => implode( ' ', $active_class ),
		);
		return $this->build_atts( $attributes );
	}

	/**
	 * Gets the posts for the current term and attaches them as a sub-menu
	 *
	 * @return string the markup
	 */
	private function get_the_posts(): string {
		$term_id = $this->current_item->object_id;
		$term    = get_term( $term_id );
		$markup  = '';
		if ( ! $term || is_wp_error( $term ) ) {
			return $markup;
		}

		$query = new \WP_Query(
			array(
				'post_type' => array( 'post', 'dev-note' ),
				'tax_query' => array(
					array(
						'taxonomy' => $term->taxonomy,
						'field'    => 'term_id',
						'terms'    => $term_id,
					),
				),
			)
		);

		if ( ! $query->have_posts() ) {
			return $markup;
		}

		$markup .= '<ul class="sub-menu list-unstyled list-group">';
		while ( $query->have_posts() ) {
			$query->the_post();
			$li_classes     = array(
				'list-group-item',
				'list-group-item-action',
				'border-0',
				'rounded-0',
				'position-relative',
			);
			$anchor_classes = array( 'text-decoration-none', 'stretched-link' );

			$is_current = get_the_ID() === $this->current_page_id;
			if ( $is_current ) {
				$li_classes[]     = 'active';
				$anchor_classes[] = 'text-white fw-bold';
			}

			$markup .= '<li ' . ( $is_current ? 'aria-current="true"' : '' ) . ' class="' . esc_attr( implode( ' ', $li_classes ) ) . '">';
			$markup .= '<a href="' . get_permalink() . '" class="' . esc_attr( implode( ' ', $anchor_classes ) ) . '">' . get_the_title() . '</a></li>';
		}
		$markup .= '</ul>';
		wp_reset_postdata();
		return $markup;
	}
}