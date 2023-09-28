<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://swapnild.com
 * @since      1.0.0
 * @package    Spotify_Wordpress_Elementor
 */

namespace SpotifyWPE\Admin;

use SpotifyWPE\Includes\Options\SFWEOptionsPanel;

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

		$options_panel = $this->get_options_page();
		new SFWEOptionsPanel( $options_panel['args'], $options_panel['settings'] );
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
	private function get_options_page() {

		// Page.
		$panel_args = array(
			'title'           => 'Spotify For WP',
			'option_name'     => 'sfwe_options',
			'slug'            => 'sfwe-options-panel',
			'user_capability' => 'manage_options',
			'tabs'            => array(
				'sfwe-api-tab'         => esc_html__( 'API Keys', 'sfwe' ),
				'sfwe-integration-tab' => esc_html__( 'Integrations', 'sfwe' ),
			),
			'icon_url'        => 'dashicons-easyproposal_admin_menu_icon',
			'position'        => '59.1',
		);

		// Settings.
		$panel_settings = array(
			// Tab 1.
			'sfwe_client_id'     => array(
				'label'       => esc_html__( 'Client ID', 'sfwe' ),
				'type'        => 'text',
				'description' => '',
				'tab'         => 'sfwe-api-tab',
			),
			'sfwe_client_secret' => array(
				'label'       => esc_html__( 'Client Secret', 'sfwe' ),
				'type'        => 'text',
				'description' => '',
				'tab'         => 'sfwe-api-tab',
			),
			// Tab 2.
			'sfwe_show_id'       => array(
				'label'       => esc_html__( 'Podcast Show ID', 'sfwe' ),
				'type'        => 'text',
				'description' => '',
				'tab'         => 'sfwe-integration-tab',
			),
			'sfwe_album_id'      => array(
				'label'       => esc_html__( 'Album ID', 'sfwe' ),
				'type'        => 'text',
				'description' => '',
				'tab'         => 'sfwe-integration-tab',
			),
		);

		return array(
			'args'     => $panel_args,
			'settings' => $panel_settings,
		);
	}

	/**
	 * Add block categories.
	 *
	 * @param array  $block_categories Array of categories.
	 * @param object $editor_context Post object.
	 * @since    1.0.0
	 * @return array Array of categories.
	 */
	public function add_block_categories( $block_categories, $editor_context ) {
		$block_categories[] = array(
			'slug'  => 'spotify-wordpress-elementor',
			'title' => __( 'Spotify For Wordpress', 'sfwe' ),
		);

		return $block_categories;
	}

	/**
	 * Register block script.
	 *
	 * @since    1.0.0
	 */
	public function register_block_script() {
		register_block_type( SPOTIFY_WORDPRESS_ELEMENTOR_DIRPATH . 'assets/admin/blocks/list-embed' );
	}

}
