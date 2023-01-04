;(function ($) {
  $(function () {
    /*--  Bulk Edit --*/

    if (jQuery("#bulk_edit").length == 1) {
      // we create a copy of the WP inline edit post function
      var wp_inline_edit = inlineEditPost.edit
      // and then we overwrite the function with our own code
      inlineEditPost.edit = function (id) {
        // "call" the original WP edit function
        // we don't want to leave WordPress hanging
        wp_inline_edit.apply(this, arguments)

        // now we take care of our business

        // get the post ID

        var post_id = 0

        if (typeof id == "object") post_id = parseInt(this.getId(id))

        if (post_id > 0) {
          // define the edit row
          var edit_row = jQuery("#edit-" + post_id)

          // get the breaking new status.

          var votes_reset_status = jQuery("#votes_reset_status-" + post_id).data("status_code")

          // populate the release date

          edit_row.find('select[name="votes_reset_status"]').val(votes_reset_status == "" ? "0" : votes_reset_status)

          // get the FAQ Author Name

          var bwl_advanced_faq_author = jQuery("#bwl_advanced_faq_author-" + post_id).data("status_code")

          // set the FAQ Author Name

          edit_row.find('select[name="bwl_advanced_faq_author"]').val(bwl_advanced_faq_author == "" ? "" : bwl_advanced_faq_author)
        }
      }

      /*------------------------------ Bulk Edit Settings ---------*/

      jQuery("#bulk_edit").on("click", function () {
        // define the bulk edit row
        var bulk_row = jQuery("#bulk-edit")

        // get the selected post ids that are being edited
        var post_ids = new Array()
        bulk_row
          .find("#bulk-titles")
          .children()
          .each(function () {
            post_ids.push(
              jQuery(this)
                .attr("id")
                .replace(/^(ttle)/i, "")
            )
          })

        // get the $votes_reset_status

        var votes_reset_status = bulk_row.find('select[name="votes_reset_status"]').val(),
          bwl_advanced_faq_author = bulk_row.find('select[name="bwl_advanced_faq_author"]').val()

        // save the data
        jQuery.ajax({
          url: ajaxurl, // this is a variable that WordPress has already defined for us
          type: "POST",
          async: false,
          cache: false,
          data: {
            action: "baf_bulk_quick_save_bulk_edit", // this is the name of our WP AJAX function that we'll set up next
            post_ids: post_ids, // and these are the 2 parameters we're passing to our function
            votes_reset_status: votes_reset_status,
            bwl_advanced_faq_author: bwl_advanced_faq_author,
          },
        })
      })
    }

    /*--  FAQ SORTING JS  --*/

    if ($("#baf_faq_sorting_container")) {
      var $baf_save_sorting = $("#baf_save_sorting")

      $baf_save_sorting.attr("data-term_id", 0)

      // Save Sorting Data.

      function baf_save_sort_data(baf_sort_filter_type, baf_sort_data) {
        return $.ajax({
          type: "POST",
          url: ajaxurl,
          data: {
            action: "bwl_advanced_faq_apply_sort", // this is the name of our WP AJAX function that we'll set up next
            baf_sort_filter_type: baf_sort_filter_type,
            baf_sort_data: baf_sort_data,
            baf_term_id: $baf_save_sorting.attr("data-term_id"),
          },
          dataType: "JSON",
        })
      }

      // Get Sort Data.

      function baf_get_sorting_data(baf_category_slug, baf_term_id) {
        return $.ajax({
          type: "POST",
          url: ajaxurl,
          data: {
            action: "baf_get_sorting_data", // this is the name of our WP AJAX function that we'll set up next
            baf_category_slug: baf_category_slug,
            baf_term_id: baf_term_id,
            baf_sort_filter: $baf_save_sorting.data("sort_filter"),
          },
          dataType: "JSON",
        })
      }

      /*------------------------------ Initializing  ---------*/

      var $baf_msg_container = $("#baf_faq_sorting_container").find("#sort-status")
      var $baf_sort_subtitle = $baf_msg_container.data("sort_subtitle")
      var $bwl_faq_items = $("#baf_faq_sorting_container").find("#bwl_faq_items")

      if ($bwl_faq_items.find("li").length) {
        $bwl_faq_items.sortable({})
      }

      // Category Wise Sorting.

      if ($("#baf_sort_faq_category").length) {
        $baf_save_sorting.attr("disabled", "disabled")

        var $baf_sort_faq_category = $("#baf_sort_faq_category")
        $baf_sort_faq_category.val("")

        $baf_sort_faq_category.on("change", function () {
          $baf_save_sorting.attr("disabled", "disabled")

          if ($baf_sort_faq_category.val() == "") {
            $baf_save_sorting.removeAttr("data-term_id")
            $bwl_faq_items.html("")
            return ""
          }

          $baf_msg_container.html(baf_text_loading)

          var baf_category_slug = $(this).val(),
            baf_term_id = $("#baf_sort_faq_category option:selected").attr("data-term_id")

          $.when(baf_get_sorting_data(baf_category_slug, baf_term_id)).done(function (response_data) {
            if (response_data.status == 1) {
              $baf_msg_container.html($baf_sort_subtitle)
              $baf_save_sorting.attr("data-term_id", baf_term_id)

              var output = ""

              $.each(response_data.data, function (index, data) {
                output += '<li id="' + data["post_id"] + '" class="menu-item">' + '<dl class="menu-item-bar">' + '<dt class="menu-item-handle">' + '<span class="menu-item-title">' + data["post_title"] + "</span>" + "</dt>" + "</dl>" + '<ul class="menu-item-transport"></ul>' + "</li>"
              })

              $bwl_faq_items.html(output)

              setTimeout(function () {
                if ($bwl_faq_items.find("li").length) {
                  $bwl_faq_items.sortable({})

                  $baf_save_sorting.removeAttr("disabled")
                }
              }, 500)
            } else {
              $baf_msg_container.html($baf_sort_subtitle)
              $baf_save_sorting.attr("disabled", "disabled")
            }
          })
        })
      } else {
        $baf_save_sorting.removeAttr("disabled")
      }

      /*------------------------------ Sorting Data Save Event  ---------*/

      $baf_save_sorting.on("click", function () {
        $baf_save_sorting.val("Saving...").attr("disabled", "disabled")

        $.when(baf_save_sort_data($(this).data("sort_filter"), $bwl_faq_items.sortable("toArray").toString())).done(function (response_data) {
          if (response_data.status == 1) {
            $baf_save_sorting.val("Saved !")

            setTimeout(function () {
              $baf_save_sorting.val("Save").removeAttr("disabled")
            }, 2000)
          }
        })
      })
    }

    /*------ Allow Only Number -----*/

    $(".baf_numeric_field").each(function () {
      var $this = $(this)

      $this.on("keypress", function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which == 13) {
          return true
        } else if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false
        } else {
          return true
        }
      })
    })

    /*--  Color Picker For Option Panel --*/

    var $baf_enable_custom_theme = $("#baf_enable_custom_theme"),
      $gradient_first_color = $("input#gradient_first_color"),
      $gradient_second_color = $("input#gradient_second_color"),
      $label_text_color = $("input#label_text_color"),
      $baf_like_icon_color = $("input#baf_like_icon_color"),
      $baf_like_icon_hover_color = $("input#baf_like_icon_hover_color")

    $gradient_first_color.wpColorPicker()
    $gradient_second_color.wpColorPicker()
    $label_text_color.wpColorPicker()
    $baf_like_icon_color.wpColorPicker()
    $baf_like_icon_hover_color.wpColorPicker()

    var $baf_custom_theme_container = $baf_enable_custom_theme.parents("table")

    $baf_custom_theme_container.find("tr").hide()

    $baf_custom_theme_container.find("tr:eq( 0 ), tr:eq( 1 )").show()

    $baf_enable_custom_theme.on("click", function () {
      $baf_custom_theme_container.find("tr:eq( 2 ), tr:eq( 3 ), tr:eq( 4 )").toggle("slow")
    })

    if ($baf_enable_custom_theme.is(":checked")) {
      $baf_custom_theme_container.find("tr:eq( 2 ), tr:eq( 3 ), tr:eq( 4 )").show()
    }

    /*-- Enable Custom Theme --*/

    /*-- Code Editor --*/

    if ($(".baf_email_tpl").length) {
      $(".baf_email_tpl").each(function () {
        var $this = $(this)

        var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {}
        editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
          indentUnit: 2,
          tabSize: 2,
          mode: "text/html",
        })
        wp.codeEditor.initialize($this, editorSettings)
      })
    }

    /*-- Code Editor --*/

    if ($("#bwl_advanced_faq_custom_css").length) {
      var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {}
      editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
        indentUnit: 2,
        tabSize: 2,
        mode: "css",
      })
      wp.codeEditor.initialize($("#bwl_advanced_faq_custom_css"), editorSettings)
    }

    if ($("#link-options").length) {
      $("#link-options").append(
        $("<div></div>")
          .addClass("baf-pop")
          .html($("<label></label>").html([$("<span></span>"), $(" <input></input>").attr({ type: "checkbox", id: "wp-baf-pop" }), "Enable FAQ Pop Up"]))
      )

      if (wpLink && typeof wpLink.getAttrs == "function") {
        wpLink.getAttrs = function () {
          wpLink.correctURL()
          return {
            href: $.trim($("#wp-link-url").val()),
            target: $("#wp-link-target").prop("checked") ? "_blank" : null,
            class: $("#wp-baf-pop").prop("checked") ? "baf_pop" : null,
          }
        }
      }
    }
  })
})(jQuery)
