<?php

class Clase extends Modelo
{
    public static $rules = [];

    public final const ID = "id";
    public final const ANIO_GRADUCION = "anio_graduacion";

    public function __construct(int|null $id = null, int|null $anio_graduacion = null)
    {
        $this->fillValuesFrom(array_keys(static::$rules), func_get_args());
    }
    public static function setRules()
    {
        static::addRule(self::ID, new DataRow("int", "INT", true, true, false, -1, -1, true, Modelo::REGEX_INTEGER));
        static::addRule(self::ANIO_GRADUCION, new DataRow("string", "STRING", true, false, false, -1, -1, false, Modelo::REGEX_INTEGER));
    }
}

Clase::setRules();
Models::set("clase", new Clase());
