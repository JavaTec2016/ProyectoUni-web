/**
 * 
 * @param {ValidadorRunner} validador 
 * @param {any{}} camposIds 
 */
function makeMensajesEvento(validador, postKey="#", inputPostfix="_input", camposIds = {}) {
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
 * @param {string} id 
 * @param {number} codigo 
 * @param {ValidadorRunner} validador 
 */
function chekGeneral(id, codigo, validador){
    console.log(validador.errorMensajes.get(id));
    if (codigo == validarCodigos.OK) {
        fb.hideInvalido(id);
        return true;
    }
    if(codigo == validarCodigos.NULL_DATA){
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
 * @param {any{}} camposIds 
 * @param {any{}} validacionRules 
 
 */
function makeValidadoresEvento(validador, form, camposIds, validacionRules, postKey="#"){
    let key, fieldId;
    key = camposIds['nombre'], fieldId = key + postKey + "_input";
    validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
        return chekGeneral(id, codigo, validador);
    })
    key = camposIds['fechaInicio'], fieldId = key + postKey + "_input";
    validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
        return chekGeneral(id, codigo, validador);
    })
    key = camposIds['fechaFin'], fieldId = key + postKey + "_input";
    validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
        if(codigo == 0){
            //si la fecha fin existe, revisa que sea mayor a la de inicio esta horrible
            if(convertirSafe(dato, tipo) instanceof Date && fechaValida(dato))
                codigo = validarFechas(fb.getValue(form, camposIds['fechaInicio'] + postKey + "_input"), dato);
        }
        return chekGeneral(id, codigo, validador);
    })
    key = camposIds['tipo'], fieldId = key + postKey + "_input";
    validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
        return chekGeneral(id, codigo, validador);
    })
    key = camposIds['descripcion'], fieldId = key + postKey + "_input";
    validador.agregarValidador(fieldId, ...validacionRules[key], (codigo, id, dato, tipo, noNulo, umbral, limite) => {
        return chekGeneral(id, codigo, validador);
    })
}