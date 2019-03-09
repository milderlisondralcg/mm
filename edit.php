<?php
include('includes/header.php');

spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

$media = new Media();
$app_codes = $media->get_app_codes(); 
$MediaID = trim($_GET['mediaid']);
$media_info = $media->get($MediaID);
extract($media_info);

if( $_SERVER['SERVER_NAME'] == "charlie.coherent.com" ){
	$direct_link = "https://pocmarcomgolfstorage.blob.core.windows.net/" . $Category . "/" . $SeoUrl;
}else{
	$direct_link = "https://content.coherent.com/" . $Category . "/" . $SeoUrl;
}

?>
<div class="main-wrapper">
    <div class="contents">
        <div class="heading">
            <h2>Add Media</h2>
        </div>

        <div class="page-contents">


	<form id="edit-media" name="edit-media" action="../_media_manager/controllers/index.php" method="post" enctype="multipart/form-data">
	
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Title">Media Title</label>
			</div>

			<div class="form-col-input">
				<input id="Title" name="Title" type="text" value="<?php echo $Title; ?>" placeholder="Enter Product Name" required />
			</div>
		</div>
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Description">Description</label>
			</div>
			<div class="form-col-input">
				<textarea class="form-control" cols="80" id="Description" name="Description" rows="5" placeholder="Enter Product Description"><?php echo $Description; ?></textarea>                 
			</div>
		</div>
		
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Tags">Tags ( separate tags by spaces ) </label>
			</div>
			<div class="form-col-input">
				<input class="form-control text-box single-line" id="Tags" name="Tags" type="text" value="<?php print $tags; ?>" />
			</div>
		</div>		

		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="uploadImage">Media</label>
			</div>			
			<div class="form-col-input">
				<input id="file_upload" type="file" name="file_upload"/>
				<div id="preview">Current File: <a href="<?php print $direct_link;?>" target="_blank"><?php print $direct_link;?></a></div><br>
				<div id="upload_notification"></div>
			</div>		
		</div>
		
		<input type="submit" value="Save">
		<input type="button" id="clear_add_form" value="Reset">
		<input type="hidden" name="Type" value="Document">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="MediaID" value="<?php print $MediaID; ?>">
	</form>
	
	</div>
</div>

<?php
include('includes/footer.php');
?>