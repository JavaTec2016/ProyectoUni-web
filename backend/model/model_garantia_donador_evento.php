
<?php
require_once('allModels.php');
class Garantia_donador_evento extends Modelo {
    public static $rules = [];

    public function __construct(mixed ...$args) {
        $this->fillValuesFrom(static::$rules, $args);
    }
    public static function setRules()
    {
        static::combineAs(array(
            "garantia"=>array(
                Garantia::ID => "id_garantia",
                Garantia::ID_DONADOR => null,
                Garantia::ID_EVENTO => null,
                Garantia::GARANTIA => null,
                Garantia::PAGO_TOTAL => null,
                Garantia::METODO_PAGO => null,
                Garantia::NUMERO_PAGOS => null,
                Garantia::NUMERO_TARJETA => null,
                Garantia::FECHA_INICIO => null,
                Garantia::FECHA_GARANTIA => null,
                Garantia::ID_CIRCULO => null,
                Garantia::ESTADO => null,
            ),
            "donador"=>array(
                Donador::NOMBRE => "nombre_donador",
                Donador::TELEFONO => null,
                Donador::EMAIL => null,
            ),
            "evento"=>array(
                Evento::NOMBRE => "nombre_evento"
            )
        ));
    }
}

Garantia_donador_evento::setRules();
Models::set("garantia_donador_evento", new Garantia_donador_evento());
?>