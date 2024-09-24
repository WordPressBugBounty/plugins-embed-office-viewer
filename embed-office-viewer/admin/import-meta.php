<?php

function eov_import_meta()
{

    $docs = new WP_Query([
        'post_type' => 'officeviewer',
        'post_status' => 'any',
        'posts_per_page' => -1
    ]);

    while ($docs->have_posts()): $docs->the_post();

        $id = get_the_ID();

        $doc_file = get_post_meta($id, '_ahp_doc_file', true);

        $width = get_post_meta($id, '_ahp_width', true);

        $height = get_post_meta($id, '_ahp_height', true);

        $is_name_top = get_post_meta($id, '_ahp_name_top', true);

        $download_button = get_post_meta($id, '_ahp_download_button', true);

        $disable_rightclick = get_post_meta($id, '_ahp_disable', true);

        if ($is_name_top != '' && false == metadata_exists('post', $id, 'eov_document')) {
            update_post_meta($id, 'eov_show_name', '1');
        } else {
            if (false == metadata_exists('post', $id, 'eov_show_name')) {
                update_post_meta($id, 'eov_show_name', '0');
            }
        }

        if ($download_button != '' && false == metadata_exists('post', $id, 'eov_download_button')) {
            update_post_meta($id, 'eov_download_button', '1');
        } else {
            if (false == metadata_exists('post', $id, 'eov_download_button')) {
                update_post_meta($id, 'eov_download_button', '0');
            }
        }

        if ($disable_rightclick != '' && false == metadata_exists('post', $id, 'eov_right_click')) {
            update_post_meta($id, 'eov_right_click', '1');
        } else {
            if (false == metadata_exists('post', $id, 'eov_right_click')) {
                update_post_meta($id, 'eov_right_click', '0');
            }
        }

        if ($doc_file && false == metadata_exists('post', $id, 'eov_document')) {
            update_post_meta($id, 'eov_document', $doc_file);
        }

        if (false == metadata_exists('post', $id, 'eov_document_width')) {
            update_post_meta($id, 'eov_document_width', array('width' => $width));
        }

        if (false == metadata_exists('post', $id, 'eov_document_height')) {
            update_post_meta($id, 'eov_document_height', array('height' => $height));
        }
        if (false == metadata_exists('post', $id, 'eov_document_source')) {
            update_post_meta($id, 'eov_document_source', 'library');
        }
        if (false == metadata_exists('post', $id, 'eov_view_type')) {
            update_post_meta($id, 'eov_view_type', 'microsoft');
        }

        // print_r($doc_file);

        // echo "<br><br>width: " . $width . "<br/>height: " . $height . "<br> Name: " . $is_name_top . "<br> downlaod: " . $download_button . "<br> disbale: " . $disable_rightclick;

    endwhile;

}

//add_action('init', 'eov_import_meta');