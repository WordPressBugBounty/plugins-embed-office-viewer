<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'BPLEOVPlugin' ) ) {

    class BPLEOVPlugin {

        public function __construct() {
            add_action( 'init', [$this, 'init'] );  
        }  
        
        public function init() {
            register_block_type( BPLEOV_PLUGIN_PATH . 'build/blocks/embed-office-viewer' );
            wp_set_script_translations( 'eov-editor', 'embed-office-viewer', BPLEOV_PLUGIN_PATH . 'languages' );
        }
    }
}