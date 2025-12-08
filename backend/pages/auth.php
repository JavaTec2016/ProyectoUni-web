<?php 

if(!isset($_SESSION)) {
    session_start();
}
if(!isset($_SESSION['autenticado']) || !$_SESSION['autenticado']) header("location: /backend/pages/login.php");
if(time() - $_SESSION['timestamp'] > 1800) header("location: logout");
$_SESSION["timestamp"] = time();
?>