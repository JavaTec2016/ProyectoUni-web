<?php
require_once(__DIR__ . '/PDAO.php');
function getUserPDAO($BD = "BD_Web")
{
    return new PDAO([
        conexionPDO::BD => $BD,
    ]);
}
