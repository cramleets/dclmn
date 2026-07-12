document.querySelectorAll(".event_meta_address").forEach((address) => {
  const spans = address.querySelectorAll("span");

  spans.forEach((span, index) => {
    span.textContent = span.textContent.replace(/,\s*$/, "");

    if (spans.length >= 2 && index < spans.length - 1) {
      span.textContent += ",";
    }
  });
});

jQuery(document).ready(function ($) {
  "use strict";
  $(document)
    .off("click", ".show_hide_filter")
    .on("click", ".show_hide_filter", function (e) {
      e.preventDefault();
      e.stopPropagation();

      const $filterBar = $(".eventful__filter_bar");
      $filterBar.toggleClass("active");

      const isActive = $filterBar.hasClass("active");
      const showHtml = $(this).data("show_button");
      const hideHtml = $(this).data("hide_button");

      $(this).html(isActive ? hideHtml : showHtml);
    });

  var eventful_myScript = function () {
    if ($(".ta-container").length > 0) {
      $(".ta-container").each(function () {
        var eventful_container = $(this),
          eventful_container_id = eventful_container.attr("id"),
          eventful_Wrapper_ID = "#" + eventful_container_id,
          eventful_sid = $(eventful_Wrapper_ID).data("sid"), // The Shortcode ID.
          eventfulCarousel = $(
            "#" + eventful_container_id + " .ta-eventful-carousel",
          ),
          eventfulAccordion = $("#" + eventful_container_id + " .ta-collapse"),
          eventfulfilter = $(
            "#" + eventful_container_id + ".eventful-filter-wrapper",
          ),
          ajaxurl = ta_eventful.ajaxurl,
          nonce = ta_eventful.nonce,
          eventfulCarouselDir = eventfulCarousel.attr("dir"),
          eventfulSwiper,
          eventfulCarouselData = eventfulCarousel.data("carousel");

        if (eventfulCarousel.length > 0) {
          var mobile_land = parseInt(
              eventfulCarouselData.responsive.mobile_landscape,
            ),
            tablet_size = parseInt(eventfulCarouselData.responsive.tablet),
            desktop_size = parseInt(eventfulCarouselData.responsive.desktop),
            lg_desktop_size = parseInt(
              eventfulCarouselData.responsive.lg_desktop,
            );
        }

        // Carousel Init function.
        function eventful_carousel_init() {
          // Carousel ticker mode.
          if (eventfulCarouselData.mode == "ticker") {
            var item = eventfulCarousel.find(
              ".swiper-wrapper .swiper-slide",
            ).length;
            eventfulSwiper = eventfulCarousel.find(".swiper-wrapper").bxSlider({
              mode: "horizontal",
              moveSlides: 1,
              slideMargin: eventfulCarouselData.spaceBetween,
              infiniteLoop: eventfulCarouselData.loop,
              slideWidth: eventfulCarouselData.ticker_width,
              minSlides: eventfulCarouselData.slidesPerView.mobile,
              maxSlides: eventfulCarouselData.slidesPerView.lg_desktop,
              speed: eventfulCarouselData.ticker_speed * item,
              ticker: true,
              tickerHover: eventfulCarouselData.stop_onHover,
              autoDirection: eventfulCarouselDir,
            });
          }

          // Carousel Swiper for Standard & Center mode.
          if (
            eventfulCarouselData.mode == "standard" ||
            eventfulCarouselData.mode == "center"
          ) {
            if (
              eventfulCarouselData.effect == "fade" ||
              eventfulCarouselData.effect == "cube" ||
              eventfulCarouselData.effect == "flip"
            ) {
              if ($(window).width() > lg_desktop_size) {
                var slidePerView =
                  eventfulCarouselData.slidesPerView.lg_desktop;
              } else if ($(window).width() > desktop_size) {
                var slidePerView = eventfulCarouselData.slidesPerView.desktop;
              } else if ($(window).width() > tablet_size) {
                var slidePerView = eventfulCarouselData.slidesPerView.tablet;
              } else if ($(window).width() > 0) {
                var slidePerView =
                  eventfulCarouselData.slidesPerView.mobile_landscape;
              }
              $(
                eventful_Wrapper_ID +
                  " .ta-eventful-carousel .swiper-wrapper > .eventful__carousel_item",
              )
                .css("width", 100 / slidePerView + "%")
                .removeClass("swiper-slide");
              var fade_items = $(
                eventful_Wrapper_ID +
                  " .ta-eventful-carousel .swiper-wrapper > .eventful__carousel_item",
              );
              var style =
                eventfulCarouselDir == "rtl" ? "marginLeft" : "marginRight";
              for (var i = 0; i < fade_items.length; i += slidePerView) {
                fade_items
                  .slice(i, i + slidePerView)
                  .wrapAll('<div class="swiper-slide"></div>');
                fade_items.eq(i - 1).css(style, 0);
              }
              eventfulSwiper = new Swiper(
                "#" + eventful_container_id + " .ta-eventful-carousel",
                {
                  speed: eventfulCarouselData.speed,
                  slidesPerView: 1,
                  spaceBetween: eventfulCarouselData.spaceBetween,
                  loop:
                    eventfulCarouselData.slidesRow.lg_desktop > "1" ||
                    eventfulCarouselData.slidesRow.desktop > "1" ||
                    eventfulCarouselData.slidesRow.tablet > "1" ||
                    eventfulCarouselData.slidesRow.mobile_landscape > "1" ||
                    eventfulCarouselData.slidesRow.mobile > "1"
                      ? false
                      : eventfulCarouselData.loop,
                  effect: eventfulCarouselData.effect,
                  slidesPerGroup: eventfulCarouselData.slideToScroll.mobile,
                  preloadImages: false,
                  observer: true,
                  runCallbacksOnInit: false,
                  initialSlide: 0,
                  grid: {
                    rows: eventfulCarouselData.slidesRow.mobile,
                    fill: "row",
                  },
                  autoHeight:
                    eventfulCarouselData.slidesRow.lg_desktop > "1" ||
                    eventfulCarouselData.slidesRow.desktop > "1" ||
                    eventfulCarouselData.slidesRow.tablet > "1" ||
                    eventfulCarouselData.slidesRow.mobile_landscape > "1" ||
                    eventfulCarouselData.slidesRow.mobile > "1"
                      ? false
                      : eventfulCarouselData.autoHeight,
                  simulateTouch: eventfulCarouselData.simulateTouch,
                  allowTouchMove: eventfulCarouselData.allowTouchMove,
                  mousewheel: eventfulCarouselData.slider_mouse_wheel,
                  centeredSlides: eventfulCarouselData.center_mode,
                  lazy: eventfulCarouselData.lazy,
                  pagination:
                    eventfulCarouselData.pagination == true
                      ? {
                          el: ".swiper-pagination",
                          clickable: true,
                          dynamicBullets: eventfulCarouselData.dynamicBullets,
                          renderBullet: function (index, className) {
                            if (eventfulCarouselData.bullet_types == "number") {
                              return (
                                '<span class="' +
                                className +
                                '">' +
                                (index + 1) +
                                "</span>"
                              );
                            } else {
                              return '<span class="' + className + '"></span>';
                            }
                          },
                        }
                      : false,
                  autoplay: {
                    delay: eventfulCarouselData.autoplay_speed,
                  },
                  navigation:
                    eventfulCarouselData.navigation == true
                      ? {
                          nextEl: ".eventful-button-next",
                          prevEl: ".eventful-button-prev",
                        }
                      : false,
                  fadeEffect: {
                    crossFade: true,
                  },
                  ally: {
                    enabled: eventfulCarouselData.enabled,
                    prevSlideMessage: eventfulCarouselData.prevSlideMessage,
                    nextSlideMessage: eventfulCarouselData.nextSlideMessage,
                    firstSlideMessage: eventfulCarouselData.firstSlideMessage,
                    lastSlideMessage: eventfulCarouselData.lastSlideMessage,
                    paginationBulletMessage:
                      eventfulCarouselData.paginationBulletMessage,
                  },
                  keyboard: {
                    enabled:
                      eventfulCarouselData.keyboard === "true" ? true : false,
                  },
                },
              );
            } else {
              eventfulSwiper = new Swiper(
                "#" + eventful_container_id + " .ta-eventful-carousel",
                {
                  speed: eventfulCarouselData.speed,
                  slidesPerView: eventfulCarouselData.slidesPerView.mobile,
                  spaceBetween: eventfulCarouselData.spaceBetween,
                  loop:
                    eventfulCarouselData.slidesRow.lg_desktop > "1" ||
                    eventfulCarouselData.slidesRow.desktop > "1" ||
                    eventfulCarouselData.slidesRow.tablet > "1" ||
                    eventfulCarouselData.slidesRow.mobile_landscape > "1" ||
                    eventfulCarouselData.slidesRow.mobile > "1"
                      ? false
                      : eventfulCarouselData.loop,
                  effect: eventfulCarouselData.effect,
                  slidesPerGroup: eventfulCarouselData.slideToScroll.mobile,
                  preloadImages: false,
                  observer: true,
                  runCallbacksOnInit: false,
                  initialSlide: 0,
                  grid: {
                    rows: eventfulCarouselData.slidesRow.mobile,
                    fill: "row",
                  },
                  autoHeight:
                    eventfulCarouselData.slidesRow.lg_desktop > "1" ||
                    eventfulCarouselData.slidesRow.desktop > "1" ||
                    eventfulCarouselData.slidesRow.tablet > "1" ||
                    eventfulCarouselData.slidesRow.mobile_landscape > "1" ||
                    eventfulCarouselData.slidesRow.mobile > "1"
                      ? false
                      : eventfulCarouselData.autoHeight,
                  simulateTouch: eventfulCarouselData.simulateTouch,
                  allowTouchMove: eventfulCarouselData.allowTouchMove,
                  mousewheel: eventfulCarouselData.slider_mouse_wheel,
                  centeredSlides: eventfulCarouselData.center_mode,
                  lazy: eventfulCarouselData.lazy,
                  pagination:
                    eventfulCarouselData.pagination == true
                      ? {
                          el: ".swiper-pagination",
                          clickable: true,
                          dynamicBullets: eventfulCarouselData.dynamicBullets,
                          renderBullet: function (index, className) {
                            if (eventfulCarouselData.bullet_types == "number") {
                              return (
                                '<span class="' +
                                className +
                                '">' +
                                (index + 1) +
                                "</span>"
                              );
                            } else {
                              return '<span class="' + className + '"></span>';
                            }
                          },
                        }
                      : false,
                  autoplay: {
                    delay: eventfulCarouselData.autoplay_speed,
                  },
                  navigation:
                    eventfulCarouselData.navigation == true
                      ? {
                          nextEl: ".eventful-button-next",
                          prevEl: ".eventful-button-prev",
                        }
                      : false,
                  breakpoints: {
                    [mobile_land]: {
                      slidesPerView:
                        eventfulCarouselData.slidesPerView.mobile_landscape,
                      slidesPerGroup:
                        eventfulCarouselData.slideToScroll.mobile_landscape,
                      grid: {
                        rows: eventfulCarouselData.slidesRow.mobile_landscape,
                      },
                      navigation:
                        eventfulCarouselData.navigation_mobile == true
                          ? {
                              nextEl: ".eventful-button-next",
                              prevEl: ".eventful-button-prev",
                            }
                          : false,
                      pagination:
                        eventfulCarouselData.pagination_mobile == true
                          ? {
                              el: ".swiper-pagination",
                              clickable: true,
                              dynamicBullets:
                                eventfulCarouselData.dynamicBullets,
                              renderBullet: function (index, className) {
                                if (
                                  eventfulCarouselData.bullet_types == "number"
                                ) {
                                  return (
                                    '<span class="' +
                                    className +
                                    '">' +
                                    (index + 1) +
                                    "</span>"
                                  );
                                } else {
                                  return (
                                    '<span class="' + className + '"></span>'
                                  );
                                }
                              },
                            }
                          : false,
                    },
                    [tablet_size]: {
                      slidesPerView: eventfulCarouselData.slidesPerView.tablet,
                      slidesPerGroup: eventfulCarouselData.slideToScroll.tablet,
                      grid: {
                        rows: eventfulCarouselData.slidesRow.tablet,
                      },
                    },
                    [desktop_size]: {
                      slidesPerView: eventfulCarouselData.slidesPerView.desktop,
                      slidesPerGroup:
                        eventfulCarouselData.slideToScroll.desktop,
                      grid: {
                        rows: eventfulCarouselData.slidesRow.desktop,
                      },
                    },
                    [lg_desktop_size]: {
                      slidesPerView:
                        eventfulCarouselData.slidesPerView.lg_desktop,
                      slidesPerGroup:
                        eventfulCarouselData.slideToScroll.lg_desktop,
                      grid: {
                        rows: eventfulCarouselData.slidesRow.lg_desktop,
                      },
                    },
                  },
                  fadeEffect: {
                    crossFade: true,
                  },
                  ally: {
                    enabled: eventfulCarouselData.enabled,
                    prevSlideMessage: eventfulCarouselData.prevSlideMessage,
                    nextSlideMessage: eventfulCarouselData.nextSlideMessage,
                    firstSlideMessage: eventfulCarouselData.firstSlideMessage,
                    lastSlideMessage: eventfulCarouselData.lastSlideMessage,
                    paginationBulletMessage:
                      eventfulCarouselData.paginationBulletMessage,
                  },
                  keyboard: {
                    enabled:
                      eventfulCarouselData.keyboard === "true" ? true : false,
                  },
                },
              );
            }
            if (eventfulCarouselData.autoplay === false) {
              eventfulSwiper.autoplay.stop();
            }
            if (
              eventfulCarouselData.stop_onHover &&
              eventfulCarouselData.autoplay
            ) {
              $(eventfulCarousel).on({
                mouseenter: function () {
                  eventfulSwiper.autoplay.stop();
                },
                mouseleave: function () {
                  eventfulSwiper.autoplay.start();
                },
              });
            }
            $(window).on("resize", function () {
              eventfulSwiper.update();
            });
            $(window).trigger("resize");
          }
        }
        if (eventfulCarousel.length > 0) {
          eventful_carousel_init();
        }
        $(
          ".ta-overlay.ta-eventful-post,.ta-content-box.ta-eventful-post",
          eventful_Wrapper_ID,
        ).on("mouseover", function () {
          $(this)
            .find(".eventful__item__content.animated:not(.eventful_hover)")
            .addClass("eventful_hover");
        });

        function eventful_item_same_height() {
          var maxHeight = 0;
          $(eventful_Wrapper_ID + ".eventful_same_height .item").each(
            function () {
              if ($(this).find(".ta-eventful-post").height() > maxHeight) {
                maxHeight = $(this).find(".ta-eventful-post").height();
              }
            },
          );
          $(
            eventful_Wrapper_ID + ".eventful_same_height .ta-eventful-post",
          ).height(maxHeight);
        }
        if (
          $(eventful_Wrapper_ID + ".eventful_same_height").hasClass(
            "eventful-filter-wrapper",
          )
        ) {
          eventful_item_same_height();
        }

        // Ajax Action for Live filter.
        var keyword = "",
          taxonomy = "",
          term_id = "",
          page = "",
          ta_eventful_lang = $(eventful_Wrapper_ID).data("lang"),
          eventful_hash_url = Array(),
          eventful_last_filter = "";
        function eventful_ajax_action(selected_term_list = null) {
          var _data = $("form#post").serialize();
          jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
              action: "admin_event_order",
              id: eventful_sid,
              data: _data,
              lang: ta_eventful_lang,
              keyword: keyword,
              taxonomy: taxonomy,
              term_id: term_id,
              nonce: nonce,
              term_list: selected_term_list,
            },
            success: function (data) {
              var $data = $(data);
              var $newElements = $data;

              if ($(eventful_Wrapper_ID).hasClass("eventful-masonry")) {
                var $event_wrapper = $(
                  ".eventful .ta-row",
                  eventful_Wrapper_ID,
                );
                $event_wrapper.masonry("destroy");
                $event_wrapper.html($newElements).imagesLoaded(function () {
                  $event_wrapper.masonry();
                });
              } else if (eventfulCarousel.length > 0) {
                if (eventfulCarouselData.mode == "ticker") {
                  eventfulSwiper.destroySlider();
                  $(".swiper-wrapper", eventful_Wrapper_ID).html($newElements);
                  eventful_carousel_init();
                  eventfulSwiper.reloadSlider();
                } else {
                  eventfulSwiper.destroy(true, true);
                  $(".swiper-wrapper", eventful_Wrapper_ID).html($newElements);
                  eventful_carousel_init();
                }
              } else {
                var $newElements = $data.css({
                  opacity: 0,
                });
                $(
                  ".eventful .ta-row, .eventful-timeline-grid, .ta-collapse, .table-responsive tbody",
                  eventful_Wrapper_ID,
                ).html($newElements);
                var $newElements = $data.css({
                  opacity: 1,
                });
              }
              eventful_lazyload();
            },
          });
        }

        // Pagination.
        function eventful_pagination_action(selected_term_list = null) {
          var LoadMoreText = $(
            ".ta-eventful-pagination-data",
            eventful_Wrapper_ID,
          ).data("loadmoretext");
          var EndingMessage = $(
            ".ta-eventful-pagination-data",
            eventful_Wrapper_ID,
          ).data("endingtext");
          var _data = $("form#post").serialize();
          jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
              action: "admin_event_pagination_bar_mobile",
              id: eventful_sid,
              data: _data,
              lang: ta_eventful_lang,
              keyword: keyword,
              taxonomy: taxonomy,
              term_id: term_id,
              page: page,
              nonce: nonce,
              term_list: selected_term_list,
            },
            success: function (data) {
              var $data = $(data);
              var $newElements = $data;
              $(
                ".eventful__event_pagination.eventful-on-mobile:not(.no_ajax)",
                eventful_Wrapper_ID,
              ).html($newElements);
              if (Pagination_Type == "ajax_load_more") {
                $(".eventful-load-more", eventful_Wrapper_ID)
                  .removeClass("finished")
                  .removeClass("eventful-hide")
                  .html(
                    '<button eventfulcessing="0">' + LoadMoreText + "</button>",
                  );
                if (
                  !$(".eventful__event_pagination a", eventful_Wrapper_ID)
                    .length
                ) {
                  $(".eventful-load-more", eventful_Wrapper_ID)
                    .show()
                    .html(EndingMessage);
                }
              }
            },
          });
          jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
              action: "admin_event_pagination_bar",
              id: eventful_sid,
              data: _data,
              lang: ta_eventful_lang,
              keyword: keyword,
              taxonomy: taxonomy,
              term_id: term_id,
              page: page,
              nonce: nonce,
              term_list: selected_term_list,
            },
            success: function (data) {
              var $data = $(data);
              var $newElements = $data;
              $(
                ".eventful__event_pagination.eventful-on-desktop:not(.no_ajax)",
                eventful_Wrapper_ID,
              ).html($newElements);
              if (Pagination_Type == "ajax_load_more") {
                console.log("LoadMore");

                $(".eventful-load-more", eventful_Wrapper_ID)
                  .removeClass("finished")
                  .removeClass("eventful-hide")
                  .html(
                    '<button eventfulcessing="0">' + LoadMoreText + "</button>",
                  );
              }
              if (
                !$(".eventful__event_pagination a", eventful_Wrapper_ID).length
              ) {
                $(".eventful-load-more", eventful_Wrapper_ID)
                  .show()
                  .html(EndingMessage);
              }
              eventful_lazyload();
            },
          });
        }
        // Live filter button reset on ajax call.
        function eventful_live_filter_reset(selected_term_list = null) {
          // Show reset button when filter is active
          if (keyword !== "" || selected_term_list.length > 0) {
            $(".reset_search_filter", eventful_Wrapper_ID).show();
          } else {
            $(".reset_search_filter", eventful_Wrapper_ID).hide();
          }
          var _data = $("form#post").serialize();
          jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
              action: "eventful_admin_live_filter_reset",
              data: _data,
              id: eventful_sid,
              lang: ta_eventful_lang,
              keyword: keyword,
              taxonomy: taxonomy,
              term_id: term_id,
              nonce: nonce,
              term_list: selected_term_list,
              last_filter: eventful_last_filter,
            },
            success: function (data) {
              var $data = $(data);
              var $newElements = $data.animate({
                opacity: 0.5,
              });
              $(".eventful__filter_bar", eventful_Wrapper_ID).html(
                $newElements,
              );
              $newElements.animate({
                opacity: 1,
              });
            },
          });
        }
        // Update Hash url array.
        function eventful_hash_update_arr(
          eventful_filter_keyword,
          filter_arr,
          key,
        ) {
          if (eventful_hash_url.length > 0) {
            eventful_hash_url.forEach(function (row) {
              if (
                $.inArray(eventful_filter_keyword, row.eventful_filter_keyword)
              ) {
                row[key] = eventful_filter_keyword;
              } else {
                eventful_hash_url.push(filter_arr);
              }
            });
          } else {
            eventful_hash_url.push(filter_arr);
          }
          return eventful_hash_url;
        }
        // On normal pagination go to current shortcode.
        var url_hash = window.location.search;
        if (url_hash.indexOf("paged") >= 0) {
          var s_id = /paged(\d+)/.exec(url_hash)[1];
          var spscurrent_id = document.getElementById("eventful_" + s_id);
          spscurrent_id.scrollIntoView();
        }
        // Update url.
        var selected_term_list = Array();
        // Initially hide the reset button
        $(".reset_search_filter", eventful_Wrapper_ID).hide();

        // Ajax post search.
        var is_pagination_url_change = false;
        $("input.eventful-search-field", eventful_Wrapper_ID).on(
          "keyup",
          function () {
            var that = $(this);
            keyword = that.val();
            eventful_last_filter = "keyword";
            var eventful_search_arr = { keyword, keyword };
            eventful_live_filter_reset(selected_term_list);
            eventful_hash_update_arr(keyword, eventful_search_arr, "keyword");
            eventful_ajax_action(selected_term_list);
            eventful_pagination_action();
            is_pagination_url_change = false;
            eventful_hash_update_arr("page", { page: "" }, "page");
          },
        );

        $(".reset_search_filter", eventful_Wrapper_ID).on(
          "click",
          function (e) {
            e.preventDefault();

            // Get the closest wrapper that includes both the button and input
            var $wrapper = $(this).closest(".eventful_filter_wrapper");

            // Find the search input within that wrapper
            var $input = $wrapper.find("input.eventful-search-field");

            // Reset the input value
            $input.val("");

            // Reset global keyword and other filters
            keyword = "";
            eventful_last_filter = "keyword";
            var eventful_search_arr = { keyword: "" };

            // Clear selected term list if needed
            selected_term_list = [];

            // Re-trigger filtering functions
            eventful_live_filter_reset(selected_term_list);
            eventful_hash_update_arr("", eventful_search_arr, "keyword");
            eventful_ajax_action(selected_term_list);
            eventful_pagination_action();

            // Reset pagination
            is_pagination_url_change = false;
            eventful_hash_update_arr("page", { page: "" }, "page");
          },
        );

        function eventful_filter_push(myarr, item) {
          var found = false;
          var i = 0;
          while (i < myarr.length) {
            if (myarr[i] === item) {
              // Do the logic (delete or replace)
              found = true;
              break;
            }
            i++;
          }
          // Add the item
          if (!found) myarr.push(item);
          return myarr;
        }

        // Pre Filter Init.
        var tax_list = Array();
        $(".eventful__filter_by", eventful_Wrapper_ID)
          .find("option:selected, input:radio:checked")
          .each(function () {
            term_id = $(this).val();
            taxonomy = $(this).data("taxonomy");
            var selected_tax_length = selected_term_list.length;
            if (selected_tax_length > 0) {
              var selected_tax =
                selected_term_list[selected_tax_length - 1]["taxonomy"];
              selected_term_list.map(function (person) {
                if (person.taxonomy === taxonomy) {
                  person.term_id = term_id;
                }
              });
              // if ($.inArray(taxonomy, tax_list) == -1) {
              selected_term_list.push({
                taxonomy,
                term_id,
              });
              //  }
              if (
                selected_term_list[selected_tax_length - 1]["term_id"] ==
                  "all" &&
                selected_tax === taxonomy
              ) {
                tax_list = tax_list.filter(function (val) {
                  return val !== taxonomy;
                });
              } else {
                tax_list = eventful_filter_push(tax_list, taxonomy);
              }
              selected_term_list = $.grep(selected_term_list, function (e, i) {
                return e.term_id != "all";
              });
            } else {
              selected_term_list.push({
                taxonomy,
                term_id,
              });
              selected_term_list = $.grep(selected_term_list, function (e, i) {
                return e.term_id != "all";
              });
              tax_list = Array(taxonomy);
            }
          });
        $("input.eventful-search-field", eventful_Wrapper_ID).each(function () {
          var that;
          var that = $(this);
          keyword = that.val();
        });
        $(".eventful__filter_by-checkbox", eventful_Wrapper_ID).each(
          function () {
            var current_tax = $(this).data("taxonomy");
            var term_ids = "";
            $(this)
              .find("input[name='" + current_tax + "']:checkbox:checked")
              .each(function () {
                term_ids += $(this).val() + ",";
                taxonomy = $(this).data("taxonomy");
              });
            term_id = term_ids.replace(/,(?=\s*$)/, "");
            selected_term_list.map(function (person) {
              if (person.taxonomy === current_tax) {
                person.term_id = term_id;
              }
            });
            selected_term_list.push({
              taxonomy,
              term_id,
            });
          },
        );
        selected_term_list = $.grep(selected_term_list, function (e, i) {
          return e.term_id.length;
        });
        selected_term_list = selected_term_list
          .map(JSON.stringify)
          .reverse() // convert to JSON string the array content, then reverse it (to check from end to beginning)
          .filter(function (item, index, arr) {
            return arr.indexOf(item, index + 1) === -1;
          }) // check if there is any occurence of the item in whole array
          .reverse()
          .map(JSON.parse);
        // Filter by checkbox.
        $(eventful_Wrapper_ID).on(
          "change",
          ".eventful__filter_by-checkbox",
          function (e) {
            e.stopPropagation();
            e.preventDefault();
            $(".eventful__filter_by-checkbox", eventful_Wrapper_ID).each(
              function () {
                var current_tax = $(this).data("taxonomy");
                var term_ids = "";
                $(this)
                  .find("input[name='" + current_tax + "']:checkbox:checked")
                  .each(function () {
                    term_ids += $(this).val() + ",";
                    taxonomy = $(this).data("taxonomy");
                  });
                term_id = term_ids.replace(/,(?=\s*$)/, "");
                selected_term_list.map(function (person) {
                  if (person.taxonomy === current_tax) {
                    person.term_id = term_id;
                  }
                });
                selected_term_list.push({
                  taxonomy,
                  term_id,
                });
              },
            );
            selected_term_list = $.grep(selected_term_list, function (e, i) {
              return e.term_id.length;
            });
            selected_term_list = selected_term_list
              .map(JSON.stringify)
              .reverse() // convert to JSON string the array content, then reverse it (to check from end to beginning)
              .filter(function (item, index, arr) {
                return arr.indexOf(item, index + 1) === -1;
              }) // check if there is any occurence of the item in whole array
              .reverse()
              .map(JSON.parse);
            var term_ids = "";
            $(this)
              .find("input:checkbox:checked")
              .each(function () {
                term_ids += $(this).val() + ",";
                taxonomy = $(this).data("taxonomy");
              });
            taxonomy = $(this).data("taxonomy");
            term_id = term_ids.replace(/,(?=\s*$)/, "");
            if (term_id.length > 0) {
              eventful_last_filter = taxonomy;
            } else {
              eventful_last_filter = eventful_last_filter;
            }
            eventful_hash_update_arr("page", { page: "" }, "page");
            eventful_live_filter_reset(selected_term_list);
            eventful_ajax_action(selected_term_list);
            eventful_pagination_action(selected_term_list);
          },
        );

        // Filter by taxonomy.
        $(eventful_Wrapper_ID).on(
          "change",
          ".eventful__filter_by",
          function (e) {
            e.stopPropagation();
            e.preventDefault();
            $(this)
              .find("option:selected, input:radio:checked")
              .each(function () {
                term_id = $(this).val();
                taxonomy = $(this).data("taxonomy");
                var selected_tax_length = selected_term_list.length;
                if (selected_tax_length > 0) {
                  var selected_tax =
                    selected_term_list[selected_tax_length - 1]["taxonomy"];
                  selected_term_list.map(function (person) {
                    if (person.taxonomy === taxonomy) {
                      person.term_id = term_id;
                    }
                  });
                  // if ($.inArray(taxonomy, tax_list) == -1) {
                  selected_term_list.push({
                    taxonomy,
                    term_id,
                  });
                  //  }
                  if (
                    selected_term_list[selected_tax_length - 1]["term_id"] ==
                      "all" &&
                    selected_tax === taxonomy
                  ) {
                    tax_list = tax_list.filter(function (val) {
                      return val !== taxonomy;
                    });
                  } else {
                    tax_list = eventful_filter_push(tax_list, taxonomy);
                  }
                  selected_term_list = $.grep(
                    selected_term_list,
                    function (e, i) {
                      return e.term_id != "all";
                    },
                  );
                } else {
                  selected_term_list.push({
                    taxonomy,
                    term_id,
                  });
                  tax_list = Array(taxonomy);
                }
              });
            if (term_id == "all") {
              eventful_last_filter = eventful_last_filter;
            } else {
              eventful_last_filter = taxonomy;
            }
            selected_term_list = selected_term_list
              .map(JSON.stringify)
              .reverse()
              .filter(function (item, index, selected_term_list) {
                return selected_term_list.indexOf(item, index + 1) === -1;
              })
              .reverse()
              .map(JSON.parse);
            eventful_hash_update_arr("page", { page: "" }, "page");
            eventful_live_filter_reset(selected_term_list);
            eventful_ajax_action(selected_term_list);
            eventful_pagination_action(selected_term_list);
          },
        );

        // Event order asc/dsc.
        $(eventful_Wrapper_ID).on("change", ".eventful__order", function (e) {
          var that;
          $(this)
            .find("option:selected, input:radio:checked")
            .each(function () {
              that = $(this);
              order = $(this).val();
            });
          var order_arr = { order, order };
          eventful_hash_update_arr(order, order_arr, "order");
          eventful_ajax_action();
          eventful_pagination_action();
          eventful_hash_update_arr("page", { page: "" }, "page");
        });

        /**
         * Grid masonry.
         */
        if ($(eventful_Wrapper_ID).hasClass("eventful-masonry")) {
          $(eventful_Wrapper_ID + " .ta-masonry_layout").masonry({
            itemSelector: ".ta-masonry-item",
          });
        }

        /**
         * The Pagination effects.
         *
         * The effects for pagination to work for both mobile and other screens.
         */
        var Pagination_Type = $(eventful_Wrapper_ID).data("pagination");
        if ($(window).width() <= 480) {
          var Pagination_Type =
            $(eventful_Wrapper_ID).data("pagination_mobile");
        }

        /**
         * Ajax load on click and Infinite scroll.
         */
        if (Pagination_Type == "ajax_load_more") {
          $(eventful_Wrapper_ID).each(function () {
            var EndingMessage = $(this)
              .find(".ta-eventful-pagination-data")
              .data("endingtext");
            var LoadMoreText = $(this)
              .find(".ta-eventful-pagination-data")
              .data("loadmoretext");
            if (
              !$(this)
                .find(".eventful-load-more")
                .hasClass("eventful-load-more-initialize")
            ) {
              if (
                $(".eventful__event_pagination a", eventful_Wrapper_ID).length
              ) {
                $(".eventful__event_pagination", eventful_Wrapper_ID)
                  .eq(0)
                  .before(
                    '<div class="eventful-load-more"><button eventfulcessing="0">' +
                      LoadMoreText +
                      "</button></div>",
                  );
              }
              $(".eventful__event_pagination", eventful_Wrapper_ID).addClass(
                "eventful-hide",
              );
              $(this)
                .find(".eventful-load-more")
                .addClass("eventful-load-more-initialize");
              $(this).on("click", ".eventful-load-more button", function (e) {
                e.preventDefault();
                if (
                  $(
                    ".eventful__event_pagination a.active:not(.eventful_next, .eventful_prev)",
                    eventful_Wrapper_ID,
                  ).length
                ) {
                  $(".eventful-load-more button").attr("eventfulcessing", 1);
                  var current_page = parseInt(
                    $(
                      ".eventful__event_pagination a.active:not(.eventful_next, .eventful_prev)",
                      eventful_Wrapper_ID,
                    ).data("page"),
                  );
                  current_page = current_page + 1;
                  $(".eventful-load-more", eventful_Wrapper_ID).hide();
                  $(".eventful__event_pagination", eventful_Wrapper_ID)
                    .eq(0)
                    .before(
                      '<div class="eventful-infinite-scroll-loader"><svg width="44" height="44" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" stroke="#444"><g fill="none" fill-rule="evenodd" stroke-width="2"><circle cx="22" cy="22" r="1"><animate attributeName="r" begin="0s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite" /> <animate attributeName="stroke-opacity" begin="0s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite" /> </circle> <circle cx="22" cy="22" r="1"> <animate attributeName="r" begin="-0.9s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite" /> <animate attributeName="stroke-opacity" begin="-0.9s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite"/></circle></g></svg></div>',
                    );
                  var totalPage = $(
                    ".eventful__event_pagination.eventful-on-desktop.infinite_scroll a:not(.eventful_next, .eventful_prev), .eventful__event_pagination.eventful-on-desktop.ajax_load_more a:not(.eventful_next, .eventful_prev)",
                    eventful_Wrapper_ID,
                  ).length;
                  if ($(window).width() <= 480) {
                    var totalPage = $(
                      ".eventful__event_pagination.eventful-on-mobile.infinite_scroll a:not(.eventful_next, .eventful_prev), .eventful__event_pagination.ajax_load_more.eventful-on-mobile  a:not(.eventful_next, .eventful_prev)",
                      eventful_Wrapper_ID,
                    ).length;
                  }
                  page = current_page;
                  var _data = $("form#post").serialize();
                  $.ajax({
                    url: ajaxurl,
                    type: "post",
                    data: {
                      page: page,
                      id: eventful_sid,
                      data: _data,
                      action: "admin_event_grid_ajax",
                      lang: ta_eventful_lang,
                      keyword: keyword,
                      taxonomy: taxonomy,
                      term_id: term_id,
                      term_list: selected_term_list,
                      nonce: nonce,
                    },
                    error: function (response) {},
                    success: function (response) {
                      var $data = $(response);
                      var $newElements = $data;
                      if ($(eventful_Wrapper_ID).hasClass("eventful-masonry")) {
                        var $event_wrapper = $(
                          ".eventful .ta-row",
                          eventful_Wrapper_ID,
                        );
                        $event_wrapper.masonry("destroy");
                        $event_wrapper
                          .append($newElements)
                          .imagesLoaded(function () {
                            $event_wrapper.masonry();
                          });
                      } else {
                        var $newElements = $data.css({
                          opacity: 0,
                        });
                        $(
                          ".eventful .ta-row, .eventful-timeline-grid, .ta-collapse, .table-responsive tbody",
                          eventful_Wrapper_ID,
                        ).append($newElements);
                        if (eventfulAccordion.length > 0) {
                          eventfulAccordion.accordion("refresh");
                          if (accordion_mode === "multi-open") {
                            eventfulAccordion
                              .find(".eventful-collapse-header")
                              .next()
                              .slideDown();
                            eventfulAccordion
                              .find(".eventful-collapse-header .fa")
                              .removeClass("fa-plus")
                              .addClass("fa-minus");
                          }
                        }
                        var $newElements = $data.css({
                          opacity: 1,
                        });
                      }
                      $(".page-numbers", eventful_Wrapper_ID).removeClass(
                        "active",
                      );
                      $(".page-numbers", eventful_Wrapper_ID).each(function () {
                        $(
                          ".eventful__event_pagination a[data-page=" +
                            page +
                            "]",
                          eventful_Wrapper_ID,
                        ).addClass("active");
                      });
                      $(
                        ".eventful-infinite-scroll-loader",
                        eventful_Wrapper_ID,
                      ).remove();
                      if (Pagination_Type == "ajax_load_more") {
                        $(".eventful-load-more").show();
                      }
                      $(".eventful-load-more button").attr(
                        "eventfulcessing",
                        0,
                      );
                      if (totalPage == current_page) {
                        $(".eventful-load-more", eventful_Wrapper_ID)
                          .addClass("finished")
                          .removeClass("eventful-hide");
                        $(".eventful-load-more", eventful_Wrapper_ID)
                          .show()
                          .html(EndingMessage);
                      }
                      eventful_lazyload();
                    },
                  });
                } else {
                  $(".eventful-load-more", eventful_Wrapper_ID)
                    .addClass("finished")
                    .removeClass("eventful-hide");
                  $(".eventful-load-more", eventful_Wrapper_ID)
                    .show()
                    .html(EndingMessage);
                }
              });
            }
          });
        }

        /* This code added for divi-builder ticker mode compatibility. */
        if (
          eventfulCarousel.length > 0 &&
          eventfulCarouselData.mode == "ticker"
        ) {
          $(".ta-eventful-carousel img").removeAttr("loading");
          $(window).on("load", function () {
            $(".ta-eventful-carousel").each(function () {
              var thisdfd = $(this);
              var thisCSS = thisdfd.attr("style");
              $(this).removeAttr("style");
              setTimeout(function () {
                thisdfd.attr("style", thisCSS);
              }, 0);
            });
          });
        }

        /* Preloader js */
        $(document).ready(function () {
          $(".eventful-preloader", eventful_Wrapper_ID).css({
            backgroundImage: "none",
            visibility: "hidden",
          });
        });
        // This function added for eventful-Lazyload.
        function eventful_lazyload() {
          var $is_find = $(".eventful__item--thumbnail img").hasClass(
            "eventful-lazyload",
          );
          if ($is_find) {
            $("img.eventful-lazyload")
              .eventful_lazyload({ effect: "fadeIn", effectTime: 2000 })
              .removeClass("eventful-lazyload")
              .addClass("eventful-lazyloaded");
          }
        }
        eventful_lazyload();
      });
    }
  };
  eventful_myScript();
});

