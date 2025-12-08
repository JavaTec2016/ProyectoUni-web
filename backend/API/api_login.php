<?php
error_reporting(0);
include_once(__DIR__ . '/../../database/conexionPDO.php');
header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $con = conexionPDO::get();
    
    $con->connect([
        conexionPDO::BD => conexionPDO::BD_USER
    ]);

    $cadenaJSON = file_get_contents('php://input');
    $datos = array();
    if (!isset($cadenaJSON) || $cadenaJSON == false) {
        $datos = $_GET;
    } else $datos = json_decode($cadenaJSON, true);

    $usr = $datos["usuario"];
    $pass = $datos["pass"];
    $u_cifrado = $usr;//hash('sha256', $usr);
    $p_cifrado = hash('sha256', $pass);

    $sql = "SELECT * FROM usuario WHERE nombre=? AND pass=?";
    $res = $con->queryAssoc($sql, [$u_cifrado, $p_cifrado]); //$conexion->query($sql, ) mysqli_query($conexion, $sql);
    $num = count($res);
    $response = array("status"=>false, "rol"=>"");


    //falta CAPCHA
    if ($num == 1) {
        session_start();
        $data = $res[0];
        $_SESSION["autenticado"] = true;
        $_SESSION["usuario"] = $usr;
        $_SESSION["rol"] = $data["rol"];
        $_SESSION['timestamp'] = time();
        $response["status"] = true;
        $response["rol"] = $_SESSION["rol"];
    } else {
        session_start();
        $_SESSION["autenticado"] = false;
    }
    echo json_encode($response);
}

?>