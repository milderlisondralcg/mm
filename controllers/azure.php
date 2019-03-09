<?php
error_reporting(E_ALL);
require_once '../../azureblob/vendor/autoload.php';
//require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('BLOB_NAME').";AccountKey=".getenv('BLOB_KEY');

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

//$fileToUpload = "../uploads/108710-coherent-09042018.pdf";

$fileToUpload = 'Dental-Payment.pdf';

$folder = "uploads";

$filetomove = "../uploads/" . $fileToUpload;

    // Create container options object.
    $createContainerOptions = new CreateContainerOptions();
	
	$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
		
	// Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");

    $containerName = "mltest";
	//$blobClient->createContainer($containerName, $createContainerOptions);
	//$myfile = fopen($fileToUpload, "w") or die("Unable to open file!");
	//fclose($myfile);
	
	# Upload file as a block blob
	echo "Uploading BlockBlob: ".PHP_EOL;
	echo $fileToUpload;
	echo "<br />";
	
	$content = fopen($filetomove, "r");
	
	print_r($content);

	//Upload blob
	//$blobClient->createBlockBlob($containerName, $fileToUpload, $content);	
	
	
	if( isset($_GET['copy']) ){
		$source_containter = "file";
		$source_blob = "103-genesis-datasheet.pdf";
		$destination_container = "file-archive";
		$destination_blob = "103-genesis-datasheet.pdf";
		$blobClient->copyBlob($destination_container,$destination_blob, $source_containter, $source_blob);
		
	}

	
	