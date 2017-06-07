jQuery(document).on( 'wplink-open', function( wrap ) {
    if ( NEW_TAB_DEFAULT == '1' ) {
        jQuery( 'input#wp-link-target' ).prop('checked', true );
    }
    jQuery("#wp-link .query-results").css("top", "180px");
});
