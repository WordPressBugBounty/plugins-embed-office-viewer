<?php
namespace BPLEOV\Helper;

class BPLEOV_Functions {

    /**
     * Get post meta value with fallback lookup to legacy eov_ keys.
     */
    public static function meta( $id, $key, $default = null ) {
        if ( metadata_exists( 'post', $id, $key ) ) {
            $value = get_post_meta( $id, $key, true );
            if ( $value !== '' ) {
                return $value;
            }
        }

        // Fallback: If key starts with bpleov_, check legacy eov_ counterpart
        if ( strpos( $key, 'bpleov_' ) === 0 ) {
            $old_key = str_replace( 'bpleov_', 'eov_', $key );
            if ( metadata_exists( 'post', $id, $old_key ) ) {
                $value = get_post_meta( $id, $old_key, true );
                if ( $value !== '' ) {
                    return $value;
                }
            }
        }

        return $default;
    }

    /**
     * Get option value with fallback lookup to legacy eov_ options.
     */
    public static function get_option( $option, $default = false ) {
        $value = get_option( $option, null );
        if ( $value !== null ) {
            return $value;
        }

        // Fallback: If option starts with bpleov_, check legacy eov_ counterpart
        if ( strpos( $option, 'bpleov_' ) === 0 ) {
            $old_option = str_replace( 'bpleov_', 'eov_', $option );
            $value = get_option( $old_option, null );
            if ( $value !== null ) {
                return $value;
            }
        }

        return $default;
    } 
    /**
     * Wrap premium metabox fields.
     */
    public static function bpleov_lock_field( $field, $is_section = false ) {
        if ( function_exists( 'bpleov_fs' ) && bpleov_fs()->can_use_premium_code() ) {
            return $field;
        }

        // Lock the UI
        if ( isset( $field['id'] ) && $field['id'] === 'bpleov_document_source' ) {
            $field['class'] = 'bpleov-lock-field document_source_btn' . ( $is_section ? 'section' : '' );
        } else {
            $field['class'] = 'bpleov-lock-field ' . ( $is_section ? 'section' : '' );
        }

        // Force safe default (prevents DB pollution)
        if ( isset( $field['default'] ) ) {
            $field['value'] = $field['default'];
        }
        return $field;
    } 

    public static function bpleov_pro_feature_list($features) {
        $html = '
        <style>
            .pdfp-pro-showcase {
                background: linear-gradient(145deg, #ffffff, #f0f7ff);
                border: 1px solid #e1e8f0;
                border-radius: 0;
                padding: 32px;
                margin-top: 24px;
                box-shadow: 0 10px 30px rgba(20, 110, 245, 0.05);
                position: relative;
                overflow: hidden;
            }
            .pdfp-pro-showcase::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 4px;
                height: 100%;
                background: linear-gradient(to bottom, #146ef5, #00d2ff);
            }
            .pdfp-pro-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 24px;
            }
            .pdfp-pro-badge {
                background: #eef5ff;
                color: #146ef5;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                padding: 4px 12px;
                border-radius: 0;
                letter-spacing: 1px;
            }
            .pdfp-pro-showcase h4 {
                font-size: 18px;
                font-weight: 700;
                color: #001737;
                margin: 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            }
            .pdfp-pro-list {
                list-style: none !important;
                padding: 0 !important;
                margin: 0 0 32px 0 !important;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 16px;
            }
            .pdfp-pro-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                font-size: 14px;
                color: #3e5569;
                line-height: 1.5;
                margin: 0 !important;
            }
            .pdfp-pro-icon {
                background: #f0f7ff;
                color: #146ef5;
                width: 24px;
                height: 24px;
                border-radius: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                margin-top: 2px;
            }
            .pdfp-pro-icon svg {
                width: 14px;
                height: 14px;
            }
            .pdfp-pro-footer {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 20px;
                border-top: 1px solid #edf2f9;
                padding-top: 24px;
            }
            .pdfp-upgrade-btn {
                background: linear-gradient(90deg, #146ef5, #00d2ff);
                color: #fff !important;
                border: none;
                padding: 12px 28px;
                border-radius: 0;
                font-weight: 600;
                font-size: 14px;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 10px;
                transition: transform 0.2s, box-shadow 0.2s;
                box-shadow: 0 4px 15px rgba(20, 110, 245, 0.2);
            }
            .pdfp-upgrade-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(20, 110, 245, 0.3);
                color: #fff !important;
            }
            .pdfp-pro-hint {
                font-size: 13px;
                color: #8492a6;
                font-style: italic;
            }
        </style>
        <div class="pdfp-pro-showcase">
            <div class="pdfp-pro-header">
                <h4>' . __('Unlock Premium Experience', 'embed-office-viewer') . '</h4>
                <span class="pdfp-pro-badge">' . __('PRO ONLY', 'embed-office-viewer') . '</span>
            </div>
            <ul class="pdfp-pro-list">';
        foreach ($features as $feature) {
            $html .= '<li class="pdfp-pro-item">
                    <div class="pdfp-pro-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>
                    </div>
                    <span>' . esc_html($feature) . '</span>
                </li>';
        }
        $html .= '</ul>
            <div class="pdfp-pro-footer">
                <a href="' . admin_url('edit.php?post_type=officeviewer&page=embed-office-viewer-pricing') . '" class="pdfp-upgrade-btn">
                    <span>' . __('Get Premium Now', 'embed-office-viewer') . '</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <span class="pdfp-pro-hint">' . __('Trusted by over 2000+ WordPress sites.', 'embed-office-viewer') . '</span>
            </div>
        </div>';

        return array(
            'type' => 'content',
            'content' => $html
        );
    }


}
