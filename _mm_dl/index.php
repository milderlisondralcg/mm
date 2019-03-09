<?php
error_reporting(E_ALL);
spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = 'models/';

    include $path.$className.'.php';
}

$media = new Media();

$dl_log = 'dl_requests.log';
if( file_exists($dl_log) ){
	if(filesize($dl_log) > 500000){
		$new_filename = "dl_requests-" . time() . ".log";
		rename("dl_requests.log",$new_filename);
	}	
}
$media_url = $_GET['media'];
$media_info = $media->get_media_by_url($media_url);
extract($media_info);

$value = explode('.', $SavedMedia);
$extension = strtolower(end($value));
$mime = detectByFileExtension($extension);
	
require_once '../azureblob/vendor/autoload.php';
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

// Create connection to BLOB Storage
if( $_SERVER['SERVER_NAME'] == "charlie.coherent.com" ){
	$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME').";AccountKey=".getenv('MM_BLOB_KEY'); // Golf/Development
}else{
	$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME_PROD').";AccountKey=".getenv('MM_BLOB_KEY_2_PROD'); // COHRstage
	//$connectionString = "DefaultEndpointsProtocol=https;AccountName=pocmarcomgolfstorage;AccountKey=GU42Ky8Y/xjuthrrkbgUjbjLH/5TU2sgezHCAWW4WxDGJa4VUVxT8teqraUNGFFsBtw1aKVJBUZY3fg155STDg=="; // Golf/Development
}

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);
$blobfilename = $SavedMedia;
$blob = $blobClient->getBlob($Category, $blobfilename);
$download_filename = str_replace(" " , "", $Title);
$download_filename = str_replace("-" , "", $download_filename) . "." . $extension;

header('Content-type: ' . $mime);
if( $mime == "image/jpeg" || $mime == "image/png" || $mime == "image/bmp" || $mime == "image/gif"){
	header('Content-Disposition: inline');
}else{
	header('Content-Disposition: attachment; filename="' .$download_filename . '"');
}
fpassthru($blob->getContentStream());


$handle = fopen($dl_log, 'a') or die('Cannot open file:  '.$dl_log);
$created_datetime = date("Y-m-d h:i:s A", time());
$data = $MediaID . "," . $Title . "," . $created_datetime . "," . $_SERVER['REMOTE_ADDR'] . "\r\n";
fwrite($handle, $data);	
				
function detectByFileExtension($extension) {    
    $extensionToMimeTypeMap = getExtensionToMimeTypeMap();

    if (isset($extensionToMimeTypeMap[$extension])) {
        return $extensionToMimeTypeMap[$extension];
    }
    return 'text/plain';
}

