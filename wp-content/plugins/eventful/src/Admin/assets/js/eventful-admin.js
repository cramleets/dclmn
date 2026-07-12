(function ($) {
  "use strict";

  /**
   * JavaScript code for admin dashboard.
   *
   */

  $(function () {
    /* Preloader */
    $("#eventful_view_options .eventful-metabox").css(
      "visibility",
      "hidden",
    );

    var eventful_layout_type = $(
      "#eventful-section-eventful_layouts_1 .eventful-layout-preset .eventful-siblings .eventful--sibling",
    );
    var eventful_get_layout_value = $(
      "#eventful-section-eventful_layouts_1 .eventful-layout-preset .eventful-siblings .eventful--sibling.eventful--active",
    )
      .find("input")
      .val();
    var eventful_timeline_type = $(
      "#eventful-section-eventful_layouts_1 .eventful-timeline-style .eventful-siblings .eventful--sibling",
    );
    var eventful_get_timeline_value = $(
      "#eventful-section-eventful_layouts_1 .eventful-timeline-style .eventful-siblings .eventful--sibling.eventful--active",
    )
      .find("input")
      .val();
    console.log("eventful_get_timeline_value", eventful_get_timeline_value);

    // Carousel Layout.
    if (
      eventful_get_layout_value === "carousel_layout" ||
      eventful_get_layout_value === "slider" ||
      eventful_get_layout_value === "center" ||
      eventful_get_layout_value === "ticker" ||
      eventful_get_layout_value === "multi-rows"
    ) {
      $(
        ".display_options .eventful-field-section_tab a.section_tab_0",
      ).trigger("click");
      $(
        ".display_options .eventful-field-section_tab a.section_tab_2",
      ).hide();
      $(
        "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_3",
      ).show();
      $(
        "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_4",
      ).hide();
    } else if (eventful_get_layout_value === "timeline") {
      if (eventful_get_timeline_value === "horizontal") {
        $(
          ".display_options .eventful-field-section_tab a.section_tab_2",
        ).hide();
      } else {
        $(
          ".display_options .eventful-field-section_tab a.section_tab_2",
        ).show();
      }
      $(
        "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_3",
      ).hide();
      $(
        "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_4",
      ).show();
      $(
        ".display_options .eventful-field-section_tab a.section_tab_0",
      ).trigger("click");
    } else {
      $(
        "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_3",
      ).hide();
      $(
        "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_4",
      ).hide();
      $(
        ".display_options .eventful-field-section_tab a.section_tab_2",
      ).show();
      $(
        "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_1 a",
      ).trigger("click");
    }

    if (eventful_get_layout_value === "ticker") {
      $(".carousel_controls .eventful-section_tab-nav").hide();
      $(
        ".carousel_controls .eventful-field-section_tab a.section_tab_0",
      ).trigger("click");
    } else {
      $(".carousel_controls .eventful-section_tab-nav").show();
    }

    /**
     * Show/Hide tabs on changing of layout.
     */
    $(eventful_layout_type).on("change", "input", function (event) {
      var eventful_get_layout_value = $(this).val();

      // Carousel Layout.
      if (
        eventful_get_layout_value === "carousel_layout" ||
        eventful_get_layout_value === "slider" ||
        eventful_get_layout_value === "center" ||
        eventful_get_layout_value === "ticker" ||
        eventful_get_layout_value === "multi-rows"
      ) {
        $(
          ".display_options .eventful-field-section_tab a.section_tab_0",
        ).trigger("click");
        $(
          "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_3",
        ).show();
        $(
          "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_4",
        ).hide();
        $(
          ".display_options .eventful-field-section_tab a.section_tab_2",
        ).hide();
        $(
          "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_1 a",
        ).trigger("click");
      } else if (eventful_get_layout_value === "timeline") {
        $(
          "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_3",
        ).hide();
        $(
          "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_4",
        ).show();
        var current_timeline_value = $(
          "#eventful-section-eventful_layouts_1 .eventful-timeline-style .eventful-siblings .eventful--sibling.eventful--active",
        )
          .find("input")
          .val();
        if (current_timeline_value === "horizontal") {
          $(
            ".display_options .eventful-field-section_tab a.section_tab_2",
          ).hide();
        } else {
          $(
            ".display_options .eventful-field-section_tab a.section_tab_2",
          ).show();
        }
        $(
          "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_1 a",
        ).trigger("click");
      } else {
        $(
          "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_3",
        ).hide();
        $(
          "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_4",
        ).hide();
        $(
          ".display_options .eventful-field-section_tab a.section_tab_2",
        ).show();
        $(
          "#eventful_view_options .eventful-nav ul li.menu-item_eventful_view_options_1 a",
        ).trigger("click");
      }

      if (eventful_get_layout_value === "ticker") {
        $(".carousel_controls .eventful-section_tab-nav").hide();
        $(
          ".carousel_controls .eventful-field-section_tab a.section_tab_0",
        ).trigger("click");
      } else {
        $(".carousel_controls .eventful-section_tab-nav").show();
      }
    });
    $(eventful_timeline_type).on("change", "input", function (event) {
      var eventful_get_layout_value = $(this).val();
      // Carousel Layout.
      if (eventful_get_layout_value === "horizontal") {
        $(
          ".display_options .eventful-field-section_tab a.section_tab_0",
        ).trigger("click");
        $(
          ".display_options .eventful-field-section_tab a.section_tab_2",
        ).hide();
      } else {
        $(
          ".display_options .eventful-field-section_tab a.section_tab_2",
        ).show();
      }
    });

    /* Preloader js */
    $("#eventful_view_options .eventful-metabox").css({
      backgroundImage: "none",
      visibility: "visible",
      minHeight: "auto",
    });
    $("#eventful_view_options .eventful-nav-metabox li").css("opacity", 1);

    /* Copy to clipboard */
    $(".eventful-shortcode-selectable").on("click", function (e) {
      e.preventDefault();
      eventful_copyToClipboard($(this));
      eventful_SelectText($(this));
      $(this).trigger("focus").select();
      $(".eventful-after-copy-text").animate(
        {
          opacity: 1,
          bottom: 25,
        },
        300,
      );
      setTimeout(function () {
        jQuery(".eventful-after-copy-text").animate(
          {
            opacity: 0,
          },
          200,
        );
        jQuery(".eventful-after-copy-text").animate(
          {
            bottom: 0,
          },
          0,
        );
      }, 2000);
    });
    $(".eventful_input").on("click", function (e) {
      e.preventDefault();
      /* Get the text field */
      var copyText = $(this);
      /* Select the text field */
      copyText.select();
      document.execCommand("copy");
      $(".eventful-after-copy-text").animate(
        {
          opacity: 1,
          bottom: 25,
        },
        300,
      );
      setTimeout(function () {
        jQuery(".eventful-after-copy-text").animate(
          {
            opacity: 0,
          },
          200,
        );
        jQuery(".eventful-after-copy-text").animate(
          {
            bottom: 0,
          },
          0,
        );
      }, 2000);
    });
    function eventful_copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
    }
    function eventful_SelectText(element) {
      var r = document.createRange();
      var w = element.get(0);
      r.selectNodeContents(w);
      var sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(r);
    }

    /**
     * Live Preview script.
     */

    var is_manage_preview = $("body").hasClass("post-type-eventful");
    var preview_box = $("#eventful_preview_box");

    if (is_manage_preview) {
      var preview_display = $("#eventful_live_preview").hide();
      var action = "eventful_admin_preview";
      var nonce = $("#eventful_metabox_nonceeventful_layouts").val();
    }

    $(document).on(
      "click",
      "#eventful-show-preview:contains(Hide)",
      function (e) {
        e.preventDefault();
        var _this = $(this);
        _this.html(
          '<i class="icofont-eye-open" aria-hidden="true"></i>Show Preview',
        );
        preview_box.html("");
        preview_display.hide();
      },
    );
    $(document).on(
      "click",
      "#eventful-show-preview:not(:contains(Hide))",
      function (e) {
        e.preventDefault();
        var previewJS = window.eventful_vars.previewJS;
        var _data = $("form#post").serialize();
        var _this = $(this);
        var data = {
          action: action,
          data: _data,
          ajax_nonce: nonce,
        };
        $.ajax({
          type: "POST",
          url: ajaxurl,
          data: data,
          error: function (response) {
            console.log(response);
          },
          success: function (response) {
            preview_display.show();
            preview_box.html(response);
            // Re-run the vertical timeline scroll-fill after preview injection.
            if (typeof window.eventfulTimelineRefresh === "function") {
              window.eventfulTimelineRefresh();
            }
            $.getScript(previewJS, function () {
              _this.html(
                '<i class="icofont-eye-blocked" aria-hidden="true"></i>Hide Preview',
              );
              $(document).on("keyup change", function (e) {
                e.preventDefault();
                _this.html(
                  '<i class="icofont-refresh" aria-hidden="true"></i>Update Preview',
                );
              });
              if (is_manage_preview) {
                $("html, body").animate(
                  { scrollTop: preview_display.offset().top - 50 },
                  "slow",
                );
                $(".eventful-preloader")
                  .animate({ opacity: 1 }, 600)
                  .hide();

                $(".wp-admin .eventful-pagination li").on(
                  "click",
                  function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    $(".eventful-pagination-not-work").animate(
                      {
                        opacity: 1,
                        bottom: 25,
                      },
                      300,
                    );
                    setTimeout(function () {
                      jQuery(".eventful-pagination-not-work").animate(
                        {
                          opacity: 0,
                        },
                        200,
                      );
                      jQuery(".eventful-pagination-not-work").animate(
                        {
                          bottom: 0,
                        },
                        0,
                      );
                    }, 2500);
                  },
                );
              }
            });
          },
        });
      },
    );
  });

  $(".theme_style_grid").on("change", function () {
    var str = "";
    $(".theme_style_grid option:selected").each(function () {
      str = $(this).val();
    });

    var src = $(".theme_style_grid .eventful-fieldset img").attr("src");
    var result = src.match(/theme\/grid\/(.+)\.webp/);
    src = src.replace(result[1], str);
    $(".theme_style_grid .eventful-fieldset img").attr("src", src);
  });

  $(".theme_style_list").on("change", function () {
    var str = "";
    $(".theme_style_list option:selected").each(function () {
      str = $(this).val();
    });

    var src = $(".theme_style_list .eventful-fieldset img").attr("src");
    var result = src.match(/theme\/list\/(.+)\.webp/);
    src = src.replace(result[1], str);
    $(".theme_style_list .eventful-fieldset img").attr("src", src);
  });

  $(".theme_style_minimal_list").on("change", function () {
    var str = "";
    $(".theme_style_minimal_list option:selected").each(function () {
      str = $(this).val();
    });

    var src = $(".theme_style_minimal_list .eventful-fieldset img").attr(
      "src",
    );
    var result = src.match(/theme\/minimal-list\/(.+)\.webp/);
    src = src.replace(result[1], str);
    $(".theme_style_minimal_list .eventful-fieldset img").attr("src", src);
  });
})(jQuery);
