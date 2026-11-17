<?php

namespace BPLEOV\Admin;

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( __NAMESPACE__ . '\BPLEOV_PromoBanner' ) ) {

    class BPLEOV_PromoBanner {
        const DISMISSED_KEY   = 'bpleov_promo_banner_dismissed_v5';
        const EXPIRATION_DATE = '2026-12-05 23:59:59';
        const COUPON_URL      = 'https://bplugins.com/products/document-viewer/pricing/?utm_source=admin&utm_medium=dashboard&utm_campaign=bfcm2026';
        private static $rendered = false;

        public function __construct() {
            add_action( 'in_admin_header',                    [ $this, 'render_banner' ], 20 );
            add_action( 'admin_notices',                      [ $this, 'render_banner' ], 9999 );
            add_action( 'all_admin_notices',                  [ $this, 'render_banner' ], 9999 );

            add_action( 'admin_head',                         [ $this, 'enqueue_styles' ] );
            add_action( 'admin_footer',                       [ $this, 'enqueue_scripts' ] );
            add_action( 'wp_ajax_bpleov_dismiss_promo_banner',  [ $this, 'ajax_dismiss' ] );

            // Add promotional offer link to plugin action links on plugins.php
            $plugin_file = defined('BPLEOV_PLUGIN_PATH') ? plugin_basename(BPLEOV_PLUGIN_PATH . 'embed-office-viewer.php') : 'embed-office-viewer/embed-office-viewer.php';
            add_filter( "plugin_action_links_{$plugin_file}",               [ $this, 'add_plugin_action_link' ], 20 );
            add_filter( "network_admin_plugin_action_links_{$plugin_file}", [ $this, 'add_plugin_action_link' ], 20 );
      
        }




        private function is_target_screen(): bool {
            global $pagenow;

            if ( isset( $_GET['bpleov_show_banner'] ) ) {
                return true;
            }
            
            if ( 'index.php' === $pagenow ) {
                return false;
            }
            
            if ( isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], ["officeviewer"] ) ) {
                return true;
            }
            if ( isset( $_GET['page'] ) ) {
                $page = sanitize_key( $_GET['page'] );
                if (
                    strpos( $page, 'officeviewer' ) !== false ||
                    strpos( $page, 'embed-office-viewer' ) !== false 
                ) {
                    return true;
                }
            }

            // 3. Screen object detection
            $screen = get_current_screen();
            if ( $screen ) {
                $id   = $screen->id ?? '';
                $base = $screen->base ?? '';
                
                if ( 'dashboard' === $id || 'dashboard' === $base ) {
                    return true;
                }

                if ( isset( $screen->post_type ) && 'ppt_viewer' === $screen->post_type ) {
                    return true;
                }

                if (
                    strpos( $id, 'officeviewer' ) !== false ||
                    strpos( $base, 'officeviewer' ) !== false ||
                    strpos( $base, 'officeviewer' ) !== false
                ) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Check if current date is past December 5, 2026
         */
        private function is_expired(): bool {
            $expiry_timestamp = strtotime( self::EXPIRATION_DATE );
            $current_time     = time();
            return ( $current_time > $expiry_timestamp );
        }

        /**
         * Check if current user has permanently dismissed the banner
         */
        private function is_dismissed(): bool {
            if ( isset( $_GET['bpleov_show_banner'] ) ) {
                return false;
            }
            return (bool) get_user_meta( get_current_user_id(), self::DISMISSED_KEY, true );
        }

        /**
         * Determine if the banner should be omitted
         */
        private function should_skip(): bool {
            if ( ! $this->is_target_screen() ) {
                return true;
            }
            
            if ( $this->is_expired() ) {
                return true;
            }
            if ( $this->is_dismissed() ) {
                return true;
            }

            return false;
        }

        /* ------------------------------------------------------------------ */
        /*  Pro CSS Styles (Design 5)                                         */
        /* ------------------------------------------------------------------ */

        public function enqueue_styles(): void {
            if ( $this->should_skip() ) { return; }
            ?>
            <style id="bpleov-promo-banner-css">
            #bpleov-promo-banner,
            .bpleov-promo-banner-wrap {
                --bpl-primary:        #2A78F6;
                --bpl-primary-hover:  #1a6660ff;
                --bpl-secondary:      #ff7a00;
                --bpl-dark:           #070127;
                --bpl-text:           #1b2e4b;
                --bpl-muted:          #475569;

                display: flex !important;
                position: relative !important;
                top: auto !important;
                left: auto !important;
                width: auto !important;
                height: auto !important;
                min-height: 84px !important;
                opacity: 1 !important;
                visibility: visible !important;
                background: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 0 !important;
                box-shadow: 0 3px 12px rgba(7, 1, 39, 0.05) !important;
                align-items: stretch !important;
                overflow: hidden !important;
                margin: 16px 20px 16px 20px !important;
                padding: 0 !important;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif !important;
                box-sizing: border-box !important;
                z-index: 9999 !important;
                animation: bpleovBannerSlide .3s ease both;
            }
            @keyframes bpleovBannerSlide {
                from { opacity: 0; transform: translateY(-6px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* Left Brand Ribbon Block */
            #bpleov-promo-banner .bpleov-brand-ribbon {
                width: 140px;
                min-width: 140px;
                background: linear-gradient(135deg, var(--bpl-primary) 0%, var(--bpl-dark) 100%);
                color: #ffffff;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 12px 10px;
                gap: 6px;
                flex-shrink: 0;
            }
            #bpleov-promo-banner .bpleov-brand-ribbon img {
                width: 38px;
                height: 38px;
                object-fit: contain;
                filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.35));
            }
            #bpleov-promo-banner .bpleov-brand-ribbon .bpleov-ribbon-title {
                font-size: 10.5px;
                font-weight: 800;
                letter-spacing: .7px;
                text-transform: uppercase;
                color: #ffffff;
                text-align: center;
                line-height: 1.2;
            }

            /* Main Content Zone */
            #bpleov-promo-banner .bpleov-content-zone {
                flex: 1;
                padding: 14px 22px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                flex-wrap: wrap;
                padding-right: 38px;
            }

            /* Left Text Stack */
            #bpleov-promo-banner .bpleov-text-stack {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }
            #bpleov-promo-banner .bpleov-headline {
                font-size: 14px;
                font-weight: 700;
                color: var(--bpl-dark);
                display: flex;
                align-items: center;
                gap: 8px;
                margin: 0;
                line-height: 1.3;
            }
            #bpleov-promo-banner .bpleov-discount-chip {
                background: #ffedd5;
                color: #c2410c;
                border: 1px solid #fed7aa;
                font-size: 11px;
                font-weight: 700;
                padding: 2px 8px;
                border-radius: 0;
                letter-spacing: .2px;
            }

            /* Feature Checklist */
            #bpleov-promo-banner .bpleov-feature-chips {
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 12px;
                color: var(--bpl-muted);
                flex-wrap: wrap;
            }
            #bpleov-promo-banner .bpleov-feature-chips span {
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }
            #bpleov-promo-banner .bpleov-feature-chips .check-icon {
                color: #10b981;
                font-weight: 800;
            }

            /* CTA Action Button */
            #bpleov-promo-banner .bpleov-btn-cta {
                background: var(--bpl-primary);
                color: #ffffff !important;
                padding: 9px 20px;
                border-radius: 0;
                font-size: 12.5px;
                font-weight: 600;
                text-decoration: none !important;
                box-shadow: 0 2px 8px rgba(20, 110, 245, 0.32);
                transition: background-color .15s ease, transform .15s ease, box-shadow .15s ease;
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            #bpleov-promo-banner .bpleov-btn-cta:hover {
                background: var(--bpl-primary-hover);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(20, 110, 245, 0.45);
            }

            /* Dismiss Button (×) */
            #bpleov-promo-banner .bpleov-banner-dismiss-btn {
                position: absolute;
                top: 8px;
                right: 8px;
                background: none;
                border: none;
                cursor: pointer;
                color: #94a3b8;
                font-size: 20px;
                line-height: 1;
                padding: 6px;
                transition: color .15s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10;
            }
            #bpleov-promo-banner .bpleov-banner-dismiss-btn:hover {
                color: var(--bpl-dark);
            }

            /* Responsive */
            @media (max-width: 960px) {
                #bpleov-promo-banner .bpleov-brand-ribbon {
                    width: 110px;
                    min-width: 110px;
                }
            }
            @media (max-width: 782px) {
                #bpleov-promo-banner,
                .bpleov-promo-banner-wrap {
                    flex-direction: column !important;
                    align-items: stretch !important;
                    margin: 12px 10px 14px 10px !important;
                }
                #bpleov-promo-banner .bpleov-brand-ribbon {
                    width: 100%;
                    flex-direction: row;
                    justify-content: flex-start;
                    padding: 8px 16px;
                }
                #bpleov-promo-banner .bpleov-content-zone {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 14px 16px;
                }
            }
            </style>
            <?php
        }

        public function enqueue_scripts(): void {
            if ( $this->should_skip() ) { return; }
            $dismiss_nonce = wp_create_nonce( 'bpleov_dismiss_promo' );
            $ajax_url      = admin_url( 'admin-ajax.php' );
            ?>
            <script id="bpleov-promo-banner-js">
            (function () {
              
                function positionBanner() {
                    var banner = document.getElementById('bpleov-promo-banner');
                    if (!banner) return;

                    var dashboardEl = document.getElementById('bpleovAdminDashboard') || document.getElementById('bpleovAnalyticsPage');
                    if (dashboardEl && banner.nextElementSibling !== dashboardEl && banner.parentElement !== dashboardEl.parentElement) {
                        dashboardEl.parentElement.insertBefore(banner, dashboardEl);
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', positionBanner);
                } else {
                    positionBanner();
                }

                // Handle permanent dismiss
                var btn = document.getElementById('bpleov-banner-close');
                if (!btn) return;
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var banner = document.getElementById('bpleov-promo-banner');
                    if (banner) {
                        banner.style.transition = 'opacity .25s ease, transform .25s ease';
                        banner.style.opacity    = '0';
                        banner.style.transform  = 'translateY(-6px)';
                        setTimeout(function() { banner.remove(); }, 260);
                    }
                    var fd = new FormData();
                    fd.append('action', 'bpleov_dismiss_promo_banner');
                    fd.append('nonce',  '<?php echo esc_js( $dismiss_nonce ); ?>');
                    fetch('<?php echo esc_url( $ajax_url ); ?>', {
                        method: 'POST',
                        credentials: 'same-origin',
                        body: fd
                    });
                });
            })();
            </script>
            <?php
        }

        public function render_banner(): void {
            if ( self::$rendered ) {
                return;
            }
            if ( $this->should_skip() ) {
                return;
            } 

            self::$rendered = true;

            $coupon_url = esc_url( self::COUPON_URL );
            $icon_url   = esc_url(BPLEOV_PLUGIN_DIR . 'assets/icon.svg');
            ?>
            <!-- Document Viewer Promo Banner (Standalone container, immune to .notice suppression) -->
            <div id="bpleov-promo-banner" class="bpleov-promo-banner-wrap">

                <!-- Left Asymmetric Brand Ribbon -->
                <div class="bpleov-brand-ribbon">
                    <img src="<?php echo $icon_url; ?>" alt="Document Viewer" />
                    <span class="bpleov-ribbon-title">Document Viewer</span>
                </div>

                <!-- Main Content Zone -->
                <div class="bpleov-content-zone">
                    <div class="bpleov-text-stack">
                        <div class="bpleov-headline">
                            <span><?php esc_html_e( "Document Viewer's Biggest Black Friday Sale is Live!", 'embed-office-viewer' ); ?></span>
                            <span class="bpleov-discount-chip"><?php esc_html_e( 'Save 45%', 'embed-office-viewer' ); ?></span>
                        </div>
                        <p style="margin-top: 0;">Get flat 45% OFF and unlock cloud intrigation, protection, and customization features at our biggest sale of the year!</p> 
                        <div class="bpleov-feature-chips">
                                  <span><strong class="check-icon">✓</strong> <?php esc_html_e('Cloud Intrigation', 'embed-office-viewer'); ?></span>
                                  <span><strong class="check-icon">✓</strong> <?php esc_html_e('Custom PDF Viewer', 'embed-office-viewer'); ?></span>
                                  <span><strong class="check-icon">✓</strong> <?php esc_html_e('Content Protection', 'embed-office-viewer'); ?></span>
                                  <span><strong class="check-icon">✓</strong> <?php esc_html_e('Advanced Viewer Controls', 'embed-office-viewer'); ?></span>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <a href="<?php echo $coupon_url; ?>" target="_blank" rel="noopener noreferrer" class="bpleov-btn-cta">
                        <?php esc_html_e( 'Grab The Deal Now →', 'embed-office-viewer' ); ?>
                    </a>
                </div>

                <!-- Dismiss Button -->
                <button id="bpleov-banner-close"
                        class="bpleov-banner-dismiss-btn"
                        title="<?php esc_attr_e( 'Dismiss', 'embed-office-viewer' ); ?>"
                        aria-label="<?php esc_attr_e( 'Dismiss banner', 'embed-office-viewer' ); ?>">
                    &times;
                </button>

            </div>
            <?php
        }

        /* ------------------------------------------------------------------ */
        /*  Plugin Action Links on plugins.php                                */
        /* ------------------------------------------------------------------ */
        public function add_plugin_action_link( array $actions ): array {
            if ( $this->is_expired() ) {
                return $actions;
            }

            $offer_link = sprintf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" style="color: #ff7a00; font-weight: 700;">%s</a>',
                esc_url( self::COUPON_URL ),
                esc_html__( 'Biggest Black Friday Sale - Save 45%', 'embed-office-viewer' )
            );

            $actions['special_offer'] = $offer_link;

            return $actions;
        }

        /* ------------------------------------------------------------------ */
        /*  AJAX Handler: Permanent Dismissal                                 */
        /* ------------------------------------------------------------------ */

        public function ajax_dismiss(): void {
            check_ajax_referer( 'bpleov_dismiss_promo', 'nonce' );
            update_user_meta( get_current_user_id(), self::DISMISSED_KEY, 1 );
            wp_send_json_success();
        }
    }

    new BPLEOV_PromoBanner();
}
