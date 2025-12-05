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
    INTEGER: "integer",
    DATE:"date",
}
function convertir(dato="", tipo="string"){
    switch(tipo){
        case validarTipos.STRING: return dato;
        case validarTipos.DOUBLE: 
            try{ return parseFloat(dato); }
            catch(e){ throw e; }
        case validarTipos.INTEGER:
            try{ return parseInt(dato); }
            catch(e){ throw e; }
        case validarTipos.DATE:
            return new Date(dato);
        default: throw new Error("Tipo de dato desconocido");
    }
}
/**
 * intenta convertir un tipo de dato, si falla lo regresa sin cambios
 * @param {string} dato 
 * @param {string} tipo 
 */
function convertirSafe(dato="", tipo="string"){
    let out = dato;
    switch(tipo){
        case validarTipos.DOUBLE:
            try{ out = parseFloat(dato); }
            catch(e){out = null; console.warn("Fallo al convertir '", dato, "' a flotante")}
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
function enRango(x=0, max=x+1, min=x-1){
    return x <= max && x >= min;
}
/**
 * revisa si el dato esta dentro del rango, si es string usa la longitud, si es Date usa el tiempo en ms
 * @param {string | number | Date} x 
 * @param {number} max 
 * @param {number} min 
 */
function enRangoMulti(x, max, min){
    if(typeof x == 'string'){
        const l = x.length;
        return enRango(l, max, min);
    }
    if(typeof x == 'number') return enRango(x, max, min);
    if(x instanceof Date) return enRango(x.getTime(), max, min);
}
/**
 * 
 * @param {string | Date} dateMenor 
 * @param {string | Date} dateMayor 
 */
function probarFechas(dateMenor, dateMayor){
    return new Date(dateMenor).getTime() < new Date(dateMayor).getTime();
}
function vacio(dato=""){
    return dato == null | dato.length == 0;
}

/**
 * 
 * @param {string} datoString 
 * @param {string} tipo 
 * @param {boolean} noNulo 
 * @param {number} umbral 
 * @param {number} limite 
 * @returns 
 */
function validar(datoString, tipo, noNulo, umbral, limite){
    let dato = null;
    if (!noNulo && vacio(datoString)) return validarCodigos.OK;
    if (noNulo && vacio(datoString)) return validarCodigos.NULL_DATA;
    console.log("SI: ", ...arguments);
    try{
        dato = convertir(datoString, tipo);
        if(dato instanceof Date && isNaN(dato.getDate())) return validarCodigos.WRONG_DATE;
    }catch(e) { return validarCodigos.WRONG_TYPE; }
    if (umbral > -1 && !enRangoMulti(dato, undefined, umbral)){ return validarCodigos.DATA_TOO_SMALL; }
    if(limite > -1 && !enRangoMulti(dato, limite)){ return validarCodigos.DATA_TOO_BIG; }

    return validarCodigos.OK;
}
/**
 * 
 * @param {string | Date} menor 
 * @param {string | Date} mayor 
 */
function validarFechas(menor, mayor){
    if(!probarFechas(menor, mayor)) return validarCodigos.DATE_NEGATIVE;
    return validarCodigos.OK;
}
function fechaValida(dato){
    return !isNaN( new Date(dato).getTime());
}
/**
 * Contiene mensajes de error especiales para un campo
 */
class ErrorEspecial {
    /**
     * @type {Map<number, string>}
     */
    mensajes = new Map();

    constructor(erroresObjeto={}){
        this.addMensajes(erroresObjeto);
    }
    addMensaje(codigo, msj){
        this.mensajes.set(codigo, msj);
    }
    addMensajes(obj={}){
        for (const codigo in obj) {
            this.mensajes.set(parseInt(codigo), obj[codigo]);
        }
    }
    getMensaje(codigo){
        return this.mensajes.get(codigo);
    }
}

class Validador {
    id="";
    params = [];
    callback = (codigo, id, dato, tipo, noNulo, umbral, limite) =>{return true}
    constructor(id, callback=null, ...params){
        if(callback) this.callback = callback;
        this.id = id;
        this.params = params;
    }
    run(dato){
        return this.callback(validar(dato, ...this.params), this.id, dato, ...this.params);
    }
}
class ValidadorRunner {
    /**
     * @type {Map<string, Validador>}
     */
    validadores = new Map();
    /**
     * @type {Map<string, ErrorEspecial>}
     */
    errorMensajes = new Map();

    agregarValidador(campoId, tipo, noNulo, umbral, limite, callback=(codigo, id, dato, tipo, noNulo, umbral, limite)=>{return true}){
        this.validadores.set(campoId, new Validador(campoId, callback, tipo, noNulo, umbral, limite));
    }
    setMensajesEspeciales(campoId, mensajes={}){
        this.errorMensajes.set(campoId, new ErrorEspecial(mensajes));
    }
    setMensajesEspecialesSerial(campoId, codigoMensajeArray=[]){
        let mensajes = {};
        for(let i = 0; i < codigoMensajeArray.length; i+=2){
            const codigo = codigoMensajeArray[i];
            const mensaje = codigoMensajeArray[i+1];
            mensajes[codigo] = mensaje;
        }
        this.setMensajesEspeciales(campoId, mensajes);
    }
    addMensajesEspeciales(campoId, mensajes={}){
        this.errorMensajes.get(campoId).addMensajes(mensajes);
    }
    getMensajeEspecial(campoId, codigo){
        return this.errorMensajes.get(campoId).getMensaje(codigo);
    }
    /**
     * 
     * @param {FormData} formData 
     */
    runValidadores(formData){
        let passed = true;
        this.validadores.forEach((v, field)=>{
            if(!formData.has(field)){
                console.warn("Saltando validacion de campo desconocido: ", field);
                return;
            }
            if(!v.run(formData.get(field).toString())) passed = false;
        });
        return passed;
    }
}