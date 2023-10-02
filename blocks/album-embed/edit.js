import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';
const { Component } = wp.element;

import classnames from 'classnames';

import './editor.scss';

import axios from 'axios';
import querystring from 'querystring-es3';

/**
 * The edit function describes the structure of your block in the context of the
 * editor.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {JSX} Element to render.
 */
export default class albumEmbedEdit extends Component {
  componentDidMount() {
    const { attributes, setAttributes, clientId } = this.props;
    const { blockID, albumArray } = attributes;

    if ( ! blockID ) {
        setAttributes( { blockID: `album-embed-${clientId}` } );
    }

    if (0 === albumArray.length) {
      this.initAlbum();
    }

    const axiosTokenInstance = axios.create({
        baseURL: 'https://accounts.spotify.com',
        headers: {
            "Content-Type": "application/json",
        }
    });

    const axiosSpotifyInstance = axios.create({
        baseURL: 'https://api.spotify.com/v1/',
        headers: {
            "Content-Type": "application/json",
        }
    });

    axiosSpotifyInstance.interceptors.request.use((config) => {
        axiosTokenInstance.post('/api/token', querystring.stringify({
            grant_type: 'client_credentials',
            client_id: SpotifyWPEAdminVars.sfwe_options.client_id,
            client_secret: SpotifyWPEAdminVars.sfwe_options.client_secret,
        }),{
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            }
        })
            .then((response) => {
                config.headers.Authorization = `Bearer ${response.data.access_token}`;
        });

        return config;
    }, (error) => {
        return Promise.reject(error);
    });

    axiosSpotifyInstance.interceptors.response.use((response) => {
        return response;
    }, async (error) => {
        const originalRequest = error.config;
        if (error.response.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;
            try {
                const response = await axiosTokenInstance.post('/api/token', querystring.stringify({
                    grant_type: 'client_credentials',
                    client_id: SpotifyWPEAdminVars.sfwe_options.client_id,
                    client_secret: SpotifyWPEAdminVars.sfwe_options.client_secret,
                }),{
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    }
                });
                axiosSpotifyInstance.defaults.headers.common.Authorization = `Bearer ${response.data.access_token}`;
                return axiosSpotifyInstance(originalRequest);
            } catch ( _error ) {
                if (_error.response && _error.response.data) {
                    return Promise.reject(_error.response.data);
                }
                return Promise.reject(_error);
            }
        }

        if ( error.response.status === 403 && error.response.data ) {
            return Promise.reject(error.response.data);
        }
        return Promise.reject(error);
    });

    axiosSpotifyInstance.get(`albums/${SpotifyWPEAdminVars.sfwe_options.album_id}/tracks?market=US&limit=50`)
    .then((response) => {
        const { data } = response;
        const { items } = data;
        const tracks = items.map((item) => {
            return {
                id: item.id,
                name: item.name,
                external_url: item.external_urls.spotify,
                uri: item.uri,
                type: item.type,
            }
        });
        setAttributes({
            albumArray: tracks,
        });
    })
    .catch((error) => {
        console.log(error.toJSON());
    });

  }

  initAlbum() {
    const { setAttributes } = this.props;
    setAttributes({
      albumArray: [],
    });
  }

  render() {
    const { attributes, setAttributes, className } = this.props;
    const { blockID, albumArray, currentTrack } = attributes;

    const classes = classnames(className, 'album-embed');

    return (
      <>
        <div className={classes} id={blockID}>
          <div className="container">
            <SelectControl
                __nextHasNoMarginBottom
                label={__('Select Track', 'sfwe')}
                help="Selected track will be displayed in the frontend."
                value={currentTrack ? currentTrack.id : null}
                options={albumArray.map((episode) => {
                    return { label: episode.name, value: episode.id };
                })}
                onChange={ ( id ) => {
                    setAttributes({ currentTrack: albumArray.find((episode) => episode.id === id) });
                }}
            />
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
      </>
    );
  }
}
