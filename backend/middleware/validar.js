const validarCodigos = {
    OK: 0,
    WRONG_TYPE: 1,
    OUT_OF_RANGE: 2,
    NULL_DATA: 3,
    WRONG_DATE: 4,
}
const validarTipos = {
    STRING: "s",
    DOUBLE: "d",
    INTEGER: "i",
    DATE:"date",
}
function convertir(dato="", tipo="s"){
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
function enRango(x, max=1, min=0){
    return x < max && x > min;
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
    
    if (!noNulo && vacio(datoString)) return validarCodigos.OK;
    if (noNulo && vacio(datoString)) return validarCodigos.NULL_DATA;
    console.log("SI: ", ...arguments);
    try{
        dato = convertir(datoString, tipo);
    }catch(e) { return validarCodigos.WRONG_TYPE; }
    if(umbral + limite > -1 && !enRango(dato, umbral, limite)) return validarCodigos.OUT_OF_RANGE;

    return validarCodigos.OK;
}
/**
 * 
 * @param {string | Date} menor 
 * @param {string | Date} mayor 
 */
function validarFechas(menor, mayor){
    if(!probarFechas(menor, mayor)) return validarCodigos.WRONG_DATE;
    return validarCodigos.OK;
}

class Validador {
    id="";
    params = [];
    callback = (codigo, id) =>{return}
    constructor(id, callback=null, ...params){
        if(callback) this.callback = callback;
        this.id = id;
        this.params = params;
    }
    run(dato){
        this.callback(validar(dato, ...this.params), this.id);
    }
}
class ValidadorRunner {
    /**
     * @type {Map<string, Validador>}
     */
    validadores = new Map();

    agregarValidador(campoId, tipo, noNulo, umbral, limite, callback=(codigo)=>{return}){
        this.validadores.set(campoId, new Validador(campoId, callback, tipo, noNulo, umbral, limite));
    }
    /**
     * 
     * @param {FormData} formData 
     */
    runValidadores(formData){
        this.validadores.forEach((v, field)=>{
            if(!formData.has(field)){
                console.warn("Saltando validacion de campo desconocido: ", field);
                return;
            }
            v.run(formData.get(field).toString())
        });
    }
}

//no hace chequeos posteriores ni soporta mensajes personalizados
function genericHandler(codigo, id){
    let errores = "";
    switch (codigo) {
        case 0: break;
        case validarCodigos.WRONG_TYPE:
            errores += "\n"+id+" no es del tipo correcto"; break;
        case validarCodigos.NULL_DATA:
            errores += "\n" + id + " no puede ser nulo"; break;
        case validarCodigos.OUT_OF_RANGE:
            errores += "\nel valor de " + id + " esta fuera del rango permitido"; break;
        case validarCodigos.WRONG_DATE:
            errores += "\n la fecha de " + id + " es incorrecta"; break;
    }
    return errores;
}