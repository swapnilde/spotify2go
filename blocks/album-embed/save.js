import classnames from 'classnames';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {JSX} Element to render.
 */
export default function albumEmbedSave(props) {
  const { className, attributes } = props;
  const { blockID, currentTrack } = attributes;

  const classes = classnames(className, 'album-embed');

  return (
    <div className={classes} id={blockID}>
      <div className="container">
          <div className={"sfwe-episode"}>
              {currentTrack && currentTrack.id && (
                  <iframe
                      id={"sfwe-episode-" + currentTrack.id}
                      frameBorder="0"
                      allowFullScreen=""
                      allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                      loading="lazy" width="100%" height="200"
                      src={"https://open.spotify.com/embed/track/" + currentTrack.id}>
                  </iframe>
              )}
          </div>
      </div>
    </div>
  );
}
