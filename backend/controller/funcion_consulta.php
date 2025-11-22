<?php

include_once(__DIR__ . '/DAO.php');
include_once(__DIR__ . '/../model/allModels.php');

function procesarConsulta(array $datos)
{

    $dao = new DAO();
    $tabla = $datos["tabla"]; //saca el modelo
    unset($datos["tabla"]);
    $filtrados = array();
    $filtrados = Models::cleanKeys($datos, "caja_");
    $filtrados = Models::cleanKeys($filtrados, "_input");
    //tovia no hay comodines
    $res = $dao->consultar($tabla, array(0 => "*"), $filtrados, $dao->fakeComodines($filtrados, true));

    $json = array("resultSet" => []);
    if ($res) {
        $json["resultSet"] = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    echo json_encode($json);
}