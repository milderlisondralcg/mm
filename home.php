<?php
include('includes/header.php');
?>
<div class="main-wrapper">
    <div class="contents">
        <div class="heading">
            <h2>Media List</h2>
        </div>

        <div class="page-contents">
            <div>
                <div class="group">
                    <table id="media_list">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Description</th>
								<th>Link To File</th>
								<th>Tags</th>
								<th>Last Modified</th>
								<th></th>
								<th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Description</th>
								<th>Link To File</th>
								<th>Tags</th>
								<th>Last Modified</th>
								<th></th>
								<th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                                <tr>
                                    <td>Title</td>
									<td>Category</td>
									<td>Description</td>
									<td>Link To File</td>
									<td>Tags</td>
									<td>Last Modified</td>
									<td>Action Delete</td>
									<td>Action Edit</td>
                                </tr>
                        </tbody>

                    </table>
                </div>
            </div>
    </div>
</div>


<script>

    $(document).ready(function () {

		//format for the Last Modified Datetime columnDefs
		 $.fn.dataTable.moment( 'MM/DD/YYYY' );
		 
        var mydatatable = $('#media_list').DataTable({
			"ajax": { 
			 "url": "/_admin_mm/controllers/",
			 "type": "POST",
			 "data": {"action":"get_home_list" }
			},
			columns: [
				{ data: 'Title' },
				{ data: 'Category' },
				{ data: 'Description' },
				{ data: 'LinkToFile' },
				{ data: 'Tags' },
				{ data: 'LastModified'},
				{ data: 'ActionDelete' },
				{ data: 'ActionEdit' }
			],
			"columnDefs": [
				{ className: "action_delete", "targets": [ 6 ] },
				{ className: "action_edit", "targets": [ 7 ] },
				{ className: "direct_link", "targets": [ 3] }
			  ],
			"order": [[ 5, "desc" ]] // sort by Last Modified Date, descending
		});
		
		$('#media_list tbody').on( 'click',  'td.action_delete', function () {			
			var media_id = $(this).parent().attr("id");	
				if( confirm("Are you sure you want to Archive this Media asset?") ){
					mydatatable.row( $(this).parents('tr') ).remove().draw(); // delete row from data table
					// send request to delete from DB
					$.post("/_admin_mm/controllers/",{"MediaID":media_id,"action":"delete"});
				}
		});	
		
		$('#media_list tbody').on( 'click',  'td.action_edit', function () {			
			var media_id = $(this).parent().attr("id");	
			if( $(this).attr("id") == "action_edit" ){
				console.log("redirect");
			}
			window.location.href = "/_admin_mm/edit.php?mediaid=" + media_id;
		});		
		
		$('#media_list tbody').on( 'click',  'td.direct_link', function () {			
			link = $(this).html();
			window.open(link);
		});
		
    });

</script>
<script src="<?php print DIRECT_TO_FILE_URL; ?>assets/js/datatables/datatables.min.js"></script>
<script src="<?php print DIRECT_TO_FILE_URL; ?>assets/js/datatables/moment.js"></script>
<script src="<?php print DIRECT_TO_FILE_URL; ?>assets/js/datatables/datetime-moment.js"></script>

<?php
include('includes/footer.php');
?>