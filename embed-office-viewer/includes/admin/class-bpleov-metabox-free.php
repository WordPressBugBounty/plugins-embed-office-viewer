<?php
namespace BPLEOV;
use BPLEOV\Helper\BPLEOV_Functions as Functions;
use CSF;

class BPLEOV_MetaboxFree {

    public static function init()
    {
        if (class_exists('CSF')) {

            $prefix = '_bpleovm_';

            CSF::createMetabox($prefix, array(
                'title' => 'Viewer Configuration',
                'post_type' => 'officeviewer',
                'data_type' => 'unserialize',
                'class' => 'spt-main-class',
                'priority' => 'high',
                'theme' => 'light'
            ));
            // Section — Document Settings
            CSF::createSection($prefix, array(
                'title' => __('Document Settings', 'embed-office-viewer'),
                'fields' => array(
                    array(
                        'id' => 'bpleov_view_type',
                        'title' => __('Viewer Type', 'embed-office-viewer'),
                        'type' => 'radio',
                        'options' => array(
                            'gooogle' => __('Google Doc Viewer', 'embed-office-viewer'),
                            'microsoft' => __('Microsoft Online Viewer', 'embed-office-viewer') 
                        ),
                        'default' => 'microsoft',
                        'class' => 'viewer-type',
                        'desc' => __('Choose how to display your files. Microsoft works best for Word, Excel, and PowerPoint, while Google is great for PDFs.', 'embed-office-viewer'),
                    ),
                    array(
                        'id' => 'bpleov_document',
                        'type' => 'upload',
                        'title' => __('Document', 'embed-office-viewer'),
                        'subtitle' => '',
                        'desc' => __('also support .pdf and .html in "View From" google', 'embed-office-viewer'),
                        'help' => 'help',
                        'before' => '<p class="dfsp">Choose a document from Library or <b>Paste an external file link.</b></p>',
                        'after' => 'Microsoft Word, Excel And Powerpodint Doc Only, Supported File Extension: .doc, .docx, .xls, .xlsx, .ppt, .pptx ',
                        'button_title' => 'Choose File',
                        'placeholder' => 'http://',
                        'class' => 'eov_document',
                    ),
                    array(
                        'id' => 'bpleov_document_width',
                        'type' => 'dimensions',
                        'title' => __('Viewer Width', 'embed-office-viewer'),
                        'height' => false,
                        'default' => array(
                            'width' => '640',
                            'unit' => 'px',
                        ),
                        'class' => 'document-width',
                        'desc' => __('Set how wide the document viewer should be. Leave blank to use the default width (640px).', 'embed-office-viewer'),
                        'units' => array('px'),
                    ),
                    array(
                        'id' => 'bpleov_document_height',
                        'type' => 'dimensions',
                        'title' => __('Viewer Height', 'embed-office-viewer'),
                        'width' => false,
                        'class' => 'document-height',
                        'default' => array(
                            'height' => '900',
                            'unit' => 'px',
                        ),
                        'desc' => __('Set how tall the document viewer should be. Leave blank to use the default height (900px).', 'embed-office-viewer'),
                        'units' => array('px'),
                    ),
                    Functions::bpleov_pro_feature_list(array(
                        __('Import Files Directly from Google Drive & Dropbox', 'embed-office-viewer'),
                        __('Fast Custom PDF Viewer (Highly optimized for PDF files)', 'embed-office-viewer'),
                        __('Disable Right-Click to Protect your content', 'embed-office-viewer'),
                        __('Hide Download & Fullscreen Icons on Office files', 'embed-office-viewer'),
                        __('Display File Name Headers & Premium Download Buttons', 'embed-office-viewer'),
                        __('Hide the Pop-out Button to Secure Documents from Direct URL Access', 'embed-office-viewer'),
                    ))
                ),
            ));

            // Section — Security & Restrictions
            CSF::createSection($prefix, array(
                'title' => __('Security & Restrictions', 'embed-office-viewer'),
                'fields' => array(
                    Functions::bpleov_pro_feature_list(array(
                        __('Import Files Directly from Google Drive & Dropbox', 'embed-office-viewer'),
                        __('Fast Custom PDF Viewer (Highly optimized for PDF files)', 'embed-office-viewer'),
                        __('Disable Right-Click to Prevent Copying & Content Theft', 'embed-office-viewer'),
                        __('Hide Native Download & Fullscreen Icons on PowerPoint/Office files', 'embed-office-viewer'),
                        __('Display Custom File Name Headers & Premium Download Buttons', 'embed-office-viewer'),
                        __('Hide the Pop-out Button to Secure Documents from Direct URL Access', 'embed-office-viewer'),
                    ))
                ),
            ));

            // Cloud API Settings
            $prefix = 'free_cloud_settings';

            // Create options
            CSF::createOptions($prefix, array(
                'framework_title' => 'Cloud API Settings',
                'theme' => 'light',
                'menu_title' => __('Cloud API Settings', 'embed-office-viewer'),
                'menu_parent' => 'edit.php?post_type=officeviewer',
                'menu_slug' => 'bpleov-onedrive',
                'menu_type' => 'submenu',
                'menu_capability' => 'manage_options',
            ));
            CSF::createSection($prefix, array(
                'id' => 'bpleov_onedrive_tab',
                'fields' => array(
                    Functions::bpleov_pro_feature_list(array(
                        __('Import Files Directly from Google Drive & Dropbox', 'embed-office-viewer'),
                        __('Fast Custom PDF Viewer (Highly optimized for PDF files)', 'embed-office-viewer'),
                        __('Disable Right-Click to Protect your content', 'embed-office-viewer'),
                        __('Hide Download & Fullscreen Icons on Office files', 'embed-office-viewer'),
                        __('Display File Name Headers & Premium Download Buttons', 'embed-office-viewer'),
                        __('Hide the Pop-out Button to Secure Documents from Direct URL Access', 'embed-office-viewer'),
                    ))
                ),
            ));

        }
    }

}
