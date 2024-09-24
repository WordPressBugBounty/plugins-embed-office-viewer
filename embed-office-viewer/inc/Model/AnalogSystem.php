<?php
namespace EOV\Model;
use EOV\Helper\DefaultArgs;
use EOV\Helper\Functions;
use EOV\Services\Template;

class AnalogSystem{

    public static function html($id){
        $data = DefaultArgs::parseArgs(self::office_doc($id));
        return Template::html($data);
    }

    public static function office_doc($id){
        $width = Functions::meta( $id, 'eov_document_width', ['width' => '640']);
        $height =  Functions::meta( $id, 'eov_document_height', ['height' => '900'] );
        return [
            'source' => Functions::meta( $id, 'eov_document_source', 'library' ),
            'viewer' => Functions::meta( $id, 'eov_view_type', 'gooogle' ),
            'showName' => Functions::meta( $id, 'eov_show_name', true ),
            'downloadBtn' => Functions::meta( $id, 'eov_download_button', true ),
            'rightClick' => Functions::meta( $id, 'eov_right_click', true ),
            'disablePopout' => Functions::meta( $id, 'eov_disbale_popout', true ),
            'disableFullscreen' => Functions::meta( $id, 'eov_disable_fullscreen', false ),
            'docFile' => Functions::meta( $id, 'eov_document', true ),
            'googleDoc' => Functions::meta( $id, 'eov_google_document', true ),
            'dropboxDoc' => Functions::meta( $id, 'eov_dropbox_document', true ),
            'oneDriveDoc' => Functions::meta( $id, 'eov_onedrive_document', true ),
            'width' => $width['width'] == '' ? '640px' : $width['width'].'px',
            'height' => $height['height'] == '' ? '842px' : $height['height'].'px',
        ];

       
    }
}