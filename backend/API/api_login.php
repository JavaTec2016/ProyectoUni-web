<?php
include_once(__DIR__ . '/../../database/conexion_bd.php');
header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $con = new ConexionBD([
        ConexionBD::BD => "BD_Web_Usuarios"
    ]);

    $cadenaJSON = file_get_contents('php://input');
    $datos = array();
    if (!isset($cadenaJSON) || $cadenaJSON == false) {
        $datos = $_GET;
    } else $datos = json_decode($cadenaJSON, true);

    $usr = $datos["usuario"];
    $pass = $datos["pass"];

    $u_cifrado = hash('sha256', $usr);
    $p_cifrado = hash('sha256', $pass);

    $sql = "SELECT * FROM usuario WHERE nombre='$u_cifrado' AND pass='$p_cifrado'";
    $res = mysqli_query($con->getConexion(), $sql);
    $data = mysqli_fetch_all($res, MYSQLI_ASSOC)[0];
    $num = mysqli_num_rows($res);

    $response = array("status"=>false, "rol"=>"");

    if ($num == 1) {
        session_start();
        $_SESSION["autenticado"] = true;
        $_SESSION["usuario"] = $usr;
        $_SESSION["rol"] = $data["rol"];
        $response["status"] = true;
        $response["rol"] = $_SESSION["rol"];
    } else {
        session_start();
        $_SESSION["autenticado"] = false;
    }
    echo json_encode($response);
}

?>