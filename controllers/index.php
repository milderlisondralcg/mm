<?php

spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = '../models/';

    include $path.$className.'.php';
}

$media = new Media();

// Uploads location
$uploads_path = "../uploads/";


if($_FILES){
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt','tiff'); // valid extensions
	
	//$path = '../uploads/'; // upload directory

	if(!empty($_POST['name']) || !empty($_POST['email']) || $_FILES['image']){
		$img = $_FILES['image']['name'];
		$tmp = $_FILES['image']['tmp_name'];

		// get uploaded file's extension
		$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

		// can upload same image using rand function
		$final_image = rand(1000,1000000).$img;

		// check's valid format
		if(in_array($ext, $valid_extensions)) { 
			$final_image = strtolower($final_image); 
			$final_path = $uploads_path.$final_image; 

			if(move_uploaded_file($tmp,$final_path)) {
				$file_mime_type = mime_content_type($final_path);
			//echo "<img src='$path' />";
				$_POST['saved_media'] = $final_image;
				$data = array("user"=>"milder.lisondra@yahoo.com","action"=>"Add new media","object"=>"Media","previous_data"=>"N/A","updated_data"=>$file_mime_type);
				$media->add($_POST);
				$media->log_action($data);
			}
		}else{
			echo 'invalid';
		}
	}
}

// log actions taken by admins
function log_action(){
	
}