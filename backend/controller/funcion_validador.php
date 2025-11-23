<?php
require_once('../model/allModels.php');
class Validador {
    const CONVERSION_FAIL = -1;
    const OK = 0;
    const WRONG_TYPE = 1;
    const DATA_TOO_SMALL = 2;
    const DATA_TOO_BIG = 3;
    const NULL_DATA = 4;
    const WRONG_DATE = 5;
    const REGEX_FAIL = 6;
    const DATE_NEGATIVE = 7;


    /**
     * valida los datos de un modelo y retorna un arreglo asociativo con formato campo => codigo
     */
    public static function escanearModelo(array $valores, string $tabla){
        $codigos = array();
        $rules = Models::get($tabla)::$rules;
        foreach ($valores as $campo => $valor) {
            $probar = $valor == null ? null : "".$valor;
            $codigos[$campo] = self::validar($probar, $rules[$campo]);
        }
        return $codigos;
    }
    public static function checkScan(array $codigos){
        foreach ($codigos as $campo => $codigo) {
            if($codigo != self::OK) return false;
        }
        return true;
    }
    /**
     * Prueba un dato en base a las reglas dadas
     */
    static function validar(string|null $dato, DataRow $rules){
        $convertido = null;
        if(!$rules[DataRow::NO_NULO]){
            if(self::vacio($dato)) return self::OK;
        }else if(self::vacio($dato)) return self::NULL_DATA;

        try{
            $regex = $rules[DataRow::REGEX];
            if($regex == Modelo::CHECK_EMAIL){
                if(filter_var($dato, FILTER_VALIDATE_EMAIL) == false) return self::REGEX_FAIL;
            }else if(!self::vacio($regex)){
                if(!preg_match("/^".$regex."$/", $dato)) return self::REGEX_FAIL;
            }

            $convertido = self::convertir($dato, $rules[DataRow::TIPO]);
            if($convertido instanceof DateTime && self::probarFecha($convertido)) return self::WRONG_DATE;
            $umbral = $rules[DataRow::UMBRAL];
            $limite = $rules[DataRow::LIMITE];
            if($umbral > -1){
                if(!self::enRangoMulti($convertido, null, $umbral)) return self::DATA_TOO_SMALL;
            }
            if ($limite > -1) {
                if (!self::enRangoMulti($convertido, $limite, null)) return self::DATA_TOO_BIG;
            }
        }catch(Exception $e){
            return self::WRONG_TYPE;
        }

        return self::OK;
    }
    /**
     * Crea un error de conversion
     */
    static private function convertirException(string $dato, string $tipo){
        return new Exception("Error de converison: '" . $dato . "' a " . $tipo . "", self::CONVERSION_FAIL);
    }
    /**
     * Convierte un string a fecha
     */
    static function convertirFecha(string $dato, string $formato = "Y-m-d"){
        return DateTime::createFromFormat($formato, $dato);
    }
    /**
     * Prueba que la fecha sea valida
     */
    static function probarFecha(DateTime $fecha){
        return checkdate($fecha->format('m'), $fecha->format('d'), $fecha->format('Y'));
    }
    /**
     * prueba si un numero esta dentro de un rango
     */
    static function enRango(int|float $x, int|float|null $max = null, int|float|null $min = null){
        if(!isset($max) || $max == null) $max = $x + 1;
        if (!isset($min) || $min == null) $min = $x - 1;
        return $min <= $x && $x <= $max;
    }
    static function enRangoMulti(int|float|string|DateTime $x, int|float|null $max = null, int|float|null $min = null){
        if($x instanceof DateTime){
            $fechaStr = $x->format('Y-m-d');
            return self::enRango(strtotime($fechaStr), $max, $min);
        }
        if(gettype($x) == 'string'){
            return self::enRango(strlen($x), $max, $min);
        }
        return self::enRango($x, $max, $min);
    }
    /**
     * revisa que una fecha sea mayor a otra
     */
    static function probarFechas(string $fechaMenor, string $fechaMayor){
        return strtotime($fechaMenor) < strtotime($fechaMayor);
    }
    /**
     * revisa si el dato esta vacio
     */
    static function vacio(string|null $dato = null){
        return $dato == null || strlen($dato) == 0;
    }
    /**
     * convierte un tipo de dato, o lanza error si no es posible
     */
    static function convertir(string $dato, string $tipo){
        switch($tipo){
            case "string":
                return $dato;
            case "date": {
                $d = self::convertirFecha($dato);
                if(!self::probarFecha($d)) throw self::convertirException($dato, $tipo);
                return $d;
            }
            case "double":{
                $d = filter_var($dato, FILTER_VALIDATE_FLOAT);
                if($d == false) throw self::convertirException($dato, $tipo);
                return $d;
            }
            case "int": {
                $d = filter_var($dato, FILTER_VALIDATE_INT);
                if ($d == false) throw self::convertirException($dato, $tipo);
                return $d;
            }
            default: throw self::convertirException($dato, $tipo);
        }
    }

}

?>