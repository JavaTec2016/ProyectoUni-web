<?php
header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $cadenaJSON = file_get_contents('php://input');
    if (!isset($cadenaJSON) || $cadenaJSON == false) {
        echo procesarConsulta($_GET);
        return;
    }
    $datos = json_decode($cadenaJSON, true);
}
?>