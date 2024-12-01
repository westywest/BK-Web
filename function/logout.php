<?php 
session_start();
session_destroy();
header('location:/BK/user/index.php');
?>