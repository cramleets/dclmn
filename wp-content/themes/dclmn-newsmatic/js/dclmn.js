jQuery(document).ready(function ($) {
  $('.dclmn-mobile-header').insertBefore('#header-menu');

  function setActiveLink() {
    var hash = window.location.hash.replace(/#/g, '');
    $('.button-group-voting-center a').removeClass('current');
    if (hash) {
      $('.button-group-voting-center a.' + hash).addClass('current');
    }
  }

  //init
  setActiveLink();

  $('.dclmn-mobile-header-open').on('click', function(e){
    e.stopPropagation();
    $('#site-navigation').toggleClass('toggled').show();
    $('body').addClass('menu-open');
  });

  $('.dclmn-mobile-header-close').on('click', function(){
    $('#site-navigation').removeClass('toggled').hide();
    $('body').removeClass('menu-open');
  });

  //listen
  $(window).on('hashchange', setActiveLink);

  $('.button-group-voting-center a').on('click', function () {
    $('.button-group-voting-center a').removeClass('current');
    $(this).addClass('current');
  });
});