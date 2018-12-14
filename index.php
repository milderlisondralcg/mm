<?php
header("Location: /_media_manager/home.php");
//check for parameter

/*
if( !isset($_GET['user']) ){
	header("Location: /_media_manager/noaccess.php");
}else{

$url = 'https://charlie.coherent.com/api/authenticate/?userid=' . $_GET['user'];
$curl_handler = curl_init();
curl_setopt($curl_handler, CURLOPT_URL, $url);
//curl_setopt($curl_handler, CURLOPT_HTTPGET, true);
curl_setopt($curl_handler, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json'
));

$result = curl_exec($curl_handler);
curl_close($curl_handler);
//print_r($result);

//print json_decode($result);

	// TODO: Session variable
	//header("Location: /_media_manager/home.php");
}

*/