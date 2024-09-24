<?php

require_once __DIR__ . '/inc/Helper/Functions.php';
require_once __DIR__ . '/inc/Helper/DefaultArgs.php';
require_once __DIR__ . '/inc/PostType/OfficeViewer.php';
require_once __DIR__ . '/inc/Model/Style.php';
require_once __DIR__ . '/inc/Model/AnalogSystem.php';
require_once 'inc/Services/Shortcode.php';
require_once 'admin/codestar-framework/codestar-framework.php';
if ( eov_fs()->is_free_plan() ) {
    // if(true){
    require_once 'admin/codestar-framework/metabox-free.php';
    // require_once 'admin/import-meta.php';
    require_once 'admin/global/free-plugin-list.php';
    require_once 'admin/global/help-usages.php';
    require_once 'admin/global/premium-plugins.php';
    require_once __DIR__ . '/inc/Model/EnqueueAssets.php';
    require_once __DIR__ . '/inc/Services/Template.php';
}