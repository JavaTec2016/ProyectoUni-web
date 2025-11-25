//incluir FetchRequest en donde se vaya a usar esto
function actualizar(url, form, success = (res) => { }, reject = (reason) => { }) {
    const req = new FetchRequest(url, "GET");
    req.setBody(form);
    return req.callbackJSON(success, reject);
}

function agregar(url, form, success = (res) => { }, reject = (reason) => { }){
    const req = new FetchRequest(url, "POST");
    req.setBody(form);
    return req.callbackJSON(success, reject);
}

function consultar(url, form, success = (res) => { }, reject = (reason) => { }){
    const req = new FetchRequest(url, "GET");
    req.setBody(form);
    return req.callbackJSON(success, reject);
}
function eliminar(url, form, success = (res) => { }, reject = (reason) => { }){
    const req = new FetchRequest(url, "GET");
    req.setBody(form);
    return req.callbackJSON(success, reject);
}
function requestRules(tabla, success=(res)=>{}, reject=(reason)=>{}){
    const req = new FetchRequest("http://localhost:80/proyesto/backend/API/api_getRuleData.php?tabla="+tabla, "GET");
    req.callbackJSON(success, reject);
}