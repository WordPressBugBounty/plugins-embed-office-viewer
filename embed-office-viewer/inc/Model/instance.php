<?php
namespace EOV\Services;

class Shortcode{
    protected static $_instance = null;

    public function __construct(){

    }

    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

}

Shortcode::instance();