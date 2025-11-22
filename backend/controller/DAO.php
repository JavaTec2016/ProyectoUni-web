<?php
    require_once(__DIR__ . '/../../database/conexion_bd.php');
    require_once(__DIR__ . '/../model/Modelo.php');

    class DAO {
        public final const ASCEND = "ASC";
        public final const DESCEND = "DESC";
        private $conexion;

        public function __construct(){
            $this->conexion = new ConexionBD();

        }
        
        public function getConexion()
        {
            return $this->conexion->getConexion();
        }

        //-----------ALTAS


        public function agregar(string $tabla, array $modelo){
            $campos = "(" . join(",", array_keys($modelo)) . ")";
            $sql = "INSERT INTO " . $tabla . " " . $campos . " VALUES";
            $ponidos = array();
            foreach ($modelo as $key => $value) {
                $val = $value;
                if (is_string(($val)) || gettype($val) == "string") {
                    $val = "'" . $val . "'";
                }
                $ponidos[$key] = $val;
            }
            $vals = "(".join(", ", $ponidos).")";
            $sql = $sql . $vals;
            return mysqli_query($this->getConexion(), $sql);
        }

        //-----------BAJAS
        
        public function eliminar(string $tabla, array $valores){
            $sql = "DELETE FROM " . $tabla . " WHERE ";
            $where = $this->makeWhereCamposSimple($valores, $this->fakeComodines($valores, false), Models::getTiposOf($tabla));
            $sql .= $where;
            mysqli_query($this->getConexion(), $sql);
        }

        public function eliminarPrimaria(string $tabla, string $primariaValor){

            $sql = "DELETE FROM " . $tabla . " WHERE ";
            $campoPrimaria = Models::get($tabla)::getCampoPrimario();

            $valores = array("" . $campoPrimaria => $primariaValor);
            $tipos = array("" . $campoPrimaria => Models::getTiposOf($tabla)[$campoPrimaria]);

            
            $sql .= $this->makeWhereCamposSimple($valores, $this->fakeComodines($valores, false), $tipos);

            return mysqli_query($this->getConexion(), $sql);
        }

        //-----------CAMBIOS

        public function modificar(string $tabla, array $filtros, Modelo $modelo){
            $sql = "UPDATE " . $tabla;
            $set = " SET " . $this->makeWhereCamposSimple($modelo->valores, $this->fakeComodines($modelo->valores, false), Models::getTiposOf($tabla), ", ");
            $where = " WHERE " .  $this->makeWhereCamposSimple($filtros, $this->fakeComodines($filtros, false), Models::getTiposOf($tabla));

            $sql .= $set . $where;

            return mysqli_query($this->getConexion(), $sql);
        }

        //-----------CONSULTAS

        public function consultarPrimaria(string $tabla, string $primariaValor){

            $sql = "SELECT * FROM " . $tabla . " WHERE ";
            $campoPrimaria = Models::get($tabla)::getCampoPrimario();
            $valores = array("" . $campoPrimaria => $primariaValor);
            $tipos = array("" . $campoPrimaria => Models::getTiposOf($tabla)[$campoPrimaria]);
            $sql .= $this->makeWhereCamposSimple($valores, $this->fakeComodines($valores, false), $tipos) . " LIMIT 1";

            return mysqli_query($this->getConexion(), $sql);

        }

        public function getCamposOrder(array $camposOrder){
            $out = array();
            foreach ($camposOrder as $campo => $orden) {
                array_push($out, $campo . " " . $orden);
            }
            return join(", ", $out);
        }
        public function consultar(string $tabla, array $selectNombres = array(0=>"*"), array|null $camposValores = null, array|null $camposComodines = null, array|null $camposOrder = null, int $limite = -1){
            $sql = "SELECT ".join(", ", $selectNombres)." FROM " . $tabla;
            
            if($camposValores != null){
                $tipos = Models::getTiposOf($tabla);
                $where = " WHERE " . $this->makeWhereCamposSimple($camposValores, $camposComodines, $tipos);
                $sql = $sql . $where;
            }
            if($camposOrder != null) $sql = $sql . " ORDER BY " . $this->getCamposOrder($camposOrder);
            if($limite > 0) $sql = $sql . " LIMIT ". $limite;
            return mysqli_query($this->getConexion(), $sql);
        }
        public function assoc(mysqli_result $res){
            return mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        ////=----------FORMATEO DINAMICO

        public function prepararStatement($consulta, $tipos = null, $valores = null){
            
            $statement = mysqli_prepare($this->getConexion(), $consulta);
            /**
             * tira error gg
             * @var string
             */
            $tiposStr = null;
            if($tipos != null){
                $tiposStr = join("", $tipos);
            }
            else if ($valores != null and $tiposStr != null){
                mysqli_stmt_bind_param($statement, $tiposStr, $valores);
            }
            return $statement;
        }
        /**
         * Construye la instruccion WHERE con arrays asociativos de los campos, sus valores y si usan comodin
         * @param array $camposValores array asociativo de campos y valores
         * @param array $camposComodines array asociativo de campos y un booleano que indica si usa comodin (busqueda LIKE)
         * @return array array asociativo de sentencias y sus respectivos valores
         */
        public function makeWhereCampos($camposValores, $camposComodines){
            $camposWhere = array();
            $filtro = null;
            foreach ($camposValores as $campo => $valor) {
                $esComodin = $camposComodines[$campo];
                
                if($esComodin){
                    $filtro = $this->makeFiltroLike($campo, $valor, "%");
                }else{
                    $filtro = $this->makeFiltro($campo, $valor);
                }
                $camposWhere[$filtro['statement']] = $filtro['valor'];
            }
            return $camposWhere;
        }
        /**
         * Retorna el segmento de filtros WHERE de una sentencia sql.
         * Es un makeWhereCampos no seguro
         */
        public function makeWhereCamposSimple($camposValores, $camposComodines, $camposTipos, string $joiner = " AND "){
            $camposWhere = array();
            $filtro = null;
            foreach ($camposValores as $campo => $valor) {
                $esComodin = $camposComodines[$campo];
                $tipo = $camposTipos[$campo];
                
                if($esComodin){
                    $filtro = $this->makeFiltroLikeSimple($campo, $valor, "", "%", $tipo);
                }else{
                    $filtro = $this->makeFiltroSimple($campo, $valor, $tipo);
                }
                
                $camposWhere[$campo] = $filtro;
            }
            return join($joiner, $camposWhere);
        }
        /**
         * Rellena un arreglo con comodines inventados
         */
        public function fakeComodines(array $camposValores, bool $estado){
            $out = array();
            foreach ($camposValores as $key => $value) {
                $out[$key] = $estado;
            }
            return $out;
        }
        /**
         * junta las llaves de un arreglo con " AND "
         */
        public function joinLlaves($camposWhere){
            return join(" AND ", array_keys($camposWhere));
        }
        /**
        * genera una parte de instruccion WHERE con el filtro LIKE del valor
        * @param string $campo nombre del campo
        * @param string $mixed valor del campo
        * @param string $wildcardPre wildcard antes del valor
        * @param string $wildcardPost wildcard despues del valor
        */
        public function makeFiltroLike(string $campo, $valor, string $wildcardPre="", string $wildcardPost=""){
            return array(
                "statement" => $campo . " LIKE ?",
                "valor" => $wildcardPre . $valor . $wildcardPost
            );
        }
        public function makeFiltroLikeSimple(string $campo, $valor, string $wildcardPre="", string $wildcardPost="", string $tipo){
            $modo = $wildcardPre . $valor . $wildcardPost;
            return $campo . " LIKE '" . $modo . "'";            
        }
        /**
         * genera una parte de instruccion WHERE con el filtro exacto del valor
         * @param string $campo nombre del campo
         * @param mixed $valor valor del campo
         */
        public function makeFiltro(string $campo, $valor){
            return array(
                "statement" => $campo . " = ?",
                "valor" => $valor
            );
        }
        public function makeFiltroSimple(string $campo, $valor, string $tipo){
            $modo = $valor;
            if ($tipo == "string" || $tipo == "date") $modo = "'" . $modo . "'";
            return $campo . " = " . $modo;
        }
        public function getTiposCadena(array $tipos){
            $out = "";
            foreach ($tipos as $tipo) {
                switch($tipo){
                    case 'date':
                    case 'string': $out += "s"; break;
                    case 'double':
                    case 'float': $out += "d"; break;
                    case 'int':
                    case 'integer': $out += "i"; break;
                }
            }
            return $out;
        }
    }

?>