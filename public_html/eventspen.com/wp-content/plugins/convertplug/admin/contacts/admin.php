<?php 
if( isset( $_GET[ 'view' ] ) && $_GET[ 'view' ] == 'new-list' ) {
	require_once( 'views/new-list.php' );
} elseif( isset( $_GET[ 'view' ] ) && $_GET[ 'view' ] == 'contacts' ) {
	require_once( 'views/contacts.php' );
} elseif( isset( $_GET[ 'view' ] ) && $_GET[ 'view' ] == 'analytics' ) {
	require_once( 'views/analytics.php' );
} else if( isset( $_GET[ 'view' ] ) && $_GET[ 'view' ] == 'contact-details' ) {
	require_once( 'views/contact-details.php' );
} else {
	require_once( 'views/dashboard.php' );
}