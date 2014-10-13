jQuery(document).ready(function ($) {

    if ($('[class^="standard_slider_slider"]').length) {
        if ($("#slider-container").length > 0) {
            $("#slider-container").empty();
            $('[class^="standard_slider_slider"]').detach().appendTo('#slider-container');
        }
        $(".standard_slider_slider").StandardSlider({
            animType: "slide-vertical",
            animInfinity: true,
            showSlideNav: false,
            autoAnim: true,
            showPauseButton: false,
            showBackNext: true,
            animTime: 1200,
            slideTime: 5000
        });
    }

    if ($('[class^="standard_slider_tab"]').length) {
        if ($("#slider-container").length > 0) {
            $("#slider-container").empty();
            $('[class^="standard_slider_tab"]').detach().appendTo('#slider-container');
        }
        $(".standard_slider_tab").StandardSlider({
            animType: "slide-vertical",
            animInfinity: true,
            showSlideNav: true,
            autoAnim: true,
            showPauseButton: false,
            showBackNext: false,
            animTime: 1200,
            slideTime: 5000
        });
    }

});
