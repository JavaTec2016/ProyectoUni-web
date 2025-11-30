<?php

include(__DIR__ . '/../backend/controller/PDAO.php');

$options = [
    conexionPDO::USUARIO => "Raz",
    conexionPDO::PASSWORD => "Transaccionian",
    conexionPDO::BD => "BD_Web",
    conexionPDO::PORT => 3306
];

$dao = new PDAO($options);

$val = $dao->agregar("clase", ["anio_graduacion"=>777]);

var_dump($val);
?>