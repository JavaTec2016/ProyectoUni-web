<?php
require_once('Models.php');
abstract class Modelo {
    public final const REGEX_AZ = "[A-Z-a-z]+";
    public final const REGEX_ACENTO = "([0-9a-zA-ZÀ-ÿ\\x{00f1}\\x{00d1}]||(\\s*[a-zA-ZÀ-ÿ\\x{00f1}\\x{00d1}]*)*[a-zA-ZÀ-ÿ\\x{00f1}\\x{00d1}])+";
    public final const REGEX_AZ_SPACE = "[A-Z-a-z]+";
    public final const REGEX_AZ_NUMBER = "[0-9A-Za-z]+";
    public final const REGEX_AZ_NUMBER_SPACE = "[0-9 A-Za-z]+";
    public final const REGEX_NO_SPECIAL = "[^@!$%^&*+\\-_=\\/\\(\\)\\[\\]]+";
    public final const REGEX_INTEGER = "[0-9]+";
    public final const REGEX_DECIMAL = "(\\d{1,10}.\\d{1,2})";
    public final const CHECK_EMAIL = "EMAIL";
    public final const CHECK_DATE = "DATE";
    /**
     * reglas del modelo, hay que declararla en cada submodelo o van a agarrar esta (las reglas de la superclase)
     * @var array<string,DataRow>
     */
    public static $rules = array();
    /**
     * @var array<string,array<string,string>>
     */
    public static $aliases = array();
    public $valores = array();
    /**
     * Inicializa las llaves de los datos del modelo y establece sus valores
     */
    public function fillValuesFrom($valueKeys, $args){
        if(count($args) == 0) return;
        for ($i=0; $i < count($valueKeys); $i++) {
            $this->valores[$valueKeys[$i]] = null;
        }
        $this->fillValues($args);
    }

    public function fillValues($args) {
        $i = 0;
        foreach ($this->valores as $attrib => $val) {
            $this->valores[$attrib] = $args[$i];
            $i++;
        }
    }
    public static function getCampoPrimario(){
        $states = static::aggregateRule(DataRow::PRIMARIA);
        foreach ($states as $key => $value) {
            if($value) return $key;
        }
        return null;
    }
    public static function filterRules(string $ruleNombre, mixed $valor){
        $ruleValues = static::aggregateRule($ruleNombre);
        $out = array();
        foreach ($ruleValues as $key => $value) {
            if ($value == $valor) array_push($out, $key);
        }
        return $out;
    }
    protected static function addRule(string $campo, DataRow $rule)
    {
        static::$rules[$campo] = $rule;
    }
    public static function aggregateRule($ruleNombre)
    {
        $out = array();
        foreach (static::$rules as $key => $value) {

            $out[$key] = $value[$ruleNombre];
        }
        return $out;
    }
    protected static function getRule(string $campo): DataRow
    {
        return static::$rules[$campo];
    }
    public static function getRuleMap(array|null $campos, array $ruleNames){
        $o = [];
        if($campos == null) $campos = array_keys(static::$rules);
        foreach ($campos as $campo) {
            $o[$campo] = static::getRule($campo)->getFields($ruleNames);
        }
        return $o;
    }
    public static function getRuleKeys(){
        return array_keys(static::$rules);
    }
    public static function setRules(){
        static::$rules = array();
    }
    public function getClass()
    {
        return static::class;
    }
    public function setValues(array $novos)
    {
        foreach ($novos as $key => $value) {
            $this->valores[$key] = $value;
        }
    }
    /**
     * asigna los valores del array si su respectiva llave existe en las reglas del modelo
     */
    public function populate(array $assoc){
        foreach(static::$rules as $campo => $rule){
            if(in_array($campo, array_keys($assoc))){
                $this->valores[$campo] = $assoc[$campo];
            }
        }
    }
    /**
     * filtra los valores del modelo, retornando aquellos cuya regla especificada coincida con el valor dado
     * @param string $ruleNombre nombre de la regla a probar en cada valor del modelo
     * @param mixed $ruleValor valor a probar para la regla
     */
    public function filtrarValues(string $ruleNombre, $ruleValor){
        $out = array();
        $ruleSet = $this->aggregateRule($ruleNombre);
        
        foreach ($this->valores as $key => $value) {
            $rule = $ruleSet[$key];
            if($rule == $ruleValor) $out[$key] = $value;
        }
        return $out;
    }
    /**
     * retorna el alias asignado a un campo en este modelo, o nulo si no es encontrada
     */
    public static function getAlias(string $seccion, string $key){
        if (!in_array($seccion, array_keys(static::$aliases))) return null;
        if (!in_array($key, array_keys(static::$aliases[$seccion]))) return null;
        return static::$aliases[$seccion][$key];
    }
    public static function setAlias(string $seccion, string $campo, string $alias){
        if($alias == null) $alias = $campo;
        static::$aliases[$seccion][$campo] = $alias;
    }
    /**
     * Combina campos de diferentes modelos con sus respectivas reglas en este modelo
     * @param array<string,array<string,string|null>> $seleccion arreglo asociativo con el formato (nombre original del campo) => (alias),
     * si el alias de un campo es nulo, se asigna el mismo nombre
     * @param string[] $tablas nombres de las tablas a combinar
     */
    public static function combineAs(array $seleccion)
    {
        foreach ($seleccion as $tabla => $campos) {
            $ruleset = Models::get($tabla);
            static::$aliases[$tabla] = array();
            foreach ($campos as $campo => $alias) {
                if($alias == null) $alias = $campo;
                static::addRule($alias, $ruleset[$campo]);
                static::setAlias($tabla, $campo, $alias);
            }
        }
    }
}

?>