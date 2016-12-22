<?php
$wp_content = explode( "wp-content" , __FILE__ );
require_once( $wp_content[0] . "wp-load.php" );
require_once( $wp_content[0] . '/wp-admin/admin.php' );

$path = plugin_dir_path( __FILE__ );

if( isset( $_GET['list_id'] ) ) {
	
	$smile_lists = get_option('smile_lists');
	$provider = '';
	$list_name = '';
	$list_id = $_GET['list_id'];
	if( $smile_lists )  {
	  if( isset($smile_lists[$list_id]) ) {
		$list = $smile_lists[$list_id];
		$list_name = $list['list-name'];
		$provider = $list['list-provider'];
	  }      
	}

	
	$id = isset( $list['list'] ) ? $list['list'] : '';
	$cp_list_id = 'cp_list_'.$_GET['list_id'];
	$listName = str_replace(" ","_",strtolower( trim( $list['list-name'] ) ) );
	$mailer = str_replace(" ","_",strtolower( trim( $provider ) ) );
	if( $mailer !== "convert_plug" ){
		$listOption = "cp_".$mailer."_".$listName;
		$contacts = get_option( $listOption);
	} else {
		$listOption = "cp_connects_".$listName;
		$contacts = get_option($listOption);
	}
	
	$list_contacts = $contacts;
    
    if( is_array($list_contacts) ) {
		
		$export_data = cpGenerateCsv($list_contacts);
		$content = $export_data;
		
		$file_name = $path.'cp_export['.$list_name.'].csv';
		$file_url = plugins_url('cp_export['.$list_name.'].csv', __FILE__ );
		$handle = fopen($file_name, "w");
		fwrite($handle, $content);
		fclose($handle);
		
		header('Pragma: public'); 	// required
		header('Expires: 0');		// no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_name)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.filesize($file_name));	// provide file size
		header('Connection: close');
		readfile($file_name);
		unlink( $file_name );
		exit();
	} else {
		exit();
	}
	
} else {
	echo '<script type="text/javascript">window.close();</script>';
}