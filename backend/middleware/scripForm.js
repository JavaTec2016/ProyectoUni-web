class FormBuilder {
    constructor() { }
    /**
 * 
 * @param {string} id 
 * @param {HTMLFormElement} form 
 */
    setForm(id, form) {
        let body = document.getElementById(id + "-body");
        body.removeChild(document.getElementById(id + "-form"));

        if (!form.id) form.setAttribute("id", id + "-body");
        body.append(form);
    }

    makeField(id = null, qlass = "mb-3") {
        let field = document.createElement("div");
        if (qlass) field.setAttribute("class", qlass);
        if (id) field.setAttribute("id", id);
        return field;
    }
    decideInput(type){
        let input = null;
        if (type == "select" || type == "textarea") {
            input = document.createElement(type);
        }else {
            input = document.createElement("input");
            input.setAttribute("type", type);
        }
        return input;
    }
    makeInput(id, type, name, qlass = "form-control") {
        let input = this.decideInput(type);
        input.setAttribute("id", id);
        if (name) input.setAttribute("name", name);
        if (qlass) input.setAttribute("class", qlass);
        return input;
    }
    makeLabel(phor = null, txt = "", qlass = "form-label") {
        let label = document.createElement("label");
        if (phor) label.setAttribute("for", phor);
        if (qlass) label.setAttribute("class", qlass);
        if (txt) label.innerHTML = txt;
        return label;
    }
    makeInvalid(id){
        let inv = document.createElement('div');
        inv.setAttribute('id', id);
        inv.setAttribute('class', 'invalid-feedback');
        return inv;
    }
    /**
     * 
     * @param {HTMLSelectElement} input 
     * @param {any{}} optionValues 
     */
    fillSelect(input, optionValues={}){
        let optionNula = document.createElement("option");
        optionNula.value = "";
        optionNula.innerHTML = "Seleccionar...";
        //input.append(optionNula);
        for(const value in optionValues){
            let option = document.createElement("option");
            option.value = value;
            option.innerHTML = optionValues[value];
            input.appendChild(option);
        }
    }
    //============ si

    buildFieldGenerico(fieldId = "", fieldAfter ="#", inputId = fieldId + fieldAfter + "_input", inputType = "", inputName = inputId, labelTxt = "", values={}) {
        let div = this.makeField(fieldId);
        let label = this.makeLabel(inputId, labelTxt);
        let input = this.makeInput(inputId, inputType, inputName);
        let invalid = this.makeInvalid(inputId+"_invalid");
        if(input instanceof HTMLSelectElement){
            this.fillSelect(input, values);
        }
        div.append(label, input, invalid);
        return div;
    }

    buildField(fieldId = "", fieldAfter="#", inputId, inputType, inputName, labelTxt, attribs = {}, values={}) {
        let field = this.buildFieldGenerico(fieldId, fieldAfter, inputId, inputType, inputName, labelTxt, values);
        let input = field.children[1];
        for (const attribName in attribs) {
            input.setAttribute(attribName, attribs[attribName]);
        }
        return field;
    }
    /**
     * 
     * @param {HTMLFormElement} form 
     * @param {FormData} data 
     */
    fillForm(form, data, postKey="#") {
    const entries = (new URLSearchParams(data)).entries();
    for (const [key, val] of entries) {
        const input = form.elements[key+postKey+"_input"];
        if(!input || !input.type) continue;
        switch (input.type) {
            case 'checkbox': input.checked = !!val; break;
            default: input.value = val; break;
        }
    }
    }
    showInvalido(id, mensaje){
        let m = document.getElementById(id+"_invalid");
        let inp = document.getElementById(id);
        inp.setAttribute("class", "form-control is-invalid");
        m.innerHTML = mensaje;
        m.hidden = false;
        m.style.display = "flex";
    }
    hideInvalido(id){
        console.log(id);
        let m = document.getElementById(id + "_invalid");
        let inp = document.getElementById(id);
        inp.setAttribute("class", "form-control is-valid");
        m.hiden = true;
        m.style.display = "none";
    }
    /**
     * 
     * @param {HTMLFormElement} form 
     * @param {string} fieldId
     */
    getValue(form, fieldId){
        return new FormData(form).get(fieldId);
    }
    /**
     * mueve los datos de un formData a otro
     * @param {FormData} data 
     * @param {string[]} newKeys 
     */
    migrateFormData(data, newKeys=[]){
        let formData = new FormData();
        let i = 0;
        data.forEach((val, oldKey)=>{
            formData.set(newKeys[i], val.toString());
            i++
        })

        return formData;
    }
    /**
     * 
     * @param {string[]} fieldIds
     * @param {(field: HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement, ev: Event)=>{}} callback 
     */
    fieldsOnChange(fieldIds, fieldsAfter="#", callback=(field, ev)=>{return}){
        fieldIds.forEach(id=>{
            const field = document.getElementById(id+fieldsAfter);
            field.onchange = (ev) => {callback(field, ev)}
        })
    }
    static setFormFieldsCorporacion(form, idAfter="#modal", camposIds){
        form.innerHTML = "";
        form.append(
            fb.buildField(camposIds.nombre, idAfter, undefined, "text", undefined, "Nombre: "),
            fb.buildField(camposIds.direccion, idAfter, undefined, "text", undefined, "Direccion: "),
            fb.buildField(camposIds.telefono, idAfter, undefined, "tel", undefined, "Telefono: "),
            fb.buildField(camposIds.email, idAfter, undefined, "email", undefined, "Email: "),
        )
    }
    static setFormFieldsEvento(form, idAfter="#modal", camposIds, ...data){
        form.innerHTML = "";
        form.append(
            fb.buildField(camposIds.nombre, idAfter, undefined, "text", undefined, "Nombre: "),
            fb.buildField(camposIds.fechaInicio, idAfter, undefined, "date", undefined, "Fecha de inicio: "),
            fb.buildField(camposIds.fechaFin, idAfter, undefined, "date", undefined, "Fecha de fin: "),
            fb.buildField(camposIds.tipo, idAfter, undefined, "select", undefined, "Tipo: ", undefined,
                data[0]
            ),
            fb.buildField(camposIds.descripcion, "#modal", undefined, "text", undefined, "Descripcion: ")
        )
    }
    static setFormFieldsDonador(form, idAfter = "#modal", camposIds, ...data){
        form.innerHTML = "";
        form.append(
            fb.buildField(camposIds.nombre, idAfter, undefined, "text", undefined, "Nombre: "),
            fb.buildField(camposIds.direccion, idAfter, undefined, "text", undefined, "Direccion: "),
            fb.buildField(camposIds.telefono, idAfter, undefined, "tel", undefined, "Telefono: "),
            fb.buildField(camposIds.email, idAfter, undefined, "email", undefined, "Email: "),
            fb.buildField(camposIds.categoria, idAfter, undefined, "select", undefined, "Categoria: ", undefined, data[0]),
            fb.buildField(camposIds.anioGraduacion, idAfter, undefined, "number", undefined, "Año de graduación: "),
            fb.buildField(camposIds.idClase, idAfter, undefined, "select", undefined, "Clase a la que pertenece: ", undefined, data[1]),
            fb.buildField(camposIds.idCorporacion, idAfter, undefined, "select", undefined, "Corporación: ", undefined, data[2]),
            fb.buildField(camposIds.nombreConyuge, idAfter, undefined, "text", undefined, "Nombre del cónyuge: "),
            fb.buildField(camposIds.idCorporacionConyuge, idAfter, undefined, "select", undefined, "Corporación del cónyuge: ", undefined, data[2]),
        )
    }
    static setFormFieldsClase(form, idAfter = "#modal", camposIds, ...data) {
        form.innerHTML = "";
        form.append(
            fb.buildField(camposIds.anioGraduacion, idAfter, undefined, "text", undefined, "Año de graduación: "),
        )
    };
    static setFormFieldsUsuario(form, idAfter = "#modal", camposIds, ...data) {
        form.innerHTML = "";
        form.append(
            fb.buildField(camposIds.nombre, idAfter, undefined, "text", undefined, "Nombre: "),
            fb.buildField(camposIds.pass, idAfter, undefined, "text", undefined, "Contraseña: "),
            fb.buildField(camposIds.rol, idAfter, undefined, "select", undefined, "Rol: ", undefined, data[0]),
        )
    }
}
const fb = new FormBuilder();