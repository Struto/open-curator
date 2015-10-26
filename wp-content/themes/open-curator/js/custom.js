/**
 * Created by Archie22is on 15/10/23.
 */

jQuery( document ).ready(function($) {


    // Rotate Featured Home Page Posts
    $(".slides > div:gt(0)").hide();

    setInterval(function() {
        $('.slides > li:first')
            .fadeOut(1000)
            .next()
            .fadeIn(1000)
            .end()
            .appendTo('.slides');
    },  3000);





});