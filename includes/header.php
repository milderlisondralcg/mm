<?php 

session_start(); 

if( $_SERVER['SERVER_NAME'] == "charlie.coherent.com" ){
	define("DIRECT_TO_FILE_URL", "https://pocmarcomgolfstorage.blob.core.windows.net/"); // Production
}else{
	define("DIRECT_TO_FILE_URL", "https://content.coherent.com/"); // Production
}
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coherent Media Manager</title>
    <link href="../_admin_mm/includes/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../_admin_mm/includes/css/Site.css" rel="stylesheet" type="text/css" />
    <link href="../_admin_mm/includes/css/themes/base/all.css" rel="stylesheet" />  

    <link rel="stylesheet" type="text/css" href="<?php print DIRECT_TO_FILE_URL; ?>/assets/js/datatables/datatables.min.css" />

    <script src="../_admin_mm/includes/js/jquery-3.3.1.min.js"></script>
    <script src="../_admin_mm/includes/js/jquery-ui-1.12.1.min.js"></script>
    <script src="../_admin_mm/includes/js/modernizr-2.6.2.js"></script>
    <meta name="description" content="The description of my page" />
</head>
<body>
        <div class="navbar-top"></div>
		<?php include('nav.php'); ?>

            <div class="active-user">
                <div class="user-name"><?php print $_SESSION['username']; ?></div>
				<a href="###" class="logout-btn">Logout</a>
            </div>

            <div class="bread-crumb">
                <ul>
                    <li>Coherent</li>
                    <li><a href="/_admin_mm/home.php">MM</a></li>
                </ul>

                <div class="clear-left"></div>
            </div>
    