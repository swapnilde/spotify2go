<?php
/**
 * The file that defines the core plugin class
 *
 * @link       https://swapnild.com
 * @since      1.0.0
 *
 * @package    Spotify_Wordpress_Elementor
 * @subpackage Spotify_Wordpress_Elementor/includes
 */

namespace SpotifyWPE\Classes;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use SpotifyWPE\Classes\SpotifyWordpressElementorLoader;
use SpotifyWPE\Classes\SpotifyWordpressElementorI18n;
use SpotifyWPE\Admin\SpotifyWordpressElementorAdmin;
use SpotifyWPE\Frontend\SpotifyWordpressElementorFrontend;
use SpotifyWPE\Includes\Options\SFWEOptionsPanel;
use SpotifyWPE\includes\SFWEHelper;

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Spotify_Wordpress_Elementor
 * @subpackage Spotify_Wordpress_Elementor/includes
 * @author     Swapnil Deshpande <hello@swapnild.com>
 */
class SpotifyWordpressElementor {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SpotifyWordpressElementorLoader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the addon.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.7.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the addon.
	 */
	const MINIMUM_PHP_VERSION = '7.3';

	/**
	 * The current instance of the SpotifyWordpressElementor class.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The current instance of the SpotifyWordpressElementor class.
	 */
	private static $instance;

	/**
	 * The unique identifier of the admin class.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      object    $plugin_admin    The string used to uniquely identify this plugin.
	 */
	protected $plugin_admin;

	/**
	 * The unique identifier of the public class.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      object    $plugin_public    The string used to uniquely identify this plugin.
	 */
	protected $plugin_public;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SPOTIFY_WORDPRESS_ELEMENTOR_VERSION' ) ) {
			$this->version = SPOTIFY_WORDPRESS_ELEMENTOR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'spotify-wordpress-elementor';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Singletons should not be cloneable.
	 *
	 * @since 1.0.0
	 */
	protected function __clone() { }

	/**
	 * Singletons should not be restorable from strings.
	 *
	 * @since 1.0.0
	 * @throws \Exception The exception class.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton SpotifyWordpressElementor' );
	}

	/**
	 * This is the static method that controls the access to the SpotifyWordpressElementor class instance.
	 *
	 * @since 1.0.0
	 * @return SpotifyWordpressElementor
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new SpotifyWordpressElementor();
		}
		return self::$instance;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$this->loader        = SpotifyWordpressElementorLoader::get_instance();
		$this->plugin_admin  = new SpotifyWordpressElementorAdmin( $this->get_plugin_name(), $this->get_version() );
		$this->plugin_public = new SpotifyWordpressElementorFrontend( $this->get_plugin_name(), $this->get_version() );

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new SpotifyWordpressElementorI18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts' );

		$options_panel = SFWEHelper::get_options_page();
		new SFWEOptionsPanel( $options_panel['args'], $options_panel['settings'] );

		if ( SFWEHelper::check_spotify_api_keys_empty() ) {
			$this->loader->add_action( 'admin_notices', $this->plugin_admin, 'spotify_api_keys_empty_notice' );
		}

		// Add a custom category for our blocks.
		$this->loader->add_filter( 'block_categories_all', $this->plugin_admin, 'add_block_categories', 10, 2 );

		// Register our block script with WordPress.
		$this->loader->add_action( 'init', $this->plugin_admin, 'register_block_script' );

		// Register elementor widget.
		if ( $this->is_compatible() ) {
			$this->loader->add_action( 'elementor/init', $this->plugin_admin, 'init_widgets' );
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    SpotifyWordpressElementorLoader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Compatibility Checks
	 *
	 * Checks whether the site meets the addon requirement.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_compatible() {

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			$this->loader->add_action( 'admin_notices', $this->plugin_admin, 'admin_notice_missing_elementor_plugin' );
			return false;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			$this->loader->add_action( 'admin_notices', $this->plugin_admin, 'admin_notice_minimum_elementor_version' );
			return false;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			$this->loader->add_action( 'admin_notices', $this->plugin_admin, 'admin_notice_minimum_php_version' );
			return false;
		}

		return true;

	}

}
