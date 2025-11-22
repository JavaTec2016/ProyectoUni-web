<?php
    //pa mover objetos como en JS
    class DataRow implements ArrayAccess {
        
        public const TIPO = "tipo";
        public const TIPO_SQL = "tipoSQL";
        public const NO_NULO = "nonulo";
        public const PRIMARIA = "primaria";
        public const FORANEA = "foranea";
        public const UMBRAL = "umbral";
        public const LIMITE = "limite";
        public const OCULTO = "oculto";
        public const REGEX = "regex";

        public $tipo;
        public $tipoSQL;
        public $nonulo;
        public $primaria = false;
        public $foranea = false;
        public $umbral;
        public $limite;
        public $oculto;
        public $regex;

        public function __construct(string $tipo, string $tipoSQL, bool $nonulo, bool $primaria, bool $foranea, int $umbral, int $limite, bool $oculto, string $regex){
            $this->tipo = $tipo;
            $this->tipoSQL = $tipoSQL;
            $this->nonulo = $nonulo;
            $this->primaria = $primaria;
            $this->foranea = $foranea;
            $this->umbral = $umbral;
            $this->limite = $limite;
            $this->oculto = $oculto;
            $this->regex = $regex;
        
        }
        public function get(string $campo){
            return $this[$campo];
        }
        public function getFields(array $campos){
            $o = [];
            foreach ($campos as $campo) {
                array_push($o, $this->get($campo));
            }
            return $o;
        }
        public function offsetExists(mixed $offset): bool
        {
            return isset($this->$offset);
        }
        public function offsetGet(mixed $offset): mixed
        {
            return $this->$offset;
        }
        public function offsetSet(mixed $offset, mixed $value): void
        {
            $this->$offset = $value;
        }
        public function offsetUnset(mixed $offset): void
        {
            unset($this->$offset);
        }
    }

?>