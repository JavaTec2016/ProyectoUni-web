<?php

include_once('../backend/controller/funcion_baja.php');
header("Content-Type: application/json");

if($_SERVER["REQUEST_METHOD"] == "DELETE"){
    $cadenaJSON = file_get_contents('php://input');

    if (!isset($cadenaJSON) || $cadenaJSON == false) {
        echo "No hay cadena JSON";
        return;
    }
    $datos = json_decode($cadenaJSON, true);
    echo procesarBaja($datos);
}

?>