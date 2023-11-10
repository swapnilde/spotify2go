<?php
/**
 * Define the internationalization functionality
 *
 * @link       https://swapnild.com
 * @since      1.0.0
 * @package    Spotify2Go
 */

namespace Spotify2Go\Classes;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the internationalization functionality.
 */
class Spotify2GoI18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'spotify2go',
			false,
			dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/'
		);

	}



}
