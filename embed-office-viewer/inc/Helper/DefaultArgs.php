<?php
namespace EOV\Helper;

class DefaultArgs{

    public static function parseArgs($data){
        $default = self::office_doc();
        $data = wp_parse_args( $data, $default );
        return $data;
    }

    public static function office_doc(){
        return [
            'source' => '',
            'viewer' => 'gooogle',
            'showName' => false,
            'downloadBtn' => false,
            'rightClick' => false,
            'disablePopout' => false,
            'docFile' => '',
            'googleDoc' => '',
            'dropboxDoc' => '',
            'oneDriveDoc' => '',
            'width' => '640px',
            'height' => '900px',
        ];
    }
}