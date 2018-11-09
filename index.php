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
                    <table id="documents">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Salesforce ID</th>
                                <th>Alternate Tracking ID</th>
                                <th>Lead Form Required</th>
                                <th>Email to Notify</th>
                                <th>Date Added</th>
                                <th>Last Modified</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Salesforce ID</th>
                                <th>Alternate Tracking ID</th>
                                <th>Lead Form Required</th>
                                <th>Email to Notify</th>
                                <th>Date Added</th>
                                <th>Last Modified</th>
                            </tr>
                        </tfoot>
                        <tbody>
<?php /*
                            @foreach (var doc in ViewBag.DocsList)
                            {
                                if (doc.Tags == "" || doc.Tags == null)
                                {
                                    doc.Tags = "N/A";
                                }
                                else
                                {
                                    doc.Tags.Trim();
                                }
                                if (doc.SalesForceId == "" || doc.SalesForceId == null)
                                {
                                    doc.SalesForceId = "N/A";
                                }
                                else
                                {
                                    doc.SalesForceId.Trim();
                                }
                                if (doc.AlternateTrackingId == "" || doc.AlternateTrackingId == null)
                                {
                                    doc.AlternateTrackingId = "N/A";
                                }

                                if (doc.Category == "file")
                                {
                                    doc.Category = "File";
                                }
                                if (doc.Category == "emailblasts")
                                {
                                    doc.Category = "Email Blasts";
                                }
                                if (doc.Category == "assets")
                                {
                                    doc.Category = "Assets";
                                }
*/
?>
                                <tr>
                                    <td><a href="/Document/DocumentDetails/@doc.Id">Title</a></td>
                                    <td>Category</td>
                                    <td>Description</td>
									<td>SalesForceId</td>
                                    <td>AlternateTrackingId</td>
                                    <td>LeadRequired</td>
                                    <td>EmailNotify</td>
                                    <td>CreatedDateTime</td>
                                    <td>ModifiedDateTime</td>
                                </tr>
                        </tbody>

                    </table>
                </div>
            </div>
    </div>
</div>


<script>
var data = [
    {
        "Title":       "Beam Master",
        "Category":   "M-LMC",
        "Description":     "",
		"Salesforce ID":"", 
		"Alternate Tracking ID":"",
		"Lead Form Required":"Yes",
		"Email to Notify": "web@coherent.com",
		"Date Added":"",
		"Last Modified":""		
    },
    {
        "Title":       "Beam Viewer",
        "Category":   "M-LMC",
        "Description":     "",
		"Salesforce ID":"", 
		"Alternate Tracking ID":"",
		"Lead Form Required":"Yes",
		"Email to Notify": "web@coherent.com",
		"Date Added":"",
		"Last Modified":""		
		
    },
    {
        "Title":       "EngergyMax PC v1.2.0.2",
        "Category":   "M-LMC",
        "Description":     "",
		"Salesforce ID":"", 
		"Alternate Tracking ID":"",
		"Lead Form Required":"N/A",
		"Email to Notify": "",
		"Date Added":"",
		"Last Modified":""		
		
    }	
];
    $(document).ready(function () {
        $('#documents').DataTable({
			data: data,
			columns: [
				{ data: 'Title' },
				{ data: 'Category' },
				{ data: 'Description' },
				{ data: 'Salesforce ID' },
				{ data: 'Alternate Tracking ID' },
				{ data: 'Lead Form Required' },
				{ data: 'Email to Notify' },
				{ data: 'Date Added' },
				{ data: 'Last Modified' }
			]
		});
    });

</script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/datatables.min.js"></script>

<?php
include('includes/footer.php');
?>