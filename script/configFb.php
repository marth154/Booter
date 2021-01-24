<?php
	session_start();
	require_once "Facebook/autoload.php";
	
	$FB = new \Facebook\Facebook([
	
		'app_id' => '664679457390505',
		'app_secret' => '0154c6786c56ff9dc003732e75c7637f',
		'default_graph_version' => 'v5.0'
	
	]);
	
	$helper = $FB->getRedirectLoginHelper();

?>