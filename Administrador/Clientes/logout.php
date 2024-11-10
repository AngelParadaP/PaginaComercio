<?php
require "../funciones/conecta.php"; 
$con = conecta(); 
session_start(); 
session_destroy(); 

header("Location: ./Home.php"); 
exit(); 
?>
