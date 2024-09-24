<?php // Silence is golden.
define('SITE_URL', get_site_url());

// Control core classes for avoid errors
// Set a unique slug_like ID

$prefix = '_eovm_';

// Create a metabox

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
            'title' => 'Document Source',
            'type' => 'button_set',
            'options' => array(
                'library' => 'Library',
                'google' => 'Google Drive',
                'onedrive' => 'OneDrive',
                'dropbox' => 'Dropbox',
            ),
            'multiselect' => false,
            'default' => 'library',
            'attributes' => array('id' => 'document_source_btn'),
            'class' => 'document_source_btn',
            'after' => '<h3 class="doc_source_premium">Premium only - <a target="_blank" href="' . get_site_url() . '/wp-admin/edit.php?post_type=officeviewer&page=embed-office-viewer-pricing">Get Premium</a></h3>',
        ),
        array(
            'id' => 'eov_view_type',
            'title' => 'Viewer',
            'type' => 'radio',
            'options' => array(
                'gooogle' => 'Google Doc Viewer',
                'microsoft' => 'Microsoft Online Viewer',
            ),
            'default' => 'microsoft',
            // 'class' => 'hayat-readyonly',
            'dependency' => array('eov_document_source', '==', 'library'),
        ),
        array(
            'id' => 'eov_document',
            'type' => 'upload',
            'title' => 'Document',
            'subtitle' => '',
            'desc' => 'also support .pdf and .html in "View From" google',
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
            'title' => 'Width',
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
            'title' => 'Height',
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
            'title' => 'Disable Pop-out',
            'class' => 'hayat-readyonly',
        ),
        array(
            'id' => 'eov_show_name',
            'type' => 'switcher',
            'title' => 'Show File Name in Top',
            'class' => 'hayat-readyonly',
            'dependency' => ['eov_document_source', '==', 'library'],
        ),
        array(
            'id' => 'eov_download_button',
            'type' => 'switcher',
            'title' => 'Show Downlaod Button On Top',
            'class' => 'hayat-readyonly',
            'dependency' => ['eov_document_source', '==', 'library'],
        ),
        array(
            'id' => 'eov_right_click',
            'type' => 'switcher',
            'title' => 'Disable Right Click',
            'class' => 'hayat-readyonly',
        ),

    ),
));
