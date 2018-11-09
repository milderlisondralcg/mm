<?php
include('includes/header.php');

spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = 'models/';

    include $path.$className.'.php';
}

$media = new Media();
$app_codes = $media->get_app_codes(); 

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
				<label class="control-label" for="Title">Name</label>
			</div>

			<div class="form-col-input">
				<input id="Title" name="Title" type="text" value="" placeholder="Enter Product Name" required />
			</div>
		</div>
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Description">Description</label>
			</div>
			<div class="form-col-input">
				<textarea class="form-control" cols="80" id="Description" name="Description" rows="5" placeholder="Enter Product Description"></textarea>                 
			</div>
		</div>

		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Category">Category</label>
			</div>

			<div class="form-col-input">
				<select id="Category" name="Category" size="5">
				<option selected="selected" value="">-- Select Category --</option>
				<option value="assets">Assets</option>
				<option value="emailblasts">Email Blasts</option>
				<option value="file">Files</option>
				<option value="m-lmc">M-LMC</option>
				</select>                   
			</div>
		</div>               
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="SalesForceId">SalesForceId</label>
			</div>

			<div class="form-col-input">
				<input class="form-control text-box single-line" id="SalesForceId" name="SalesForceId" type="text" value="" />
			</div>
		</div>
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="AlternateTrackingId">AlternateTrackingId</label>
			</div>

			<div class="form-col-input">
				<input class="form-control text-box single-line" id="AlternateTrackingId" name="AlternateTrackingId" type="text" value="" />
			</div>
		</div>	


		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="AppCode">App Code</label>
			</div>

			<div class="form-col-input">				
				<select class="form-control" id="AppCode" multiple="multiple" name="AppCode[]" size="10">
					<option selected="selected" value="">-- Select App Code --</option>
					<?php foreach( $app_codes as $app_code){ ?>
						<option value="<?php print $app_code['ID'];?>">(<?php print $app_code['AppCode'];?>) <?php print $app_code['AppName'];?></option>
					<?php }	?>
				</select>

			</div>
		</div>
		
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="ProductGroup">Product Group</label>
			</div>
			<div class="form-col-input">
				<select class="form-control" id="ProductGroup" name="ProductGroup" size="8">
					<option selected="selected" value="">-- Select Product Group --</option>
					<option value="Compass">Compass</option>
					<option value="Laser Diode Module">Laser Diode Module</option>
					<option value="MP System">MP System</option>
					<option value="MP Tool">MP Tool</option>
					<option value="Obis">Obis</option>
					<option value="Sapphire">Sapphire</option>
					<option value="Unknown">Unknown</option>
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
				<input id="uploadImage" type="file" accept="image/*" name="image" required/>
				<div id="preview"></div><br>
			</div>		
		</div>
		
		<input type="submit" value="Add">
		<input type="button" id="clear_add_form" value="Reset">
	</form>
	
	</div>
</div>

<?php
include('includes/footer.php');
?>