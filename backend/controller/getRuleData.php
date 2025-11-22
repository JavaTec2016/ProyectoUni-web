<?php
require_once(__DIR__ . '/../model/allModels.php');
function getRuleData(array $input){
    $tabla = $input["tabla"];

    $json = array(
        "rules"=> Models::get($tabla)::getRuleMap(null, 
            [DataRow::TIPO, DataRow::NO_NULO, DataRow::UMBRAL, DataRow::LIMITE] )
        );

    return json_encode($json);
}

?>