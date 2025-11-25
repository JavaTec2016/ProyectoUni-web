<?php
include_once(__DIR__ . '/DAO.php');
include_once(__DIR__ . '/../model/allModels.php');
include_once(__DIR__ . '/funcion_validador.php');
function procesarAlta(array $datos){
    $dao = new DAO();
    $modelo = array();
    $tabla = $datos["tabla"];
    unset($datos['tabla']);
    $modelo = Models::cleanKeys($datos, "caja_");
    $modelo = Models::cleanKeys($modelo, "_input");
    $modelo = Models::cleanKeys($modelo, "_input");
    $modelo = Models::cutKeys($modelo, "#");
    //==============VALIDAR (hagalo lado de cliente nel)
    
    $codigos = Validador::escanearModelo($modelo, $tabla);
    $datos_correctos = Validador::checkScan($codigos);

    $res = false;
    $json = array("status"=> $res, "validation"=>$codigos);
    if ($datos_correctos) {
        $modelo = Validador::convertirModelo($tabla, $modelo);
        $res = $dao->agregar($tabla, $modelo);
    }
    if ($res != false) $res = true;
    $json['status'] = $res;
    return json_encode($json);
}

?>
