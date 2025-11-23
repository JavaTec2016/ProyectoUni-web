<?php
require_once('Modelo.php');
class Garantia extends Modelo
{
    public static $rules = [];

    public final const ID = "id";
    public final const ID_DONADOR = "id_donador";
    public final const ID_EVENTO = "id_evento";
    public final const GARANTIA = "garantia";
    public final const PAGO_TOTAL = "pago_total";
    public final const METODO_PAGO = "metodo_pago";
    public final const NUMERO_PAGOS = "numero_pagos";
    public final const FECHA_GARANTIA = "fecha_garantia";
    public final const ID_CIRCULO = "id_circulo";

    public function __construct(
        int|null $id = null,
        int|null $id_donador = null,
        int|null $id_evento = null,
        float|null $garantia = null,
        float|null $pago_total = null,
        string|null $metodo_pago = null,
        int|null $numero_pagos = null,
        string|null $fecha_garantia = null,
        int|null $id_circulo = null,
    ){
        $this->fillValuesFrom(array_keys(static::$rules), func_get_args());
    }

    public static function setRules()
    {
        static::addRule(self::ID, new DataRow("int", "INT", true, true, false, -1, -1, true, Modelo::REGEX_INTEGER));
        static::addRule(self::ID_DONADOR, new DataRow("int", "INT", true, false, true, -1, -1, false, Modelo::REGEX_INTEGER));
        static::addRule(self::ID_EVENTO, new DataRow("int", "INT", true, false, true, -1, -1, false, Modelo::REGEX_INTEGER));
        static::addRule(self::GARANTIA, new DataRow("double", "DECIMAL", true, false, false, -1, -1, false, Modelo::REGEX_DECIMAL));
        static::addRule(self::PAGO_TOTAL, new DataRow("double", "DECIMAL", true, false, false, -1, -1, false, Modelo::REGEX_DECIMAL));
        static::addRule(self::METODO_PAGO, new DataRow("string", "VARCHAR", true, false, false, 0, 50, false, Modelo::REGEX_ACENTO));
        static::addRule(self::NUMERO_PAGOS, new DataRow("int", "INT", true, false, false, -1, -1, false, Modelo::REGEX_INTEGER));
        static::addRule(self::FECHA_GARANTIA, new DataRow("date", "DATE", true, false, false, -1, -1, false, Modelo::CHECK_DATE));
        static::addRule(self::ID_CIRCULO, new DataRow("int", "INT", true, false, true, -1, -1, false, Modelo::REGEX_INTEGER));
    }
}
Garantia::setRules();
Models::set("garantia", new Garantia());
