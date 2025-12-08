<?php
require_once(__DIR__ . '/PDAO.php');
if (!isset($_SESSION)) {
    session_start();
}
    if (false) {
        return json_encode(["status" => false]);
    }
    function getUserPDAO($BD = "BD_Web"){
    return new PDAO([
        conexionPDO::BD => $BD,
    ]);
    }
?>