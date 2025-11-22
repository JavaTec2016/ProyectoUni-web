<?php
include_once(__DIR__ . '/../controller/funcion_altas.php');
header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] == 'POST'){ //agregar


    $cadenaJSON = file_get_contents('php://input');    
    if(!isset($cadenaJSON) || $cadenaJSON == false){
        if(!$_POST){
            echo "No hay cuerpo";
            return;
        }else{
            echo procesarAlta($_POST);
            return;
        }
        
    }
    $datos = json_decode($cadenaJSON, true);
    echo procesarAlta($datos);
}
?>