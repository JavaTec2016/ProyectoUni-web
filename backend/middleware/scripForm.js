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

    buildFieldGenerico(fieldId = "", inputId = fieldId + "_input", inputType = "", inputName = inputId, labelTxt = "", values={}) {
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

    buildField(fieldId = "", inputId = fieldId + "_input", inputType = "", inputName = inputId, labelTxt = "", attribs = {}, values={}) {
        let field = this.buildFieldGenerico(fieldId, inputId, inputType, inputName, labelTxt, values);
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
    fillForm(form, data) {
    const entries = (new URLSearchParams(data)).entries();
    for (const [key, val] of entries) {
        const input = form.elements[key+"_input"];
        if(!input || !input.type) continue;
        switch (input.type) {
            case 'checkbox': input.checked = !!val; break;
            default: input.value = val; break;
        }
    }
    }
    showInvalido(id, mensaje){
        let m = document.getElementById(id+"_invalid");
        m.innerHTML = mensaje;
        m.hidden = false;
        m.style.display = "flex";
    }
    hideInvalido(id){
        console.log(id);
        let m = document.getElementById(id + "_invalid");
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
}
const fb = new FormBuilder();