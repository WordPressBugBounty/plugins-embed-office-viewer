<?php
namespace EOV\Model;

class GlobalChanges{
    protected static $_instance = null;

    public function __construct(){

    }

    public static function instance(){
        if(self::$_instance === null){
            self::$_instaance = new self();
        }
        return self::$_instance();
    }

}

GlobalChanges::instance();