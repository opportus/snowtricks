jQuery(document).ready(function($) {
    $('#arrow-scroll-down').click(function() {
        $('html, body').animate({ scrollTop: $(window).height() + $('.navbar').outerHeight() }, 600);

        return false;
    });

    $('#arrow-scroll-up').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 600);

        return false;
    });

    $(document).scroll(function() {
        if ($(window).scrollTop() > $(window).height()) {
            $('#arrow-scroll-up').fadeIn();

        } else if ($('#arrow-scroll-up').css('display') !== 'none') {
            $('#arrow-scroll-up').fadeOut();
        }
    });
});
