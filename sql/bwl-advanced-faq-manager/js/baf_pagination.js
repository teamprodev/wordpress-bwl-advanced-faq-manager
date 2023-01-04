;(function ($) {
  "use strict"

  $(function () {
    function baf_get_url_params(url) {
      // get query string from url (optional) or window
      var queryString = url ? url.split("?")[1] : window.location.search.slice(1)

      // we'll store the parameters here
      var obj = {}

      // if query string exists
      if (queryString) {
        // stuff after # is not part of query string, so get rid of it
        queryString = queryString.split("#")[0]

        // split our query string into its component parts
        var arr = queryString.split("&")

        for (var i = 0; i < arr.length; i++) {
          // separate the keys and the values
          var a = arr[i].split("=")

          // in case params look like: list[]=thing1&list[]=thing2
          var paramNum = undefined
          var paramName = a[0].replace(/\[\d*\]/, function (v) {
            paramNum = v.slice(1, -1)
            return ""
          })

          // set parameter value (use 'true' if empty)
          var paramValue = typeof a[1] === "undefined" ? true : a[1]

          // (optional) keep case consistent
          paramName = paramName.toLowerCase()
          paramValue = paramValue.toLowerCase()

          // if parameter name already exists
          if (obj[paramName]) {
            // convert value to array (if still string)
            if (typeof obj[paramName] === "string") {
              obj[paramName] = [obj[paramName]]
            }
            // if no array index number specified...
            if (typeof paramNum === "undefined") {
              // put the value on the end of the array
              obj[paramName].push(paramValue)
            }
            // if array index number specified...
            else {
              // put the value at that index number
              obj[paramName][paramNum] = paramValue
            }
          }
          // if param name doesn't exist yet, set it
          else {
            obj[paramName] = paramValue
          }
        }
      }

      return obj
    }

    function goToByScroll() {
      //http://localhost/dev.plugin/baf/faq-search/?fid=3823

      var parser = document.createElement("a")
      parser.href = window.location.href

      if (typeof parser.search != "undefind" && parser.search != "") {
        var fid = baf_get_url_params().fid

        if (typeof fid === "undefined") {
          return ""
        }

        var $scroll_faq_id = $("#faq-" + fid)

        if ($scroll_faq_id.length > 0) {
          $("html,body").animate({ scrollTop: $scroll_faq_id.offset().top }, 1, function () {
            $scroll_faq_id.removeAttr("style")
            $scroll_faq_id.find("input[type=checkbox]").removeAttr("checked")

            $scroll_faq_id.find("label").removeClass("closed-label").addClass("opened-label")
            $scroll_faq_id
              .find("article")
              .css({
                height: "auto", // issue in here
                visibility: "visible",
                padding: "11px 10px 10px 10px",
              })
              .addClass("article-box-shadow")
              .removeClass("baf-hide-article")
          })
        }
      }
    }

    goToByScroll()

    var baf_display_limit = 10

    function baf_get_pagination_html($baf_section, show_per_page, number_of_items, baf_search) {
      // show_per_page == start_on
      // number_of_items = end_on

      var $baf_paginate_status = $baf_section.find(".baf_page_navigation").data("paginate")

      if ($baf_paginate_status == 0) {
        var $baf_search_field = $baf_section.find("#bwl_filter_" + $baf_section.attr("container_id"))
        var $baf_search_field_current_value = $.trim($baf_search_field.val())

        if ($baf_search_field_current_value.length > -1 && $baf_search_field_current_value.length < 2) {
          $baf_section.find("div.bwl-faq-container").css("display", "block")
        }

        $baf_search_field.removeClass("search_load").addClass("search_icon")
        return false
      }

      if (typeof baf_search != "undefined" && baf_search == 1) {
        if ($baf_paginate_status == 1) {
          var $searched_faq_items = $baf_section.find("div.bwl-faq-container:visible")

          $searched_faq_items.addClass("filter")

          var total_faq_items = $searched_faq_items.size()
          var number_of_items = number_of_items
          var $items_need_to_show = $searched_faq_items.slice(0, show_per_page)
          var $items_need_to_hide = $searched_faq_items.slice(show_per_page, total_faq_items)
          $items_need_to_hide.css("display", "none")
        }

        $baf_section.find("input[type=text]").removeClass("search_load").addClass("search_icon")
      } else {
        //getting the amount of elements inside content div

        $baf_section.find("div.bwl-faq-container").css("display", "none")

        //and show the first n (show_per_page) elements
        $baf_section.find("div.bwl-faq-container").slice(0, show_per_page).css("display", "block")

        var number_of_items = $baf_section.find("div.bwl-faq-container").size()
      }

      //calculate the number of pages we are going to have
      var number_of_pages = Math.ceil(number_of_items / show_per_page)

      //set the value of our hidden input fields
      $baf_section.find("#current_page").val(0)
      $baf_section.find("#show_per_page").val(show_per_page)
      //now when we got all we need for the navigation let's make it '

      /*
             what are we going to have in the navigation?
             - link to previous page
             - links to specific pages
             - link to next page
             */
      var navigation_html = '<a class="previous_link" href="#">&laquo;</a>'
      var current_link = 0

      var page_array = []
      var display_none_class = ""
      var baf_pages_string = string_singular_page

      while (number_of_pages > current_link) {
        page_array[current_link] = current_link

        if (number_of_pages > baf_display_limit && current_link >= baf_display_limit) {
          display_none_class = " baf_dn"
        }

        navigation_html += '<a class="page_link' + display_none_class + '" href="#" longdesc="' + current_link + '">' + (current_link + 1) + "</a>"
        current_link++
      }

      if (number_of_pages > 1) {
        baf_pages_string = string_plural_page
      }

      navigation_html += '<a class="next_link" href="#">&raquo;</a><br /><span class="total_pages">' + string_total + " " + number_of_pages + " " + baf_pages_string + "</span>"

      $baf_section.find("#baf_page_navigation").html("").html(navigation_html)

      if ($baf_paginate_status == 0) {
        $baf_section.find("#baf_page_navigation").remove()
      }

      if (page_array.length == 0) {
        $baf_section.find("#baf_page_navigation").css("display", "none")
      } else {
        $baf_section.find("#baf_page_navigation").css("display", "block")
      }

      //add active_page class to the first page link
      $baf_section.find("#baf_page_navigation .page_link:first").addClass("active_page")

      $baf_section.find(".next_link").on("click", function () {
        var new_page = parseInt($baf_section.find("#current_page").val()) + 1

        //if there is an item after the current active link run the function

        var $active_page = $baf_section.find(".active_page").next(".page_link")

        if ($active_page.length == true) {
          if ($active_page.hasClass("baf_dn")) {
            $active_page.removeClass("baf_dn")

            var total_link_need_to_hide = parseInt($baf_section.find("a.page_link:visible").length) - baf_display_limit

            $baf_section.find("a.page_link:visible").slice(0, total_link_need_to_hide).addClass("baf_dn")
          }

          baf_go_to_page($baf_section, new_page)
        }

        baf_beautify_pagination($baf_section)

        return false
      })

      $baf_section.find(".previous_link").on("click", function () {
        var new_page = parseInt($baf_section.find("#current_page").val()) - 1
        //if there is an item before the current active link run the function

        var $active_page = $baf_section.find(".active_page").prev(".page_link")
        var number_of_items = $baf_section.find("div.bwl-faq-container").size()
        var start = parseInt($baf_section.find("a.page_link:visible:first").attr("longdesc")) - 1

        var end = $baf_section.find("a.page_link:visible:last").attr("longdesc")

        if ($active_page.length == true) {
          if (start > -1 && end < number_of_items) {
            $baf_section.find("a.page_link").addClass("baf_dn")
            $baf_section.find("a.page_link").slice(start, end).removeClass("baf_dn")
          }

          baf_go_to_page($baf_section, new_page)
        }

        baf_beautify_pagination($baf_section)

        return false
      })

      $baf_section.find(".page_link").on("click", function () {
        var current_link = $(this).attr("longdesc")

        baf_go_to_page($baf_section, current_link)

        baf_beautify_pagination($baf_section)

        return false
      })

      baf_beautify_pagination($baf_section)
    }

    function baf_beautify_pagination($baf_section) {
      var $visible_page_size = 4,
        $current_page_index = $baf_section.find(".active_page").index(),
        $baf_dotted_link = '<a href="#" class="baf_dotted_link">...</a>'

      //    console.log($current_page_index);

      if ($baf_section.find(".page_link").length) {
        var $page_link = $baf_section.find(".page_link"),
          $total_pages = $page_link.length, // 12
          $first_page_link = $page_link.first(),
          $last_page_link = $page_link.last()
        //        console.log($total_pages);

        // 4>=4
        //                console.log($visible_page_size);
        //                console.log($total_pages);
        if ($visible_page_size >= $total_pages - 1) {
          return 1
        }

        /*- Dotted Link Addition -*/

        // 12 > 4

        if ($total_pages > $visible_page_size) {
          //                    console.log("Total Page > Visible Page Size");
          // Check if another Fake Link available or not.
          if ($baf_section.find(".baf_dotted_link").length > 0) {
            $baf_section.find(".baf_dotted_link").remove()
          }

          // Display Number of Visible Pages.
          // Default 4.
          //                    console.log($visible_page_size);
          $page_link.removeClass("baf_dn")
          $page_link.slice($visible_page_size).addClass("baf_dn")

          // Added Dotted  Link Last Page Link & Remove Last Number Link
          $last_page_link.before($baf_dotted_link)
          $last_page_link.removeClass("baf_dn")
        }

        /*- Dotted Link Addition -*/

        /*
-- c =5
                - t = 8
                 */
        if ($current_page_index > $total_pages - 2) {
          //                console.log("$current_page_index > $total_pages-2");

          // Check if another Fake Link available or not.
          if ($baf_section.find(".baf_dotted_link").length > 0) {
            $baf_section.find(".baf_dotted_link").remove()
          }

          // Add Fake Link Before The Last Page Link.
          //                    $first_page_link.after($baf_dotted_link).removeClass('baf_dn');

          // Display Number of Visible Pages.
          // Default 4.
          $page_link.slice($visible_page_size).addClass("baf_dn")
        }

        /*-- Show & Hide The Page Links --*/

        // 5 > 4

        // 6 - 4 = 2
        // 7-4=3
        //                console.log($current_page_index);

        if ($current_page_index == $visible_page_size) {
          //                    console.log("$current_page_index == $visible_page_size");
          $first_page_link.removeClass("baf_dn")
        }

        if ($current_page_index > $visible_page_size) {
          //                    console.log("$current_page_index > $visible_page_size");

          //                    console.log(" " + $current_page_index);

          // c = 5
          // v = 4
          // s = c-v = 5-4 = 1
          // e = c

          //            var tag_index = $current_page_index - $visible_page_size-1;

          var start = $current_page_index - $visible_page_size
          var end = $current_page_index

          //                    console.log("s" + start);
          //                    console.log("e" + end);

          $baf_section.find(".baf_page_navigation").children(".page_link").addClass("baf_dn")
          $baf_section.find(".baf_page_navigation").children(".page_link").slice(start, end).removeClass("baf_dn")
          // Display The Last Page Link.
          $first_page_link.after($baf_dotted_link).removeClass("baf_dn").removeClass("baf_dn")
          $last_page_link.removeClass("baf_dn")
        }

        //                keep_first_and_last_link_opened($baf_section);
      }
    }

    function keep_first_and_last_link_opened($baf_section) {
      var $first_link = $baf_section.find(".page_link").first(),
        $last_link = $baf_section.find(".page_link").last()

      $([]).add($first_link).add($last_link).removeClass("baf_dn")
    }

    function baf_go_to_page($baf_section, page_num) {
      var search_status = 0

      if ($baf_section.find("input[type=text]").length && $baf_section.find("input[type=text]").val().length > 1) {
        search_status = 1
      }

      var show_per_page = parseInt($baf_section.find("#show_per_page").val())

      //get the element number where to start the slice from
      var start_from = page_num * show_per_page

      //get the element number where to end the slice
      var end_on = start_from + show_per_page

      if (search_status == 1) {
        $baf_section.find("div.filter").css("display", "none").slice(start_from, end_on).css("display", "block")
      } else {
        $baf_section.find("div.bwl-faq-container").css("display", "none").slice(start_from, end_on).css("display", "block")
      }

      /*get the page link that has longdesc attribute of the current page and add active_page class to it
             and remove that class from previously active page link*/
      $baf_section
        .find(".page_link[longdesc=" + page_num + "]")
        .addClass("active_page")
        .siblings(".active_page")
        .removeClass("active_page")

      //update the current page input field
      $baf_section.find("#current_page").val(page_num)
    }

    function highlightEvent(acc_content, search_keywords, $baf_search_field) {
      var regex = new RegExp(search_keywords, "gi")

      acc_content.highlightRegex(regex, {
        highlight_color: $baf_search_field.data("highlight_color"),
        highlight_bg: $baf_search_field.data("highlight_bg"),
      })
    }

    function removeHighlightEvent($faq_container) {
      $faq_container.find("i.highlight").each(function () {
        $(this).replaceWith($(this).text())
      })
    }

    /*------------------------------ Start Accordion Code From Here ---------*/

    if ($("section.ac-container").length) {
      $("section.ac-container").each(function () {
        // Write all code inside of this block.

        var $baf_section = $(this)

        var $baf_item_per_page_val = $baf_section.find(".baf_page_navigation").data("pag_limit")

        var $baf_paginate_status = $baf_section.find(".baf_page_navigation").data("paginate")

        if ($baf_paginate_status == 0) {
          $baf_item_per_page_val = $baf_section.find(".bwl-faq-container").size() // get all FAQ size
        }

        // Initially We display 5 items and hide other items.

        baf_get_pagination_html($baf_section, $baf_item_per_page_val)

        var $baf_container_id = $baf_section.attr("container_id")
        var $baf_expand_btn = $baf_section.find(".baf-expand-all"),
          $baf_collapsible_btn = $baf_section.find(".baf-collapsible-all")

        if ($baf_expand_btn.length == 1 && $baf_collapsible_btn.length == 1) {
          var label_default_state_color = $baf_section.find("label").attr("style")
          //                                 current_faq_label_container.attr("style", label_default_state_color);

          $baf_expand_btn.on("click", function () {
            $("div.bwl-faq-container-" + $baf_container_id).each(function () {
              //                                    $(this).find('article').css({
              //                                        height: 'auto', // issue in here
              //                                        visibility: 'visible',
              //                                        padding: '11px 10px 10px 10px',
              //                                    }).addClass("article-box-shadow");

              // Display Articles.
              $(this).find("article").removeAttr("style").removeClass("baf-hide-article").addClass("baf-show-article baf-article-padding")
              // Update Label Icon.
              $(this).find("label").removeAttr("class").addClass("opened-label")
              // Update Check Box Status.
              $(this).find("input[type=checkbox]").prop("checked", false) // Unchecks it
              //
              //                                    var $all_article_faq_label = $(this).find('label');
              //                                        $all_article_faq_label.css({
              //                                                                        background: checked_background
              //                                                                    });
            })
          })

          $baf_collapsible_btn.on("click", function () {
            $("div.bwl-faq-container-" + $baf_container_id).each(function () {
              // Display Articles.
              $(this).find("article").removeAttr("style").removeClass("baf-show-article baf-article-padding").addClass("baf-hide-article article-box-shadow")
              // Update Label Icon.
              $(this).find("label").removeAttr("class").addClass("closed-label")
              // Update Check Box Status.
              $(this).find("input[type=checkbox]").prop("checked", true) // Unchecks it
            })
          })
        }
      })
    }

    /*------------------------------  Search JS ---------*/

    if ($(".bwl-faq-search-panel")) {
      $(".bwl-faq-search-panel").each(function () {
        var $unique_faq_container_id = $(this).data("form_id"),
          $filter_search_container = $("#bwl_filter_" + $unique_faq_container_id),
          $faq_search_result_container = $("#bwl-filter-message-" + $unique_faq_container_id),
          $faq_container = $(".bwl-faq-container-" + $unique_faq_container_id),
          $baf_section = $faq_container.parent("section.ac-container"),
          $baf_ctrl_btn = $baf_section.find(".baf-ctrl-btn"),
          $baf_search_only_title_val = $(this).data("search_only_title"),
          $baf_item_per_page_val = $(this).data("pag_limit"),
          $baf_btn_clear = $(this).find(".baf-btn-clear")

        var baf_total_items

        $faq_container.removeClass("filter")
        $filter_search_container.val("")

        var filter_timeout, remove_filter_timeout

        $filter_search_container.on("keyup", function () {
          var $baf_search_field = $(this)

          clearTimeout(remove_filter_timeout)

          clearTimeout(filter_timeout)

          var filter = $.trim($(this).val())

          if (filter.length == 0) {
            $baf_search_field.removeClass("search_load").addClass("search_icon")
            $(this).val("")
            $faq_container.removeClass("filter")
            $baf_btn_clear.addClass("baf_dn")
          }

          if (filter.length > -1 && filter.length < 2) {
            $baf_search_field.removeClass("search_load").addClass("search_icon")

            $faq_search_result_container.html("").css("margin-bottom", "0px")
            baf_get_pagination_html($baf_section, $baf_item_per_page_val)
            $faq_container.removeClass("filter")
            $baf_btn_clear.addClass("baf_dn")
          } else {
            $baf_search_field.removeClass("search_icon").addClass("search_load")
            $baf_btn_clear.addClass("baf_dn")
          }

          remove_filter_timeout =
            filter.length < 2 &&
            setTimeout(function () {
              removeHighlightEvent($faq_container)

              // Update Label Icon.
              $faq_container.find("label").removeAttr("class").addClass("closed-label")
              // Update Article Block.
              $faq_container.find("article").removeAttr("style").removeClass("baf-show-article baf-article-padding").addClass("baf-hide-article article-box-shadow")
              // Update Check Box Status.
              $faq_container.find("input[type=checkbox]").prop("checked", true) // Unchecks it
            }, 0)

          filter_timeout =
            filter.length >= 2 &&
            setTimeout(function () {
              var count = 0

              removeHighlightEvent($faq_container)

              $faq_container.each(function () {
                var acc_heading = $(this).find("label"),
                  acc_container = $(this).find("div.baf_content"),
                  search_keywords = filter,
                  search_string

                if ($baf_search_only_title_val == 1) {
                  search_string = acc_heading.text()
                } else {
                  search_string = acc_heading.text() + acc_container.text()
                }

                /*------------------------------  Start New Code------------------------ ---------*/

                highlightEvent(acc_heading, search_keywords, $baf_search_field)

                if ($baf_search_only_title_val == 0) {
                  highlightEvent(acc_container, search_keywords, $baf_search_field)
                }

                /*------------------------------End New Code  ---------*/

                if (search_string.search(new RegExp(filter, "gi")) < 0) {
                  $(this).css("display", "none")

                  // Update Label Icon.
                  $(this).find("label").removeAttr("class").addClass("closed-label")
                  // Update Article Block.
                  $(this).find("article").removeAttr("style").removeClass("baf-show-article baf-article-padding").addClass("baf-hide-article article-box-shadow")
                  // Update Check Box Status.
                  $(this).find("input[type=checkbox]").prop("checked", true) // Unchecks it

                  $faq_container.removeClass("filter")
                } else {
                  highlightEvent(acc_heading, search_keywords, $baf_search_field)

                  if ($baf_search_only_title_val == 0) {
                    highlightEvent(acc_container, search_keywords, $baf_search_field)
                  }

                  $(this).css("display", "block")

                  // Update Label Icon.
                  $(this).find("label").removeAttr("class").addClass("opened-label")
                  // Update Article Block.
                  $(this).find("article").removeAttr("style").removeClass("baf-hide-article").addClass("baf-show-article baf-article-padding")
                  // Update Check Box Status.
                  $(this).find("input[type=checkbox]").prop("checked", false) // Unchecks it
                  count++
                }
              })

              if (count == 0) {
                baf_total_items = count
                if ($baf_ctrl_btn.length) {
                  $baf_ctrl_btn.css("display", "none")
                }
                $faq_search_result_container.html($noting_found_text).css("margin-bottom", "10px")
                baf_get_pagination_html($baf_section, $baf_item_per_page_val, baf_total_items, 1)
              } else {
                baf_total_items = count
                var count_string = count > 1 ? count + " " + $plural_faq : count + " " + $singular_faq

                if ($baf_ctrl_btn.length) {
                  $baf_ctrl_btn.css("display", "block")
                }

                $faq_search_result_container.html($found_text + " " + count_string).css("margin-bottom", "10px")

                baf_get_pagination_html($baf_section, $baf_item_per_page_val, baf_total_items, 1)
              }

              $baf_btn_clear.removeClass("baf_dn")
            }, 200)
        })

        /*----- SUGGESTION BOX ----*/

        if ($baf_section.find(".baf_suggestion").length) {
          var $baf_suggestion = $baf_section.find(".baf_suggestion")

          $baf_suggestion.find("a").on("click", function () {
            $filter_search_container.val($(this).text()).trigger("keyup")
            return false
          })
        }

        // Clear Field.

        $baf_btn_clear.on("click", function () {
          $baf_btn_clear.addClass("baf_dn")
          if ($baf_ctrl_btn.length) {
            $baf_ctrl_btn.css("display", "block")
          }
          $filter_search_container.val("")
          $filter_search_container.removeClass("search_load").addClass("search_icon")
          $faq_search_result_container.html("").css("margin-bottom", "0px")
          baf_get_pagination_html($baf_section, $baf_item_per_page_val)
          $faq_container.removeClass("filter")

          removeHighlightEvent($faq_container)

          // Update Label Icon.
          $faq_container.find("label").removeAttr("class").addClass("closed-label")

          $faq_container.find("article").removeAttr("style").removeClass("baf-show-article baf-article-padding").addClass("baf-hide-article article-box-shadow")
          // Update Check Box Status.
          $faq_container.find("input[type=checkbox]").prop("checked", true) // Unchecks it
        })

        $filter_search_container.keypress(function (e) {
          if (e.keyCode === 13) {
            return false
          }
        })
      })
    }
  })
})(jQuery)
