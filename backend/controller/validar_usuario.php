<?php

    include_once(__DIR__ . '/../../database/conexionPDO.php');
    $con = conexionPDO::get();
    $con->connect([
        conexionPDO::BD => conexionPDO::BD_USER
    ]);
    $usr = $_POST["usuario_input"];
    $pass = $_POST["pass_input"];

    //verificar si existe el usuario
    $res = 1;

    if($con){
        //cifraderas pero mejores
        $u_cifrado = $usr;
        $p_cifrado = hash('sha256', $pass);

        $sql = "SELECT * FROM usuario WHERE nombre=? AND pass=?";
        $res = $con->queryAssoc($sql, [$u_cifrado, $p_cifrado]); //$conexion->query($sql, ) mysqli_query($conexion, $sql);
        $num = count($res);
        //el usuario existe y el recaptcha tmb
        if($num==1 && isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] != ''){
            $data = $res[0];
            session_start();
            $_SESSION["autenticado"] = true;
            $_SESSION["usuario"] = $usr;
            $_SESSION["pass"] = $pass;
            $_SESSION["rol"] = $data["rol"];
            $_SESSION['timestamp'] = time();
            header("location: feed");
        }else{
            session_start();
            $_SESSION["autenticado"] = false;
            header("location: login");
        }

        
    }
?>