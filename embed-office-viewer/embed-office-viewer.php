<?php

/*
 * Plugin Name: Document Viewer for Office
 * Plugin URI:  http://bplugins.com
 * Description: You can Embed Microsoft Word, Excel And Powerpodint File in wordpress Using 'Document Viewer for Office' Plugin.
 * Version: 2.2.10
 * Author: bPlugins
 * Author URI: http://bPlugins.com
 * License: GPLv3
 * Text Domain:  eov
 * Domain Path:  /languages
 */
define( 'EOV_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
define( 'EOV_VERSION', '2.2.10' );
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
                    'first-path' => 'edit.php?post_type=officeviewer&page=eov-support',
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
/**
 * this code remove due to slow query
 */
// add_action( 'init', 'eov_upgrade_function', 10, 2 );
// function eov_upgrade_function()
// {
//     if ( eov_fs()->is_free_plan() ) {
//         eov_import_meta();
//     }
// }
// load textdomain
function ovp_load_textdomain() {
    load_plugin_textdomain( 'eov', false, dirname( __FILE__ ) . "/languages" );
}

add_action( "plugins_loaded", 'ovp_load_textdomain' );
/*Some Set-up*/
require_once __DIR__ . '/upgrade.php';