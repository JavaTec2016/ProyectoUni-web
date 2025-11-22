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
    let dato = null;
    if(!noNulo) {
        if (vacio(datoString)) return validarCodigos.OK;
        else return validarCodigos.NULL_DATA;
    }try{
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
    params = [];
    callback = (codigo) =>{return}
    constructor(callback=null, ...params){
        if(callback) this.callback = callback;
        this.params = params;
    }
    run(dato){
        this.callback(validar(dato, ...this.params));
    }
}
class ValidadorRunner {
    /**
     * @type {Map<string, Validador>}
     */
    validadores = new Map();

    agregarValidador(campoId, tipo, noNulo, umbral, limite, callback=(codigo)=>{return}){
        this.validadores.set(campoId, new Validador(callback, tipo, noNulo, umbral, limite));
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