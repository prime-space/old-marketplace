function category_btn( id ) {
	if ( id_menu_open != id && id_menu_open != 0 ) {
		$( '[data-pod_category="' + id_menu_open + '"]' ).slideToggle();
		$( '[data-category="' + id_menu_open + '"]' ).toggleClass( 'active' );
		id_menu_open = id;

	} else if ( id_menu_open == id && id_menu_open != 0 ) {
		id_menu_open = 0;

	} else if ( id_menu_open != id && id_menu_open == 0 ) {
		id_menu_open = id;

	}

	$( '[data-pod_category="' + id + '"]' ).slideToggle();
	$( '[data-category="' + id + '"]' ).toggleClass( 'active' );

};

var doc = $( document ),
	id_menu_open = 0,
	id_spoiler = 0;

doc.on( 'click', '[data-category-btn]', function () {
	var id = $( this ).data( 'category-btn' );
	category_btn( id );

}).on( 'click', '[daya-gar_widg="btn"]', function () {
	$( '[daya-gar_widg="btn"]' ).toggleClass( 'active' );
	$( '[daya-gar_widg="content"]' ).slideToggle();

}).on( 'click', '[data-btn_tabs_id]', function () {
	var a = $( this ).data( 'btn_tabs_id' );

	$( '[data-btn_tabs_id]' ).removeClass( 'active' );
	$( '[data-btn_tabs_content]' ).removeClass( 'active' );
	$( '[data-btn_tabs_id="' + a + '"]' ).addClass( 'active' );
	$( '[data-btn_tabs_content="' + a + '"]' ).addClass( 'active' );

}).on( 'click', '[data-tabs-btn]', function () {
	if ( ! $( this ).hasClass( 'active' ) ) {
		var id = $( this ).data( 'tabs-btn' );
		$( '[data-tabs-btn]' ).removeClass( 'active' );
		$( '[data-tabs-content]' ).removeClass( 'active' );
		$( '[data-tabs-btn="' + id + '"]' ).addClass( 'active' );
		$( '[data-tabs-content="' + id + '"]' ).addClass( 'active' );

	}

}).on( 'click', '[data-spoiler_title]', function () {
	var a = $( this ).data( 'spoiler_title' );

	if ( id_spoiler != 0 && id_spoiler == a ) {
		$( '[data-spoiler_title="' + a + '"]' ).html( $( this ).data( 'spoiler_title_open' ) );
		$( '[data-spoiler_content="' + a + '"]' ).slideUp();
		id_spoiler = 0;

	} else if ( id_spoiler != 0 && id_spoiler != a ) {
		$( '[data-spoiler_title="' + id_spoiler + '"]' ).html( $( this ).data( 'spoiler_title_open' ) );
		$( '[data-spoiler_content="' + id_spoiler + '"]' ).slideUp();
		$( '[data-spoiler_title="' + a + '"]' ).html( $( this ).data( 'spoiler_title_closed' ) );
		$( '[data-spoiler_content="' + a + '"]' ).slideDown();
		id_spoiler = a;

	} else if ( id_spoiler == 0 && id_spoiler != a ) {
		$( '[data-spoiler_title="' + a + '"]' ).html( $( this ).data( 'spoiler_title_closed' ) );
		$( '[data-spoiler_content="' + a + '"]' ).slideDown();
		id_spoiler = a;

	}

}).ready( function ( $ ) {
	$( '[data-toggle="tooltip"]' ).tooltip({container: 'body'});
	$( 'select:not([multiple])' ).styler();
	get_share_button();

});
