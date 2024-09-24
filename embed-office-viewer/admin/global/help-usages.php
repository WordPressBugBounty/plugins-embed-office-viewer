<?php

/*-------------------------------------------------------------------------------*/
// Developer page
/*-------------------------------------------------------------------------------*/
add_action('admin_menu', 'eov_support_page');

function eov_support_page()
{
    add_submenu_page('edit.php?post_type=officeviewer', 'Help & Support', 'Help & Usages', 'manage_options', 'eov-support', 'eov_support_page_callback');
}

function eov_support_page_callback()
{
    ?>
    <div class="bplugins-container">
        <div class="row">
            <div class="bplugins-features">
                <div class="col col-12">
                    <div class="bplugins-feature center">
                        <h1>Help & Usages</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="bplugins-container">
    <div class="row">
        <div class="bplugins-features">
            <div class="col col-4">
                <div class="bplugins-feature center">
                    <i class="fa fa-life-ring"></i>
                    <h3>Need any Assistance?</h3>
                    <p>Our Expert Support Team is always ready to help you out promptly.</p>
                    <a href="https://wordpress.org/support/plugin/embed-office-viewer/" target="_blank" class="button
                    button-primary">Contact Support</a>
                </div>
            </div>
            <div class="col col-4">
                <div class="bplugins-feature center">
                    <i class="fa fa-file-text"></i>
                    <h3>Looking for Documentation?</h3>
                    <p>We have detailed documentation on every aspects of HTML5 Video Player.</p>
                    <a href="https://office-viewer.bplugins.com/docs/" target="_blank" class="button button-primary">Documentation</a>
                </div>
            </div>
            <div class="col col-4">
                <div class="bplugins-feature center">
                    <i class="fa fa-thumbs-up"></i>
                    <h3>Like This Plugin?</h3>
                    <p>If you like HTML5 Video Player, please leave us a 5 &#11088; rating.</p>
                    <a href="https://wordpress.org/support/plugin/embed-office-viewer/reviews/#new-post" target="_blank" class="button
                    button-primary">Rate the Plugin</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bplugins-container">
    <div class="row">
        <div class="bplugins-features">
            <div class="col col-12">
                <div class="bplugins-feature center" style="padding:5px;">
                    <h2 style="font-size:22px;">Looking For Demo? <a href="https://office-viewer.bplugins.com/" target="_blank">Click Here</a></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bplugins-container">
    <div class="row">
        <div class="bplugins-features">
            <div class="col col-12">
                <div class="bplugins-feature center">
                    <h1><a href="https://www.youtube.com/embed/mUlMpuPMP5Q">Video Tutorials</a></h1><br/>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
}