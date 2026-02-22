jQuery(document).ready(function ($) {
  $('.dclmn-mobile-header').insertBefore('#header-menu');

  function setActiveLink() {
    var hash = window.location.hash.replace(/#/g, '');
    console.log(hash);
    $('.button-group-voting-center a').removeClass('current');
    if (hash) {
      $('.button-group-voting-center a.' + hash).addClass('current');
    }
  }

  //init
  setActiveLink();

  $('.dclmn-mobile-header-open').on('click', function (e) {
    e.stopPropagation();
    $('#site-navigation').toggleClass('toggled').show();
    $('body').addClass('menu-open');
  });

  $('.dclmn-mobile-header-close').on('click', function () {
    $('#site-navigation').removeClass('toggled').hide();
    $('body').removeClass('menu-open');
  });

  //listen
  $(window).on('hashchange', setActiveLink);

  $('.button-group-voting-center a').on('click', function () {
    $('.button-group-voting-center a').removeClass('current');
    $(this).addClass('current');
  });

  $('.menu-main-menu-container').addClass('menu-menu-1-container');

  $('.street-name-refresh').on('click', function (e) {
    e.preventDefault();
    $('#street-name-generator-content').html('<img src="/wp-includes/images/spinner.gif">');
    $.get(sbiajaxurl, { action: 'get_street_name' }, function (data) {
      $('#street-name-generator-content').html(data);
    });
  });

  $('#cp-login-form').on('submit', function (e) {
    e.preventDefault();

    $el = $('#cp-email');
    $result = $('#cp-login-result');
    email = $el.val();
    $el.removeClass('error');
    $result.removeClass('error').hide();
    if (!email || !/^(?!.*\.\.)[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      $el.addClass('error');
    } else {
      $result.html('<img src="/wp-includes/images/spinner.gif">').fadeIn();

      $.post(sbiajaxurl, { action: 'user_login', email: email }, function (data) {
        try {
          var json = jQuery.parseJSON(data);
        } catch (err) {
          $result.addClass('error').html(err.message);
          return;
        }

        if ('success' != json.status) {
          $result.addClass('error').html('<span class="result">' + json.message + '</span>');
        } else {
          $result.html('<span class="result">' + json.message + '</span>');
        }
      });
    }
  });

  $('.precinct-voters-table thead h1').on('click', function () {
    let party = $(this).data('party');
    $('.precinct-voters-table .header-row[data-party=' + party + ']').slideToggle();
    $('.precinct-voters-table tbody[data-party=' + party + ']').slideToggle();
  });

  function contact_table_toggle() {
    $('.contacts-table tr').hide();
    $('.contact-table-toggle:checked').each(function () {
      $('.contacts-table tr[data-contact_type_id=' + $(this).data('contact_type_id') + ']').show();
    });
  }


  function email_checked_cps() {
    let emails = get_checked_cps();

    if (!emails.length) {
      alert('No One Selected.');
      return;
    }

    // Flags to control where emails go
    const toFlag = $('#to_button').is(':checked');
    const bccFlag = $('#bcc_button').is(':checked');

    let url = 'mailto:';
    if (toFlag) url += emails.join(',');

    const params = [];
    if (bccFlag) params.push('bcc=' + encodeURIComponent(emails.join(',')));

    if (params.length) url += '?' + params.join('&');

    window.open(url);
  }

  function get_checked_cps() {
    let emails = [];
    $('.email-checkbox:checked').each(function () {
      const email = $(this).closest('tr').find('td[data-label="Email"]').text().trim();
      if (email) emails.push(email);
    });

    return emails;
  }

  function copy_checked_cps() {
    let emails = get_checked_cps();

    if (!emails.length) {
      alert('No One Selected.');
      return false;
    }

    // Collect checked emails
    $(emails).each(function () {
      const email = $(this).closest('tr').find('td[data-label="Email"]').text().trim();
      if (email) emails.push(email);
    });
    let dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    dummy.value = emails.join(',');
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);

    return true;
  }

  $('.email-checkbox-all').on('click', function () {
    $('.email-checkbox, .email-checkbox-all').prop('checked', $(this).is(':checked'));    
  });

  $('.email-checked').on('click', function (e) {
    e.preventDefault();
    let checked_cps = get_checked_cps();
    let $el = $(this);
    let text_holder = $el.text();

    if (!checked_cps.length) {
      $el.addClass('error').text('No One Selected');
      setTimeout(function () {
        $el.text(text_holder).removeClass('error');
      }, 1500);
      return;
    }
    $('body').addClass('modal-open');
  });

  $('.copy-checked').on('click', function (e) {
    e.preventDefault();

    let checked_cps = get_checked_cps();
    console.log(checked_cps);
    let $el = $(this);
    let text_holder = $el.text();

    //delay this interacction for a moment. "feels" better.
    setTimeout(function () {
      if (!checked_cps.length) {
        $el.addClass('error').text('No One Selected');
        setTimeout(function () {
          $el.text(text_holder).removeClass('error');
        }, 1500);
        return;
      }

      if (copy_checked_cps()) {
        $el.addClass('loading').text('Copied');
        setTimeout(function () {
          $el.text(text_holder).removeClass('loading');
        }, 1000);
      }
    }, 90);

  });

  $(document).on('click', '#prestitial-close', function () {
    $('body').removeClass('modal-open');
  });

  $(document).on('click', '.email-go', function () {
    email_checked_cps();
    $('body').removeClass('modal-open');
  });
})