;(function ($) {
  $(document).ready(function () {
    /*------------------------------ Form Submission  ---------*/

    // Create Random Number For Captcha.

    function randomNum(maxNum) {
      return Math.floor(Math.random() * maxNum + 1) //return a number between 1 - 10
    }

    // Convert Hex Color to RGBA.

    function baf_hex_to_rgba(hex, opacity) {
      var c
      if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
        c = hex.substring(1).split("")
        if (c.length == 3) {
          c = [c[0], c[0], c[1], c[1], c[2], c[2]]
        }
        c = "0x" + c.join("")
        return "rgba(" + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(",") + "," + opacity + ")"
      } else {
        return 'rgba("0,0,0,' + opacity + '")'
      }
    }

    // Custom scripts to generate dynamic css.

    function generate_baf_custom_style() {
      if ($(".baf_custom_style").length > 0) {
        var baf_custom_style_string = ""

        $(".baf_custom_style").each(function () {
          var container_id = $(this).attr("container_id"),
            $section_faq_unique_class = ".section_baf_" + container_id,
            first_color = $(this).data("first_color"),
            second_color = $(this).data("second_color"),
            label_text_color = $(this).data("label_text_color"),
            accordion_arrow = $(this).data("accordion_arrow")

          baf_custom_style_string += $section_faq_unique_class + "{clear:both;}"
          baf_custom_style_string += $section_faq_unique_class + " label{ background: linear-gradient( " + baf_hex_to_rgba(first_color, 1) + ", " + baf_hex_to_rgba(second_color, 1) + ") !important; color: " + label_text_color + " !important;}"
          baf_custom_style_string += $section_faq_unique_class + " label.opened-label{  background: linear-gradient( " + baf_hex_to_rgba(first_color, 1) + ", " + baf_hex_to_rgba(first_color, 1) + ") !important;}"
          baf_custom_style_string += $section_faq_unique_class + ' label.opened-label:after{ font-family: "FontAwesome"; content:  "' + "\\" + accordion_arrow + '" !important; color: ' + label_text_color + " !important;}"
          baf_custom_style_string += $section_faq_unique_class + ' label.closed-label:after{ font-family: "FontAwesome"; content:  "' + "\\" + accordion_arrow + '" !important; color: ' + label_text_color + " !important;}"
          baf_custom_style_string += $section_faq_unique_class + " .baf-expand-all { background: " + first_color + " !important; color: " + label_text_color + " !important;}"
          baf_custom_style_string += $section_faq_unique_class + " .baf-expand-all:hover { background: " + second_color + " !important; color: " + label_text_color + " !important;}"
          baf_custom_style_string += $section_faq_unique_class + " .baf-collapsible-all{ background: " + first_color + " !important; color: " + label_text_color + " !important;}"
          baf_custom_style_string += $section_faq_unique_class + " .baf-collapsible-all:hover{ background: " + second_color + " !important; color: " + label_text_color + " !important;}"
          baf_custom_style_string += $section_faq_unique_class + " .active_page{ background: " + first_color + " !important;}"

          // Custom Theme.
          baf_custom_style_string += $section_faq_unique_class + ".baf_layout_semi_round .bwl-faq-container{ background: linear-gradient( " + baf_hex_to_rgba(first_color, 1) + ", " + baf_hex_to_rgba(second_color, 1) + ") !important;}"
          baf_custom_style_string += $section_faq_unique_class + ".baf_layout_round .bwl-faq-container{ background: linear-gradient( " + baf_hex_to_rgba(first_color, 1) + ", " + baf_hex_to_rgba(second_color, 1) + ") !important;}"
        })

        $("<style data-type='baf_custom_style-custom-css'>" + baf_custom_style_string + "</style>").appendTo("head")
      }
    }

    /*@ Start Collapsable Accordion
     * @Since 1.4.4
     */

    var accordion_container = $(".ac-container"),
      accordion_single_faq_post_container = $(".single-faq-post")

    /*
     * @ Force all the checkbox inside accordion hidden.
     * @Since- 1.4.4
     */

    accordion_container.find("input[type=checkbox]").css({
      display: "none", // block (Keep it none)
    })

    if (typeof bwl_advanced_faq_collapsible_accordion_status != "undefined" && bwl_advanced_faq_collapsible_accordion_status == 2) {
      /*
       * Show All FAQs Opened
       * @Since 1.4.4
       */

      accordion_container.find("article").removeAttr("style").addClass("baf-show-article baf-article-padding article-box-shadow")

      accordion_container.find("input[type=checkbox]").prop("checked", true)

      accordion_container.find("label").addClass("opened-label")

      accordion_container.find("label").on("click", function () {
        var label_id = $(this).attr("label_id"),
          parent_container_id = $(this).attr("parent_container_id")

        // Modify Accordion Container.

        accordion_container = $(".ac-container[container_id=" + parent_container_id + "]")

        /*------------------------------  LABEL SECTION ---------*/

        var current_faq_label_container = $(this),
          current_faq_checkbox = current_faq_label_container.next("input[type=checkbox]"), // here we need to keep squence. First Label, then checkbox , then article. It's releated with shortcode output.
          current_article_faq_container = current_faq_checkbox.next("article")

        /*------------------------------  ARTICLE SECTION------------------------ ---------*/

        if (current_faq_checkbox.is(":checked")) {
          current_article_faq_container.removeAttr("style").removeClass("baf-show-article").addClass("baf-hide-article baf-article-padding")
          current_faq_label_container.removeAttr("class").addClass("closed-label")
        } else {
          current_article_faq_container.removeAttr("style").removeClass("baf-hide-article baf-article-padding").addClass("baf-show-article")
          current_faq_label_container.removeAttr("class").addClass("opened-label")
        }
      })
    } else {
      // Regular Collapsable Accordion

      //New Code.
      accordion_container.find("article").removeAttr("style").addClass("baf-hide-article article-box-shadow")

      // Now we set all checkbox checked.

      accordion_container.find("input[type=checkbox]").prop("checked", true)

      accordion_container.find("label").addClass("closed-label")

      accordion_container.find("label").on("click", function () {
        var label_id = $(this).attr("label_id"),
          parent_container_id = $(this).attr("parent_container_id")
        // Modify Accordion Container.

        accordion_container = $(".ac-container[container_id=" + parent_container_id + "]")

        /*------------------------------  LABEL SECTION ---------*/

        var current_faq_label_container = $(this),
          current_faq_checkbox = current_faq_label_container.next("input[type=checkbox]"), // here we need to keep squence. First Label, then checkbox , then article. It's releated with shortcode output.
          current_article_faq_container = current_faq_checkbox.next("article")

        /*------------------------------  ARTICLE SECTION------------------------ ---------*/

        if (current_faq_checkbox.is(":checked")) {
          //New Code.
          accordion_container.find("article").removeAttr("style").removeClass("baf-show-article baf-article-padding").addClass("baf-hide-article article-box-shadow")

          // Now we set all checkbox checked.

          accordion_container.find("input[type=checkbox]").prop("checked", true) //
          accordion_container.find("label").removeAttr("class").addClass("closed-label")

          // Checked
          current_faq_checkbox.prop("checked", true) // Uncheck it
          current_article_faq_container.removeAttr("style").removeClass("baf-hide-article").addClass("baf-show-article baf-article-padding")
          current_faq_label_container.removeAttr("class").addClass("opened-label")
        } else {
          // For Unchecked.
          current_faq_label_container.parent().find("input[type=checkbox]").prop("checked", false) // Uncheck it
          current_faq_label_container.parent().find("article").removeAttr("style").removeClass("baf-show-article baf-article-padding").addClass("baf-hide-article article-box-shadow")
          current_faq_label_container.parent().find("label").removeAttr("class").addClass("closed-label")
        }
      })
    }

    // Display Specific Row Opened.

    if ($(".baf_row_open").length) {
      $(".baf_row_open").each(function () {
        var $this = $(this),
          $baf_row_open = $this.data("row_open") - parseInt(1),
          $bwl_faq_container = $this.find("div.bwl-faq-container:eq(" + $baf_row_open + ")")

        if ($bwl_faq_container.length) {
          $bwl_faq_container.find("label").trigger("click")
        }
      })
    }

    /*
     * Introduced: Version - 1.4.9
     * Create Date: 04-04-2014
     * Last Update: 04-04-2014
     */

    if (accordion_single_faq_post_container.length > 0) {
      if ($(".baf-single-collapse").length == 0) {
        accordion_single_faq_post_container.find("label").trigger("click")
      }
    }

    // Custom FAQ Category Tab.

    function switch_tabs(obj) {
      obj.parent().parent().find(".bwl-faq-tab-content").slideUp("fast")

      obj.parent().find("li").removeClass("active")

      var id = obj.find("a", 0).attr("rel")

      $("#" + id).slideDown("slow")

      obj.addClass("active")
    }

    if ($(".bwl-faq-tabs").length) {
      $(".bwl-faq-tabs li").on("click", function () {
        if ($(this).find(".bwl-faq-link").attr("class") != "bwl-faq-link") {
          switch_tabs($(this))
        }
      })
    }

    /*----- FAQ LIST SEARCH ----*/

    if ($(".baf_filter_list").length) {
      $(".baf_filter_list").each(function () {
        var $this = $(this),
          $filter_form = $this.closest("form"),
          $faq_container_id = $("#" + $this.attr("id"))
        $faq_container_id.bwlFaqFilter({
          unique_id: $filter_form.data("unique_id"),
          paginate: $filter_form.data("paginate"),
          pag_limit: $filter_form.data("pag_limit"),
          search_only_title: $filter_form.data("search_only_title"),
        })
      })
    }

    /*--  Rating --*/

    $(document).on("click", ".post-like-container a", function () {
      var $icon_container = jQuery(this)

      var $thumb_icon_class = $icon_container.find("i")
      var $info_icon = $icon_container.data("info_icon")
      var $loading_msg = $icon_container.data("loading_msg")

      if ($thumb_icon_class.attr("class") === $info_icon) {
        return false
      }

      $thumb_icon_class.attr("class", $info_icon)

      $icon_container.siblings(".count").text($loading_msg)

      // Retrieve post ID from data attribute
      var $post_id = $icon_container.data("post_id")

      $.ajax({
        url: ajaxurl,
        type: "POST",
        dataType: "JSON",
        data: {
          action: "bwl_advanced_faq_apply_rating", // action will be the function name
          post_like: true,
          post_id: $post_id,
        },
        success: function (data) {
          // If vote successful
          if (data.status == 0) {
            $icon_container.addClass("voted")
            $icon_container.siblings(".count").text("").append(data.msg)
          }
          if (data.status == 1) {
            $icon_container.addClass("voted")
            $icon_container.siblings(".count").text("").append(data.msg)
          }
        },
        error: function (xhr, textStatus, e) {
          console.log("There was an error saving the update.")
          return
        },
      })

      return false
    })
    /*--  FAQ Submission --*/

    $(".bwl_advanced_faq_form").each(function () {
      // Since: version 1.6.6
      // Check Pre selection status.

      var $this = $(this),
        reset_cat_field = 1

      if ($this.is("[data-sel_cat]")) {
        reset_cat_field = 0
      }

      $this.find("input#title").val("")

      if (reset_cat_field === 1) {
        $this.find("select#cat").val("-1")
      }
    })

    $(".bwl_advanced_faq_form")
      .find("input[type=submit]")
      .on("click", function () {
        var form_submit_button = $(this),
          bwl_advanced_faq_form_id = form_submit_button.attr("bwl_advanced_faq_form_id"),
          form_box_container = $("#" + bwl_advanced_faq_form_id),
          form_field_container = $("#" + bwl_advanced_faq_form_id + " .bwl_advanced_faq_form"),
          reset_cat_field = 1

        if (form_field_container.is("[data-sel_cat]")) {
          reset_cat_field = 0
        }

        var message_box = form_box_container.find(".bwl-faq-form-message-box"),
          title = form_field_container.find("#title"),
          cat = form_field_container.find("#cat"),
          captcha_status = form_field_container.find("#captcha_status")

        if (captcha_status.val() == 1) {
          var num1 = form_field_container.find("#num1")
          var num2 = form_field_container.find("#num2")
          var captcha = form_field_container.find("#captcha")
          var all_fields = $([]).add(title).add(cat).add(captcha)
        } else {
          var all_fields = $([]).add(title).add(cat)
        }

        var bValid = true,
          required_field_msg = "",
          ok_border = "border: 1px solid #EEEEEE",
          error_border = "border: 1px solid #E63F37"

        if ($.trim(title.val()).length < title.data("min_length") || $.trim(title.val()).length > title.data("max_length")) {
          title_bValid = false
          title.attr("style", error_border)
          required_field_msg += " " + title.data("error_msg") + "<br />"
        } else {
          title_bValid = true
          title.attr("style", ok_border)
          required_field_msg += ""
        }

        bValid = bValid && title_bValid

        if ($.trim(cat.val()) == -1) {
          cat_bValid = false
          cat.attr("style", error_border)
          required_field_msg += " " + err_faq_category + "<br />"
        } else {
          cat_bValid = true
          cat.attr("style", ok_border)
          required_field_msg += ""
        }

        bValid = bValid && cat_bValid

        if (captcha_status.val() == 1) {
          if (parseInt($.trim(num1.val())) + parseInt($.trim(num2.val())) != parseInt($.trim(captcha.val()))) {
            captcha_bValid = false
            captcha.attr("style", error_border)
            required_field_msg += " " + err_faq_captcha
          } else {
            captcha_bValid = true
            captcha.attr("style", ok_border)
            required_field_msg += ""
          }

          bValid = bValid && captcha_bValid
        }

        //Alert Message Box For Required Fields.

        if (bValid == false) {
          message_box.html("").addClass("bwl-form-error-box").html(required_field_msg).slideDown("slow")
        }

        if (bValid == true) {
          all_fields.attr("style", ok_border)
          all_fields.addClass("bwl_advanced_faq_disabled_field").attr("disabled", "disabled")
          form_submit_button.addClass("bwl_advanced_faq_disabled_field").attr("disabled", "disabled")
          message_box.html("").removeClass("bwl-form-error-box").addClass("bwl-form-wait-box").html(string_please_wait).slideDown("slow")

          $.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: "JSON",
            data: {
              action: "bwl_advanced_faq_save_post_data", // action will be the function name,
              title: title.val(),
              cat: cat.val(),
              _baf_form_nonce: form_field_container.find("#_baf_form_nonce").val(),
            },
            success: function (data) {
              if (data.bwl_faq_add_status == 1) {
                //Reload For New Number.

                if (captcha_status.val() == 1) {
                  num1.val(randomNum(5))
                  num2.val(randomNum(9))
                }

                message_box.removeClass("bwl-form-wait-box").html("").html(string_ques_added).addClass("bwl-form-success-box").delay(3000).slideUp("slow")
                all_fields.val("").removeAttr("disabled").removeClass("bwl_advanced_faq_disabled_field")

                if (reset_cat_field === 1) {
                  cat.val("-1") //fixed in version 1.6.2 // Update in version 1.6.6
                }

                form_submit_button.removeAttr("disabled").removeClass("bwl_advanced_faq_disabled_field")
              } else {
                message_box.removeClass("bwl-form-wait-box").html("").html(string_ques_unable_add).addClass("bwl-form-error-box").delay(3000).slideUp("slow")
                all_fields.removeAttr("disabled").removeClass("bwl_advanced_faq_disabled_field")
                form_submit_button.removeAttr("disabled").removeClass("bwl_advanced_faq_disabled_field")
              }
            },
            error: function (xhr, textStatus, e) {
              message_box.removeClass("bwl-form-wait-box").html("").html(string_ques_unable_add).addClass("bwl-form-error-box").delay(3000).slideUp("slow")
              all_fields.removeAttr("disabled").removeClass("bwl_advanced_faq_disabled_field")
              form_submit_button.removeAttr("disabled").removeClass("bwl_advanced_faq_disabled_field")
              return
            },
          })
        }

        return false
      })

    // Finally generate Custom CSS.

    generate_baf_custom_style()
  })
})(jQuery)
