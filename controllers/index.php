<?php
session_start();
error_reporting(E_ALL);
spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = '../models/';

    include $path.$className.'.php';
}

$media = new Media();
$auth = new Auth();
/***************** Load Azure classes **************************/

require_once '../../azureblob/vendor/autoload.php';
//require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

// Create connection to BLOB Storage
if( $_SERVER['SERVER_NAME'] == "charlie.coherent.com" ){
	$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME').";AccountKey=".getenv('MM_BLOB_KEY'); // Golf/Development
	define("PROCESSED_URL", "https://charlie.coherent.com/go/");
	define("DIRECT_TO_FILE_URL", "https://pocmarcomgolfstorage.blob.core.windows.net/"); // Golf
}else{
	$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME_PROD').";AccountKey=".getenv('MM_BLOB_KEY_2_PROD'); // COHRstage
	define("PROCESSED_URL", "https://cohrstage.coherent.com/go/");
	define("DIRECT_TO_FILE_URL", "https://content.coherent.com/"); // Production
}
$blobClient = BlobRestProxy::createBlobService($connectionString);

/***************************************************************/


// Uploads location
$uploads_path = "../uploads/";
$action = "";
//define("DIRECT_TO_FILE_URL", "https://content.coherent.com/"); // Production

// check for action requested
if( isset($_POST['action']) ){
	$action = $_POST['action'];
}elseif( isset($_GET['action']) ){
	if( isset($_GET['user']) ){
		$MemberID = trim($_GET['user']);
		$action = "auth";		
	}
}

switch($action){
	case "update":
		extract($_POST);
		$media_info = $media->get($MediaID);
		$result = $media->update($_POST);
		if($result){
			$response = array("result"=>"valid");
		}else{
			$response = array("result"=>"invalid");
		}
		print json_encode($response);
		break;
	case "delete":
		extract($_POST);
		$media_info = $media->get($MediaID);
		if($media->delete($MediaID) == 1){

			$data['source_container'] = $media_info['Category'];
			$data['source_blob'] = $media_info['SavedMedia'];
			$data['destination_blob'] = time() . "-" . $media_info['SavedMedia'];
				switch($media_info['Category']){
					case "file":
						$data['destination_container'] = "file-archive";
						break;
					case "assets":
						$data['destination_container'] = "assets-archive";
						break;
					case "m-lmc":
						$data['destination_container'] = "m-lmc-archive";
						break;								
				}
				extract($data);

			copy_media($data);
			delete_media($data);
			
			$log_data = array("user"=>"milder.lisondra@yahoo.com","action"=>"Media Archived","object"=>$MediaID,"previous_data"=>"N/A","updated_data"=>"N/A");
			$media->log_action($log_data); // Log admin action			
			print json_encode(array("result"=>true));
		}
		break;
	case "get_home_list":
		$result = $media->get_media_all();
		if( $result !== 0){
			foreach( $result as $row){
				extract($row);
				$link_to_file = DIRECT_TO_FILE_URL . $Category . "/" . $SavedMedia;
				$last_modified = date("m/d/Y", strtotime($CreatedDateTime)); // friendly date and time format
				switch($Category){
					case "file":
						$Category = "File";
						break;
					case "assets":
						$Category = "Assets";
						break;
					case "emailer":
						$Category = "Emailer";
						break;
					case "m-lmc":
						$Category = "M-LMC";
						break;						
				}
				$all_media[] = array("DT_RowId"=>$MediaID,"Title"=>$Title,"Category"=>$Category,"Description"=>$Description,"LinkToFile"=>$link_to_file,"LastModified"=>$last_modified,"Tags"=>$Tags,"ActionDelete"=>"Archive","ActionEdit"=>"Edit");
			}
			print json_encode(array("data"=>$all_media));		
		}else{
			print json_encode(array("recordsTotal"=>0));
		}	
		break;
	case "auth":
		$member_last_activity = $auth->get_last_activity($MemberID);
		extract($member_last_activity);
		if( calc_ts_diff($last_activity) > getenv('MM_AUTH_TIMEOUT') ){ // user has not been active inside EE control panel for more than 30 minutes
			header("Location: /_admin_mm/noaccess.php");
		}else{
			// Set username in session
			$_SESSION['username'] = $username;

			header("Location: /_admin_mm/index.php");
		}
		break;
	case "add":

			if($_FILES){
				$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt','tiff','zip','csv','xls','xlsx','sql','txt','gz'); // valid extensions

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
							
							if( $result['result'] === true){

								$log_data = array("user"=>$_SESSION['username'],"action"=>"Add new media","object"=>$result['MediaID'],"previous_data"=>"N/A","updated_data"=>$file_mime_type);
								$media->log_action($log_data); // Log admin action
							
								$title_temp = strtolower($_POST['Title']);
								$title_temp = preg_replace('/[^a-zA-Z0-9\']/', '-', $title_temp); // remove special characters
								$title_temp = str_replace("'", '', $title_temp); // remove apostrophes
									
								/* if(substr($title_temp,-1,1) == "-"){
									$title_temp = substr($title_temp,0,strlen($title_temp) - 1);
								} */
								$title_temp = trim(preg_replace('/-+/', '-', $title_temp), '-'); // remove double dash and trailing dash

								
								$seo_url = $result['MediaID'] . "-" . $title_temp . "." . $ext;
								$azure_filename = $result['MediaID'] . "-" . $title_temp . "." . $ext;
								// Update the record with the filename actually stored in Azure and the properly formatted SeoUrl
								$update_data = array("SavedMedia"=>$azure_filename,"MediaID"=>$result['MediaID'], "SeoUrl"=>$seo_url);
								$media->update_savedmedia_seourl($update_data);
								$containerName = $file_category;
								$content = fopen($final_path, "r");
								//Upload media asset to Azure Blob Storage
								$azure_upload_result = $blobClient->createBlockBlob($containerName, $azure_filename, $content);	
								$result['direct_url'] =  DIRECT_TO_FILE_URL . $file_category . "/" . $azure_filename ;
								$result['processed_url'] =  PROCESSED_URL . $seo_url ;
								print json_encode($result);
							}else{
								print json_encode($result);
							}
						}
					}else{
						$result['result'] = 'invalid';
						print json_encode($result); 
					}
				}
			}	
		break;
	case "add_video":
		print_r($_FILES);
		if($_FILES){
			$num_files_sent = count($_FILES['file_upload']['name']);
			for( $i=0; $i<=$num_files_sent; $i++ ){
				if( isset($_FILES['file_upload']['name'][$i]) ){
					print $_FILES['file_upload']['name'][$i];
					print "\r\n";
				}
			}
		}
		break;
}

// copy blob from one container to another
function copy_media($data){
	global $blobClient;
	extract($data);
	$blobClient->copyBlob($destination_container,$destination_blob, $source_container, $source_blob);
}

// delete blob
function delete_media($data){
	global $blobClient;
	extract($data);
	$blobClient->deleteBlob($source_container, $source_blob);
}

// Calculate difference between given timestamp and current timestamp
// pass in a timestamp
function calc_ts_diff($ts){

	$ts1 = strtotime(date("m/d/Y g:i A",$ts));
	$ts2 = strtotime(date("m/d/Y g:i A",time()));
	$seconds_diff = $ts2 - $ts1;     
	return $seconds_diff;
}
