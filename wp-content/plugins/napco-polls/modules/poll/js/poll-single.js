
jQuery(document).ready(function ($) {
  $('#results-refresh-trigger').on('click', function (e) {
    e.preventDefault();

    var $el_result = $('#results-target');

    $('#results-target-wrapper').addClass('loading');
    $el_result.removeClass('ajax-result ajax-result-error');

    data = {
      action: 'results_refresh',
      poll_id: $('meta[property="dclmn_page_id"]').attr('content'),
    };

    $.post(ajaxurl, data, function (data) {
      $('#results-target-wrapper').removeClass('loading');

      var data = jQuery.parseJSON(data);
      if (!data) {
        out = 'Invalid return.';
        $el_result.addClass('ajax-result-error');
      } else {
        out = data.msg;
        $el_result.addClass('ajax-result').html(out).fadeIn();
        if ('success' != data.status) {
          $el_result.addClass('ajax-result-error');
        }
      }

      $el_result.html(out).fadeIn();
    });
  });
  
  $('form.napco-poll').on('submit', function(){
    if (!$('input[name=choice_id]:checked').length) {
      alert('Please make a selection.');
      return false;
    }
  });
});
