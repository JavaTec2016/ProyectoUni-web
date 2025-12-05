<?php
    include_once(__DIR__ . '/../../database/conexion_bd.php');
    $con = new ConexionBD([
        ConexionBD::BD => "BD_Web_Usuarios"
    ]);
    $usr = $_POST["usuario_input"];
    $pass = $_POST["pass_input"];

    //verificar si existe el usuario

    $conexion = $con->getConexion();
    $res = 1;

    if($conexion){
        //cifraderas pero mejores
        $u_cifrado = $usr;
        $p_cifrado = hash('sha256', $pass);

        $sql = "SELECT * FROM usuario WHERE nombre='$u_cifrado' AND pass='$p_cifrado'";
        $res = mysqli_query($conexion, $sql);
        $num = mysqli_num_rows($res);

        if($num==1){
            $data = mysqli_fetch_all($res, MYSQLI_ASSOC)[0];
            session_start();
            $_SESSION["autenticado"] = true;
            $_SESSION["usuario"] = $usr;
            $_SESSION["pass"] = $pass;
            $_SESSION["rol"] = $data["rol"];
            header("location: feed");
            exit(0);
        }else{
            session_start();
            $_SESSION["autenticado"] = false;
            header("location: login");
            exit(0);
        }

        
    }
?>