// ─── Same-height equalization (admin live preview) ──────────────────────────
// Equalizes `.eventful__item` heights row-by-row inside every
// `.ta-eventful-section.eventful_same_height` section. Each section is bound
// independently with its own image-load + resize debounce so re-renders of the
// preview can't double-bind handlers.
jQuery(function ($) {
  "use strict";

  function eventful_waitImagesLoaded(imgs, cb) {
    if (!imgs || !imgs.length) { cb(); return; }
    var remaining = imgs.length;
    var done = function () { if (--remaining <= 0) cb(); };
    imgs.forEach(function (img) {
      if (img.complete && img.naturalWidth > 0) {
        done();
      } else if (typeof MutationObserver !== "undefined" &&
                 (!img.getAttribute("src") || /^data:/.test(img.getAttribute("src")))) {
        var observer = new MutationObserver(function () {
          var src = img.getAttribute("src");
          if (src && !/^data:/.test(src)) {
            observer.disconnect();
            if (img.complete && img.naturalWidth > 0) {
              done();
            } else {
              $(img).one("load.esh error.esh", done);
            }
          }
        });
        observer.observe(img, { attributes: true, attributeFilter: ["src"] });
      } else if (!img.complete) {
        $(img).one("load.esh error.esh", done);
      }
    });
  }

  function eventful_equalize_section($section) {
    var $items = $section.find(".eventful__item:visible");
    if (!$items.length) return;
    setSameHeight($items, $section[0]);
  }

  function same_height_item() {
    $(".ta-eventful-section.eventful_same_height").each(function () {
      var $section = $(this);
      if ($section.data("eventfulSameHeightBound")) {
        eventful_equalize_section($section);
        return;
      }
      $section.data("eventfulSameHeightBound", true);

      var runFor = function () { eventful_equalize_section($section); };

      eventful_waitImagesLoaded(
        $section.find("img").toArray(),
        runFor
      );

      var sid = $section.data("sid") || $section.attr("id") || Math.random();
      var resizeTimer;
      $(window).on("resize.eventful_same_height_" + sid, function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(runFor, 150);
      });
    });
  }
  same_height_item();
  $(window).on("load.eventful_same_height", function () {
    same_height_item();
  });

  function setSameHeight(items, scope) {
    var $items = $(items);
    $items.css("height", "");

    var scopeTop = 0;
    if (scope && scope.getBoundingClientRect) {
      scopeTop = scope.getBoundingClientRect().top;
    }

    var rows = [];
    var tolerance = 4;
    $items.each(function () {
      var top = this.getBoundingClientRect().top - scopeTop;
      var placed = false;
      for (var i = 0; i < rows.length; i++) {
        if (Math.abs(rows[i].top - top) <= tolerance) {
          rows[i].items.push(this);
          placed = true;
          break;
        }
      }
      if (!placed) rows.push({ top: top, items: [this] });
    });

    rows.forEach(function (row) {
      var maxH = 0;
      row.items.forEach(function (el) {
        var h = el.getBoundingClientRect().height;
        if (h > maxH) maxH = h;
      });
      if (maxH > 0) {
        row.items.forEach(function (el) {
          $(el).outerHeight(maxH);
        });
      }
    });
  }
});

