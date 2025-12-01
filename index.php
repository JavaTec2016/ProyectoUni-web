<?php
header("Location: backend/pages/");
define("URL_BASE", "/");
$path = $_SERVER['REQUEST_URI'];

//mochale la URL directorio
if (substr($path, 0, strlen(URL_BASE)) == URL_BASE) {
    $path = substr($path, strlen(URL_BASE));
}

echo $path;

    switch($path){
        case '':
            require "backend/pages/index.php";
            break;
        
    }
?>