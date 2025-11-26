<?php

    class ConexionBD {
        private $conexion;
        public const HOST = "host";
        public const PORT = "port";
        public const USUARIO = "usuario";
        public const PASSWORD = "password";
        public const BD = "bd";

        private $options = array(
        self::HOST => "localhost",
        self::PORT => 3306,
        self::USUARIO => "dominik",
        self::PASSWORD => "santiago",
        self::BD => "BD_Web"
        );
        public function __construct(array $opciones = array()){
            
            foreach ($opciones as $key => $value) {
                if(!isset($this->options[$key])) continue;
                $this->options[$key] = $value;
            }
            
            $address = $this->options[self::HOST] . ":" . $this->options[self::PORT];
            $this->conexion = mysqli_connect($address, $this->options[self::USUARIO], $this->options[self::PASSWORD], $this->options[self::BD]);

            if(!$this->conexion){
                die("ERROR: conexion fallida ---- \n" . mysqli_connect_error());
            }
            $this->conexion->autocommit(false);
        }
        public function commit(){
            $this->conexion->commit();
        }
        public function rollback(){
            $this->conexion->rollback();
        }
        public function getConexion(){
            return $this->conexion;
        }
    }
?>