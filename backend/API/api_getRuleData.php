<?php
error_reporting(0);
require_once(__DIR__ . "/../controller/getRuleData.php");
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