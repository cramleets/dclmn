jQuery(document).ready(function ($) {

  window.get_poll_choices = function (post_id = false) {

    if (!post_id)
      post_id = $('#post_ID').val();

    $('#poll-choices-target').html('<img src="/wp-includes/images/spinner.gif">');

    data = {
      action: 'get_poll_choices',
      parent_id: post_id,
    };

    $.get(ajaxurl, data, function (data) {
      $('#poll-choices-target').html(data);
      make_sortable();
    });
  }

  $(document).on('click', '.open-poll-choice-modal', function (e) {
    e.preventDefault();
    var parent_id = $('#post_ID').val();
    var choice_id = $(this).attr('data-choice-id');
    var url = ajaxurl + '?action=poll_choice_modal&parent_id=' + parent_id;
    if (typeof choice_id !== "undefined") {
      url += '&choice_id=' + choice_id;
    }

    tb_show('Add Choice to Post #' + parent_id, url);
  });

  $('.get-poll-choices').on('click', function () {
    get_poll_choices();
  });

  $(document).on('click', '.poll-choice-delete', function (e) {
    e.preventDefault();
    var post_id = $(this).attr('data-choice-id');
    if (!confirm('Are you sure?')) {
      return;
    }

    data = {
      action: 'poll_choice_delete',
      post_id: post_id,
    };

    $('#poll-choices-target tbody tr[data-post-id=' + post_id + ']').addClass('deleting');

    $.post(ajaxurl, data, function (data) {
      $('#poll-choices-target tbody tr[data-post-id=' + post_id + ']').addClass('deleted').fadeOut(750);
    });
  });

  $(document).on('choices_ready', function () {
    get_poll_choices();
  });

  function make_sortable() {
    $('#poll-choices-target tbody').sortable({
      update: function (event, ui) {
        var $el_result = $('#sort_choices_result');
        var sortedIDs = $('#poll-choices-target tbody').sortable('toArray');

        $el_result.removeClass('ajax-result ajax-result-error').html('<img src="/wp-includes/images/spinner.gif">').fadeIn();

        data = {
          action: 'sort_poll_choices',
          poll_id: $('#post_ID').val(),
          choice_ids: sortedIDs,
        };

        $.post(ajaxurl, data, function (data) {
          var data = jQuery.parseJSON(data);
          if (!data) {
            out = 'Invalid return.';
            $el_result.addClass('ajax-result-error');
          } else {
            out = data.msg;
            $el_result.addClass('ajax-result').html(out).fadeIn();
            if ('fail' == data.status) {
              $el_result.addClass('ajax-result-error');
            }
          }

          $el_result.html(out).fadeIn('slow').css('display', 'inline-block').delay(2200).fadeOut('fast');
        });
      }
    });
  }

  $(document).trigger('choices_ready');
});
