<?php
error_reporting(E_ALL);
require_once '../../azureblob/vendor/autoload.php';
//require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

// Connection for GOLF Azure Storage
$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('BLOB_NAME').";AccountKey=".getenv('BLOB_KEY');

// Connection for Production Azure Storage
$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('BLOB_NAME_PROD').";AccountKey=".getenv('BLOB_KEY_2_PROD');

$mm_table = "mm_media";


// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

$container = $_GET['container'];
$sql_import_file = $container . '-' . time() . '.sql';
$file_handle = fopen($sql_import_file, 'a') or die('Cannot open file:  '.$sql_import_file);
$blobList = $blobClient->listBlobs($container);
$sql_query = "INSERT INTO `".$mm_table."` (`Title`,`SavedMedia`,`SeoUrl`,`Category`,`CreatedDatetime`) VALUES ";
fwrite($file_handle, $sql_query);

$sql_query_ext = "";

	foreach($blobList->getBlobs() as $key => $blob) {
		$filename_exploded = explode(".",$blob->getName());
		$Title = addslashes(trim($filename_exploded[0]));	
		print "Title: " . $Title;
		print '<br/>';
		
		$SavedMedia = addslashes(trim($blob->getName()));
		print "Saved Media: " . $SavedMedia;
		print '<br/>';
		
		$SeoUrl = trim($filename_exploded[0]);
		$SeoUrl = strtolower(str_replace("'","",$SeoUrl));
		$SeoUrl = str_replace("_","-",$SeoUrl);
		$SeoUrl = str_replace(" ","-",$SeoUrl);			
		$SeoUrl = preg_replace("/[']/","", $SeoUrl);	
		$SeoUrl = $SeoUrl . "." . $filename_exploded[1];
		print "SeoUrl:" . $SeoUrl;
		print '<br/>';
		print '<br/>';
		print '<br/>';
		$CreatedDateTime =  date("Y-m-d G:i",time());
		$Category = $container;		
		$sql_query_ext .= "('".$Title."','".$SavedMedia."','".$SeoUrl."','".$Category."','".$CreatedDateTime."')," . "\r\n";

		fwrite($file_handle, $sql_query_ext);	
		$sql_query_ext = "";
	}
	
	fclose($file_handle);
	
	
/* 	
   foreach($blobList->getBlobs() as $key => $blob) {
	   $filename_exploded = explode(".",$blob->getName());
	   echo '<br/>';
	  $blobprops = $blobClient->getBlobProperties($container,$blob->getName());
	  $props = $blobprops->getProperties();
	 // echo 'Content type: ' . $props->getContentType();
	  echo '<br/>';
		$Title = addslashes($filename_exploded[0]);	
	   if( $Title != "js/jquery/1" || $Title != "bootstrap-3.3.6" ||  $Title != "js" || $Title != "logo" || $Title != "photos" ){
			$SavedMedia = $blob->getName();
			$SavedMedia = addslashes($SavedMedia);	
			
			print "Saved Media: " .  $SavedMedia . '<br/>';
			
			$SeoUrl = strtolower($blob->getName());
			$SeoUrl = strtolower(str_replace("'","",$SeoUrl));
			$SeoUrl = str_replace("_","-",$SeoUrl);
			$SeoUrl = str_replace(" ","-",$SeoUrl);			
			$SeoUrl = preg_replace("/[']/","", $SeoUrl);
			
			
			$CreatedDateTime =  date("Y-m-d G:i",time());
			$Category = $container;
			$sql_query_ext .= "('".$Title."','".$SavedMedia."','".$SeoUrl."','".$Category."','".$CreatedDateTime."')," . "\r\n";
			//print $sql_query_ext;
			//echo '<br/>';		   
			fwrite($file_handle, $sql_query_ext);	
	   }

    } */