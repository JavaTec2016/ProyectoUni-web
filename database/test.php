<?php

include(__DIR__ . '/../backend/controller/PDAO.php');

$options = [
    conexionPDO::USUARIO => "Raz",
    conexionPDO::PASSWORD => "Transaccionian",
    conexionPDO::BD => "BD_Web",
    conexionPDO::PORT => 3306
];

$dao = new PDAO($options);

$val = $dao->modificar("clase", ["id"=>40], new Clase(null, 900));

var_dump($val);
?>