function getExtensionToMimeTypeMap() {
    return [
        'hqx'   => 'application/mac-binhex40',
        'cpt'   => 'application/mac-compactpro',
        'csv'   => 'text/x-comma-separated-values',
        'bin'   => 'application/octet-stream',
        'dms'   => 'application/octet-stream',
        'lha'   => 'application/octet-stream',
        'lzh'   => 'application/octet-stream',
        'exe'   => 'application/octet-stream',
        'class' => 'application/octet-stream',
        'psd'   => 'application/x-photoshop',
        'so'    => 'application/octet-stream',
        'sea'   => 'application/octet-stream',
        'dll'   => 'application/octet-stream',
        'oda'   => 'application/oda',
        'pdf'   => 'application/pdf',
        'ai'    => 'application/pdf',
        'eps'   => 'application/postscript',
        'ps'    => 'application/postscript',
        'smi'   => 'application/smil',
        'smil'  => 'application/smil',
        'mif'   => 'application/vnd.mif',
        'xls'   => 'application/vnd.ms-excel',
        'ppt'   => 'application/powerpoint',
        'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'wbxml' => 'application/wbxml',
        'wmlc'  => 'application/wmlc',
        'dcr'   => 'application/x-director',
        'dir'   => 'application/x-director',
        'dxr'   => 'application/x-director',
        'dvi'   => 'application/x-dvi',
        'gtar'  => 'application/x-gtar',
        'gz'    => 'application/x-gzip',
        'gzip'  => 'application/x-gzip',
        'php'   => 'application/x-httpd-php',
        'php4'  => 'application/x-httpd-php',
        'php3'  => 'application/x-httpd-php',
        'phtml' => 'application/x-httpd-php',
        'phps'  => 'application/x-httpd-php-source',
        'js'    => 'application/javascript',
        'swf'   => 'application/x-shockwave-flash',
        'sit'   => 'application/x-stuffit',
        'tar'   => 'application/x-tar',
        'tgz'   => 'application/x-tar',
        'z'     => 'application/x-compress',
        'xhtml' => 'application/xhtml+xml',
        'xht'   => 'application/xhtml+xml',
        'zip'   => 'application/x-zip',
        'rar'   => 'application/x-rar',
        'mid'   => 'audio/midi',
        'midi'  => 'audio/midi',
        'mpga'  => 'audio/mpeg',
        'mp2'   => 'audio/mpeg',
        'mp3'   => 'audio/mpeg',
        'aif'   => 'audio/x-aiff',
        'aiff'  => 'audio/x-aiff',
        'aifc'  => 'audio/x-aiff',
        'ram'   => 'audio/x-pn-realaudio',
        'rm'    => 'audio/x-pn-realaudio',
        'rpm'   => 'audio/x-pn-realaudio-plugin',
        'ra'    => 'audio/x-realaudio',
        'rv'    => 'video/vnd.rn-realvideo',
        'wav'   => 'audio/x-wav',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'jpe'   => 'image/jpeg',
        'png'   => 'image/png',
        'gif'   => 'image/gif',
        'bmp'   => 'image/bmp',
        'tiff'  => 'image/tiff',
        'tif'   => 'image/tiff',
        'svg'   => 'image/svg+xml',
        'css'   => 'text/css',
        'html'  => 'text/html',
        'htm'   => 'text/html',
        'shtml' => 'text/html',
        'txt'   => 'text/plain',
        'text'  => 'text/plain',
        'log'   => 'text/plain',
        'rtx'   => 'text/richtext',
        'rtf'   => 'text/rtf',
        'xml'   => 'application/xml',
        'xsl'   => 'application/xml',
        'mpeg'  => 'video/mpeg',
        'mpg'   => 'video/mpeg',
        'mpe'   => 'video/mpeg',
        'qt'    => 'video/quicktime',
        'mov'   => 'video/quicktime',
        'avi'   => 'video/x-msvideo',
        'movie' => 'video/x-sgi-movie',
        'doc'   => 'application/msword',
        'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dot'   => 'application/msword',
        'dotx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'word'  => 'application/msword',
        'xl'    => 'application/excel',
        'eml'   => 'message/rfc822',
        'json'  => 'application/json',
        'pem'   => 'application/x-x509-user-cert',
        'p10'   => 'application/x-pkcs10',
        'p12'   => 'application/x-pkcs12',
        'p7a'   => 'application/x-pkcs7-signature',
        'p7c'   => 'application/pkcs7-mime',
        'p7m'   => 'application/pkcs7-mime',
        'p7r'   => 'application/x-pkcs7-certreqresp',
        'p7s'   => 'application/pkcs7-signature',
        'crt'   => 'application/x-x509-ca-cert',
        'crl'   => 'application/pkix-crl',
        'der'   => 'application/x-x509-ca-cert',
        'kdb'   => 'application/octet-stream',
        'pgp'   => 'application/pgp',
        'gpg'   => 'application/gpg-keys',
        'sst'   => 'application/octet-stream',
        'csr'   => 'application/octet-stream',
        'rsa'   => 'application/x-pkcs7',
        'cer'   => 'application/pkix-cert',
        '3g2'   => 'video/3gpp2',
        '3gp'   => 'video/3gp',
        'mp4'   => 'video/mp4',
        'm4a'   => 'audio/x-m4a',
        'f4v'   => 'video/mp4',
        'webm'  => 'video/webm',
        'aac'   => 'audio/x-acc',
        'm4u'   => 'application/vnd.mpegurl',
        'm3u'   => 'text/plain',
        'xspf'  => 'application/xspf+xml',
        'vlc'   => 'application/videolan',
        'wmv'   => 'video/x-ms-wmv',
        'au'    => 'audio/x-au',
        'ac3'   => 'audio/ac3',
        'flac'  => 'audio/x-flac',
        'ogg'   => 'audio/ogg',
        'kmz'   => 'application/vnd.google-earth.kmz',
        'kml'   => 'application/vnd.google-earth.kml+xml',
        'ics'   => 'text/calendar',
        'zsh'   => 'text/x-scriptzsh',
        '7zip'  => 'application/x-7z-compressed',
        'cdr'   => 'application/cdr',
        'wma'   => 'audio/x-ms-wma',
        'jar'   => 'application/java-archive',
    ];
}