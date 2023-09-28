<?php
/**
 * Server-side rendering of the `list-embed block.
 *
 * @link       https://swapnild.com
 * @since      1.0.0
 * @package    Spotify_Wordpress_Elementor
 */

$settings = $attributes ?? array();

?>

<div <?php echo esc_attr( get_block_wrapper_attributes() ); ?>>
	<div class="container">
		<h1>Episodes List</h1>
	</div>
</div>
