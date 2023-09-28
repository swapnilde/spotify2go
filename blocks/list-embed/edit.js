/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
const { Component } = wp.element;
import classnames from 'classnames';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import ServerSideRenderX from './server-side-render-x'

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default class listEmbedEdit extends Component {
  componentDidMount() {
    const { attributes, setAttributes, clientId } = this.props;
    const { blockID, episodesArray } = attributes;

    if ( ! blockID ) {
        setAttributes( { blockID: `list-embed-${clientId}` } );
    }

    if (0 === episodesArray.length) {
      this.initEpisodes();
    }
  }

  initEpisodes() {
    const { setAttributes } = this.props;
    setAttributes({
      episodesArray: [
        {
          title: ''
        },
      ],
    });
  }

  render() {
    const { attributes, setAttributes, className } = this.props;
    const { blockID, episodesArray } = attributes;

    const classes = classnames(className, 'list-embed');

    return (
      <>
        <InspectorControls>
          <div className="sfwe-block__controls">
            <PanelBody title={__('Podcast Episodes List', 'sfwe')} initialOpen={true}>
            </PanelBody>
          </div>
        </InspectorControls>

        <div className={classes} id={blockID}>
          <div className="container">
            <h1>{ __('Episodes List', 'sfwe') }</h1>
          </div>
        </div>

        {/*<ServerSideRenderX block="spotify-wordpress-elementor/list-embed" attributes={attributes} />*/}
      </>
    );
  }
}
