if (typeof Object.create !== "function") {
  Object.create = function (obj) {
    function F() {}
    F.prototype = obj
    return new F()
  }
}

;(function ($, window, document, undefind) {
  var BWLFaqFilter = {
    init: function (options, elem) {
      var self = this
      self.elem = elem
      self.$elem = $(elem)

      /*------------------------------ DEFAULT OPTIONS ---------*/

      this.options = $.extend({}, $.fn.bwlFaqFilter.options, options) // Override old sutff
      ;(self.filter_search_container = $("#bwl_filter_" + this.options.unique_id)), (self.faq_search_result_container = $("#bwl-filter-message-" + this.options.unique_id)), (self.faq_container = $(".bwl-faq-container-" + this.options.unique_id)), (self.section_container = $("section#" + this.options.unique_id))

      self.baf_btn_clear = self.section_container.find(".baf-btn-clear")

      /*----- SUGGESTION BOX ----*/

      if (self.section_container.find(".baf_suggestion").length) {
        var $baf_suggestion = self.section_container.find(".baf_suggestion")

        $baf_suggestion.find("a").on("click", function () {
          self.filter_search_container.val($(this).text()).trigger("keyup")
          return false
        })
      }

      /*------------------------------ BIND ALL CLICK EVENTS  ---------*/
      self.$elem.val("") // Initlally make it's value null
      //            self.section_container.find('.baf_page_navigation').remove();
      //            self.section_container.find('.bwl-faq-container').css("display", "block");
      this.bindEvent()
    },

    bindEvent: function () {
      var self = this
      var filter_timeout, remove_filter_timeout
      self.$elem.keyup(function () {
        var $baf_search_field = $(this)

        clearTimeout(remove_filter_timeout)

        clearTimeout(filter_timeout)

        var filter = $.trim($(this).val())

        if (filter.length == 0) {
          $baf_search_field.removeClass("search_load").addClass("search_icon")
          $(this).val("")
          self.baf_btn_clear.addClass("baf_dn")
          self.faq_search(1)
        }

        if (filter.length > -1 && filter.length < 2) {
          $baf_search_field.removeClass("search_load").addClass("search_icon")
          self.baf_btn_clear.addClass("baf_dn")
        } else {
          $baf_search_field.removeClass("search_icon").addClass("search_load")
          self.baf_btn_clear.addClass("baf_dn")
        }

        // New Code.

        remove_filter_timeout =
          filter.length < 2 &&
          setTimeout(function () {
            self.removeHighlightEvent()

            // Update Label Icon.
            self.section_container.find("label").removeAttr("class").addClass("closed-label")
            // Update Article Block.
            self.section_container.find("article").removeAttr("style").removeClass("baf-show-article baf-article-padding").addClass("baf-hide-article article-box-shadow")
            // Update Check Box Status.
            self.section_container.find("input[type=checkbox]").prop("checked", true) // Unchecks it
          }, 0)

        // End New Code.

        filter_timeout =
          filter.length >= 2 &&
          setTimeout(function () {
            self.baf_btn_clear.removeClass("baf_dn")
            self.faq_search(1)
          }, 200)
      })

      // Clear Field.
      self.baf_btn_clear.on("click", function () {
        self.baf_btn_clear.addClass("baf_dn")
        self.$elem.val("")
        self.$elem.removeClass("search_load").addClass("search_icon")
        self.faq_search_result_container.html("").css("margin-bottom", "0px")
        self.faq_search(1)
        self.faq_container.removeClass("filter")
        self.removeHighlightEvent()

        // Update Label Icon.
        self.section_container.find("label").removeAttr("class").addClass("closed-label")

        self.section_container.find("article").removeAttr("style").removeClass("baf-show-article baf-article-padding").addClass("baf-hide-article article-box-shadow")
        // Update Check Box Status.
        self.section_container.find("input[type=checkbox]").prop("checked", true) // Unchecks it
      })

      self.$elem.keypress(function (e) {
        if (e.keyCode === 13) {
          return false
        }
      })
    },

    highlightEvent: function (acc_content, search_keywords) {
      var self = this
      var regex = new RegExp(search_keywords, "gi")

      acc_content.highlightRegex(regex, {
        highlight_color: self.$elem.data("highlight_color"),
        highlight_bg: self.$elem.data("highlight_bg"),
      })
    },

    removeHighlightEvent: function () {
      var self = this

      self.section_container.find("i.highlight").each(function () {
        $(this).replaceWith($(this).text())
      })
    },

    faq_search: function (baf_search) {
      var self = this

      var filter = jQuery.trim(self.$elem.val()).toLowerCase(),
        count = 0,
        search_string

      self.removeHighlightEvent()

      self.section_container.find("section").each(function () {
        var each_section_counter = 0

        var $baf_section = jQuery(this)

        var show_per_page = self.options.pag_limit,
          search_only_title = self.options.search_only_title,
          number_of_items = $baf_section.find("div.bwl-faq-container").length

        // second iteration
        jQuery(this)
          .find("div.bwl-faq-container")
          .each(function (index) {
            var $bwl_faq_container = $(this)

            var acc_heading = $(this).find("label"),
              acc_container = $(this).find("div.baf_content"),
              search_keywords = filter

            search_string = acc_heading.text() + acc_container.text()

            /*-----  START NEW CODE FOR TAXONOMOY ----*/

            if ($baf_section.prev("div.baf_taxonomy_info_container").length > 0 && self.$elem.attr("data-taxonomy_info_search") == 1) {
              var baf_taxonomy_info = $baf_section.prev("div.baf_taxonomy_info_container")

              search_string = search_string + baf_taxonomy_info.text()

              self.highlightEvent(baf_taxonomy_info, search_keywords)
            }

            /*-----  END NEW CODE FOR TAXONOMOY  - ---------*/

            /*------------------------------  Start New Code------------------------ ---------*/

            self.highlightEvent(acc_heading, search_keywords)
            self.highlightEvent(acc_container, search_keywords)

            /*------------------------------End New Code  ---------*/
            //
            search_string = search_string.toLowerCase()

            if (search_string.indexOf(filter) < 0) {
              //Not found!
              jQuery(this).css("display", "none")

              // Update Label Icon.
              jQuery(this).find("label").removeAttr("class").addClass("closed-label")
              // Update Article Block.
              jQuery(this).find("article").removeAttr("style").removeClass("baf-show-article baf-article-padding").addClass("baf-hide-article article-box-shadow")
              // Update Check Box Status.
              jQuery(this).find("input[type=checkbox]").prop("checked", true) // Unchecks it

              $bwl_faq_container.removeClass("filter")
            } else {
              self.highlightEvent(acc_heading, search_keywords)
              self.highlightEvent(acc_container, search_keywords)

              //Found!
              jQuery(this).css("display", "block")

              // Update Label Icon.
              jQuery(this).find("label").removeAttr("class").addClass("opened-label")
              // Update Article Block.
              jQuery(this).find("article").removeAttr("style").removeClass("baf-hide-article").addClass("baf-show-article baf-article-padding")
              // Update Check Box Status.
              jQuery(this).find("input[type=checkbox]").prop("checked", false) // Unchecks it

              $bwl_faq_container.addClass("filter")
              //                            if( $baf_ctrl_btn.length ) {
              //                                $baf_ctrl_btn.css('display', 'block');
              //                            }
              each_section_counter++
              count++
            }
          }) // End second iteration.

        if (each_section_counter === 0) {
          // Hide Title.
          jQuery(this).prev("div.baf_taxonomy_info_container").css("display", "none") //Updated in version 1.6.7
          jQuery(this).find(".baf_page_navigation").css("display", "none") // Hide Pagination Section.
          if (jQuery(this).find(".baf-ctrl-btn").length) {
            jQuery(this).find(".baf-ctrl-btn").css("display", "none")
          }
        } else {
          // Show title.
          jQuery(this).prev("div.baf_taxonomy_info_container").css("display", "block") //Updated in version 1.6.7
          jQuery(this).find(".baf_page_navigation").css("display", "block") // Show Pagination Section.
          if (jQuery(this).find(".baf-ctrl-btn").length) {
            jQuery(this).find(".baf-ctrl-btn").css("display", "block")
          }
          // Going to put pagination code in here!
          number_of_items = each_section_counter
          self.baf_get_pagination_html($baf_section, show_per_page, number_of_items, baf_search)
        }

        self.baf_btn_clear.removeClass("baf_dn")
      })

      if (count === 0) {
        self.faq_search_result_container.html($noting_found_text).css("margin-bottom", "10px")
      } else {
        if (filter === "") {
          self.faq_search_result_container.html("").css("margin-bottom", "0px")
        } else {
          var count_string = count > 1 ? count + " " + $plural_faq : count + " " + $singular_faq
          self.faq_search_result_container.html($found_text + " " + count_string).css("margin-bottom", "10px")
        }
      }

      self.section_container.find("input[type=text]").removeClass("search_load").addClass("search_icon")
    },

    baf_get_pagination_html: function ($baf_section, show_per_page, number_of_items, baf_search) {
      var self = this

      var baf_display_limit = 10

      // show_per_page == start_on
      // number_of_items = end_on
      var $baf_paginate_status = $baf_section.find(".baf_page_navigation").data("paginate")

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

          self.baf_go_to_page($baf_section, new_page)
        }
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

          self.baf_go_to_page($baf_section, new_page)
        }
        return false
      })

      $baf_section.find(".page_link").on("click", function () {
        var current_link = $(this).attr("longdesc")

        self.baf_go_to_page($baf_section, current_link)
        return false
      })
    },

    baf_go_to_page: function ($baf_section, page_num) {
      var search_status = 0

      var $baf_section_wrapper = $("section#" + $baf_section.parents("section").attr("id"))

      if ($baf_section_wrapper.find("input[type=text]").length && $baf_section_wrapper.find("input[type=text]").val().length > 1) {
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
    },
  }

  // Initialization Of Plugin

  $.fn.bwlFaqFilter = function (options) {
    return this.each(function () {
      var faq_filter = Object.create(BWLFaqFilter)
      faq_filter.init(options, this)
    })
  }

  // Default Options Setion.

  $.fn.bwlFaqFilter.options = {
    unique_id: "",
    paginate: 1,
    pag_limit: 5,
    search_only_title: 0,
  }
})(jQuery, window, document)
