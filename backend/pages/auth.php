<?php 

if(!isset($_SESSION)) {
    session_start();
}
if(!isset($_SESSION['autenticado']) || !$_SESSION['autenticado']) header("location: /backend/pages/login.php");

?>