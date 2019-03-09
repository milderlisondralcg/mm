<?php
error_reporting(E_ALL);
require_once '../../azureblob/vendor/autoload.php';
//require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME').";AccountKey=".getenv('ACCOUNT_KEY');
$connectionString = "DefaultEndpointsProtocol=https;AccountName=mmblobcohr;AccountKey=4aOouaoDgYheE+hJNhUQL9FEOlr/Cqc2qhqJ0RV0DKqudxfyzvzm8v2l3ojjnwPWLSIx5xNUSP5M5B0uBIxtEg==";

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

//$fileToUpload = "../uploads/108710-coherent-09042018.pdf";

$fileToUpload = '108710-coherent-09042018.pdf';

$folder = "uploads";

$filetomove = "../uploads/" . $fileToUpload;

    // Create container options object.
    $createContainerOptions = new CreateContainerOptions();
	
	$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
		
	// Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");

    $containerName = "files";
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
	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);	

	
	