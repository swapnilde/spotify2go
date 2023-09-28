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
	 * The current instance of the SpotifyWordpressElementor class.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The current instance of the SpotifyWordpressElementor class.
	 */
	private static $instance;

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

		$this->loader = SpotifyWordpressElementorLoader::get_instance();

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

		$plugin_admin = new SpotifyWordpressElementorAdmin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add a custom category for our blocks.
		$this->loader->add_filter( 'block_categories_all', $plugin_admin, 'add_block_categories', 10, 2 );

		// Register our block script with WordPress.
		$this->loader->add_action( 'init', $plugin_admin, 'register_block_script' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new SpotifyWordpressElementorFrontend( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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

}
