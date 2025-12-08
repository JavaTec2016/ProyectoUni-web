<?php
error_reporting(0);
    include_once(__DIR__ . '/../../database/DAO.php');
    if(!isset($_SESSION) || !$_SESSION['autenticado']) die(0);
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $cadenaJSON = file_get_contents('php://input');
        $datos = array();
        if (!isset($cadenaJSON) || $cadenaJSON == false) {
            $datos = $_GET;
        }else $datos = json_decode($cadenaJSON, true);

        if($datos['MODE'] == "commit"){
            $dao = new DAO();
            $dao->getConexion()->commit();
            return;
        }
        if ($datos['MODE'] == "rollback") {
            $dao = new DAO();
            $dao->getConexion()->rollback();
            return;
        }
    }

?>