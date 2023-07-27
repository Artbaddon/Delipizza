<?php
session_start();
session_destroy();
header("Location:../admin-pannel/admin-login.php");
exit;
?>
