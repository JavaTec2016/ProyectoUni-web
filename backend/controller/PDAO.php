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

    ///ABCC

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
    public function eliminar(string $tabla, array $modelo){
        $tablaRules = Models::get($tabla)::$rules;
        $sql = "DELETE FROM " . $tabla . " WHERE ";
        $sql = $sql . $this->makeWhereCampos($modelo);
        return $this->execute($sql, $modelo, $tablaRules);
    }

    public function eliminarPrimaria(string $tabla, mixed $primaria){
        $campoPrimaria = Models::getPrimariaOf($tabla);
        return $this->eliminar($tabla, [$campoPrimaria => $primaria]);
    }
    public function modificar(string $tabla, array $filtros, Modelo $modelo){
        $tablaRules = Models::get($tabla)::$rules;
        $sql = "UPDATE " . $tabla . " SET ";
        $campos = $this->makeSetCampos($modelo->valores) . " WHERE ";
        $where = $this->makeWhereCampos($filtros);

        $sql = $sql . $campos . $where;

        
        ///hay que bindear por separado porque el arreglo SET y el WHERE utilizan las mismas llaves
        $stmt = $this->conexion->prepare($sql);
        $this->conexion->bind($stmt, $modelo->valores, $tablaRules);
        $this->conexion->bind($stmt, $filtros, $tablaRules, count($modelo->valores)+1);
        
        return $stmt->execute();
    }
    public function consultar(string $tabla, array $selectNombres = array(0 => "*"), array|null $camposValores = null, array|null $camposComodines = null, array|null $camposOrder = null, int $limite = -1) {
        $sql = "SELECT " . join(", ", $selectNombres) . " FROM " . $tabla;
        $where = "";
        $filtros = [];
        if ($camposValores != null) {
            $sql = $sql .  " WHERE ";
            $sentencia = $this->makeWhereCamposLike($camposValores);
            $where = $sentencia['statement'];
            $filtros = $sentencia['values'];

            
            $sql = $sql . $where;
        };
        if ($camposOrder != null) $sql = $sql . " ORDER BY " . $this->getCamposOrder($camposOrder);
        if ($limite > 0) $sql = $sql . " LIMIT " . $limite;
        return $this->conexion->queryAssoc($sql, array_values($filtros));
    }

    //BUILD CONSULTA

    public function makeWhereCampos(array $modelo){
        $where = [];
        foreach ($modelo as $campo => $valor) {
            $partes = $this->makeFiltro($campo, $valor);
            $where[$campo] = $partes['statement'];
        }
        return join(" AND ", $where);
    }
    public function makeWhereCamposLike(array $modelo)
    {
        $where = [];
        $values = [];
        foreach ($modelo as $campo => $valor) {
            $partes = $this->makeFiltroLike($campo, $valor, "", "%");
            $where[$campo] = $partes['statement'];
            $values[$campo] = $partes['valor'];
        }
        return [
            "statement" => join(" AND ", $where),
            "values" => $values
        ];
    }
    public function makeSetCampos(array $modelo)
    {
        $where = [];
        foreach ($modelo as $campo => $valor) {
            $partes = $this->makeFiltro($campo, $valor);
            $where[$campo] = $partes['statement'];
        }
        return join(", ", $where);
    }
    /**
     * genera una parte de instruccion WHERE con el filtro exacto del valor
     * @param string $campo nombre del campo
     * @param mixed $valor valor del campo
     */
    public function makeFiltro(string $campo, $valor)
    {
        return array(
            "statement" => $campo . " = ?",
            "valor" => $valor
        );
    }
    /**
     * genera una parte de instruccion WHERE con el filtro LIKE del valor
     * @param string $campo nombre del campo
     * @param mixed $valor valor del campo
     * @param string $wildcardPre wildcard antes del valor
     * @param string $wildcardPost wildcard despues del valor
     */
    public function makeFiltroLike(string $campo, $valor, string $wildcardPre = "", string $wildcardPost = "")
    {
        $clause = "(". $campo . " LIKE ?";
        if (strlen("" . $valor) == 0) $clause .= " OR " . $campo . " IS NULL";
        $clause .= ")";
        return array(
            "statement" => $clause,
            "valor" => $wildcardPre . $valor . $wildcardPost
        );
    }
    public function getCamposOrder(array $camposOrder)
    {
        $out = array();
        foreach ($camposOrder as $campo => $orden) {
            array_push($out, $campo . " " . $orden);
        }
        return join(", ", $out);
    }
    public function call(string $procedureName, array $params){
        $questions = [];
        foreach ($params as $key => $value) {
            array_push($questions, "?");
        }
        $procedure = $procedureName . "(" . join(", ", $questions) .")";
        return $this->conexion->preparedExecute($procedure, $params);
    }
    /**
     * Agrega un usuario tanto a la tabla de usuarios como a la BD, y establece su rol
     */
    public function makeUser(string $nombre, string $pass, string $rol){
        $usr = new Usuario($nombre, $pass, $rol);
        $this->agregar("usuario", $usr->valores);
        return $this->call("addUsuario", $usr->valores);
    }
}

?>