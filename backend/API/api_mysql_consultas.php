<?php
error_reporting(0);
include_once(__DIR__ . '/../controller/funcion_consulta.php');
header("Content-Type: application/json");
if($_SERVER["REQUEST_METHOD"] == "GET"){
    $cadenaJSON = file_get_contents('php://input');
    
    if (!isset($cadenaJSON) || $cadenaJSON == false) {
        echo procesarConsulta($_GET);
        return;
    }
    $datos = json_decode($cadenaJSON, true);
    echo procesarConsulta($datos);
    
}

?>