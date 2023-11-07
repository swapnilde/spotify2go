<?php
/**
 * Helper functions for the plugin.
 *
 * @link       https://swapnild.com
 * @since      1.0.0
 * @package    Spotify2Go
 */

namespace Spotify2Go\includes;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Helper functions for the plugin.
 */
class SGOHelper {

	/**
	 * Check if the spotify client id and secret are set.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return boolean True if empty.
	 */
	public static function check_spotify_api_keys_empty() {
		$sfwe_options          = get_option( 'sfwe_options' );
		$spotify_client_id     = $sfwe_options['sfwe_client_id'] ?? '';
		$spotify_client_secret = $sfwe_options['sfwe_client_secret'] ?? '';

		return empty( $spotify_client_id ) || empty( $spotify_client_secret );
	}

	/**
	 * Get the spotify access token.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return string Access token.
	 */
	public static function get_spotify_access_token() {
		$url          = 'https://accounts.spotify.com/api/token';
		$access_token = get_transient( 'sfwe_spotify_access_token' );
		$sfwe_options = get_option( 'sfwe_options' );
		if ( empty( $access_token ) ) {
			$token_data      = wp_remote_post(
				$url,
				array(
					'body' => array(
						'grant_type'    => 'client_credentials',
						'client_id'     => $sfwe_options['sfwe_client_id'] ?? '',
						'client_secret' => $sfwe_options['sfwe_client_secret'] ?? '',
					),
				)
			);
			$parsed_response = json_decode( wp_remote_retrieve_body( $token_data ) );
			set_transient( 'sfwe_spotify_access_token', $parsed_response->access_token, $parsed_response->expires_in );
			$access_token = $parsed_response->access_token;
		}

		return $access_token;
	}

	/**
	 * Get the spotify episodes.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return  array    Episodes.
	 */
	public static function get_spotify_all_episodes() {
		$sfwe_options    = get_option( 'sfwe_options' );
		$spotify_show_id = $sfwe_options['sfwe_show_id'] ?? '';
		$url             = 'https://api.spotify.com/v1/shows/' . $spotify_show_id . '/episodes?market=US';
		$access_token    = self::get_spotify_access_token();
		$show            = wp_remote_get(
			$url,
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_token,
				),
			)
		);
		$episodes        = json_decode( wp_remote_retrieve_body( $show ) );
		$episodes_array  = array();
		foreach ( $episodes->items as $episode ) {
			$episodes_array[ $episode->id ] = $episode->name;
		}

		return $episodes_array;
	}

	/**
	 * Get the spotify show tracks.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return  array    Show tracks.
	 */
	public static function get_spotify_show_tracks() {
		$sfwe_options    = get_option( 'sfwe_options' );
		$spotify_show_id = $sfwe_options['sfwe_album_id'] ?? '';
		$url             = 'https://api.spotify.com/v1/albums/' . $spotify_show_id . '/tracks?market=US';
		$access_token    = self::get_spotify_access_token();
		$show            = wp_remote_get(
			$url,
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_token,
				),
			)
		);
		$tracks          = json_decode( wp_remote_retrieve_body( $show ) );
		$tracks_array    = array();
		foreach ( $tracks->items as $track ) {
			$tracks_array[ $track->id ] = $track->name;
		}

		return $tracks_array;
	}

	/**
	 * Register menu, submenu, options pages .
	 *
	 * @since    1.0.0
	 * @access   private
	 * @return array Array of pages configuration.
	 */
	public static function get_options_page() {

		// Page.
		$panel_args = array(
			'title'           => 'Spotify2Go',
			'option_name'     => 'sfwe_options',
			'slug'            => 'sfwe-options-panel',
			'user_capability' => 'manage_options',
			'tabs'            => array(
				'sfwe-api-tab'         => esc_html__( 'API Keys', 'spotify2go' ),
				'sfwe-integration-tab' => esc_html__( 'Integrations', 'spotify2go' ),
			),
			'icon_url'        => 'dashicons-easyproposal_admin_menu_icon',
			'position'        => '59.1',
		);

		// Settings.
		$panel_settings = array(
			// Tab 1.
			'sfwe_client_id'     => array(
				'label'       => esc_html__( 'Client ID', 'spotify2go' ),
				'type'        => 'text',
				'description' => '',
				'tab'         => 'sfwe-api-tab',
			),
			'sfwe_client_secret' => array(
				'label'       => esc_html__( 'Client Secret', 'spotify2go' ),
				'type'        => 'text',
				'description' => '',
				'tab'         => 'sfwe-api-tab',
			),
			// Tab 2.
			'sfwe_show_id'       => array(
				'label'       => esc_html__( 'Podcast Show ID', 'spotify2go' ),
				'type'        => 'text',
				'description' => '',
				'tab'         => 'sfwe-integration-tab',
			),
			'sfwe_album_id'      => array(
				'label'       => esc_html__( 'Album ID', 'spotify2go' ),
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
}
