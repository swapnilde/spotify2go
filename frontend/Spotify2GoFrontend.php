<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://swapnild.com
 * @since      1.0.0
 * @package    Spotify2Go
 */

namespace Spotify2Go\Frontend;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Spotify2Go\includes\SGOHelper;

/**
 * The public-facing functionality of the plugin.
 */
class Spotify2GoFrontend {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @param string $hook Name of the hook.
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {

		wp_enqueue_style( $this->plugin_name, SPOTIFY_WORDPRESS_ELEMENTOR_URLPATH . 'assets/frontend/css/spotify-wordpress-elementor-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @param string $hook Name of the hook.
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		wp_enqueue_script(
			$this->plugin_name . '-manifest',
			SPOTIFY_WORDPRESS_ELEMENTOR_URLPATH . 'assets/manifest.js',
			array(),
			$this->version,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_enqueue_script(
			$this->plugin_name . '-vendor',
			SPOTIFY_WORDPRESS_ELEMENTOR_URLPATH . 'assets/vendor.js',
			array(),
			$this->version,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_enqueue_script(
			$this->plugin_name,
			SPOTIFY_WORDPRESS_ELEMENTOR_URLPATH . 'assets/frontend/js/spotify-wordpress-elementor-public.js',
			array( 'jquery' ),
			$this->version,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_localize_script(
			$this->plugin_name,
			'Spotify2GoFrontendVars',
			array(
				'home_url'     => get_home_url(),
				'site_url'     => esc_url_raw( get_site_url() ),
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'rest_url'     => esc_url_raw( get_rest_url() ),
				'user'         => wp_get_current_user(),
				'user_avatar'  => get_avatar_url( wp_get_current_user()->ID ),
				'sfwe_options' => array(
					'client_id'     => SGOHelper::check_spotify_api_keys_empty() ? '' : get_option( 'sfwe_options' )['sfwe_client_id'],
					'client_secret' => SGOHelper::check_spotify_api_keys_empty() ? '' : get_option( 'sfwe_options' )['sfwe_client_secret'],
					'show_id'       => get_option( 'sfwe_options' )['sfwe_show_id'],
					'album_id'      => get_option( 'sfwe_options' )['sfwe_album_id'],
				),
			)
		);

	}

}
