<?php
session_start();
    if (!$_SESSION['autenticado']) {
        return json_encode(["status" => false]);
    }
    function getUserPDAO(){
    return new PDAO([
        conexionPDO::USUARIO => $_SESSION['usuario'],
        conexionPDO::PASSWORD => $_SESSION['pass'],
    ]);
    }
?>