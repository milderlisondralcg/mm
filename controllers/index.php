<?php
error_reporting(E_ALL);
spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = '../models/';

    include $path.$className.'.php';
}

$media = new Media();

/***************** Load Azure classes **************************/

require_once '../../azureblob/vendor/autoload.php';
//require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('BLOB_NAME').";AccountKey=".getenv('BLOB_KEY');
$blobClient = BlobRestProxy::createBlobService($connectionString);

/***************************************************************/


// Uploads location
$uploads_path = "../uploads/";


if($_FILES){ print_r($_FILES);
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt','tiff','zip','csv','xls','xlsx','sql','txt'); // valid extensions

	if(!empty($_POST['name']) || !empty($_POST['email']) || $_FILES['file_upload']){
		$uploaded_filename = $_FILES['file_upload']['name'];
		$tmp = $_FILES['file_upload']['tmp_name'];

		// get uploaded file's extension
		$ext = strtolower(pathinfo($uploaded_filename, PATHINFO_EXTENSION));

		// check's valid format
		if(in_array($ext, $valid_extensions)) { 

			$moved_filename = trim(strtolower($uploaded_filename));
			$final_path = $uploads_path.$moved_filename; 

			if(move_uploaded_file($tmp,$final_path)) {
				
				// Determine file category
				$file_category = trim($_POST['Category']);
				$file_mime_type = mime_content_type($final_path);
				$_POST['saved_media'] = $moved_filename;
				$result = $media->add($_POST);
				
				$log_data = array("user"=>"milder.lisondra@yahoo.com","action"=>"Add new media","object"=>"Media","previous_data"=>"N/A","updated_data"=>$file_mime_type);
				$media->log_action($log_data); // Log admin action
				
				if( $result['result'] == true){
					$azure_filename = strtolower(str_replace(" " ,"-",$_POST['Title'])) . "-" . $result['ID']. "." . $ext;
					// Update the record with the filename actually stored in Azure
					$update_data = array("saved_media"=>$azure_filename,"id"=>$result['ID']);
					$media->update($update_data);
					$containerName = "files";
					$content = fopen($final_path, "r");
					//Upload media asset to Azure Blob Storage
					$azure_upload_result = $blobClient->createBlockBlob($containerName, $azure_filename, $content);	

				}
			}
		}else{
			echo 'invalid';
		}
	}
}
