<?php
include_once(__DIR__ . '/../model/allModels.php');
include_once(__DIR__ . '/GetUserPDAO.php');
function procesarConsulta(array $datos)
{

    $tabla = $datos["tabla"]; //saca el modelo
    unset($datos["tabla"]);
    $dao = null;
    if ($tabla == "usuario") $dao = getUserPDAO(conexionPDO::BD_USER);
    else $dao = getUserPDAO(conexionPDO::BD_MAIN);
    
    $filtrados = array();
    $filtrados = Models::cleanKeys($datos, "caja_");
    $filtrados = Models::cleanKeys($filtrados, "_input");
    $filtrados = Models::cutKeys($filtrados, "#");
    //tovia no hay comodines
    $res = $dao->consultar($tabla, array(0 => "*"), $filtrados);
    $json = array("resultSet" => []);
    if ($res) {
        $json["resultSet"] = $res;
    }
    echo json_encode($json);
}