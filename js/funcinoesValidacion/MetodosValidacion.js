const validarCodigos = {
    OK: 0,
    WRONG_TYPE: 1,
    DATA_TOO_SMALL: 2,
    DATA_TOO_BIG: 3,
    NULL_DATA: 4,
    WRONG_DATE: 5,
    REGEX_FAIL: 6,
    DATE_NEGATIVE: 7,
}
const validarTipos = {
    STRING: "string",
    DOUBLE: "double",
    INTEGER: "int",
    DATE: "date",
}
class MetodosValidacion {

    ///METODOS GENERALES

    /**
 * 
 * @param {string} id 
 * @param {number} codigo 
 * @param {ValidadorRunner} validador 
 */
    static chekGeneral(id, codigo, validador) {
        console.log(validador.errorMensajes.get(id));
        if (codigo == validarCodigos.OK) {
            fb.hideInvalido(id);
            return true;
        }
        if (codigo == validarCodigos.NULL_DATA) {
            fb.showInvalido(id, "Campo requerido");
            return false;
        }

        fb.showInvalido(id, validador.getMensajeEspecial(id, codigo));
        return false;
    }
    /**
     * 
     * @param {ValidadorRunner} validador 
     * @param {HTMLFormElement} form 
     */
    static validarForm(validador, form) {
        if (Object.keys(validacionRules).length == 0 || !validador.runValidadores(new FormData(form))) return false;
        return true;
    }
    ///EVENTO

