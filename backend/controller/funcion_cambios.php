<?php

header("Content-Type: application/json");
include_once(__DIR__ . '/../model/allModels.php');
include_once(__DIR__ . '/funcion_validador.php');
include_once(__DIR__ . '/GetUserPDAO.php');
function procesarCambio(array $datos){
    
    $tabla = $datos["tabla"]; //saca el modelo
    unset($datos["tabla"]);
    $dao = null;
    if ($tabla == "usuario") $dao = getUserPDAO(conexionPDO::BD_USER);
    else $dao = getUserPDAO(conexionPDO::BD_MAIN);
    
    $primaria = Models::get($tabla)::getCampoPrimario();
    $num = $datos["OLD_" . $primaria];
    unset($datos["OLD_" . $primaria]);
    //filtro pal dao
    
    $filtro = array("" . $primaria => $num);
    $valores = Models::cleanKeys($datos, "_input");
    $valores = Models::cutKeys($valores, "#");
    $res = false;

    //validadero

    $codigos = Validador::escanearModelo($valores, $tabla);
    $datos_correctos = Validador::checkScan($codigos);
    $json = array("status" => $res, "validation" => $codigos);
    
    if($datos_correctos){
        //modelo actualizao
        $valores = Validador::convertirModelo($tabla, $valores);
        $modelo = Models::instanciar($tabla, $valores);
        $res = $dao->modificar($tabla, $filtro, $modelo);
    }
    if ($res != false) $res = true;
    $json['status'] = $res;
    echo json_encode($json);
}

