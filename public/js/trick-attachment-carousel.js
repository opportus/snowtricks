jQuery(document).ready(function($) {
    if ($(window).width() > 768) {
        if ($('#trick-attachment-carousel .carousel-inner').children().length > 3) {
            $('.trick-attachment-carousel-control').removeClass('d-none');
        }
    } else {
        if ($('#trick-attachment-carousel .carousel.inner').children().length > 1) {
            $('.trick-attachment-carousel-control').removeClass('d-none');
        }
    }
});

jQuery('.trick-attachment-carousel-toggler').on('click', function() {
    $(this).hide();
    $(this).next().show();
});

$('#trick-attachment-carousel').on('slide.bs.carousel', function (e) {
    var $e = $(e.relatedTarget);
    var idx = $e.index();
    var itemsPerSlide = 3;
    var totalItems = $('.carousel-item').length;
    
    if (idx >= totalItems-(itemsPerSlide-1)) {
        var it = itemsPerSlide - (totalItems - idx);
        for (var i=0; i<it; i++) {
            // append slides to end
            if (e.direction=="left") {
                $('.carousel-item').eq(i).appendTo('.carousel-inner');
            }
            else {
                $('.carousel-item').eq(0).appendTo('.carousel-inner');
            }
        }
    }
});
