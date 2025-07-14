<?php
if (!class_exists('EOVPlugin')) {

    class EOVPlugin {

    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action( "enqueue_block_assets", [$this, "eovBlockAssets"]);

        add_action('wp_ajax_eovPipeChecker', [$this, 'eovPipeChecker']);
        add_action('wp_ajax_nopriv_eovPipeChecker', [$this, 'eovPipeChecker']);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('rest_api_init', [$this, 'registerSettings']);
    }

    function eovPipeChecker() {
        $nonce = $_POST['_wpnonce'] ?? null;
        
        if (!wp_verify_nonce($nonce, 'wp_ajax')) {
            wp_send_json_error('Invalid Request');
        }

        wp_send_json_success([
            'isPipe' => eovIsPremium()
        ]);
	}

    function registerSettings() {
        register_setting('eovUtils', 'eovUtils', [
            'show_in_rest' => [
                'name' => 'eovUtils',
                'schema' => ['type' => 'string']
            ],
            'type' => 'string',
            'default' => wp_json_encode(['nonce' => wp_create_nonce('wp_ajax')]),
            'sanitize_callback' => 'sanitize_text_field'
        ]);
    }

    function eovBlockAssets() {
        $data = array(
            'pdfJsFilePath'  => EOV_PLUGIN_DIR . 'assets/pdfjs-new/web/viewer.html'
        );

        if ( eovIsPremium() ) {
            $api_data = get_option( 'eov_onedrive' );
            if ( ! is_array( $api_data ) ) {
                $api_data = [];
            }
            $data['credentials'] = array(
                'google' => array(
                    'api_key'        => $api_data['eov_google_apikey'] ?? '',
                    'client_id'      => $api_data['eov_google_client_id'] ?? '',
                    'project_number' => $api_data['eov_google_project_number'] ?? '',
                ),
                'dropbox' => array(
                    'app_key' => $api_data['eov_dropbox_appkey'] ?? '',
                ),
                'onedrive' => array(
                    'client_id' => $api_data['eov_onedrive_client_id'] ?? '',
                ),
            );
        }

        $frontend_data = array(
            'pdfJsFilePath'  => EOV_PLUGIN_DIR . 'assets/pdfjs-new/web/viewer.html',
        );
    
        // Pass data to JavaScript
        wp_localize_script( 'eov-embed-office-viewer-editor-script', 'eovData', $data );
        wp_localize_script( 'eov-embed-office-viewer-view-script', 'eovData', $frontend_data );
    }
    
    function init() {
        register_block_type(__DIR__. '/build/blocks/embed-office-viewer');
        wp_set_script_translations('eov-editor', 'embed-office-viewer', plugin_dir_path(__FILE__) . 'languages');
    }
    }
    new EOVPlugin();
}