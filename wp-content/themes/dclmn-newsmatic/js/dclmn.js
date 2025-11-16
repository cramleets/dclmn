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
})