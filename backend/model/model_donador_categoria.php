<?php

class Donador_Categoria extends Modelo
{
    public static $rules = [];

    public final const NOMBRE = "nombre";

    public function __construct(int|null $id = null, string|null $nombre = null, float|null $monto_minimo = null)
    {
        $this->fillValuesFrom(array_keys(static::$rules), func_get_args());
    }
    public static function setRules()
    {
        static::addRule(self::NOMBRE, new DataRow("string", "STRING", true, true, false, 0, 50, true, Modelo::REGEX_A_Z_ACENTO));
    }
}

Donador_Categoria::setRules();
Models::set("donador_categoria", new Donador_Categoria());
