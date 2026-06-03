(function ($) {
    $(document).ready(function () {
        if (eov.plugin == "pro") {

        } else {
            //alert("working");
            $("[value='dropbox']").parent().attr("style", "opacity:0.4;cursor:not-allowed");
            $("[value='dropbox']")
                .parent()
                .on("click", function () {
                    this.prop("disabled", true);
                });

            //alert("working");
            $("[value='google']").parent().attr("style", "opacity:0.4;cursor:not-allowed");
            $("[value='google']")
                .parent()
                .on("click", function () {
                    this.prop("disabled", true);
                });
        }

        // copy to clipboard
        $(".eov_front_shortcode input").on("click", function (e) {
            e.preventDefault();

            let shortcode = $(this).parent().find("input")[0];
            shortcode.select();
            shortcode.setSelectionRange(0, 30);
            document.execCommand("copy");
            $(this).parent().find(".htooltip").text("Copied Successfully!");
        });

        $(".eov_front_shortcode input").on("mouseout", function () {
            $(this).parent().find(".htooltip").text("Copy To Clipboard");
        });

        var $li = $('input[value="js"]').closest('li');

        // Disable radio
        $li.find('input').prop('disabled', true);
        $li.addClass('eov-lock-field');
        // Add PRO badge
        var $text = $li.find('.csf--text');

        if (!$text.find('.eov-pro-badge').length) {
            $text.append(' <span class="eov-pro-badge">PRO</span>');
        }

    });
})(jQuery);
