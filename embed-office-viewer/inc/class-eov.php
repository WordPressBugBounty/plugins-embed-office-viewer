<?php

class EOV {

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('init', [$this, 'init'], 0);
        add_action('plugins_loaded', [$this, 'load_dependencies']);
        add_action('plugins_loaded', [__CLASS__, 'load_textdomain']);
        add_action('admin_enqueue_scripts', [$this, 'eov_admin_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'eov_public_scripts']);
    }

    public function init() {
        if (!class_exists('CSF')) {
            require_once EOV_PLUGIN_PATH . 'admin/codestar-framework/codestar-framework.php';
        }

        if (function_exists('eov_fs') && eov_fs()->can_use_premium_code__premium_only() && file_exists(EOV_PLUGIN_PATH . 'premium-files/metabox-pro.php')) {
            require_once EOV_PLUGIN_PATH . 'premium-files/metabox-pro.php';
        }

        if (function_exists('eov_fs') && eov_fs()->is_free_plan() && file_exists(EOV_PLUGIN_PATH . 'admin/codestar-framework/metabox-free.php')) {
            require_once EOV_PLUGIN_PATH . 'admin/codestar-framework/metabox-free.php';
        }
    }

    public function load_dependencies() {
        require_once EOV_PLUGIN_PATH . 'block.php';
        require_once EOV_PLUGIN_PATH . 'inc/Helper/Functions.php';
        require_once EOV_PLUGIN_PATH . 'inc/Helper/DefaultArgs.php';
        require_once EOV_PLUGIN_PATH . 'inc/PostType/OfficeViewer.php';
        require_once EOV_PLUGIN_PATH . 'inc/Model/Style.php';
        require_once EOV_PLUGIN_PATH . 'inc/Model/AnalogSystem.php';
        require_once EOV_PLUGIN_PATH . 'inc/Services/Shortcode.php';

        if (function_exists('eov_fs') && eov_fs()->is_free_plan()) {
            require_once EOV_PLUGIN_PATH . 'admin/global/free-plugin-list.php';
            // require_once EOV_PLUGIN_PATH . 'admin/global/premium-plugins.php'; // Old Stuff
            // require_once EOV_PLUGIN_PATH . 'inc/Model/EnqueueAssets.php'; // Old Stuff
            require_once EOV_PLUGIN_PATH . 'inc/Services/Template.php';
        }

        if (function_exists('eov_fs') && eov_fs()->can_use_premium_code__premium_only()) {
            require_once EOV_PLUGIN_PATH . 'premium-files/shortcode-pro.php';
            // require_once EOV_PLUGIN_PATH . 'premium-files/EnqueueAssets.php'; // Old Stuff
            require_once EOV_PLUGIN_PATH . 'premium-files/GlobalChangesPro.php';
            require_once EOV_PLUGIN_PATH . 'admin/import-meta.php';
            require_once EOV_PLUGIN_PATH . 'premium-files/Template.php';
        }

    }

    public static function load_textdomain() {
        load_plugin_textdomain('eov', false, dirname(__FILE__) . '/languages');
    }

    public function eov_admin_scripts($hook) {
        global $typenow;

        if ($typenow == 'officeviewer') {
            $dropbox_app_key = get_option( 'eov_onedrive' ) ?? [];

            echo '<script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="' . esc_attr( $dropbox_app_key['eov_dropbox_appkey'] ?? "" ) . '"></script>';

            wp_enqueue_style( 'eov-admin-css', EOV_PLUGIN_DIR . 'admin/css/style.css' );

            if (function_exists('eov_fs') && eov_fs()->is_free_plan() && file_exists(EOV_PLUGIN_PATH . 'assets/js/script-free.js')) {
                wp_enqueue_script( 'eov-admin-js', EOV_PLUGIN_DIR . 'assets/js/script-free.js', array( 'jquery' ), "" );
                $eov_plugin = array(
                    'plugin' => 'free',
                );
                wp_localize_script( 'eov-admin-js', 'eov', $eov_plugin );
            }

            if (function_exists('eov_fs') && eov_fs()->can_use_premium_code__premium_only() && file_exists(EOV_PLUGIN_PATH . 'assets/js/script-pro.js')) {
                wp_enqueue_script('eov-google-js', EOV_PLUGIN_DIR . 'assets/js/google.js', array( 'eov-google-picker-js' ), null, true );
                wp_enqueue_script( 'eov-google-picker-js', 'https://apis.google.com/js/api.js?onload=onApiLoad', array(), null,  true );
                wp_enqueue_script( 'eov-admin-pro-js', EOV_PLUGIN_DIR . 'assets/js/script-pro.js', array( 'jquery' ), "" );

                $api_data = array();
                $api_form_data = get_option( 'eov_onedrive' );
                
                if ( is_array($api_form_data) && (array_key_exists('eov_google_apikey', $api_form_data) || array_key_exists('eov_google_client_id', $api_form_data) || array_key_exists('eov_google_project_number', $api_form_data) || array_key_exists('eov_dropbox_appkey', $api_form_data)) ) {
                    $api_data = array(
                        'google'     => array(
                            'api_key'        => $api_form_data['eov_google_apikey'],
                            'client_id'      => $api_form_data['eov_google_client_id'],
                            'project_number' => $api_form_data['eov_google_project_number']
                        ), 
                        'dropbox'     => array(
                            'app_key' => $api_form_data['eov_dropbox_appkey'],
                        ),
                        'plugin'      => 'pro',
                    );
                } else {
                    $api_data = array(
                        'plugin' => 'free',
                    );
                }
    
                wp_localize_script( 'eov-google-js', 'api', $api_data );
                wp_localize_script( 'eov-admin-pro-js', 'api', $api_data );
            }

        }

        // Dashboard Script and Style
        if ($hook === "officeviewer_page_dashboard") {
            wp_enqueue_script('ovp-dashboard-js', EOV_PLUGIN_DIR . 'build/admin-help.js', ['react', 'react-dom'], EOV_VERSION, true);
            wp_enqueue_script('ovp-fs-js', EOV_PLUGIN_DIR . 'assets/js/fs.js', [], EOV_VERSION, true);
            wp_enqueue_style('ovp-dashboard-css', EOV_PLUGIN_DIR . 'build/admin-help.css', [], EOV_VERSION);
        }

    }

    public function eov_public_scripts() {
        wp_enqueue_script( 'eov', EOV_PLUGIN_DIR .'assets/js/script.js', array(), '' );
    }

    public static function activation_redirect() {
        add_option('stp_do_activation_redirect', true);
    }

    public static function do_redirect_to_dashboard() {
        if (get_option('stp_do_activation_redirect')) {
            delete_option('stp_do_activation_redirect');
            if (!is_network_admin() && !isset($_GET['activate-multi'])) {
                wp_safe_redirect(admin_url('edit.php?post_type=officeviewer&page=dashboard#/dashboard'));
                exit;
            }
        }
    }

}
