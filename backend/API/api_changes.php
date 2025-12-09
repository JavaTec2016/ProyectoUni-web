<?php
    include_once(__DIR__ . '/../controller/GetUserPDAO.php');
    if(!isset($_SESSION) || !$_SESSION['autenticado']) die(0);
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $cadenaJSON = file_get_contents('php://input');
        $datos = array();
        if (!isset($cadenaJSON) || $cadenaJSON == false) {
            $datos = $_GET;
        }else $datos = json_decode($cadenaJSON, true);

        if($datos['MODE'] == "commit"){
            $dao = getUserPDAO();
            $dao->getConexion()->commit();
            return;
        }
        if ($datos['MODE'] == "rollback") {
            $dao = getUserPDAO();
            $dao->getConexion()->rollback();
            return;
        }
    }

?>