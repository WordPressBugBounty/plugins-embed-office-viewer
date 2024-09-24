<?php
namespace EOV\Model;

class EnqueueAssets{
    protected static $_instance = null;

    public function __construct(){
        add_action('wp_enqueue_scripts',[$this, 'publicAssets']);
        add_action('admin_enqueue_scripts',[$this, 'adminAssets']);
        
        add_action( 'admin_head', [$this, 'eov_add_simple_css'] );
    }

    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function publicAssets(){
        wp_enqueue_script(
            'eov',
            EOV_PLUGIN_DIR .'assets/js/script.js',
            array(),
            ''
        );
    }

    public function adminAssets($screen){
        $_screen = get_current_screen();
        global  $post ;
        
        if ( !empty($post) && $post->post_type == 'officeviewer' || 'officeviewer_page_eov-onedrive' == $screen ) {
            wp_enqueue_script(
                'eov-admin-js',
                EOV_PLUGIN_DIR . 'admin/js/script-free.js',
                array( 'jquery' ),
                ''
            );

            $eov = array(
                'plugin' => 'free',
            );
        
            wp_localize_script( 'eov-admin-js', 'eov', $eov );
            
        }
        
        if ($_screen->post_type == 'officeviewer' || $screen == 'officeviewer_page_eov-support' || $screen == 'officeviewer_page_eov-plugins-from-bplugins' ) {
            wp_enqueue_style( 'eov-admin-css', EOV_PLUGIN_DIR . 'admin/css/style.css' );
        }
    }

    function eov_add_simple_css(){
        ?>
    <style>
    /*Readonly Fields*/
    
    .hayat-readyonly {
        filter:invert(1);
    }
    
    .hayat-readyonly:hover:after {
        content: "This option is available in the Pro Version only.";
        position: absolute;
        top: 0;
        width: 95%;
        height: 100%;
        vertical-align: middle;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #2196F3;
    }
    </style>
    
    <?php 
    }

}

EnqueueAssets::instance();