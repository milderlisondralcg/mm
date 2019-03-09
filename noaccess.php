<?php 
error_reporting(0);
$ee_login = "https://" . $_SERVER['SERVER_NAME'] . "/cohr-login.php";
?>
<html>
<body>
Your session has expired.<br/>
Please <a href="<?php print $ee_login; ?>">log into</a> Expression Engine.
</body>
</html>