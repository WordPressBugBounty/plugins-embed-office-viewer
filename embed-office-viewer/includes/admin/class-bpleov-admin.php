<?php
namespace BPLEOV\Admin; 

class BPLEOV_Admin {
    protected static $_instance = null;

    public static function instance() {
        if ( self::$_instance === null ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [$this, 'bpleov_admin_scripts'] );
    }

    public function bpleov_admin_scripts( $hook ) {
        global $typenow;

        if ( $typenow == 'officeviewer' ) { 
            wp_enqueue_style( 'bpleov-admin-css', BPLEOV_PLUGIN_DIR . 'assets/css/style.css', array(), BPLEOV_VERSION );

            if ( file_exists( BPLEOV_PLUGIN_PATH . 'assets/js/script-free.js' ) ) {
                wp_enqueue_script( 'bpleov-admin-js', BPLEOV_PLUGIN_DIR . 'assets/js/script-free.js', array( 'jquery' ), BPLEOV_VERSION, true );
                $bpleov_plugin = array(
                    'plugin' => 'free',
                );
                wp_localize_script( 'bpleov-admin-js', 'eov', $bpleov_plugin );
            } 

        }

        // Dashboard Script and Style
        if ( $hook === "officeviewer_page_eov-dashboard" ) { 
            $asset_file = include BPLEOV_PLUGIN_PATH . 'build/admin-dashboard.asset.php';
            wp_enqueue_script( 'ovp-dashboard-js', BPLEOV_PLUGIN_DIR . 'build/admin-dashboard.js', array_merge( $asset_file['dependencies'], ['wp-util'] ), BPLEOV_VERSION, true );
            wp_enqueue_style( 'ovp-dashboard-css', BPLEOV_PLUGIN_DIR . 'build/admin-dashboard.css', [], BPLEOV_VERSION );
        }

    }
}
