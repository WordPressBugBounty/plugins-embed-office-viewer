<?php // Silence is golden.
define('SITE_URL', get_site_url());

// Control core classes for avoid errors

// Create a metabox
if (class_exists('CSF')) {
    
    // ReadOnly Fields STyle
    add_action('admin_footer', function () {
        ?>
            <style>
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
    });

    $prefix = '_eovm_';

    CSF::createMetabox($prefix, array(
        'title' => 'Viewer Setup',
        // 'class' => 'spt-main-class',
        'post_type' => 'officeviewer',
        'data_type' => 'unserialize',
        'class' => 'spt-main-class',
        'priority' => 'high',
    ));
    
    $onedrive_icon = plugin_dir_url(__FILE__) . 'admin/skydrive.png';
    
    // Create a section
    CSF::createSection($prefix, array(
        'title' => '',
        'fields' => array(
            array(
                'id' => 'eov_document_source',
                'title' => __('Document Source', 'eov'),
                'type' => 'button_set',
                'options' => array(
                    'library' => __('Library', 'eov'),
                    'google' => __('Google Drive' , 'eov'),
                    'dropbox' => __('Dropbox' , 'eov'),
                ),
                'multiselect' => false,
                'default' => 'library',
                'attributes' => array('id' => 'document_source_btn'),
                'class' => 'document_source_btn',
                'after' => '<h3 class="doc_source_premium">Premium only - <a target="_blank" href="' . get_site_url() . '/wp-admin/edit.php?post_type=officeviewer&page=embed-office-viewer-pricing">Get Premium</a></h3>',
            ),
            array(
                'id' => 'eov_view_type',
                'title' => __('Viewer', 'eov'),
                'type' => 'radio',
                'options' => array(
                    'gooogle' => __('Google Doc Viewer', 'eov'),
                    'microsoft' => 'Microsoft Online Viewer',
                ),
                'default' => 'microsoft',
                // 'class' => 'hayat-readyonly',
                'dependency' => array('eov_document_source', '==', 'library'),
            ),
            array(
                'id' => 'eov_document',
                'type' => 'upload',
                'title' => __('Document', 'eov'),
                'subtitle' => '',
                'desc' => __('also support .pdf and .html in "View From" google', 'eov'),
                'help' => 'help',
                'before' => '<p class="dfsp">Choose a document from Library or <b>Paste an external file link.</b></p>',
                'after' => 'Microsoft Word, Excel And Powerpodint Doc Only, Supported File Extension: .doc, .docx, .xls, .xlsx, .ppt, .pptx ',
                'button_title' => 'Choose File',
                'placeholder' => 'http://',
                'dependency' => array('eov_document_source', '==', 'library'),
            ),
            array(
                'id' => 'eov_document_width',
                'type' => 'dimensions',
                'title' => __('Width', 'eov'),
                'height' => false,
                'default' => array(
                    'width' => '640',
                    'unit' => 'px',
                ),
                'class' => 'document-width',
                'desc' => '<p>Leave blank if you want to use viewer default width (640px)</p>',
                'units' => array('px'),
            ),
            array(
                'id' => 'eov_document_height',
                'type' => 'dimensions',
                'title' => __('Height', 'eov'),
                'width' => false,
                'class' => 'document-height',
                'default' => array(
                    'height' => '900',
                    'unit' => 'px',
                ),
                'desc' => '<p>Leave blank if you want to use viewer default height (900px)</p>',
                'units' => array('px'),
            ),
            array(
                'id' => 'eov_disbale_popout',
                'type' => 'switcher',
                'title' => __('Disable Pop-out', 'eov'),
                'class' => 'hayat-readyonly',
            ),
            array(
                'id' => 'eov_show_name',
                'type' => 'switcher',
                'title' => __('Show File Name in Top', 'eov'),
                'class' => 'hayat-readyonly',
                'dependency' => ['eov_document_source', '==', 'library'],
            ),
            array(
                'id' => 'eov_download_button',
                'type' => 'switcher',
                'title' => __('Show Downlaod Button On Top', 'eov'),
                'class' => 'hayat-readyonly',
                'dependency' => ['eov_document_source', '==', 'library'],
            ),
            array(
                'id' => 'eov_right_click',
                'type' => 'switcher',
                'title' => __('Disable Right Click', 'eov'),
                'class' => 'hayat-readyonly',
            ),
        ),
    ));
    
}