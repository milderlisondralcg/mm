<?php

if( !isset($_GET['user']) ){
	header("Location: /_media_manager/noaccess.php");
}else{

$user_id = trim($_GET['user']);
print $user_id;
}