<?php
include_once(__DIR__ . '/../model/allModels.php');
include_once(__DIR__ . '/GetUserPDAO.php');
function procesarBaja($datos){

    $tabla = $datos["tabla"]; //saca el modelo
    unset($datos["tabla"]);
    $dao = null;
    if ($tabla == "usuario") $dao = getUserPDAO(conexionPDO::BD_USER);
    else $dao = getUserPDAO(conexionPDO::BD_MAIN);

    $datos = Models::cleanKeys($datos, "caja_");
    $datos = Models::cleanKeys($datos, "_input");
    $datos = Models::cutKeys($datos, "#");
    $num = $datos[Models::get($tabla)::getCampoPrimario()]; //saca el nombre de la llave primaria del modelo

    if ($tabla == "usuario") $estado = $dao->deleteUser($num);
    else $estado = $dao->eliminarPrimaria($tabla, $num);
    if ($estado != false) $estado = true;
    
    echo json_encode(array("status" => $estado));
}

?>