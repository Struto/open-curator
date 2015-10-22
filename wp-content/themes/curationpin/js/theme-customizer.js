/**
*** The customizer for CurationPin
 */
( function( $ ) {

	wp.customize( 'curationpin_menu_bg_color', function( value )
	{ value.bind( function( newval ) 
		{ 
			$('#main-nav-wrapper').css('background', newval );
		}); 
	});
	
	wp.customize( 'curationpin_menu_link_color', function( value )
	{ value.bind( function( newval ) 
		{ 
			$('.main-nav ul li a').css('color', newval );
			$('.main-nav ul ul li a').css('color', newval );
		}); 
	});
	
	wp.customize( 'curationpin_logo_padding_right', function( value ) {
		value.bind( function( to ) {
//			$( '.main_logo' ).css( {'padding-right': to + 'px'} );
			$( '.main_logo' ).css('cssText','padding-right:'+to+'px !important');
		} );
	} );
	
} )( jQuery );