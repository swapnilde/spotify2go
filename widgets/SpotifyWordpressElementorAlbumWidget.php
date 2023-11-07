<?php
/**
 * SpotifyWordpressElementorAlbumWidget
 *
 * @link       https://swapnild.com
 * @since      1.0.0
 *
 * @package    Spotify_Wordpress_Elementor
 * @subpackage Spotify_Wordpress_Elementor/widgets
 */

namespace SpotifyWPE\Widgets;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Utils;
use SpotifyWPE\includes\SFWEHelper;

/**
 * SpotifyWordpressElementorAlbumWidget
 *
 * @since      1.0.0
 * @package    Spotify_Wordpress_Elementor
 * @subpackage Spotify_Wordpress_Elementor/widgets
 */
class SpotifyWordpressElementorAlbumWidget extends Widget_Base {

	/**
	 * Elementor Widget Name.
	 *
	 * @inheritDoc
	 */
	public function get_name() {
		return 'spotify-wordpress-elementor-album-widget';
	}

	/**
	 * Elementor Widget Title.
	 *
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Album & Tracks', 'spotify2go' );
	}

	/**
	 * Elementor Widget Icon.
	 *
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-play';  // TODO: Change this icon for the album widget.
	}

	/**
	 * Elementor Widget Categories.
	 *
	 * @inheritDoc
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Elementor Widget Keywords.
	 *
	 * @inheritDoc
	 */
	public function get_keywords() {
		return array( 'spotify', 'album', 'track', 'embed' );
	}

	/**
	 * Elementor Widget scripts.
	 *
	 * @inheritDoc
	 */
	public function get_script_depends() {
		return array();
	}

	/**
	 * Elementor Widget styles.
	 *
	 * @inheritDoc
	 */
	public function get_style_depends() {
		return array();
	}

	/**
	 * Elementor Widget controls.
	 *
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_content_controls();
	}

	/**
	 * Elementor Widget content controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'sfwe_album_content_section',
			array(
				'label' => __( 'Spotify Album', 'spotify2go' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sfwe_album_display_type',
			array(
				'label'       => __( 'Display Type', 'spotify2go' ),
				'description' => __( 'Choose whether to display a full album or a single track.', 'spotify2go' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'full',
				'options'     => array(
					'full'   => __( 'Full Album', 'spotify2go' ),
					'single' => __( 'Single Track', 'spotify2go' ),
				),
			)
		);

		$this->add_control(
			'sfwe_album_list',
			array(
				'label'       => __( 'Select Track', 'spotify2go' ),
				'description' => __( 'Select the track you want to display.', 'spotify2go' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => SFWEHelper::get_spotify_show_tracks(),
				'condition'   => array(
					'sfwe_album_display_type' => 'single',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sfwe_album_style_section',
			array(
				'label' => __( 'Styles', 'spotify2go' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'sfwe_album_height',
			array(
				'label'      => esc_html__( 'Height', 'spotify2go' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 200,
				),
			)
		);

		$this->add_control(
			'sfwe_album_width',
			array(
				'label'      => esc_html__( 'Width', 'spotify2go' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Elementor Widget render.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	protected function render() {
		$settings     = $this->get_settings_for_display();
		$node_id      = $this->get_id();
		$is_editor    = Plugin::$instance->editor->is_edit_mode();
		$sfwe_options = get_option( 'sfwe_options' );

		$this->add_render_attribute( 'container', 'id', 'sfwe-album-' . $node_id );
		$this->add_render_attribute( 'container', 'class', array( 'sfwe-album' ) );
		$this->add_render_attribute( 'container', 'data-node-id', $node_id );

		if ( $is_editor ) {
			$this->add_render_attribute( 'container', 'class', 'sfwe-album-editor' );
		}

		$height = isset( $settings['sfwe_album_height']['size'], $settings['sfwe_album_height']['unit'] ) ? esc_attr( $settings['sfwe_album_height']['size'] . $settings['sfwe_album_height']['unit'] ) : '200';
		$width  = isset( $settings['sfwe_album_width']['size'], $settings['sfwe_album_width']['unit'] ) ? esc_attr( $settings['sfwe_album_width']['size'] . $settings['sfwe_album_width']['unit'] ) : '100%';

		?>

		<div <?php echo esc_attr( $this->get_render_attribute_string( 'container' ) ); ?>>
			<?php if ( 'full' === $settings['sfwe_album_display_type'] ) : ?>
				<iframe
					id="sfwe-show-<?php echo esc_attr( $sfwe_options['sfwe_album_id'] ?? '' ); ?>"
					frameBorder="0"
					allowFullScreen=""
					allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
					loading="lazy"
					width="<?php echo esc_attr( $width ); ?>"
					height="<?php echo esc_attr( $height ); ?>"
					src="https://open.spotify.com/embed/album/<?php echo esc_attr( $sfwe_options['sfwe_album_id'] ?? '' ); ?>">
				</iframe>
			<?php endif; ?>

			<?php if ( 'single' === $settings['sfwe_album_display_type'] && $settings['sfwe_album_list'] ) : ?>
				<iframe
					id="sfwe-episode-<?php echo esc_attr( $settings['sfwe_album_list'] ?? '' ); ?>"
					frameBorder="0"
					allowFullScreen=""
					allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
					loading="lazy"
					width="<?php echo esc_attr( $width ); ?>"
					height="<?php echo esc_attr( $height ); ?>"
					src="https://open.spotify.com/embed/track/<?php echo esc_attr( $settings['sfwe_album_list'] ?? '' ); ?>">
				</iframe>
			<?php endif; ?>

			<?php if ( $is_editor && 'single' === $settings['sfwe_album_display_type'] && empty( $settings['sfwe_album_list'] ) ) : ?>
				<div class="sfwe-album-editor-placeholder elementor-panel-alert elementor-panel-alert-info">
					<?php esc_html_e( 'Select a track to display.', 'spotify2go' ); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php
	}
}
