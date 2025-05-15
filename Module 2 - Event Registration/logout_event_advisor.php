<?php
session_start();
session_unset();
session_destroy();
header("Location:../Module 1 - Login/login.php");
exit();
?>
