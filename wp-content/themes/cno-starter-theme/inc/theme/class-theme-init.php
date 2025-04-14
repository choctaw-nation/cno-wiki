<?php
/**
 * Initializes the Theme
 *
 * @package ChoctawNation
 */

namespace ChoctawNation;

use WP_Block_Type_Registry;

/** Builds the Theme */
class Theme_Init {
	/** The type of site
	 *
	 * @var 'nation'|'commerce' $theme_type
	 */
	private string $theme_type;

	/** Constructor Function that also loads the proper favicon package
	 *
	 * @param 'nation'|'commerce' $type the type of site to load favicons for.
	 */
	public function __construct( string $type = 'nation' ) {
		$this->theme_type = $type;
		$this->load_required_files();
		$this->disable_discussion();
		$this->load_favicons( 'nation' );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_cno_scripts' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'load_typekit' ) );
		add_action( 'after_setup_theme', array( $this, 'cno_theme_support' ) );
		add_action( 'init', array( $this, 'alter_post_types' ) );
		add_filter( 'allowed_block_types_all', array( $this, 'restrict_allowed_block_types' ), 10, 2 );
		/**
		 * Filter the priority of the Yoast SEO metabox
		 */
		add_filter(
			'wpseo_metabox_prio',
			function (): string {
				return 'low';
			}
		);
	}

	/**
	 * Load favicons based on the type of site
	 */
	private function load_favicons() {
		add_action(
			'wp_head',
			function () {
				$href = get_stylesheet_directory_uri() . '/img/favicons';
				switch ( $this->theme_type ) {
					case 'commerce':
						$href .= '/commerce';
						break;
					case 'nation':
						$href .= '/nation';
						break;
					default:
				}
				echo "<link rel='apple-touch-icon' sizes='180x180' href='{$href}/apple-touch-icon.png'>
				<link rel='icon' type'='image/png' sizes='192x192' href='{$href}/android-chrome-192x192.png'>
				<link rel='icon' type'='image/png' sizes='512x512' href='{$href}/android-chrome-512x512.png'>
				<link rel='icon' type='image/png' sizes='32x32' href='{$href}/favicon-32x32.png'>
				<link rel='icon' type='image/png' sizes='16x16' href='{$href}/favicon-16x16.png'>
				<link rel='mask-icon' href='{$href}/safari-pinned-tab.svg' color='#000000'>";
			}
		);
	}

	/** Load required files. */
	private function load_required_files() {
		$base_path = get_template_directory() . '/inc';

		// Load Global Functions
		$global_functions = array( 'theme-functions', 'role-functions' );
		foreach ( $global_functions as $global_function ) {
			require_once $base_path . "/theme/{$global_function}.php";
		}

		$acf_classes = array(
			'generator',
			'image',
		);
		foreach ( $acf_classes as $acf_class ) {
			require_once $base_path . "/acf/acf-classes/class-{$acf_class}.php";
		}

		$asset_loaders = array(
			'enum-enqueue-type',
			'class-asset-loader',
		);
		foreach ( $asset_loaders as $asset_loader ) {
			require_once $base_path . "/theme/asset-loader/{$asset_loader}.php";
		}

		$navwalkers = array(
			'navwalker',
			'sidebar-navwalker',
		);
		foreach ( $navwalkers as $navwalker ) {
			require_once $base_path . "/theme/navwalkers/class-{$navwalker}.php";
		}

		$utility_files = array(
			'allow-svg'          => 'Allow_SVG',
			'role-editor'        => 'Role_Editor',
			'badge-generator'    => null,
			'search-rest-api'    => 'Search_Rest_API',
			'capability-handler' => 'Capability_Handler',
		);
		foreach ( $utility_files as $utility_file => $class_name ) {
			require_once $base_path . "/theme/class-{$utility_file}.php";
			if ( $class_name ) {
				$class = __NAMESPACE__ . '\\' . $class_name;
				new $class();
			}
		}
	}

	/** Remove comments, pings and trackbacks support from posts types. */
	private function disable_discussion() {
		// Close comments on the front-end
		add_filter( 'comments_open', '__return_false', 20, 2 );
		add_filter( 'pings_open', '__return_false', 20, 2 );

		// Hide existing comments.
		add_filter( 'comments_array', '__return_empty_array', 10, 2 );

		// Remove comments page in menu.
		add_action(
			'admin_menu',
			function () {
				remove_menu_page( 'edit-comments.php' );
			}
		);

		// Remove comments links from admin bar.
		add_action(
			'init',
			function () {
				if ( is_admin_bar_showing() ) {
					remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
				}
			}
		);
	}

