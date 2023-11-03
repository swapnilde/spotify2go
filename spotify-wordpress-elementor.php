<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://swapnild.com
 * @since             1.0.0
 * @package           Spotify_Wordpress_Elementor
 *
 * @wordpress-plugin
 * Plugin Name:       Spotify For WordPress & Elementor
 * Plugin URI:        https://swapnild.com
 * Description:       Spotify For WordPress & Elementor help you share interactive content from Spotify on your website. Embed podcast, an album, or other audio content to your website and promote your music, share your new podcast episodes with fans, or highlight your favourite album or playlist.
 * Version:           1.0.0
 * Author:            Swapnil Deshpande
 * Author URI:        https://swapnild.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spotify-wordpress-elementor
 * Domain Path:       /languages
 */

use SpotifyWPE\Classes\SpotifyWordpressElementor;
use SpotifyWPE\Classes\SpotifyWordpressElementorActivator;
use SpotifyWPE\Classes\SpotifyWordpressElementorDeactivator;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'SPOTIFY_WORDPRESS_ELEMENTOR_VERSION', '1.0.0' );
define( 'SPOTIFY_WORDPRESS_ELEMENTOR_DIRPATH', plugin_dir_path( __FILE__ ) );
define( 'SPOTIFY_WORDPRESS_ELEMENTOR_URLPATH', plugin_dir_url( __FILE__ ) );

// SpotifyWPE autoloader.
require_once SPOTIFY_WORDPRESS_ELEMENTOR_DIRPATH . 'includes/autoloader.php';

/**
 * The code that runs during plugin activation.
 */
function activate_spotify_wordpress_elementor() {
	SpotifyWordpressElementorActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_spotify_wordpress_elementor() {
	SpotifyWordpressElementorDeactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_spotify_wordpress_elementor' );
register_deactivation_hook( __FILE__, 'deactivate_spotify_wordpress_elementor' );

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_spotify_wordpress_elementor() {

	$plugin = SpotifyWordpressElementor::get_instance();
	$plugin->run();

}
run_spotify_wordpress_elementor();
