/**
*** The customizer for CurationPin
 */
( function( $ ) {


	wp.customize( 'curationnews_menu_top_padding', function( value ) {
		value.bind( function( to ) {
			$( '#nav-main' ).css('padding-top', to+'px');
		} );
	} );
	wp.customize( 'curationflux_header_image_bottom_padding', function( value ) {
		value.bind( function( to ) {
			$( '#header_image' ).css('padding-bottom', to+'px');
		} );
	} );
	
	wp.customize( 'curationflux_post_top_padding', function( value ) {
		value.bind( function( to ) {
			$( '.home #post-list' ).css('padding-top', to+'px');
		} );
	} );



} )( jQuery );