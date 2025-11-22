<?php
require_once('Models.php');
abstract class Modelo {

    /**
     * reglas del modelo, hay que declararla en cada submodelo o van a agarrar esta (las reglas de la superclase)
     */
    public static $rules = array();
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
}

?>