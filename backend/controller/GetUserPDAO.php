<?php
require_once(__DIR__ . '/PDAO.php');
function getUserPDAO($BD = conexionPDO::BD_MAIN)
{
    return new PDAO([
        conexionPDO::BD => $BD,
    ]);
}
