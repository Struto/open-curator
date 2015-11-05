/**
 * Created by Archie22is on 15/10/23.
 */

jQuery( document ).ready(function($) {

    // Featured videos fancybox
    //$('.various').click( function() {
        //$(".various").fancybox({
        $(".various").fancybox({
            maxWidth	: 800,
            maxHeight	: 600,
            fitToView	: false,
            width		: '90%',
            height		: '90%',
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
        });
        //return false;
    //});


    // Video Masonry
    var $container = $('.work');
    $container.packery({
        itemSelector: '.box',
        isAnimated: true
    });


    // Some scripts here


});
