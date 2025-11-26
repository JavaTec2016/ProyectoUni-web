<?php

include_once(__DIR__ . '/../controller/funcion_baja.php');
if (!isset($_SESSION) || !$_SESSION['autenticado']) die(0);
header("Content-Type: application/json");

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $cadenaJSON = file_get_contents('php://input');

    if (!isset($cadenaJSON) || $cadenaJSON == false) {
        if ($_POST) {
            echo procesarBaja($_POST);
        } else if ($_GET) {
            echo procesarBaja($_GET);
        }
        else {
            echo "No hay datos";
            return;
        }
        return;
    }
    $datos = json_decode($cadenaJSON, true);
    echo procesarBaja($datos);
}

?>