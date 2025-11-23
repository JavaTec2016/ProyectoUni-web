<?php

class Pago extends Modelo {
    public static $rules = [];

    public final const ID = "id";
    public final const ID_GARANTIA = "id_garantia";
    public final const FECHA = "fecha";
    public final const MONTO = "monto";

    public function __construct(int|null $id = null, int|null $id_garantia = null, string|null $fecha = null, float|null $monto = null)
    {
        $this->fillValuesFrom(array_keys(static::$rules), func_get_args());
    }
    public static function setRules()
    {
        static::addRule(self::ID, new DataRow("int", "INT", true, true, false, -1, -1, true, Modelo::REGEX_INTEGER));
        static::addRule(self::ID_GARANTIA, new DataRow("int", "INT", true, false, true, -1, -1, true, Modelo::REGEX_INTEGER));
        static::addRule(self::FECHA, new DataRow("DATE", "DATE", true, false, false, -1, -1, false, Modelo::CHECK_DATE));
        static::addRule(self::MONTO, new DataRow("double", "DECIMAL", true, false, false, -1, -1, false, Modelo::REGEX_DECIMAL));
    }
}

Pago::setRules();
Models::set("pago", new Pago());

?>