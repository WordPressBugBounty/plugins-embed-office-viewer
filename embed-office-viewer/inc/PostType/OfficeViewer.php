<?php
namespace EOV\PostType;

class Shortcode{
    protected static $_instance = null;    
    protected static $post_type = 'officeviewer';

    public function __construct(){
        add_action( 'init', [$this, 'ovp_create_post_type'] );
        if(is_admin()){
            add_filter( 'post_row_actions',[$this, 'eov_remove_row_actions'], 10, 2 );
            add_filter( 'gettext', [$this, 'eov_change_publish_button'], 10, 2 );

            add_filter('post_updated_messages', [$this, 'eov_updated_messages']);
            add_action('edit_form_after_title', [$this, 'eov_shortcode_area']);
            add_filter( 'admin_footer_text', [$this, 'eov_admin_footer']);	 
            add_filter('manage_officeviewer_posts_columns', [$this, 'ST4_columns_head_only_officeviewer'], 10);
            add_action('manage_officeviewer_posts_custom_column', [$this, 'ST4_columns_content_only_officeviewer'], 10, 2);
            add_action( 'add_meta_boxes', [$this, 'eov_myplugin_add_meta_box'] );
            
            add_action('admin_head-post.php', [$this, 'eov_hide_publishing_actions']);
            add_action('admin_head-post-new.php', [$this, 'eov_hide_publishing_actions']);

        }
    }

    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function ovp_create_post_type(){
        register_post_type( 'officeviewer', array(
            'labels'              => array(
            'name'          => __( 'Office Viewer' ),
            'singular_name' => __( 'Office Documents' ),
            'add_new'       => __( 'Add New' ),
            'add_new_item'  => __( 'Add New' ),
            'edit_item'     => __( 'Edit' ),
            'new_item'      => __( 'New ' ),
            'view_item'     => __( 'View' ),
            'search_items'  => __( 'Search' ),
            'not_found'     => __( 'Sorry, we couldn\'t find the Doc file you are looking for.' ),
        ),
            'public'              => false,
            'show_ui'             => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'menu_position'       => 14,
            'show_in_rest'        => true,
            'menu_icon'           => EOV_PLUGIN_DIR . '/img/icon.png',
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
        if( $post->post_type == self::$post_type ) {
            unset( $idtions['view'] );
            unset( $idtions['inline hide-if-no-js'] );
        }
        return $idtions;
    }

    function eov_updated_messages( $messages ) {
        $messages[self::$post_type][1] = __('Updated ');
        return $messages;
    }

    function eov_change_publish_button( $translation, $text ) {
        if ( self::$post_type == get_post_type())
        if ( $text == 'Publish' )
            return 'Save';
        
        return $translation;
    }

    function eov_shortcode_area(){
        global $post;
        if($post->post_type== self::$post_type){ ?>
        <div class="eov_playlist_shortcode">
            <div class="shortcode-heading">
                <div class="icon"><span class="dashicons dashicons-video-alt3"></span> <?php _e("Embed Office Viewer", "eov"); ?></div>
                <div class="text"> <a href="https://bplugins.com/support/" target="_blank"><?php _e("Supports", "eov"); ?></a></div>
            </div>
            <div class="shortcode-left">
                <h3><?php _e("Shortcode", "eov") ?></h3>
                <p><?php _e("Copy and paste this shortcode into your posts, pages and widget content:", "eov") ?></p>
                <div class="shortcode" selectable>[office_doc id='<?php echo esc_attr($post->ID); ?>']</div>
            </div>
            <div class="shortcode-right">
                <h3><?php _e("Template Include", "eov") ?></h3>
                <p><?php _e("Copy and paste the PHP code into your template file:", "eov"); ?></p>
                <div class="shortcode">&lt;?php echo do_shortcode('[office_doc id="<?php echo esc_attr($post->ID); ?>"]');
                ?&gt;</div>
            </div>
        </div>
        <?php   
        }
    }

    function eov_admin_footer( $text ) {
        if ( self::$post_type == get_post_type() ) {
            $url = 'https://wordpress.org/support/plugin/embed-office-viewer/reviews/?filter=5#new-post';
            $text = sprintf( __( 'If you like <strong>Embed Office Viewer</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'h5ap-domain' ), $url );
        }
    
        return $text;
    }

    // CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
    function ST4_columns_head_only_officeviewer($defaults) {
        $defaults['shortcode'] = 'ShortCode';
        $v = $defaults['date'];
        unset($defaults['date']);
        $defaults['date'] = $v;
        return $defaults;
    }

    function ST4_columns_content_only_officeviewer($column_name, $post_id) {
        if ($column_name == 'shortcode') {
            echo '<div class="eov_front_shortcode"><input style="text-align: center; border: none; outline: none; background-color: #1e8cbe; color: #fff; padding: 4px 10px; border-radius: 3px;" value="[office_doc id=' . esc_attr($post_id) . ']" ><span class="htooltip">Copy To Clipboard</span></div>';
        }
    }

    
    function eov_myplugin_add_meta_box() {
        add_meta_box(
            'donation',
            __( 'Support Office Viewer', 'ovp' ),
            [$this, 'eov_review_callback'],
            'officeviewer',
            'side'
        );
    }

    function eov_review_callback(){
        echo  'If you like <strong>Embed Office Viewer </strong> Plugin, please leave us a <a href="https://wordpress.org/support/plugin/embed-office-viewer/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733; rating.</a> Your Review is very important to us as it helps us to grow more.

        <p>Need some improvement ? <a href="mailto:abuhayat.du@gmail.com">Please let me know </a> how can i improve the Plugin.</p>' ;
    }

    function eov_hide_publishing_actions(){
        global $post;
        if($post->post_type == self::$post_type){
            echo  '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ' ;
        }
    }

}

Shortcode::instance();