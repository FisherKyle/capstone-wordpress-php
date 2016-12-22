<?php

$module = $_GET['module'];
$theme = $_GET['theme'];
$class = $_GET['class'];

require_once( CP_BASE_DIR.'/modules/'.$module.'/functions/functions.options.php' );

$settings = $class::$options;
foreach( $settings as $style => $options ){
	if( $style == $_GET['theme'] ){
		$demo_html = $options['demo_url'];
		$demo_dir = $options['demo_dir'];
		$customizer_js = $options['customizer_js'];
	}
}

$handle = fopen($demo_dir, "r");
$post_content = fread($handle, filesize($demo_dir));
print_r($post_content);