<?php
namespace BPLEOV\Core;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BPLEOV_OfficeViewer {
    protected static $_instance = null;    
    protected static $post_type = 'officeviewer';

    public function __construct() {
        add_action( 'init', [$this, 'ovp_create_post_type'] );
        if ( is_admin() ) {
            add_filter( 'post_row_actions', [$this, 'eov_remove_row_actions'], 10, 2 );
            add_filter( 'gettext', [$this, 'eov_change_publish_button'], 10, 2 );

            add_filter( 'post_updated_messages', [$this, 'eov_updated_messages'] );
            add_action( 'edit_form_after_title', [$this, 'eov_shortcode_area'] ); 
            add_filter( 'manage_officeviewer_posts_columns', [$this, 'ST4_columns_head_only_officeviewer'], 10 );
            add_action( 'manage_officeviewer_posts_custom_column', [$this, 'ST4_columns_content_only_officeviewer'], 10, 2 ); 
            
            add_action( 'admin_head-post.php', [$this, 'eov_hide_publishing_actions'] );
            add_action( 'admin_head-post-new.php', [$this, 'eov_hide_publishing_actions'] );
        }
        // Add Help Pages
        add_action( 'admin_menu', [$this, 'ovp_help_pages'] );
    }

    public static function instance() {
        if ( self::$_instance === null ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function ovp_create_post_type() {
        register_post_type( 'officeviewer', array(
            'labels'              => array(
                'name'          => __( 'Office Viewer', 'embed-office-viewer' ),
                'singular_name' => __( 'Office Documents', 'embed-office-viewer' ),
                'add_new'       => __( 'Add New', 'embed-office-viewer' ),
                'add_new_item'  => __( 'Add New', 'embed-office-viewer' ),
                'edit_item'     => __( 'Edit', 'embed-office-viewer' ),
                'new_item'      => __( 'New ', 'embed-office-viewer' ),
                'view_item'     => __( 'View', 'embed-office-viewer' ),
                'search_items'  => __( 'Search', 'embed-office-viewer' ),
                'not_found'     => __( 'Sorry, we couldn\'t find the Doc file you are looking for.', 'embed-office-viewer' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'menu_position'       => 14,
            'show_in_rest'        => true,
            'menu_icon'           => BPLEOV_PLUGIN_DIR . '/assets/images/icon.png',
            'has_archive'         => false,
            'hierarchical'        => false,
            'capability_type'     => 'post',
            'rewrite'             => array(
                'slug' => 'officeviewer',
            ),
            'supports'            => array( 'title' ),
        ) );
    }

    function eov_remove_row_actions( $idtions ) {
        global $post;
        if ( isset( $post->post_type ) && $post->post_type == self::$post_type ) {
            unset( $idtions['view'] );
            unset( $idtions['inline hide-if-no-js'] );
        }
        return $idtions;
    }

    public function eov_updated_messages( $messages ) {
        $messages[self::$post_type][1] = __( 'Updated ', 'embed-office-viewer' );
        return $messages;
    }

    public function eov_change_publish_button( $translation, $text ) {
        if ( self::$post_type == get_post_type() ) {
            if ( $text == 'Publish' ) {
                return 'Save';
            }
        }
        return $translation;
    }

    public static function eov_shortcode_area() {
        global $post; 
        if ( isset( $post->post_type ) && $post->post_type === 'officeviewer' ) : ?>
            <div class="eov_shortcode">
                <code 
                    class="shortcode_copy" 
                    data-code="[office_doc id='<?php echo esc_attr( $post->ID ); ?>']">
                    [office_doc id='<?php echo esc_attr( $post->ID ); ?>']
                </code>

                <p class="shortcode_desc">
                    <?php echo esc_html__( "Copy this shortcode and paste it into your post, page, or text widget content.", "embed-office-viewer" ); ?>
                </p>
            </div>

            <script>
                document.addEventListener('click', function (e) {
                    var el = e.target.closest('.shortcode_copy');
                    if (!el) return;

                    navigator.clipboard.writeText(el.dataset.code).then(function () {
                        var original = el.textContent;
                        el.textContent = 'Copied!';

                        setTimeout(function () {
                            el.textContent = original;
                        }, 1000);
                    });
                });
            </script>
        <?php endif;
    } 

    // CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
    public function ST4_columns_head_only_officeviewer( $defaults ) {
        $defaults['shortcode'] = 'ShortCode';
        $v = $defaults['date'];
        unset( $defaults['date'] );
        $defaults['date'] = $v;
        return $defaults;
    }

    public function ST4_columns_content_only_officeviewer( $column_name, $post_id ) {
        if ( $column_name == 'shortcode' ) {
            echo '<div class="eov_front_shortcode"><input style="text-align: center;" value="[office_doc id=' . esc_attr( $post_id ) . ']" readonly><span class="htooltip">Copy To Clipboard</span></div>';
        }
    } 

    public function eov_hide_publishing_actions() {
        global $post;
        if ( isset( $post->post_type ) && $post->post_type == self::$post_type ) {
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
        }
    }

    public function ovp_help_pages() {
        add_submenu_page(
            'edit.php?post_type=officeviewer',
            'Help & Demos',
            'Help & Demos',
            'manage_options',
            'eov-dashboard',
            [$this, 'ovp_render_dashboard']
        );
    } 

    public function ovp_render_dashboard() {
        ?>
       <style>#wpcontent { padding-left: 0 !important; }</style>
       <div id='bpleovAdminDashboard'
            data-info='<?php echo esc_attr( wp_json_encode( [
                'version' => BPLEOV_VERSION
            ] ) ); ?>'
        ></div>
        <?php
    }

}

BPLEOV_OfficeViewer::instance();
