<?php
session_start();
session_destroy();
header("Location:../views/user-login.php");
exit;
// Pero la idea no es hacer un SPA ya que se me hace mas complejo Single Page Application
?>