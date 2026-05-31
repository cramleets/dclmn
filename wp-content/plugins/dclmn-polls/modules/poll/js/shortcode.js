jQuery(document).ready(function ($) {
  $('.dclmn-poll-wrapper form').on('submit', function (e) {
    e.preventDefault();
    if (!$('input[name=choice_id]:checked').length) {
      alert('Please make a selection.');
      return false;
    }

    e.preventDefault();

    var $el_result = $('.dclmn-poll-action-wrapper');
    
    $el_result.css('min-height', $el_result.height()+'px');

    $el_result.addClass('loading');
    $el_result.removeClass('ajax-result ajax-result-error').html('<img src="/wp-includes/images/spinner.gif">').fadeIn();

    data = {
      action: 'dclmn_poll_vote',
      data: $(this).serialize(),
    };

    $.post(ajaxurl, data, function (data) {
      $el_result.removeClass('loading');

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
});