// ─── Vertical timeline scroll-driven progress fill ──────────────────────────
// Updates `--timeline-progress` on each vertical `.layout_timeline` so the
// `::after` overlay (defined in _timeline.scss) fills from top to bottom
// as the user scrolls. The horizontal timeline (.swiper-container) is skipped.
// When inside `.eventful_preview_box` (admin live preview), scroll and viewport
// dimensions are taken from that container, not from window.
(function () {
  "use strict";

  var timelines = []; // array of { el, container } — container null for front-end
  var ticking = false;

  function getScrollContainer(el) {
    var node = el.parentElement;
    while (node) {
      if (node.classList && node.classList.contains("eventful_preview_box")) {
        return node;
      }
      node = node.parentElement;
    }
    return null;
  }

  function computeProgress(el, container) {
    var rect = el.getBoundingClientRect();
    var relTop, vh;

    if (container) {
      var cRect = container.getBoundingClientRect();
      relTop = rect.top - cRect.top;
      vh = container.clientHeight;
    } else {
      relTop = rect.top;
      vh = window.innerHeight || document.documentElement.clientHeight;
    }

    var startOffset = vh * 0.75;
    var endOffset = vh * 0.25;
    var travel = rect.height + startOffset - endOffset;
    if (travel <= 0) return 1;

    var progress = (startOffset - relTop) / travel;
    if (progress < 0) return 0;
    if (progress > 1) return 1;
    return progress;
  }

  function update() {
    ticking = false;
    for (var i = 0; i < timelines.length; i++) {
      var item = timelines[i];
      var p = computeProgress(item.el, item.container);
      var px = p * item.el.offsetHeight;
      item.el.style.setProperty("--timeline-progress", px.toFixed(2) + "px");
    }
  }

  function request() {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(update);
  }

  function refresh() {
    var nodes = document.querySelectorAll(
      ".layout_timeline:not(.swiper-container)"
    );
    timelines = Array.prototype.slice.call(nodes).map(function (el) {
      return { el: el, container: getScrollContainer(el) };
    });
    if (timelines.length) update();
  }

  function init() {
    refresh();
    if (!timelines.length) return;

    var attached = [];
    for (var i = 0; i < timelines.length; i++) {
      var c = timelines[i].container;
      if (c && attached.indexOf(c) === -1) {
        attached.push(c);
        c.addEventListener("scroll", request, { passive: true });
      }
    }
    window.addEventListener("scroll", request, { passive: true });
    window.addEventListener("resize", request);
    window.addEventListener("load", function () {
      refresh();
      request();
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();

// ─── Horizontal timeline Swiper-driven progress fill ────────────────────────
// Updates `--timeline-progress-x` on each `.horizontal_timeline .layout_timeline`
// based on the underlying Swiper instance's `progress` (0..1).
(function () {
  "use strict";

  function update(container) {
    if (!container || !container.swiper || typeof container.swiper.progress !== "number") {
      return;
    }
    var p = container.swiper.progress;
    if (p < 0) p = 0;
    if (p > 1) p = 1;
    container.style.setProperty("--timeline-progress-x", (p * 100).toFixed(2) + "%");
  }

  function attach(container) {
    var tries = 0;
    var maxTries = 40;
    (function poll() {
      if (container.swiper && typeof container.swiper.on === "function") {
        container.swiper.on("progress", function () { update(container); });
        container.swiper.on("slideChange", function () { update(container); });
        container.swiper.on("resize", function () { update(container); });
        update(container);
        return;
      }
      if (++tries < maxTries) {
        setTimeout(poll, 50);
      }
    })();
  }

  function init() {
    var nodes = document.querySelectorAll(
      ".horizontal_timeline .layout_timeline.swiper-container"
    );
    if (!nodes.length) return;
    Array.prototype.forEach.call(nodes, function (container) {
      container.style.setProperty("--timeline-progress-x", "0%");
      attach(container);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
