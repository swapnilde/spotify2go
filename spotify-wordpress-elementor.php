<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SPOTIFY_WORDPRESS_ELEMENTOR_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-spotify-wordpress-elementor-activator.php
 */
function activate_spotify_wordpress_elementor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spotify-wordpress-elementor-activator.php';
	Spotify_Wordpress_Elementor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-spotify-wordpress-elementor-deactivator.php
 */
function deactivate_spotify_wordpress_elementor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spotify-wordpress-elementor-deactivator.php';
	Spotify_Wordpress_Elementor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_spotify_wordpress_elementor' );
register_deactivation_hook( __FILE__, 'deactivate_spotify_wordpress_elementor' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-spotify-wordpress-elementor.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_spotify_wordpress_elementor() {

	$plugin = new Spotify_Wordpress_Elementor();
	$plugin->run();

}
run_spotify_wordpress_elementor();
