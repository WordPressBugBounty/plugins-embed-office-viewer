<?php

/*
 * Plugin Name: Document Viewer for Office
 * Plugin URI:  http://bplugins.com
 * Description: You can Embed Microsoft Word, Excel And Powerpodint File in wordpress Using 'Document Viewer for Office' Plugin.
 * Version: 2.3.0
 * Author: bPlugins
 * Author URI: http://bPlugins.com
 * License: GPLv3
 * Text Domain:  eov
 * Domain Path:  /languages
 */
if ( function_exists( 'eov_fs' ) ) {
    eov_fs()->set_basename( false, __FILE__ );
} else {
    // Some Set-up
    define( 'EOV_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
    define( 'EOV_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
    define( 'EOV_VERSION', '2.3.0' );
    if ( !function_exists( 'eov_fs' ) ) {
        // Create a helper function for easy SDK access.
        function eov_fs() {
            global $eov_fs;
            if ( !isset( $eov_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $eov_fs = fs_dynamic_init( array(
                    'id'             => '7003',
                    'slug'           => 'embed-office-viewer',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_0657e65491580bc23260341c9d8e0',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                        'days'               => 7,
                        'is_require_payment' => true,
                    ),
                    'menu'           => array(
                        'slug'       => 'edit.php?post_type=officeviewer',
                        'first-path' => 'edit.php?post_type=officeviewer&page=dashboard#/dashboard',
                    ),
                    'is_live'        => true,
                ) );
            }
            return $eov_fs;
        }

        // Init Freemius.
        eov_fs();
        // Signal that SDK was initiated.
        do_action( 'eov_fs_loaded' );
    }
    if ( !function_exists( 'eovIsPremium' ) ) {
        function eovIsPremium() {
            return eov_fs()->can_use_premium_code();
        }

    }
    // Load Main Plugin Class
    require_once EOV_PLUGIN_PATH . 'inc/class-eov.php';
    // Initialize
    EOV::instance();
    // Activation Redirect For Free Version
    if ( 'embed-office-viewer/embed-office-viewer.php' === plugin_basename( __FILE__ ) ) {
        register_activation_hook( __FILE__, ['EOV', 'activation_redirect'] );
        add_action( 'admin_init', ['EOV', 'do_redirect_to_dashboard'] );
    }
}