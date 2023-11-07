<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://swapnild.com
 * @since             1.0.0
 * @package           Spotify2Go
 *
 * @wordpress-plugin
 * Plugin Name:       Spotify2Go
 * Plugin URI:        https://swapnild.com
 * Description:       Spotify2Go help you share interactive content from Spotify on your website. Embed podcast, an album, or other audio and video content to your website and promote your music, share your new podcast episodes with fans, or highlight your favourite album or playlist.
 * Version:           1.0.0
 * Author:            Swapnil Deshpande
 * Author URI:        https://swapnild.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spotify2go
 * Domain Path:       /languages
 */

use Spotify2Go\Classes\Spotify2Go;
use Spotify2Go\Classes\Spotify2GoActivator;
use Spotify2Go\Classes\Spotify2GoDeactivator;

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

// Spotify2Go autoloader.
require_once SPOTIFY_WORDPRESS_ELEMENTOR_DIRPATH . 'includes/autoloader.php';

/**
 * The code that runs during plugin activation.
 */
function activate_spotify_wordpress_elementor() {
	Spotify2GoActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_spotify_wordpress_elementor() {
	Spotify2GoDeactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_spotify_wordpress_elementor' );
register_deactivation_hook( __FILE__, 'deactivate_spotify_wordpress_elementor' );

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_spotify_wordpress_elementor() {

	$plugin = Spotify2Go::get_instance();
	$plugin->run();

}
run_spotify_wordpress_elementor();
