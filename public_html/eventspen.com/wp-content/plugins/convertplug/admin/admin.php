<?php
if( isset( $_GET[ 'view' ] ) && $_GET[ 'view' ] == 'smile-mailer-integrations' ) {
	require_once( 'integrations.php' );
} else if( isset( $_GET[ 'view' ] ) && $_GET[ 'view' ] == 'modules' ) {
	require_once( 'modules.php' );
} else if( isset( $_GET['view'] ) && $_GET['view'] ==  'settings' ) {
	require_once( 'settings.php' );
} else if( isset( $_GET['view'] ) && $_GET['view'] ==  'cp_import' ) {
	require_once( 'cp_import.php' );
} else if( isset( $_GET['view'] ) && $_GET['view'] ==  'registration' ) {
	require_once( 'registration.php' );
} else if( isset( $_GET['view'] ) && $_GET['view'] ==  'debug' ) {
	require_once( 'debug.php' );
} else if( isset( $_GET['view'] ) && $_GET['view'] ==  'knowledge_base' ) {
	require_once( 'knowledge_base.php' );
} else {
	require_once('get_started.php');
}
