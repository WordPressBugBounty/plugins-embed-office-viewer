(function ($) {
  $(document).ready(function () {
    if (eov.plugin == "pro") {
      if (eov.client_id == "") {
        //alert("working");
        $("[value='onedrive']").parent().attr("style", "opacity:0.4;cursor:not-allowed");
        $("[value='onedrive']")
          .parent()
          .on("click", function () {
            this.prop("disabled", true);
          });
      }
      if (eov.dropbox_appkey == "") {
        //alert("working");
        $("[value='dropbox']").parent().attr("style", "opacity:0.4;cursor:not-allowed");
        $("[value='dropbox']")
          .parent()
          .on("click", function () {
            this.prop("disabled", true);
          });
      }
      if (eov.g_apikey == "" || eov.g_client_id == "" || eov.project_number == "") {
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
      $("[value='onedrive']").parent().attr("style", "opacity:0.4;cursor:not-allowed");
      $("[value='onedrive']")
        .parent()
        .on("click", function () {
          this.prop("disabled", true);
        });

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
      cancel: function () {},
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
          success: function (data) {},
        });
        e.preventDefault();
      });
    }

    /* OneDrive Picker */
    $("<a href='#' class='button button-primary' id='eov_onedrive_picker'>Choose From OneDrive</a>").insertAfter("#eov_ondeive_file_url");
    $("#eov_onedrive_picker").on("click", function () {
      if (eov.client_id == "") {
        alert("please Set Application (Client) ID From Cloud API Settings");
      } else {
        var odOptions = {
          clientId: eov.client_id,
          action: "share",
          multiSelect: false,
          advanced: {
            createLinkParameters: { type: "embed", scope: "anonymous" },
            redirectUri: window.location.href,
          },
          success: function (files) {
            $("#eov_ondeive_file_url").val(files.value[0].permissions[0].link.webUrl);
          },
          cancel: function () {
            /* cancel handler */
          },
          error: function (error) {
            /* error handler */
          },
        };
        OneDrive.open(odOptions);
      }
    });

    /* Google Picker */
    const website = "//" + window.location.hostname;
    $("<a href='#' class='button button-primary' id='eov_google_picker' onclick='openPicker()'>Choose From Google Drive</a>").insertAfter("#eov_google_document_url");

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
  });
})(jQuery);
