<?php
include_once(__DIR__ . '/DAO.php');
include_once(__DIR__ . '/../model/allModels.php');

function procesarAlta(array $datos){
    $dao = new DAO();
    $modelo = array();
    $tabla = $datos["tabla"];
    unset($datos['tabla']);
    $modelo = Models::cleanKeys($datos, "caja_");
    $modelo = Models::cleanKeys($modelo, "_input");
    $modelo = Models::cleanKeys($modelo, "_input");
    $modelo = Models::cutKeys($modelo, "#");
    //==============VALIDAR (hagalo lado de cliente)
    $datos_correctos = true;
    $res = false;
    if ($datos_correctos) {
       
        $res = $dao->agregar($tabla, $modelo);
    }
    if ($res != false) $res = true;
    $json = array("status" => $res);
    return json_encode($json);
}

?>
