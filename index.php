<?php
$url = $_GET['url'] ?? '';
$url = trim($url, '/'); // limpiar barras
//echo $url;

/////////////otras paginas

$urlSegment = explode("_", $url);
if (count($urlSegment) < 1) return;

//paginas ABCC
if ($urlSegment[0] == "abcc") {
    require "backend/pages/abcc/" . $url . ".php";
    return;
}

///////////paginas no abcc

switch ($url) {
    case '':
        require "backend/pages/index.php";
        return;
    case 'logout':
        require "backend/controller/cerrar_sesion.php";
        return;
    case 'login':
        require "backend/pages/login.php";
        return;
    case 'validar':
        require "backend/controller/validar_usuario.php";
        return;
    case 'feed':
        require "backend/pages/feedEventos.php";
        return;
    ////bloquear acceso a paginas fuera del rango
    default:
        http_response_code(404);
        return;
}

