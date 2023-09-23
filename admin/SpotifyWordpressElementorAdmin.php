<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://swapnild.com
 * @since      1.0.0
 * @package    Spotify_Wordpress_Elementor
 */

namespace SpotifyWPE\Admin;

use SpotifyWPE\Includes\Options\SpotifyWPEOptionPages;

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

		$option_page = new SpotifyWPEOptionPages( $this->get_pages() );
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

	/**
	 * Register menu, submenu, options pages .
	 *
	 * @since    1.0.0
	 * @return array Array of pages configuration.
	 */
	private function get_pages() {
		$pages = array(
			'spotify-wordpress-elementor' => array(
				'page_title' => __( 'Spotify For WP', 'sample-domain' ),
				// TODO: Change this and its css to your own icon.
				'icon_url'   => 'dashicons-easyproposal_admin_menu_icon',
				'sections'   => array(
					'sfwe-api-section'         => array(
						'id'     => 'sfwe-api-section',
						'title'  => __( 'API Keys', 'sample-domain' ),
						// translators: %s: URL to Spotify Developer Dashboard.
						'text'   => sprintf( __( 'You can get your API keys from <a href="%s" target="_blank">here</a>.', 'sfwe' ), 'https://developer.spotify.com/dashboard/applications' ),
						'fields' => array(
							'sfwe-client-id'     => array(
								'id'          => 'sfwe-client-id',
								'title'       => __( 'Client ID', 'sfwe' ),
								'placeholder' => __( 'Client ID', 'sfwe' ),
							),
							'sfwe-client-secret' => array(
								'id'          => 'sfwe-client-secret',
								'title'       => __( 'Client Secret', 'sfwe' ),
								'placeholder' => __( 'Client Secret', 'sfwe' ),
							),
						),
					),
					'sfwe-integration-section' => array(
						'id'     => 'sfwe-integration-section',
						'title'  => __( 'Integrations', 'sample-domain' ),
						'fields' => array(
							'sfwe-show-id'  => array(
								'id'          => 'sfwe-show-id',
								'title'       => __( 'Podcast Show ID', 'sfwe' ),
								'placeholder' => __( 'Podcast Show ID', 'sfwe' ),
							),
							'sfwe-album-id' => array(
								'id'          => 'sfwe-album-id',
								'title'       => __( 'Album ID', 'sfwe' ),
								'placeholder' => __( 'Album ID', 'sfwe' ),
							),
						),
					),
				),
			),
		);

		return $pages;
	}

}
