<?php
/**
 * Initializes the Theme
 *
 * @package ChoctawNation
 */

namespace ChoctawNation;

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
	}

	/**
	 * Bootstrap the theme in the `after_setup_theme` hook.
	 */
	public function setup_theme(): void {
		$this->load_required_files();
		$this->disable_discussion();
		$this->load_favicons();
		$this->edit_roles();
		$this->allow_svg();
		$this->handle_plugins();
		$this->cno_theme_support();
		$this->handle_gutenberg();
		$this->enable_site_search();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_cno_scripts' ) );
		add_action( 'init', array( $this, 'alter_post_types' ) );
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
	 * Edit user roles and capabilities
	 */
	private function edit_roles() {
		$role_editor = new Admin\Role_Editor();
		add_action( 'admin_init', array( $role_editor, 'create_custom_roles' ) );
		add_filter( 'map_meta_cap', array( $role_editor, 'allow_edit_css' ), 10, 3 );
	}

	/**
	 * Allow SVG uploads
	 */
	private function allow_svg() {
		$svg = new Admin\Allow_SVG();
		add_filter( 'upload_mimes', array( $svg, 'cc_mime_types' ) );
		add_action( 'admin_head', array( $svg, 'fix_svg' ) );
	}

	/**
	 * Handle Plugins
	 */
	private function handle_plugins() {
		$handler = new Plugins\Plugins_Handler();
		$handler->handle_acf();
		$handler->handle_yoast();
		$handler->handle_gravity_forms();
		add_action( 'init', array( $handler, 'disable_plugins_per_environment' ) );
		add_filter( 'auto_update_plugin', array( $handler, 'handle_auto_update_plugin' ) );
		add_filter( 'googlesitekit_tagmanager_tag_blocked', array( $handler, 'block_gtm_for_logged_in_users' ) );
	}

	/**
	 * Handle the block editor
	 */
	private function handle_gutenberg() {
		$gutenberg_handler = new Admin\Gutenberg_Handler();
		$gutenberg_handler->cno_block_theme_support();
		add_action( 'init', array( $gutenberg_handler, 'init_block_theme' ) );
		add_action( 'enqueue_block_editor_assets', array( $gutenberg_handler, 'enqueue_block_editor_assets' ) );
		add_action( 'enqueue_block_assets', array( $gutenberg_handler, 'enqueue_block_assets' ) );
		add_filter( 'block_editor_settings_all', array( $gutenberg_handler, 'alter_gutenberg_ui' ), 10, 2 );
		add_filter( 'allowed_block_types_all', array( $gutenberg_handler, 'restrict_block_types' ), 10, 2 );
		add_filter( 'use_block_editor_for_post_type', array( $gutenberg_handler, 'handle_page_templates' ) );
		add_filter( 'should_load_remote_block_patterns', '__return_false' );
	}

	/**
	 * Enable global site search
	 */
	private function enable_site_search() {
		$site_search_handler = new Features\Site_Search();
		add_action( 'rest_api_init', array( $site_search_handler, 'register_routes' ) );
	}


	/**
	 * Adds scripts with the appropriate dependencies
	 */
	public function enqueue_cno_scripts() {
		$this->eager_js();

		new Utils\Asset_Loader(
			'bootstrap',
			Utils\Enqueue_Type::both,
			'vendors',
			array(
				'scripts' => array(),
				'styles'  => array(),
			)
		);

		new Utils\Asset_Loader(
			'global',
			Utils\Enqueue_Type::both,
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
