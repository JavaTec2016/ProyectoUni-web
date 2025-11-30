<?php
require_once(__DIR__ . "/../../database/conexionPDO.php");
require_once(__DIR__ . "/../model/allModels.php");
class PDAO {
    public final const ASCEND = "ASC";
    public final const DESCEND = "DESC";
    private $conexion;

    public function __construct(array $options = array())
    {
        $this->conexion = conexionPDO::get();
        $this->conexion->connect($options);
    }
    public function execute(string $sql, array $values, array $rules){
        $stmt = $this->conexion->prepare($sql);
        $this->conexion->bind($stmt, $values, $rules);
        return $stmt->execute();
    }
    public function agregar(string $tabla, array $modelo){
        $tablaRules = Models::get($tabla)::$rules;
        $campos = "(" . join(",", array_keys($modelo)) . ")";

        $sql = "INSERT INTO " . $tabla . " " . $campos . " VALUES";
        $ponidos = array();
        foreach ($modelo as $key => $value) {
            $ponidos[$key] = "?";
        }
        $vals = "(" . join(", ", $ponidos) . ")";

        $sql = $sql.$vals;

        return $this->execute($sql, $modelo, $tablaRules);
    }
}

?>