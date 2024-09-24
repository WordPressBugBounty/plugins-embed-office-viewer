<?php
namespace EOV\Services;
use EOV\Model\AnalogSystem;

class ShortcodeFree{
    protected static $_instance = null;

    public function __construct(){
        add_shortcode( 'office_doc', [$this, 'ovp_add_shortcode'] );
    }

    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    //Lets register our shortcode
    function ovp_add_shortcode( $atts ) {
        $post_type = get_post_type($atts['id']);
        if($post_type != 'officeviewer'){
            return false;
        }
        ob_start();
        echo AnalogSystem::html(esc_html($atts['id']));

        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}

ShortcodeFree::instance();