<?php
require_once(__DIR__ . '/PDAO.php');
if (false) {
    session_start();
}
    if (false) {
        return json_encode(["status" => false]);
    }
    function getUserPDAO($BD = "BD_Web"){
    return new PDAO([
        conexionPDO::USUARIO => "",//$_SESSION['usuario'],
        conexionPDO::PASSWORD => "",// $_SESSION['pass'],
        conexionPDO::BD => $BD,
    ]);
    }
?>