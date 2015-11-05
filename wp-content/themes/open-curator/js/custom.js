/**
 * Created by Archie22is on 15/10/23.
 */

jQuery( document ).ready(function($) {

    //
    $(".various").fancybox({
        maxWidth	: 800,
        maxHeight	: 600,
        fitToView	: false,
        width		: '70%',
        height		: '70%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });


    // Video Masonry
    var $container = $('.work');

    $container.packery({
        itemSelector: '.box',
        isAnimated: true
    });


    // Some scripts here


});
