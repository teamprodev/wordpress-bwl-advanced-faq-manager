;(function ($) {
  // Editor Part

  function baf_add_sc_action() {
    var $baf_sc_container = $("#baf_editor_popup_content")

    var $custom_faq_type = $("[name=custom_faq_type]")

    var faq_category_container = $("#faq_category_container"),
      faq_topics_container = $("#faq_topics_container"),
      faq_item_container = $("#faq_item_container"),
      faq_tab_container = $("#faq_tab_container"),
      faq_description_container = $("#faq_description_container"),
      baf_sc_settings = $(".baf_sc_settings"),
      list_status = 0

    $custom_faq_type.change(function () {
      var $custom_faq_type_value = $(this).val()

      if ($custom_faq_type_value == 2) {
        faq_category_container.removeClass("bafm_dn")
        faq_tab_container.removeClass("bafm_dn")
        faq_topics_container.addClass("bafm_dn")
        faq_description_container.removeClass("bafm_dn")
        faq_item_container.addClass("bafm_dn")
        baf_sc_settings.removeClass("bafm_dn")
        list_status = 1
      } else if ($custom_faq_type_value == 3) {
        faq_category_container.addClass("bafm_dn")
        faq_topics_container.removeClass("bafm_dn")
        faq_tab_container.removeClass("bafm_dn")
        faq_description_container.removeClass("bafm_dn")
        faq_item_container.addClass("bafm_dn")
        baf_sc_settings.removeClass("bafm_dn")
        list_status = 1
      } else if ($custom_faq_type_value == 4) {
        faq_category_container.addClass("bafm_dn")
        faq_topics_container.addClass("bafm_dn")
        faq_tab_container.addClass("bafm_dn")
        faq_description_container.addClass("bafm_dn")
        faq_item_container.removeClass("bafm_dn")
        baf_sc_settings.addClass("bafm_dn")
        list_status = 0
      } else {
        faq_category_container.addClass("bafm_dn")
        faq_topics_container.addClass("bafm_dn")
        faq_tab_container.addClass("bafm_dn")
        faq_item_container.addClass("bafm_dn")
        baf_sc_settings.removeClass("bafm_dn")
        list_status = 0
      }
    })

    $("#addShortCodebtn").click(function (event) {
      // Columns

      // INITIALIZE ALL SHORTCODE TEXT

      var sc_faq_limit = "",
        sc_faq_order = "",
        sc_faq_sbox = "",
        sc_faq_bwla_form = "",
        sc_faq_orderby = "",
        sc_faq_pagination = "",
        sc_faq_schema = "",
        sc_taxonomy_info = "",
        bwla_item_per_page = ""

      var shortcode = "[bwla_faq"

      // FAQ category
      if ($baf_sc_container.find("#faq_category").multipleSelect("getSelects").length !== 0) {
        shortcode += ' faq_category="' + $baf_sc_container.find("#faq_category").multipleSelect("getSelects") + '" '
      }

      // FAQ TOPICS
      if ($baf_sc_container.find("#faq_topics").multipleSelect("getSelects").length !== 0) {
        shortcode += ' faq_topics="' + $baf_sc_container.find("#faq_topics").multipleSelect("getSelects") + '" '
      }

      // NUMBER OF FAQs
      if ($("#no_of_faqs").val().split(" ").join("").length !== 0) {
        shortcode += ' limit="' + $("#no_of_faqs").val().split(" ").join("") + '" '

        sc_faq_limit = ' limit="' + $("#no_of_faqs").val().split(" ").join("") + '" '
      }

      // ORDER BY
      if ($baf_sc_container.find("#orderby").val().length !== 0) {
        shortcode += ' orderby="' + $("#orderby").val() + '" '

        sc_faq_orderby = ' orderby="' + $("#orderby").val() + '" '
      }

      // ORDER TYPE
      if ($baf_sc_container.find("#order").val().length !== 0) {
        shortcode += ' order="' + $("#order").val() + '" '

        sc_faq_order = ' order="' + $("#order").val() + '" '
      }

      // Show Search Form
      if ($baf_sc_container.find("#sbox").is(":checked")) {
        shortcode += ' sbox="1" '
        sc_faq_sbox = ' sbox="1" '
      } else {
        shortcode += ' sbox="0" '
        sc_faq_sbox = ' sbox="0" '
      }

      // Show Search Form
      if ($baf_sc_container.find("#bwla_pagination").is(":checked")) {
        shortcode += ' paginate="1" '

        shortcode += ' pag_limit="' + $("#bwla_item_per_page").val() + '" '

        sc_faq_pagination = ' paginate="1" '

        bwla_item_per_page = ' pag_limit="' + $("#bwla_item_per_page").val() + '" '
      } else {
        shortcode += ""
      }

      // Show Schema
      if ($baf_sc_container.find("#bwla_schema").is(":checked")) {
        shortcode += ' schema="1" '
      } else {
        shortcode += ""
      }

      // Show Taxonomy Description
      if ($baf_sc_container.find("#bwla_taxonomy_info").is(":checked")) {
        shortcode += ' taxonomy_info="1" '

        sc_taxonomy_info = ' taxonomy_info="1" list="1" '
      } else {
        shortcode += ""
      }

      // Show Tab

      var bwl_tabify_status = 0

      if ($baf_sc_container.find("#bwl_tabify").is(":checked")) {
        bwl_tabify_status = 1
      } else {
        shortcode += ' list="' + list_status + '" '
      }

      // Ending of Shortcode
      shortcode += " /]"

      // Show FAQ Form

      if ($baf_sc_container.find("#bwla_form").is(":checked")) {
        shortcode += '[bwla_form status="1" /]'
        sc_faq_bwla_form = '[bwla_form status="1" /]'
      }

      // Custom Tabify Shortcode Generator.

      var current_faq_type = $("input[name=custom_faq_type]:checked").val()

      if (current_faq_type == 4) {
        var faq_items = $baf_sc_container.find("#faq_items").val()

        shortcode = '[bwla_faq single="1" fpid="' + faq_items + '"/]'
      }

      if (bwl_tabify_status === 1) {
        // Vertical Mode Status.
        // Introduced in version 1.6.3

        var bwl_tabify_ver_sc = ""

        if ($baf_sc_container.find("#bwl_tabify_ver").is(":checked")) {
          bwl_tabify_ver_sc += " vertical=1"
        }

        var selected_faq_category = $baf_sc_container.find("#faq_category").multipleSelect("getSelects", "text"),
          selected_faq_category_slug = $baf_sc_container.find("#faq_category").multipleSelect("getSelects"),
          selected_faq_topics = $baf_sc_container.find("#faq_topics").multipleSelect("getSelects", "text"),
          selected_faq_topics_slug = $baf_sc_container.find("#faq_topics").multipleSelect("getSelects")

        if (selected_faq_category.length > 0 && current_faq_type == 2) {
          shortcode = "[bwl_faq_tabs" + bwl_tabify_ver_sc + "]"

          for (i = 0; i < selected_faq_category.length; i++) {
            shortcode += '[bwl_faq_tab title="' + jQuery.trim(selected_faq_category[i]) + '"]' + ' [bwla_faq bwl_tabify="' + bwl_tabify_status + '" faq_category="' + selected_faq_category_slug[i] + '" ' + sc_faq_orderby + " " + sc_faq_order + " " + sc_faq_limit + " " + sc_faq_sbox + " " + sc_faq_pagination + " " + sc_taxonomy_info + " " + bwla_item_per_page + "] " + sc_faq_bwla_form + "[/bwl_faq_tab] "
          }

          shortcode += "[/bwl_faq_tabs]"
        }

        if (selected_faq_topics.length > 0 && current_faq_type == 3) {
          shortcode = "[bwl_faq_tabs]"

          for (i = 0; i < selected_faq_topics.length; i++) {
            shortcode += '[bwl_faq_tab title="' + selected_faq_topics[i] + '"]' + ' [bwla_faq bwl_tabify="' + bwl_tabify_status + '" faq_topics="' + selected_faq_topics_slug[i] + '" ' + sc_faq_orderby + " " + sc_faq_order + " " + sc_faq_limit + " " + sc_faq_sbox + " " + sc_faq_pagination + " " + sc_taxonomy_info + " " + bwla_item_per_page + "] " + sc_faq_bwla_form + "[/bwl_faq_tab] "
          }

          shortcode += "[/bwl_faq_tabs]"
        }

        window.send_to_editor(shortcode)
      } else {
        window.send_to_editor(shortcode)
      }

      $("#baf_editor_overlay").remove()

      return false
    })

    $("#closeShortCodebtn, .btn_baf_editor_close").click(function (event) {
      $("#baf_editor_overlay").remove()
      return false
    })

    /*------------------------------ Category ---------*/

    $("select#faq_category").add("multiple", "multiple")

    $("select#faq_category").multipleSelect({
      placeholder: "- Select -",
      selectAll: true,
      filter: true,
    })

    $("select#faq_category").multipleSelect("uncheckAll")

    /*------------------------------ Topics ---------*/

    $("select#faq_topics").add("multiple", "multiple")

    $("select#faq_topics").multipleSelect({
      placeholder: "- Select -",
      selectAll: true,
      filter: true,
    })

    $("select#faq_topics").multipleSelect("uncheckAll")

    // Enable Drag Drop Of Editor

    $("#baf_editor_popup").draggable({
      cursor: "move",
      drag: function () {
        $(this).css({
          height: "auto",
        })
      },
    })
  }

  function handle_baf_sc_content() {
    return jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "HTML",
      data: {
        action: "baf_sc_content", // action will be the function name
      },
    })
  }

  /*-- Start TinyMCE Integration Code --*/

  tinymce.PluginManager.add("baf", function (editor, url) {
    // Add Button to Visual Editor Toolbar
    editor.addButton("baf", {
      title: "BWL Advanced FAQ Shortcode Editor",
      cmd: "baf",
      image: url + "/icons/bwl-adv-faq-editor.png",
    })

    editor.addCommand("baf", function () {
      if ($("#shortcode_controle").length) {
        $("#shortcode_controle").remove()
      } else {
        var baf_sc_loading_icon = '<img src="' + url + '/icons/load_icon.gif" class="baf_sc_load_icon"></img>'

        $("body").append('<div id="baf_editor_overlay"><div id="baf_editor_popup">' + baf_sc_loading_icon + "</div></div>")

        $.when(handle_baf_sc_content()).done(function (data) {
          $("#baf_editor_popup").html("").html(data).draggable({ cursor: "move" })
          baf_add_sc_action()

          $("#baf_editor_popup").css("margin-top", $(window).height() / 2 - $("#baf_editor_popup").height() / 2)

          $(window).resize(function () {
            $("#baf_editor_popup").css("margin-top", $(window).height() / 2 - $("#baf_editor_popup").height() / 2)
          })
        })
      }

      return
    })
  })
})(jQuery)
