<?php
/**
 * SpotifyWordpressElementorPodcastWidget
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
 * SpotifyWordpressElementorPodcastWidget
 *
 * @since      1.0.0
 * @package    Spotify_Wordpress_Elementor
 * @subpackage Spotify_Wordpress_Elementor/widgets
 */
class SpotifyWordpressElementorPodcastWidget extends Widget_Base {

	/**
	 * Elementor Widget Name.
	 *
	 * @inheritDoc
	 */
	public function get_name() {
		return 'spotify-wordpress-elementor-podcast-widget';
	}

	/**
	 * Elementor Widget Title.
	 *
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Podcast & Episodes', 'sfwe' );
	}

	/**
	 * Elementor Widget Icon.
	 *
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-video-playlist';  // TODO: Change this icon for the podcast widget.
	}

	/**
	 * Elementor Widget Categories.
	 *
	 * @inheritDoc
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Elementor Widget Keywords.
	 *
	 * @inheritDoc
	 */
	public function get_keywords() {
		return array( 'spotify', 'podcast', 'show', 'embed' );
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
			'sfwe_podcast_content_section',
			array(
				'label' => __( 'Content', 'sfwe' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sfwe_podcast_display_type',
			array(
				'label'       => __( 'Display Type', 'sfwe' ),
				'description' => __( 'Choose whether to display a full show or a single episode.', 'sfwe' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'full',
				'options'     => array(
					'full'   => __( 'Full Show', 'sfwe' ),
					'single' => __( 'Single Episode', 'sfwe' ),
				),
			)
		);

		$this->add_control(
			'sfwe_podcast_list',
			array(
				'label'       => __( 'Select Podcast', 'sfwe' ),
				'description' => __( 'Select the podcast you want to display.', 'sfwe' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => SFWEHelper::get_spotify_all_episodes(),
				'condition'   => array(
					'sfwe_podcast_display_type' => 'single',
				),
			)
		);

		$this->add_control(
			'sfwe_podcast_video',
			array(
				'label'        => __( 'Is this a video episode?', 'sfwe' ),
				'description'  => __( 'Enable this option if this episode is a video.', 'sfwe' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Video', 'sfwe' ),
				'label_off'    => __( 'Audio', 'sfwe' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'sfwe_podcast_display_type' => 'single',
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

		$this->add_render_attribute( 'container', 'id', 'sfwe-podcast-' . $node_id );
		$this->add_render_attribute( 'container', 'class', array( 'sfwe-podcast' ) );
		$this->add_render_attribute( 'container', 'data-node-id', $node_id );

		if ( $is_editor ) {
			$this->add_render_attribute( 'container', 'class', 'sfwe-podcast-editor' );
		}

		$video = 'yes' === $settings['sfwe_podcast_video'] ? 'video' : '';

		?>

		<div <?php echo esc_attr( $this->get_render_attribute_string( 'container' ) ); ?>>
			<?php if ( 'full' === $settings['sfwe_podcast_display_type'] ) : ?>
				<iframe
					id="sfwe-show-<?php echo esc_attr( $sfwe_options['sfwe_show_id'] ?? '' ); ?>"
					frameBorder="0"
					allowFullScreen=""
					allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
					loading="lazy" width="100%" height="200"
					src="https://open.spotify.com/embed/show/<?php echo esc_attr( $sfwe_options['sfwe_show_id'] ?? '' ); ?>">
				</iframe>
			<?php endif; ?>

			<?php if ( 'single' === $settings['sfwe_podcast_display_type'] && $settings['sfwe_podcast_list'] ) : ?>
				<iframe
					id="sfwe-episode-<?php echo esc_attr( $settings['sfwe_podcast_list'] ?? '' ); ?>"
					frameBorder="0"
					allowFullScreen=""
					allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
					loading="lazy" width="100%" height="200"
					src="https://open.spotify.com/embed/episode/<?php echo esc_attr( $settings['sfwe_podcast_list'] ?? '' ); ?>/<?php echo esc_attr( $video ); ?>">
				</iframe>
			<?php endif; ?>

			<?php if ( $is_editor && 'single' === $settings['sfwe_podcast_display_type'] && empty( $settings['sfwe_podcast_list'] ) ) : ?>
				<div class="sfwe-podcast-editor-placeholder elementor-panel-alert elementor-panel-alert-info">
					<?php esc_html_e( 'Select a podcast to display.', 'sfwe' ); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php
	}
}
