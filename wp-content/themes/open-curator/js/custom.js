/**
 * Created by Archie22is on 15/10/23.
 */

jQuery( document ).ready(function($) {


    // Video Masonry
    var $container = $('#masonry');
    $container.masonry({
        itemSelector: '.grid-item',
        isAnimated: true
    });


    /*
    $('#masonry').masonry({
        itemSelector: '.grid-item',
        columnWidth: 1000
    });
    */

    // Other JS here



    // Rotate Featured Home Page Posts
    /*
    $(".slides > div:gt(0)").hide();

    setInterval(function() {
        $('.slides > li:first')
            .fadeOut(1000)
            .next()
            .fadeIn(1000)
            .end()
            .appendTo('.slides');
    },  3000);
    */


});
