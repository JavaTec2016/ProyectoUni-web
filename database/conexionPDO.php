<?php

class conexionPDO {
    /**
     * @var PDO
     */
    private $conexion = null;
    /**
     * @var conexionPDO
     */
    private static $instance;
    public const HOST = "host";
    public const PORT = "port";
    public const USUARIO = "usuario";
    public const PASSWORD = "password";
    public const BD = "bd";
    public const BD_MAIN_LOCAL = "BD_Web";
    public const BD_USER_LOCAL = "BD_Web_Usuarios";

    public const BD_MAIN_HOSTING = "4676369_bdweb";
    public const BD_USER_HOSTING = "4676369_bdweb";
    public const BD_MAIN = self::BD_MAIN_LOCAL;
    public const BD_USER = self::BD_USER_LOCAL;

    private $optionsLocal = array(
        self::HOST => "localhost",
        self::PORT => 3306,
        self::USUARIO => "dominik",
        self::PASSWORD => "santiago",
        self::BD => self::BD_MAIN_LOCAL
    );

    private $optionsHosting = array(
        self::HOST => "fdb1032.awardspace.net",
        self::PORT => 3306,
        self::USUARIO => "4676369_bdweb",
        self::PASSWORD => "Caradeweb1209",
        self::BD => self::BD_MAIN_HOSTING
    );
    
    private $options;

    ///singleton

    private function __construct() {
        $this->options = $this->optionsLocal;
    }

    public static function get(){
        if(self::$instance == null) self::$instance = new conexionPDO();
        return self::$instance;
    }

    public function connect(array $opciones = array()){
        $this->setOptions($opciones);
        $address = $this->options[self::HOST] . ":" . $this->options[self::PORT];
        $dsn = "mysql:host=".$address.";dbname=".$this->options[self::BD];
        $this->conexion = new PDO($dsn, $this->options[self::USUARIO], $this->options[self::PASSWORD]);
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    private function setOptions(array $opciones = array()){
        foreach ($opciones as $key => $value) {
            if (!isset($this->options[$key])) continue;
            $this->options[$key] = $value;
        }
    }
    public function prepare(string $sql){
        return $this->conexion->prepare($sql);
    }
    public static function getType(string $type){
        switch($type){
            case "int":
            case "integer":
            case "i":
                return PDO::PARAM_INT;
            case "bool":
            case "boolean":
            case "b":
                return PDO::PARAM_BOOL;
            default:
                return PDO::PARAM_STR;
        }
    }
    /**
     * enlaza valores a un preparedStatement con placeholders posicionales
     * @param PDOStatement $stmt
     * @param array<string,mixed> $values
     * @param array<string,DataRow> $rules
     */
    public function bind(PDOStatement $stmt, array $values, array $rules, int $offset=1){
        $i = $offset;
        foreach ($values as $key => $value) {
            $rule = $rules[$key];
            $tipo = self::getType($rule->get(DataRow::TIPO));
            if (empty($value)) $value = null;
            $stmt->bindValue($i, $value, $tipo);
            //echo "Bound param " . $i . " (" . $key . ") to value " . $value . "<br>";
            $i++;
        }
        
    }
    public function queryAssoc(string $sql, array $values){
        $stmt = $this->prepare($sql);
        $stmt->execute($values);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function preparedExecute(string $sql, array $values){
        $stmt = $this->prepare($sql);
        return $stmt->execute($values);
    }
    public function commit()
    {
        $this->conexion->commit();
    }
    public function rollback()
    {
        $this->conexion->rollback();
    }
}

?>