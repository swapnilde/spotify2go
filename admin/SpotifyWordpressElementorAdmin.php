<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://swapnild.com
 * @since      1.0.0
 * @package    Spotify_Wordpress_Elementor
 */

namespace SpotifyWPE\Admin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 */
class SpotifyWordpressElementorAdmin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook Name of the hook.
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {

		wp_enqueue_style( $this->plugin_name, SPOTIFY_WORDPRESS_ELEMENTOR_URLPATH . 'assets/admin/css/spotify-wordpress-elementor-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param string $hook Name of the hook.
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		wp_enqueue_script( $this->plugin_name . '-manifest', SPOTIFY_WORDPRESS_ELEMENTOR_URLPATH . 'assets/manifest.js', array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-vendor', SPOTIFY_WORDPRESS_ELEMENTOR_URLPATH . 'assets/vendor.js', array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name, SPOTIFY_WORDPRESS_ELEMENTOR_URLPATH . 'assets/admin/js/spotify-wordpress-elementor-admin.js', array( 'jquery' ), $this->version, true );

		wp_localize_script(
			$this->plugin_name,
			'SpotifyWPEAdminVars',
			array(
				'home_url'    => get_home_url(),
				'site_url'    => esc_url_raw( get_site_url() ),
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'rest_url'    => esc_url_raw( get_rest_url() ),
				'user'        => wp_get_current_user(),
				'user_avatar' => get_avatar_url( wp_get_current_user()->ID ),
			)
		);

	}

}
