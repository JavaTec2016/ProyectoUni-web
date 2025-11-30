<?php
include_once(__DIR__ . '/DAO.php');
include_once(__DIR__ . '/../model/allModels.php');

function procesarBaja($datos){
    $dao = new DAO();

    $tabla = $datos["tabla"]; //saca el modelo
    $datos = Models::cleanKeys($datos, "caja_");
    $datos = Models::cleanKeys($datos, "_input");
    $datos = Models::cutKeys($datos, "#");
    $num = $datos[Models::get($tabla)::getCampoPrimario()]; //saca el nombre de la llave primaria del modelo

    $estado = $dao->eliminarPrimaria($tabla, $num);
    $dao->getConexion()->commit();
    if ($estado != false) $estado = true;
    
    echo json_encode(array("status" => $estado));
}

?>