// remap jQuery to $
(function($){})(window.jQuery);

/* trigger when page is ready */
$(document).ready(function (){

$(function() {
	// Find all YouTube videos
	var $allVideos = $("iframe[src^='http://www.youtube.com']"),
    // The element that is fluid width
    $fluidEl = $("article");
	// Figure out and save aspect ratio for each video
	$allVideos.each(function() {
		$(this)
			.data('aspectRatio', this.height / this.width)
			// and remove the hard coded width/height
			.removeAttr('height')
			.removeAttr('width');
	});

	// When the window is resized
	// (You'll probably want to debounce this)
	$(window).resize(function() {
		var newWidth = $fluidEl.width();
		// Resize all videos according to their own aspect ratio
		$allVideos.each(function() {
			var $el = $(this);
			$el
				.width(newWidth-30)
				.height(newWidth * $el.data('aspectRatio'));

		});
	// Kick off one resize to fix all videos on page load
	}).resize();

});
// end of function for youtube
});