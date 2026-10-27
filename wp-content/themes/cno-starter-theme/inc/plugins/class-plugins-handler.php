<?php
/**
 * Class: Plugin Handler
 *
 * @package ChoctawNation
 */

namespace ChoctawNation\Plugins;

/**
 * Handles plugin functionality
 */
class Plugins_Handler {
	/**
	 * The current environment type (e.g., 'production', 'staging', 'development', 'local').
	 *
	 * @var string $environment
	 */
	private string $environment;

	/**
	 * Constructor to initialize the environment type.
	 */
	public function __construct() {
		$this->environment = wp_get_environment_type();
	}
	/**
	 * Disable certain plugins based on the environment type.
	 */
	public function disable_plugins_per_environment() {
		if ( 'production' === $this->environment || ! is_admin() ) {
			return;
		}

		$plugins_to_disable = array(
			'wordfence/wordfence.php'                 => array( 'local', 'development', 'staging' ),
			'wp-mail-smtp-pro/wp_mail_smtp.php'       => array( 'local', 'development', 'staging' ),
			'google-site-kit/google-site-kit.php'     => array( 'local', 'development', 'staging' ),
			'gravityformsrecaptcha/recaptcha.php'     => array( 'local' ),
			'autoupdater/autoupdater.php'             => array( 'local', 'development', 'staging' ),
			'autoptimize/autoptimize.php'             => array( 'local', 'development' ),
			'wordpress-seo/wp-seo.php'                => array( 'local', 'development' ),
			'yoast-test-helper/yoast-test-helper.php' => array( 'local', 'development' ),
		);

		foreach ( $plugins_to_disable as $plugin => $environments ) {
			if ( in_array( $this->environment, $environments, true ) ) {
				if ( is_plugin_active( $plugin ) ) {
					deactivate_plugins( $plugin );
				}
			}
		}
	}

	/**
	 * Handle automatic plugin updates based on environment.
	 *
	 * @param ?bool $update Whether to update the plugin.
	 * @return ?bool
	 */
	public function handle_auto_update_plugin( ?bool $update ): ?bool {
		if ( 'production' === $this->environment ) {
			return $update;
		}
		return true;
	}

	/**
	 * Handle ACF functionality, including saving and loading JSON files from the theme directory.
	 */
	public function handle_acf() {
		if ( ! defined( 'ACF_PRO' ) || ! defined( 'ACF_VERSION' ) ) {
			return;
		}
		$acf = new ACF_Handler();
		$acf->init_save_filters();
		add_filter( 'acf/settings/enable_datastore', '__return_true' );
		add_filter( 'acf/settings/load_json', array( $acf, 'load_json_paths' ) );
	}

	/**
	 * Handle Gravity Forms functionality, such as adding Bootstrap classes to buttons.
	 */
	public function handle_gravity_forms() {
		if ( ! class_exists( 'GFForms' ) ) {
			return;
		}
		$gravity_forms_handler = new Gravity_Forms_Handler();
		add_filter( 'gform_submit_button', array( $gravity_forms_handler, 'handle_additional_gform_button_classes' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( $gravity_forms_handler, 'dequeue_recaptcha_scripts' ), 30 );
		add_filter( 'gform_default_styles', array( $gravity_forms_handler, 'set_default_styles' ) );
	}

	/**
	 * Handle Yoast SEO functionality, such as using the excerpt or ACF brief description as the meta description if none is set.
	 */
	public function handle_yoast() {
		if ( ! defined( 'WPSEO_VERSION' ) ) {
			return;
		}
		$yoast_handler = new Yoast_Handler();
		// Filter the priority of the Yoast SEO metabox.
		add_filter( 'wpseo_metabox_prio', fn() => 'low' );
		add_filter( 'wpseo_metadesc', array( $yoast_handler, 'meta_description_handler' ) );
	}

	/**
	 * Prevent Site Kit from loading GTM for logged-in users.
	 *
	 * @param bool $blocked Whether the GTM tag is blocked.
	 * @return bool
	 */
	public function block_gtm_for_logged_in_users( bool $blocked ): bool {
		return $blocked || is_user_logged_in() || 'production' !== $this->environment;
	}
}
