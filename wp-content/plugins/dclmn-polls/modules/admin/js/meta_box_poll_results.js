
jQuery(document).ready(function ($) {
  $('#results-refresh-trigger').on('click', function (e) {
    e.preventDefault();

    var $el_result = $('#results-target');

    $('#results-target-wrapper').addClass('loading');
    $el_result.removeClass('ajax-result ajax-result-error');

    data = {
      action: 'results_refresh',
      poll_id: $('#post_ID').val(),
    };

    $.post(ajaxurl, data, function (data) {
      $('#results-target-wrapper').removeClass('loading');

      var data = jQuery.parseJSON(data);
      if (!data) {
        out = 'Invalid return.';
      } else {
        out = data.msg;
        $el_result.html(out).fadeIn();
      }

      $el_result.html(out).fadeIn();
    });
  });
  
  $('#votes-refresh-trigger').on('click', function (e) {
    e.preventDefault();

    var $el_result = $('#votes-target');

    $('#votes-target-wrapper').addClass('loading');
    $el_result.removeClass('ajax-result ajax-result-error').html('<img src="/wp-includes/images/spinner.gif">');

    data = {
      action: 'votes_refresh',
      poll_id: $('#post_ID').val(),
    };

    $.post(ajaxurl, data, function (data) {
      $('#votes-target-wrapper').removeClass('loading');

      var data = jQuery.parseJSON(data);
      if (!data) {
        out = 'Invalid return.';
      } else {
        out = data.msg;
        $el_result.html(out).fadeIn();
      }

      $el_result.html(out).fadeIn();
    });
  });
  
  $('.delete-all-poll-votes').on('click', function(){
    if (!confirm('Are you sure you want to delete all votes for this poll?')) {
      return
    }
    if (!confirm('I mean seriously. Are you really, really sure?')) {
      return
    }
    
    $el_result = $('.delete-all-poll-votes-results');
    $el_result.removeClass('ajax-result ajax-result-error').html('<img src="/wp-includes/images/spinner.gif">');

    data = {
      action: 'votes_delete',
      poll_id: $('#post_ID').val(),
    };

    $.post(ajaxurl, data, function (data) {
      var data = jQuery.parseJSON(data);
      if (!data) {
        out = 'Invalid return.';
      } else {
        out = data.msg;
        $el_result.html(out).addClass('ajax-result').fadeIn();
      }

      $el_result.html(out).fadeIn();
    });
  });
});