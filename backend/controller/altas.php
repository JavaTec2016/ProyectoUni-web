<?php
include_once(__DIR__ . '/DAO.php');
include_once(__DIR__ . '/../model/allModels.php');

$datos = json_decode(file_get_contents('php://input'), true);
    $dao = new DAO();
    $modelo = array();
    $tabla = $datos["tabla"];
    unset($datos['tabla']);
    $modelo = Models::cleanKeys($datos, "caja_");
    $modelo = Models::cleanKeys($datos, "_input");
    //==============VALIDAR (hagalo lado de cliente)
    $datos_correctos = true;
    $res = false;
    if ($datos_correctos) {
        //var_dump($modelo);
        $res = $dao->agregar($tabla, $modelo);
    }
    if ($res != false) $res = true;
    $json = array("status" => $res);
    return json_encode($json);


?>