	/**
	 * Adds scripts with the appropriate dependencies
	 */
	public function enqueue_cno_scripts() {
		$this->load_typekit();
		$this->eager_js();

		new Asset_Loader(
			'bootstrap',
			Enqueue_Type::both,
			'vendors',
			array(
				'scripts' => array(),
				'styles'  => array(),
			)
		);

		new Asset_Loader(
			'global',
			Enqueue_Type::both,
			null,
			array(
				'scripts' => array( 'bootstrap' ),
				'styles'  => array( 'bootstrap' ),
			)
		);
		wp_localize_script( 'global', 'cnoSiteData', array( 'rootUrl' => home_url() ) );

		// style.css
		wp_enqueue_style(
			'main',
			get_stylesheet_uri(),
			array( 'global' ),
			wp_get_theme()->get( 'Version' )
		);
	}

	/** Load Typekit */
	public function load_typekit() {
		wp_enqueue_style(
			'typekit',
			'https://use.typekit.net/jky5sek.css',
			array(),
            null // phpcs:ignore
		);
	}

	/** Scripts intentionally set as render-blocking */
	private function eager_js() {
		$color_mode_asset_file = require_once get_template_directory() . '/dist/modules/color-mode-handler.asset.php';
		wp_enqueue_script(
			'color-mode-handler',
			get_template_directory_uri() . '/dist/modules/color-mode-handler.js',
			array(),
			$color_mode_asset_file['version'],
			false
		);
	}

	/** Registers Theme Supports */
	public function cno_theme_support() {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'align-wide' );

		register_nav_menus(
			array(
				'sidebar_menu' => 'Sidebar Menu',
				'primary_menu' => 'Primary Menu',
			)
		);
	}

	/** Remove post type support from posts types. */
	public function alter_post_types() {
		$post_types = array(
			'post',
			'page',
		);
		foreach ( $post_types as $post_type ) {
			$this->disable_post_type_support( $post_type );
		}
	}

	/**
	 * Filters the list of allowed block types in the block editor.
	 *
	 * This function restricts the available block types to a set list, and adds additional blocks based on post type.
	 *
	 * @param array|bool $allowed_block_types Array of block type slugs, or boolean to enable/disable all.
	 * @param object     $block_editor_context The current block editor context.
	 *
	 * @return array The array of allowed block types.
	 */
	public function restrict_allowed_block_types( $allowed_block_types, $block_editor_context ) {
		if ( ! $allowed_block_types ) {
			return $allowed_block_types;
		}
		$all_blocks           = \WP_Block_Type_Registry::get_instance()->get_all_registered();
		$all_types            = array_keys( $all_blocks );
		$filtered_block_types = array(
			'core/heading',
			'core/image',
			'core/list',
			'core/list-item',
			'core/paragraph',
			'core/cover',
			'core/media-text',
			'core/gallery',
			'core/group',
			'core/details',
			'core/columns',
			'core/column',
			'core/video',
			'core/text-columns',
			'core/quote',
			'core/table',
			'core/read-more',
			'core/shortcode',
			'core/separator',
			'core/table',
			'core/more',
			'core/pattern',
			'core/buttons',
			'core/button',
			'core/block',
		);

		$dev_post_types = array( 'website', 'dev-note' );
		if ( in_array( $block_editor_context->post->post_type, $dev_post_types, true ) ) {
			$dev_blocks           = array(
				'core/embed',
				'core/code',
				'core/template-part',
				'core/preformatted',
				'core/html',
				'core/freeform',
				'core/missing',
				'core/post-date',
				'core/post-excerpt',
				'core/post-featured-image',
				'dm-code-snippet/code-snippet-block-dm',
			);
			$filtered_block_types = array_merge( $filtered_block_types, $dev_blocks );
		}
		return $filtered_block_types;
	}

	/**
	 * Disable post-type-supports from posts
	 *
	 * @param string $post_type the post type to remove supports from.
	 */
	private function disable_post_type_support( string $post_type ) {
		$supports = array(
			'comments',
			'trackbacks',
		);
		foreach ( $supports as $support ) {
			if ( post_type_supports( $post_type, $support ) ) {
				remove_post_type_support( $post_type, $support );
			}
		}
	}
}