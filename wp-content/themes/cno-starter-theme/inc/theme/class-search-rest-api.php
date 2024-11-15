<?php
/**
 * The Search REST API
 *
 * @package ChoctawNation
 */

namespace ChoctawNation;

use WP;
use WP_REST_Request;
use WP_REST_Response;

/**
 * The Search REST API class
 */
class Search_Rest_API {
	/**
	 * The namespace for the REST API.
	 *
	 * @var string
	 */
	protected $namespace = 'cno';

	/**
	 * The version for the REST API.
	 *
	 * @var string
	 */
	protected $version = '1';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			"{$this->namespace}/v{$this->version}",
			'/search',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'perform_search' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Perform the search.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response The response object.
	 */
	public function perform_search( WP_REST_Request $request ): WP_REST_Response {
		$search_query = sanitize_text_field( $request->get_param( 's' ) );

		$query = new \WP_Query(
			array(
				's'          => $search_query,
				'post_type'  => array( 'post', 'page', 'dev-note' ),
				'relevanssi' => true,
			)
		);

		$results = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$results[] = array(
					'title' => get_the_title(),
					'link'  => get_permalink(),
				);
			}
			wp_reset_postdata();
		}

		return new WP_REST_Response( $results, 200 );
	}
}