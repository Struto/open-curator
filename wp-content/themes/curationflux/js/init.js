jQuery(document).ready(function(){
	
	numPosts = $('.post', '#post-list').length;
	sidebarWidgets = $('.widget', '#sidebar-single');
	numSidebarWidgets = sidebarWidgets.length;
	footerWidgets = $('.widget', '#sidebar-footer');
	numFooterWidgets = footerWidgets.length;
	
	$('.post', '#post-list').each(
		function()
		{
			if($('.no-more', this).length > 0) return;
			$(this).click(
				function()
				{
					window.location.href = $('a', this).first().attr('href');
				}
			);
		}
	);
	
	$('.gallery img', '#post').hover(
		function()
		{
			var g = $('.gallery img', '#post');
			for(var i = 0; i < g.length; i++)
			{
				$(g[i]).stop(true).animate({ opacity: $(this).attr('src') == $(g[i]).attr('src') ? 1 : .5 }, 300);
			}
		},
		function()
		{
			$('.gallery img', '#post').stop(true).animate({ opacity: 1 }, 300);
		}
	);
	
	$('.gallery', '#post').each(
		function()
		{
			$(this).magnificPopup({
				delegate: 'a[href$=".jpg"],a[href$=".png"],a[href$=".webp"],a[href$=".gif"]',
				type: 'image',
				gallery: { enabled: true }
			});
		}
	);
	
	$('a[href$=".jpg"],a[href$=".png"],a[href$=".webp"],a[href$=".gif"]', '#post').each(
		function()
		{
			if(!$(this).parent().hasClass('gallery-icon'))
			{
				$(this)
					.addClass('zoomLink')
					.magnificPopup({ type: 'image' })
				;
			}
		}
	);
	
	$(window).resize(
		function(e)
		{
			bodyClasses = $('body').attr('class');
			var w = $(window).width();
			var s = [480, 640, 800, 1280];
			for(var i in s) $('body').removeClass('lt-' + s[i] + ' gt-' + s[i]);
			for(var i in s) $('body').addClass((w >= s[i] ? 'g' : 'l') + 't-' + s[i]);
			
			if((numPosts > 0 || numSidebarWidgets > 0 || numFooterWidgets > 0) && bodyClasses != $('body').attr('class'))
			{
				resortColumns();
			}
		}
	);
	$(window).resize();
	
	$('#mobile-menu').click(
		function(event)
		{
			event.preventDefault();
			
			if($('#page').hasClass('open'))
			{
				$('#page').removeClass('open');
			}
			else
			{
				$('#page').addClass('open');
			}
			return false;
		}
	);
});

var numPosts,
	sidebarWidgets,
	numSidebarWidgets,
	footerWidgets,
	numFooterWidgets,
	bodyClasses
;

var resortColumns = function()
{
	var numCols = 4;
	if($('body').hasClass('lt-800')) numCols = 3;
	if($('body').hasClass('lt-640')) numCols = 2;
	if($('body').hasClass('lt-480')) numCols = 1;
	
	for(var i = 0; i < numPosts; i++)
	{
		$('#post-' + i).appendTo($('#col' + ((i % numCols) + 1)));
	}
	
	if($('body').hasClass('lt-800'))
	{
		for(var i = 0; i < numSidebarWidgets; i++)
		{
			$(sidebarWidgets[i]).appendTo($('#scol' + ((i % numCols) + 1)));
		}
	}
	else
	{
		for(var i = 0; i < numSidebarWidgets; i++)
		{
			$(sidebarWidgets[i]).appendTo($('#scol1'));
		}
	}
	
	for(var i = 0; i < numFooterWidgets; i++)
	{
		console.info(numFooterWidgets);
		$(footerWidgets[i]).appendTo($('#fcol' + ((i % numCols) + 1)));
	}
}