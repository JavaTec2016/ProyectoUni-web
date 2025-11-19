<?php
    require_once('DataRow.php');
    abstract class Models {
        static $MODELOS = array();

        /**
         * @return Modelo
         */
        public static function get(string $nombre)
        {
            return static::$MODELOS[$nombre];
        }
        //wtf
        public static function instanciarLimpio(string $nombre){
            return new $nombre;
        }
        public static function instanciar(string $nombre, array $args){
            /**@var Modelo */
            $inst = new $nombre;
            $inst->populate($args);
            return $inst;
        }
        public static function set(string $nombre, Modelo $modelo){
            static::$MODELOS[$nombre] = $modelo;
        }
        public static function getTiposOf(string $nombre){
            return static::get($nombre)::aggregateRule(DataRow::TIPO);
        }
        public static function getPrimariaOf(string $nombre){
            return static::get($nombre)::getCampoPrimario();
        }
        public static function cleanStr(string $value, string $toClean){
            return str_replace($toClean, "", $value);
        }
        /**
         * retorna un asociativo con llaves limpias y sus valores
         */
        public static function cleanKeys(array $assoc, string $valueToClean){
            $out = array();
            foreach ($assoc as $key => $value) {
                $out[static::cleanStr($key, $valueToClean)] = $value;
            }
            return $out;
        }
    }
?>