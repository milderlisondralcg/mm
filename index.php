<?php
session_start();

/* is this app the www site ************************************ */
$isLive = getenv('CUSTOMCONNSTR_IS_LIVE') === 'true'? true: false;
/* initialize give access variable */
$giveAccess = true;

if($isLive) { Require('../App_Constant/security.php'); };

if(!isset($_SESSION['username'])){
	header("Location: /_admin_mm/noaccess.php");
}else{
	header("Location: /_admin_mm/home.php");
}