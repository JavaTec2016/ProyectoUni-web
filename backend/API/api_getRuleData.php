<?php
require_once(__DIR__ . "/../controller/getRuleData.php");
if(!isset($_SESSION) || !$_SESSION['autenticado']) die(0);
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $cadenaJSON = file_get_contents('php://input');

    if (!isset($cadenaJSON) || $cadenaJSON == false) {
        echo getRuleData($_GET);
        return;
    }
    $datos = json_decode($cadenaJSON, true);
    echo getRuleData($datos);
}
?>