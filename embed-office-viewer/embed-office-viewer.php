<?php
/*
 * Plugin Name: Document Viewer for Office
 * Plugin URI:  http://bplugins.com
 * Description: You can Embed Microsoft Word, Excel And Powerpodint File in wordpress Using 'Document Viewer for Office' Plugin.
 * Version: 2.4.0
 * Author: bPlugins
 * Author URI: http://bPlugins.com
 * License: GPLv3
 * Text Domain:  embed-office-viewer
 * Domain Path:  /languages
 * @fs_premium_only  /includes/admin/class-bpleov-license-activation.php, /includes/admin/class-bpleov-metabox-pro.php, /includes/public/class-bpleov-shortcode-pro.php, /includes/public/class-bpleov-template-pro.php, /includes/Model/class-bpleov-global-changes-pro.php, /assets/pdfjs-new/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'bpleov_fs' ) ) {
    bpleov_fs()->set_basename( true, __FILE__ );
} else {
    // Setup Constants
    define( 'BPLEOV_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
    define( 'BPLEOV_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
    define( 'BPLEOV_VERSION', '2.4.0' );
    define( 'BPLEOV_HAS_PRO', 'embed-office-viewer-premium/embed-office-viewer.php' === plugin_basename( __FILE__ ) );

    if ( ! function_exists( 'bpleov_fs' ) ) {
        // Create a helper function for easy SDK access.
        function bpleov_fs() {
            global $bpleov_fs;
            
            if ( ! isset( $bpleov_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $bpleov_fs = fs_dynamic_init( array(
                    'id'             => '7003',
                    'slug'           => 'embed-office-viewer',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_0657e65491580bc23260341c9d8e0',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'menu'           => array(
                        'slug'       => 'edit.php?post_type=officeviewer',
                        'first-path' => 'edit.php?post_type=officeviewer&page=eov-dashboard#/welcome',
                        'support'    => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            
            return $bpleov_fs;
        }
        
        // Init Freemius.
        bpleov_fs();
        // Signal that SDK was initiated.
        do_action( 'bpleov_fs_loaded' );
    } 
    
    // Load Composer Autoloader
    if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
        require_once dirname( __FILE__ ) . '/vendor/autoload.php';
    }

    // Load Main Plugin Class
    require_once BPLEOV_PLUGIN_PATH . 'includes/class-bpleov.php';
    // Initialize
    BPLEOV\BPLEOV_Main::instance();

}
