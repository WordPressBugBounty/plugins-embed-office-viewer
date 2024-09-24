<?php
require_once ABSPATH . "wp-admin/includes/plugin-install.php";
function eov_free_plugin_assets(){
    wp_enqueue_script('plugin-install');
    wp_enqueue_script('updates');
}
add_action('init', 'eov_free_plugin_assets');
//$table->display();
if (!class_exists('Eov_Free_plugins')) {
    class Eov_Free_plugins
    {

        public function __construct()
        {
            add_action('admin_menu', array($this, 'eov_free_plugins_menu'));
        }
        public function eov_free_plugins_menu()
        {
            add_submenu_page(
                'edit.php?post_type=officeviewer',
                'bPlugins',
                'Free Plugins From bPlugins LLC',
                'manage_options',
                '/plugin-install.php?s=abuhayat&tab=search&type=author'
            );
        }
    }
}
new Eov_Free_plugins();