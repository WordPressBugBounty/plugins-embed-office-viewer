<?php
namespace BPLEOV\Services;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use BPLEOV\Helper\BPLEOV_Functions as Functions;

class BPLEOV_ShortcodeFree {
    protected static $_instance = null;

    public function __construct() {
        add_shortcode( 'office_doc', [$this, 'ovp_add_shortcode'] );
    }

    public static function instance() {
        if ( self::$_instance === null ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function get_doc_data( $id ) {
        $width  = Functions::meta( $id, 'bpleov_document_width', ['width' => '640'] );
        $height = Functions::meta( $id, 'bpleov_document_height', ['height' => '900'] );
        return [
            'source'              => Functions::meta( $id, 'bpleov_document_source', 'library' ),
            'viewer'              => Functions::meta( $id, 'bpleov_view_type', 'gooogle' ),
            'showName'            => Functions::meta( $id, 'bpleov_show_name', false ),
            'downloadBtn'         => Functions::meta( $id, 'bpleov_download_button', false ),
            'rightClick'          => Functions::meta( $id, 'bpleov_right_click', false ),
            'isRemoveDownloadBtn' => Functions::meta( $id, 'isRemoveDownloadBtn', false ),
            'isRemoveFullScreen'  => Functions::meta( $id, 'isRemoveFullScreen', false ),
            'disablePopout'       => Functions::meta( $id, 'bpleov_disbale_popout', false ),
            'disableFullscreen'   => Functions::meta( $id, 'bpleov_disable_fullscreen', false ),
            'docFile'             => Functions::meta( $id, 'bpleov_document', true ),
            'googleDoc'           => Functions::meta( $id, 'bpleov_google_document', "" ),
            'dropboxDoc'          => Functions::meta( $id, 'bpleov_dropbox_document', "" ),
            'oneDriveDoc'         => Functions::meta( $id, 'bpleov_onedrive_document', "" ),
            'width'               => $width['width'] == '' ? '640px' : $width['width'] . 'px',
            'height'              => $height['height'] == '' ? '842px' : $height['height'] . 'px',
        ];
    }

    public function ovp_add_shortcode( $atts ) {
        if ( ! isset( $atts['id'] ) ) {
            return '';
        }
        $post_type = get_post_type( $atts['id'] );
        if ( $post_type != 'officeviewer' ) {
            return false;
        }

        $data = self::get_doc_data( esc_html( $atts['id'] ) );

        // Map shortcode/meta settings to Gutenberg block attributes
        $attributes = [
            'officeViewer' => [
                'docSource'            => $data['source'],
                'viewer'               => $data['viewer'] === 'gooogle' ? 'google' : $data['viewer'],
                'dropboxURL'           => $data['dropboxDoc'],
                'googleDriveURL'       => $data['googleDoc'],
                'onedriveURL'          => $data['oneDriveDoc'],
                'officeFile'           => [
                    'url'   => $data['docFile'],
                    'title' => basename( $data['docFile'] )
                ],
                'height'               => $data['height'],
                'width'                => $data['width'],
                'disablePopout'        => (bool) $data['disablePopout'],
                'showFileNameOnTop'    => (bool) $data['showName'],
                'showDownloadBtnOnTop' => (bool) $data['downloadBtn'],
                'disableRightClick'    => (bool) $data['rightClick'],
                'disableFullScreen'    => (bool) $data['disableFullscreen'],
                'isRemoveDownloadBtn'  => (bool) $data['isRemoveDownloadBtn'],
                'isRemoveFullScreen'   => (bool) $data['isRemoveFullScreen'],
            ]
        ];

        $block = [
            'blockName'    => 'eov/embed-office-viewer',
            'attrs'        => $attributes,
            'innerContent' => [],
        ];

        return render_block( $block );
    }
}

BPLEOV_ShortcodeFree::instance();
