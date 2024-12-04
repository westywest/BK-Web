<?php 
session_start();
session_destroy();
header('location:/BK/users/index.php');
?>