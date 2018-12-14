<?php
include('includes/header.php');

spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

$media = new Media();
$app_codes = $media->get_app_codes(); 
$media_info = $media->get(trim($_GET['mediaid'])); 
extract($media_info);

?>
<div class="main-wrapper">
    <div class="contents">
        <div class="heading">
            <h2>Add Media</h2>
        </div>

        <div class="page-contents">


	<form id="add-media" name="add-media" action="../_media_manager/controllers/index.php" method="post" enctype="multipart/form-data">
	
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
				<label class="control-label" for="Category">Category</label>
			</div>
 			<div class="form-col-input">
				<select id="Category" name="Category" size="5" required>
				<option value="">-- Select Category --</option>
				<option value="assets" <?php if($Category == "assets"){ echo 'selected'; } ?> >Assets</option>
				<option value="emailblasts"  <?php if($Category == "assets"){ echo 'selected'; } ?>>Email Blasts</option>
				<option value="file"  <?php if($Category == "assets"){ echo 'selected'; } ?> >Files</option>
				<option value="m-lmc"  <?php if($Category == "assets"){ echo 'selected'; } ?> >M-LMC</option>
				</select>                   
			</div>
		</div>  
		
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Tags">Tags ( separate tags by spaces ) </label>
			</div>
			<div class="form-col-input">
				<input class="form-control text-box single-line" id="Tags" name="Tags" type="text" value="" />
			</div>
		</div>		

		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="uploadImage">Media</label>
			</div>			
			<div class="form-col-input">
				<!--<input id="uploadImage" type="file" accept="image/*" name="image" required/>-->
				<input id="file_upload" type="file" name="file_upload" required/>
				<div id="preview"></div><br>
				<div id="upload_notification"></div>
			</div>		
		</div>
		
		<input type="submit" value="Add">
		<input type="button" id="clear_add_form" value="Reset">
		<input type="hidden" name="Type" value="Document">
	</form>
	
	</div>
</div>

<?php
include('includes/footer.php');
?>