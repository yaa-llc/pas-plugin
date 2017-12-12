jQuery(function($){
   
   $(".wpb_portfolio").delegate(".wpb_fp_preview", "click", function (e) {

      /* add loader  */
      $(this).after('<div class="wpb-fp-loading dark"><i></i><i></i><i></i><i></i></div>');

      var post_id = $(this).attr('data-post-id');
      var lightbox_effect = $(this).attr('data-effect');
      var data = { action: 'wpb_fp_quickview', portfolio: post_id};
         $.post(wpb_fp_ajax_name.ajax_url, data, function(response) {

            $.magnificPopup.open({
               mainClass: lightbox_effect,
               tLoading: '',
               type: 'ajax',
               gallery:{
                  enabled:true,
               },
               delegate: 'a.wpb_fp_preview',
               items: {
                  src: '<div class="white-popup mfp-with-anim wpb_fp_quick_view">'+response+'</div>',
                  type: 'inline'
               }
            });

            /* remove loader  */
            $('.wpb-fp-loading').remove();
         });

      e.preventDefault();
   });

});