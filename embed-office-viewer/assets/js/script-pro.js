(function ($) {
    $(document).ready(function () {
        if (api.plugin == "pro") {
            if (api.dropbox.app_key == "") {
                //alert("working");
                $("[value='dropbox']").parent().attr("style", "opacity:0.4;cursor:not-allowed");
                $("[value='dropbox']")
                    .parent()
                    .on("click", function () {
                        this.prop("disabled", true);
                    });
            }
            if (api.google.api_key == "" || api.google.client_id == "" || api.google.project_number == "") {
                //alert("working");
                $("[value='google']").parent().attr("style", "opacity:0.4;cursor:not-allowed");
                $("[value='google']")
                    .parent()
                    .on("click", function () {
                        this.prop("disabled", true);
                    });
            }


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

        //Dropbox Chooser
        options = {
            success: function (files) {
                //alert("Here's the file link: " + files[0].link)
                $("#dropbox_cloud_file_url").val(files[0].link);
            },
            cancel: function () { },
            linkType: "preview", // or "direct"
            multiselect: false, // or true
            //extensions: ['.pdf', '.doc', '.docx'],
            folderselect: false, // or true
            //sizeLimit: 1024, // or any positive number
        };

        if (window.Dropbox) {
            var button = Dropbox.createChooseButton(options);
            $("#dropbox_cloud_file_url").parent().append(button);

            $("#dropbox_api_connect").on("click", function (e) {
                Dropbox.choose({
                    linkType: "preview",
                    multiselect: false,
                    folderselect: false,
                    success: function (data) { },
                });
                e.preventDefault();
            });
        }

        /* Google Picker */
        const website = "//" + window.location.hostname;
        $("<a href='#' class='button button-primary' id='eov_google_picker'>Choose From Google Drive</a>").insertAfter("#eov_google_document_url");

        $(
            "<div id='google_empty_alert'><div class='alert_text'><span>×</span><p>Please, Configure Google API From <a target='_blank' href='" +
            website +
            "/wp-admin/edit.php?post_type=officeviewer&page=eov-onedrive'>Cloud API Settings</a></p><div class='g_footer'><a href='#' class='g_ok button button-primary'>OK</a></div></div><div class='google_alert_overlay'></div></div>"
        ).insertAfter("#eov_google_picker");

        $(".google_alert_overlay").on("click", function () {
            // $(".google_empty_alert").hide();
            $("#google_empty_alert").hide();
        });
        $(".alert_text span").on("click", function () {
            $("#google_empty_alert").hide();
        });
        $(".alert_text .g_ok").on("click", function () {
            $("#google_empty_alert").hide();
        });


        const $docInput = $('.eov_document input');
        const $extInput = $('.eov_document_ext_field input');
        const $downloadBtn = $(".eov_disable_download_btn");

        function updateExtensionField() {
            const docValue = $docInput.val();
            if (!docValue) return;

            const ext = docValue.split('.').pop().toLowerCase();
            $extInput.val(ext).trigger('change');

            // Show/hide logic based on extension
            if (ext === "pptx") {
                $downloadBtn.show();
            } else {
                $downloadBtn.hide();
            }
        }

        // Trigger on load if value exists
        if ($docInput.val()) {
            updateExtensionField();
        }

        // Trigger on change/input
        $docInput.on('change input', updateExtensionField);

    });



})(jQuery);
