<?php

include_once(__DIR__ . '/DAO.php');
include_once(__DIR__ . '/../model/allModels.php');

function procesarConsulta($datos){

    $dao = new DAO();
    $tabla = $datos["tabla"]; //saca el modelo
    unset($datos["tabla"]);
    $filtrados = array();
    $filtrados = Models::cleanKeys($datos, "caja_");
    //tovia no hay comodines
    $res = $dao->consultar($tabla, array(0 => "*"), $filtrados, $dao->fakeComodines($filtrados, true));

    if ($res) {
        echo json_encode(mysqli_fetch_all($res, MYSQLI_ASSOC));
    } else {
        echo json_encode(array());
    }

}
?>