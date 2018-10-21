(function ($) {
  'use strict';

  $(document).ready(function ($) {

    showHideDisplayTypeMetaBox();

    $('.relations_select').select2({
      dir: $('body').hasClass('rtl') ? "rtl" : 'ltr'
    });

    $('select.relations_select.search_posts').select2({
      ajax: {
        url: relations_ajax.url,
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            action: 'relations_search_posts',
            q: params.term
          };
        },
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: false
      },
      minimumInputLength: 3,
      placeholder: relations_ajax.typeThreeChar,
    });
    
    $(document).on('change', '#relations_content_type', function (event) {
      if( $(this).val() === 'custom' ) {
        $('.relation_field.custom_posts').slideDown(150);
      } else {
        $('.relation_field.custom_posts').slideUp(150);
      }
    });

    $(document).on('change', '#relations_display_type', function (event) {
      if( $(this).val() === 'custom' ) {
        $('div#relations_template_custom_template').slideDown(150);
      } else {
        $('div#relations_template_custom_template').slideUp(150);
      }
    });


    $(document).on('change', '#relations_position', function (event) {
      if( $(this).val() === 'inline' ) {
        $('.relation_field.inject_paragraph_number').slideDown(150);
      } else {
        $('.relation_field.inject_paragraph_number').slideUp(150);
      }
    });


    function showHideDisplayTypeMetaBox() {
      var displayType = $('#relations_display_type').val();
      if( displayType !== 'custom' ) {
        $('div#relations_template_custom_template').hide();
      }
    }


  });

})(jQuery);
