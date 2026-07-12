; (function ($) {
    const hash = window.location.hash.replace('#', '');
    // Help page tab menu script.
    $('.eventful').on('click', '.header_nav_menu a', function (e) {
        if ($(this).hasClass('active')) {
            return;
        }
        let tabId = $(this).attr('data-id');
        $('.header_nav_menu a').each((i, item) => {
            $(item).removeClass('active');
            $('#' + $(item).attr('data-id')).css('display', 'none');
        })
        $(this).addClass('active');

        $('#' + tabId).css('display', 'block');

        if ('recommended-tab' === tabId) {
            $('#menu-posts-eventful ul li').each((i, item) => {
                $(item).removeClass('current');
            })
            $('#menu-posts-eventful ul li:nth-child(7)').addClass('current');
        }
        if ('lite-to-pro-tab' === tabId) {
            $('#menu-posts-eventful ul li').each((i, item) => {
                $(item).removeClass('current');
            })
            $('#menu-posts-eventful ul li:nth-child(8)').addClass('current');
        }
        if (('get-start-tab' === tabId || 'pro-plugins-tab' === tabId)) {
            $('#menu-posts-eventful ul li').each((i, item) => {
                $(item).removeClass('current');
            })
            $('#menu-posts-eventful ul li:nth-child(9)').addClass('current');
        }
    })

    $('#menu-posts-eventful').on('click', 'ul li a', (e) => {
        var element = e.target.closest('a');

        if ('edit.php?post_type=eventful&page=eventful' === $(element).attr('href')) {
            $(element).attr('href', '#get-start');
        }

        setTimeout(() => {
            var hashValue = window.location.hash.replace('#', '');
            if (hashValue) {
                $('#menu-posts-eventful ul li').each((i, item) => {
                    $(item).removeClass('current');
                })
            }
            if ('recommended' === hashValue) {
                $('.eventful .header_nav_menu a[data-id="recommended-tab"]').trigger('click');
                $(element).parent().addClass('current')
            }
            if ('lite-to-pro' === hashValue) {
                $('.eventful .header_nav_menu a[data-id="lite-to-pro-tab"]').trigger('click');
                $(element).parent().addClass('current')
            }
            if ('get-start' === hashValue) {
                $('.eventful .header_nav_menu a[data-id="get-start-tab"]').trigger('click');
                $(element).parent().addClass('current')
            }
        }, 0);
    })

    if ('recommended' === hash) {
        $('.eventful .header_nav_menu a[data-id="recommended-tab"]').trigger('click');
        $('#menu-posts-eventful ul li:nth-child(7)').removeClass('current');
        $('#menu-posts-eventful ul li:nth-child(7)').addClass('current');
    }
    if ('lite-to-pro' === hash) {
        $('.eventful .header_nav_menu a[data-id="lite-to-pro-tab"]').trigger('click');
        $('#menu-posts-eventful ul li:nth-child(7)').removeClass('current');
        $('#menu-posts-eventful ul li:nth-child(6)').addClass('current');
    }
    if ('pro-plugins' === hash) {
        $('.eventful .header_nav_menu a[data-id="pro-plugins-tab"]').trigger('click');
    }

    $('body').on('click', '.install-now', function (e) {
        var _this = $(this);
        var _href = _this.attr('href');

        _this.addClass('updating-message').html('Installing...');

        $.get(_href, function (data) {
            location.reload();
        });

        e.preventDefault();
    });

    document.addEventListener("DOMContentLoaded", function () {
        const playListItems = document.querySelectorAll(".play_list_item");
        const iframe = document.querySelector(".video iframe");

        playListItems.forEach(item => {
            item.addEventListener("click", function () {
                // Get the video ID from data attribute
                const videoId = this.getAttribute("data-video_id");

                // Update iframe source
                iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;

                // Remove 'active' class from all items
                playListItems.forEach(el => el.classList.remove("active"));

                // Add 'active' class to clicked item
                this.classList.add("active");
            });
        });
    });



})(jQuery);