    /**
 * 
 * @param {ValidadorRunner} validador 
 * @param {any{}} camposIds 
 */
    static makeMensajesEvento(validador, postKey = "#", inputPostfix = "_input", camposIds = {}) {
        validador.setMensajesEspecialesSerial(camposIds['nombre'] + postKey + inputPostfix, [
            validarCodigos.DATA_TOO_SMALL, "como",
            validarCodigos.DATA_TOO_BIG, "No puede exceder 100 caracteres",
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.REGEX_FAIL, "Solo se permiten letras, numeros y espacios",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['fechaInicio'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.REGEX_FAIL, "Formato de fecha invalido",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['fechaFin'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.REGEX_FAIL, "Formato de fecha invalido",
            validarCodigos.DATE_NEGATIVE, "Debe ser mayor a la fecha de inicio",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['tipo'] + postKey + inputPostfix, [
            validarCodigos.WRONG_TYPE, "Seleccione una opcion valida 1",
            validarCodigos.DATA_TOO_SMALL, "Seleccione una opcion valida 2",
            validarCodigos.DATA_TOO_BIG, "Seleccione una opcion 3",
            validarCodigos.NULL_DATA, "Seleccione una opcion 4",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['descripcion'] + postKey + inputPostfix, [
            1, "",
            2, "",
            3, "",
            4, "",
            5, "",
            6, "",
        ]);
    }
    /**
 * 
 * @param {ValidadorRunner} validador 
 * @param {HTMLFormElement} form 
 * @param {any{}} camposIds 
 * @param {any{}} validacionRules 
 
 */
    static makeValidadoresEvento(validador, form, camposIds, validacionRules, postKey = "#") {
        let key, fieldId;
        key = camposIds['nombre'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['fechaInicio'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['fechaFin'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            if (codigo == 0) {
                //si la fecha fin existe, revisa que sea mayor a la de inicio esta horrible
                if (convertirSafe(dato, tipo) instanceof Date && fechaValida(dato))
                    codigo = validarFechas(fb.getValue(form, camposIds['fechaInicio'] + postKey + "_input"), dato);
            }
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['tipo'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['descripcion'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
    }

    ///CORPORACION

    /**
     * crea mensajes de error para corporacion
     * @param {ValidadorRunner} validador 
     * @param {any{}} camposIds 
     */
    static makeMensajesCorporacion(validador, postKey = "#", inputPostfix = "_input", camposIds = {}) {
        validador.setMensajesEspecialesSerial(camposIds['nombre'] + postKey + inputPostfix, [
            validarCodigos.DATA_TOO_BIG, "No puede exceder 100 caracteres",
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.REGEX_FAIL, "Solo se permiten letras, numeros y espacios",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['direccion'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.DATA_TOO_BIG, "No debe exceder 200 caracteres",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['telefono'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.DATA_TOO_SMALL, "",
            validarCodigos.DATA_TOO_BIG, "No debe exceder 10 caracteres",
            validarCodigos.REGEX_FAIL, "Debe ser un formato de número telefónico válido",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['email'] + postKey + inputPostfix, [
            validarCodigos.WRONG_TYPE, "como",
            validarCodigos.DATA_TOO_SMALL, "",
            validarCodigos.DATA_TOO_BIG, "No debe exceder 50 caracteres",
            validarCodigos.NULL_DATA, "",
            validarCodigos.REGEX_FAIL, "Debe ser un formato de correo válido",
        ]);
    }
    /**
     * crea validadores para corporacion
     * @param {ValidadorRunner} validador 
     * @param {HTMLFormElement} form 
     * @param {any{}} camposIds 
     * @param {any{}} validacionRules 
     */
    static makeValidadoresCorporacion(validador, form, camposIds, validacionRules, postKey = "#") {
        let key, fieldId;
        key = camposIds['nombre'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['direccion'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['telefono'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['email'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
    }

    ///GARANTIA

    /**
     * crea mensajes de error para garantia
     * @param {ValidadorRunner} validador 
     * @param {any{}} camposIds 
     */
    static makeMensajesGarantia(validador, postKey = "#", inputPostfix = "_input", camposIds = {}) {
        validador.setMensajesEspecialesSerial(camposIds['idDonador'] + postKey + inputPostfix, [
            validarCodigos.DATA_TOO_BIG, "",
            validarCodigos.NULL_DATA, "",
            validarCodigos.REGEX_FAIL, "",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['idEvento'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "",
            validarCodigos.DATA_TOO_BIG, "",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['garantia'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.DATA_TOO_SMALL, "",
            validarCodigos.DATA_TOO_BIG, "No debe exceder 12 numeros (incluyendo decimales)",
            validarCodigos.REGEX_FAIL, "Debe ser un numero decimal positivo",
        ]);
        //pago total skipeado
        validador.setMensajesEspecialesSerial(camposIds['metodoPago'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.DATA_TOO_SMALL, "",
            validarCodigos.DATA_TOO_BIG, "No debe exceder 50 caracteres",
            validarCodigos.REGEX_FAIL, "Sólo se admiten letras y espacios",
        ]);
        //numero pagos skipeado
        validador.setMensajesEspecialesSerial(camposIds['fechaInicio'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.REGEX_FAIL, "Formato de fecha invalido",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['fechaGarantia'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.REGEX_FAIL, "Formato de fecha invalido",
            validarCodigos.DATE_NEGATIVE, "No debe ser menor a la fecha de inicio"
        ]);
        validador.setMensajesEspecialesSerial(camposIds['idCirculo'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "",
            validarCodigos.DATA_TOO_BIG, "",
        ]);
    }
    /**
     * crea validadores para garantia
     * @param {ValidadorRunner} validador 
     * @param {HTMLFormElement} form 
     * @param {any{}} camposIds 
     * @param {any{}} validacionRules 
     */
    static makeValidadoresGarantia(validador, form, camposIds, validacionRules, postKey = "#") {
        let key, fieldId;
        key = camposIds['idDonador'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['idEvento'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['garantia'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['metodoPago'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['fechaInicio'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['fechaGarantia'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            if (codigo == 0) {
                //otra vez
                if (convertirSafe(dato, tipo) instanceof Date && fechaValida(dato))
                    codigo = validarFechas(fb.getValue(form, camposIds['fechaInicio'] + postKey + "_input"), dato);
            }
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['idCirculo'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
    }

    ///DONADOR

    static makeMensajesDonador(validador, postKey = "#", inputPostfix = "_input", camposIds = {}) {
        validador.setMensajesEspecialesSerial(camposIds['nombre'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "",
            validarCodigos.DATA_TOO_BIG, "No puede exceder 100 caracteres",
            validarCodigos.REGEX_FAIL, "No se permiten caracteres especiales",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['direccion'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.DATA_TOO_SMALL, "",
            validarCodigos.DATA_TOO_BIG, "No debe exceder 200 caracteres",
            validarCodigos.REGEX_FAIL, "",
        ]);
        //pago total skipeado
        validador.setMensajesEspecialesSerial(camposIds['telefono'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.DATA_TOO_SMALL, "Debe contener 10 caracteres",
            validarCodigos.DATA_TOO_BIG, "No debe exceder 10 caracteres",
            validarCodigos.REGEX_FAIL, "Formato de numero telefónico inválido",
        ]);
        //numero pagos skipeado
        validador.setMensajesEspecialesSerial(camposIds['email'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.DATA_TOO_BIG, "No debe exceder 100 caracteres",
            validarCodigos.REGEX_FAIL, "Formato de correo invalido",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['categoria'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "No puede ser nulo",
            validarCodigos.REGEX_FAIL, "Categoria inválida",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['anioGraduacion'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "",
            validarCodigos.WRONG_TYPE, "Debe ser un número entero positivo",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['idClase'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "",
            validarCodigos.WRONG_TYPE, "ID inválido",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['idCorporacion'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "",
            validarCodigos.WRONG_TYPE, "ID inválido",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['nombreConyuge'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "",
            validarCodigos.DATA_TOO_BIG, "No puede exceder 100 caracteres",
            validarCodigos.REGEX_FAIL, "No se permiten caracteres especiales",
        ]);
        validador.setMensajesEspecialesSerial(camposIds['idCorporacionConyuge'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "",
            validarCodigos.WRONG_TYPE, "ID inválido",
        ]);
    }
    /**
     * crea validadores para donador
     * @param {ValidadorRunner} validador 
     * @param {HTMLFormElement} form 
     * @param {any{}} camposIds 
     * @param {any{}} validacionRules 
     */
    static makeValidadoresDonador(validador, form, camposIds, validacionRules, postKey = "#") {
        let key, fieldId;
        key = camposIds['nombre'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['direccion'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['telefono'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['email'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['categoria'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['anioGraduacion'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            console.log(dato, tipo, regex)
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['idClase'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['idCorporacion'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['nombreConyuge'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
        key = camposIds['idCorporacionConyuge'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
            return this.chekGeneral(id, codigo, validador);
        })
    }

    ///CLASE

    /**
     * crea mensajes de error para corporacion
     * @param {ValidadorRunner} validador 
     * @param {any{}} camposIds 
     */
    static makeMensajesClase(validador, postKey = "#", inputPostfix = "_input", camposIds = {}) {
        validador.setMensajesEspecialesSerial(camposIds['anioGraduacion'] + postKey + inputPostfix, [
            validarCodigos.NULL_DATA, "",
            validarCodigos.WRONG_TYPE, "Debe ser un número entero positivo",
            validarCodigos.REGEX_FAIL, "Debe ser un número entero positivo"
        ]);
    }
    /**
     * crea validadores para corporacion
     * @param {ValidadorRunner} validador 
     * @param {HTMLFormElement} form 
     * @param {any{}} camposIds 
     * @param {any{}} validacionRules 
     */
    static makeValidadoresClase(validador, form, camposIds, validacionRules, postKey = "#") {
        let key, fieldId;
        key = camposIds['anioGraduacion'], fieldId = key + postKey + "_input";
        validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => {
            return this.chekGeneral(id, codigo, validador);
        })
    }
}

/**
 * Contiene mensajes de error especiales para un campo
 */
class ErrorEspecial {
    /**
     * @type {Map<number, string>}
     */
    mensajes = new Map();

    constructor(erroresObjeto = {}) {
        this.addMensajes(erroresObjeto);
    }
    addMensaje(codigo, msj) {
        this.mensajes.set(codigo, msj);
    }
    addMensajes(obj = {}) {
        for (const codigo in obj) {
            this.mensajes.set(parseInt(codigo), obj[codigo]);
        }
    }
    getMensaje(codigo) {
        return this.mensajes.get(codigo);
    }
}
/**
 * valida un dato con reglas preestablecidas y hace algo con el codigo
 */
class Validador {
    id = "";
    params = [];
    callback = (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => { return true }
    constructor(id, callback = null, ...params) {
        if (callback) this.callback = callback;
        this.id = id;
        this.params = params;
    }
    run(dato) {
        return this.callback(validar(dato, ...this.params), this.id, dato, ...this.params);
    }
}
/**
 * guarda validadores que usa para validar un formulario
 */
class ValidadorRunner {
    /**
     * @type {Map<string, Validador>}
     */
    validadores = new Map();
    /**
     * @type {Map<string, ErrorEspecial>}
     */
    errorMensajes = new Map();

    agregarValidador(campoId, tipo, noNulo, umbral, limite, regex, callback = (codigo, id, dato, tipo, noNulo, umbral, limite, regex) => { return true }) {
        this.validadores.set(campoId, new Validador(campoId, callback, tipo, noNulo, umbral, limite, regex));
    }
    setMensajesEspeciales(campoId, mensajes = {}) {
        this.errorMensajes.set(campoId, new ErrorEspecial(mensajes));
    }
    setMensajesEspecialesSerial(campoId, codigoMensajeArray = []) {
        let mensajes = {};
        for (let i = 0; i < codigoMensajeArray.length; i += 2) {
            const codigo = codigoMensajeArray[i];
            const mensaje = codigoMensajeArray[i + 1];
            mensajes[codigo] = mensaje;
        }
        this.setMensajesEspeciales(campoId, mensajes);
    }
    addMensajesEspeciales(campoId, mensajes = {}) {
        this.errorMensajes.get(campoId).addMensajes(mensajes);
    }
    getMensajeEspecial(campoId, codigo) {
        return this.errorMensajes.get(campoId).getMensaje(codigo);
    }
    /**
     * 
     * @param {FormData} formData 
     */
    runValidadores(formData) {
        let passed = true;
        this.validadores.forEach((v, field) => {
            if (!formData.has(field)) {
                console.warn("Saltando validacion de campo desconocido: ", field);
                return;
            }
            if (!v.run(formData.get(field).toString())) passed = false;
        });
        return passed;
    }
}

/**
 * convierte un tipo de dato o tira error si no puede
 * @param {string} dato 
 * @param {string} tipo 
 */
function convertir(dato = "", tipo = "string") {
    switch (tipo) {
        case validarTipos.STRING: return dato;
        case validarTipos.DOUBLE:
            try { return parseFloat(dato); }
            catch (e) { throw e; }
        case validarTipos.INTEGER:
            try { return parseInt(dato); }
            catch (e) { throw e; }
        case validarTipos.DATE:
            return new Date(dato);
        default: throw new Error("Tipo de dato desconocido");
    }
}
/**
 * intenta convertir un tipo de dato o no si no puede
 * @param {string} dato 
 * @param {string} tipo 
 */
function convertirSafe(dato = "", tipo = "string") {
    let out = dato;
    switch (tipo) {
        case validarTipos.DOUBLE:
            try { out = parseFloat(dato); }
            catch (e) { out = null; console.warn("Fallo al convertir '", dato, "' a flotante") }
            break;
        case validarTipos.INTEGER:
            try { out = parseInt(dato); }
            catch (e) { out = null; console.warn("Fallo al convertir '", dato, "' a entero") }
            break;
        case validarTipos.DATE:
            out = new Date(dato);
            break;
        default: break;
    }
    return out;
}
/**
 * revisa si un numero esta en el rango establecido
 * @param {number} x 
 * @param {number} max 
 * @param {number} min 
 */
function enRango(x = 0, max = x + 1, min = x - 1) {
    return x <= max && x >= min;
}
/**
 * revisa si el dato esta dentro del rango, si es string usa la longitud, si es Date usa el tiempo en ms
 * @param {string | number | Date} x 
 * @param {number} max 
 * @param {number} min 
 */
function enRangoMulti(x, max, min) {
    if (typeof x == 'string') {
        const l = x.length;
        return enRango(l, max, min);
    }
    if (typeof x == 'number') return enRango(x, max, min);
    if (x instanceof Date) return enRango(x.getTime(), max, min);
}
/**
 * prueba que una fecha sea mayor a otra
 * @param {string | Date} dateMenor 
 * @param {string | Date} dateMayor 
 */
function probarFechas(dateMenor, dateMayor) {
    return new Date(dateMenor).getTime() < new Date(dateMayor).getTime();
}
/**
 * revisa si un dato esta vacio
 * @param {string} dato 
 */
function vacio(dato = "") {
    return dato == null | dato.length == 0;
}
function probarRegex(dato="", regex=""){
    if(regex=="EMAIL" || regex=="DATE") return true;
    let pattern = new RegExp("^"+regex+"$", "g");
    console.log("regex gloval: ", "^" + regex + "$", " para ", dato);
    return pattern.test(dato);
}
/**
 * secuencia de validacion, retorna un codigo de error si sale mal o 0 si sale bien
 * @param {string} datoString 
 * @param {string} tipo 
 * @param {boolean} noNulo 
 * @param {number} umbral 
 * @param {number} limite 
 */
function validar(datoString, tipo, noNulo, umbral, limite, regex="") {
    let dato = null;
    if (!noNulo && vacio(datoString)) return validarCodigos.OK;
    if (noNulo && vacio(datoString)) return validarCodigos.NULL_DATA;
    //console.log("SI: ", ...arguments);
    try {
        dato = convertir(datoString, tipo);
        if (dato instanceof Date && isNaN(dato.getDate())) return validarCodigos.WRONG_DATE;
    } catch (e) { return validarCodigos.WRONG_TYPE; }
    if (umbral > -1 && !enRangoMulti(dato, undefined, umbral)) { return validarCodigos.DATA_TOO_SMALL; }
    if (limite > -1 && !enRangoMulti(dato, limite)) { return validarCodigos.DATA_TOO_BIG; }
    if(regex != null && regex.length > 0){
        if(!probarRegex(datoString, regex)) return validarCodigos.REGEX_FAIL;
    }
    return validarCodigos.OK;
}
/**
 * revisa fechas y retorna un codigo de error si son negativas
 * @param {string | Date} menor 
 * @param {string | Date} mayor 
 */
function validarFechas(menor, mayor) {
    if (!probarFechas(menor, mayor)) return validarCodigos.DATE_NEGATIVE;
    return validarCodigos.OK;
}
/**
 * revisa que el dato sea una fecha valida
 * @param {string} dato 
 */
function fechaValida(dato) {
    return !isNaN(new Date(dato).getTime());
}