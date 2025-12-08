//incluir FetchRequest en donde se vaya a usar esto
const APIUrl = "http://localhost:80/backend/API/";
function trimAPIUrl(url){
    let urlStart = url.indexOf("api");
    let url2 = url.substring(urlStart);
    return url2;
}
function actualizar(url, form, success = (res) => { }, reject = (reason) => { }) {
    const req = new FetchRequest(APIUrl +url, "GET");
    req.setBody(form);
    return req.callbackJSON(success, reject, true);
}

function agregar(url, form, success = (res) => { }, reject = (reason) => { }){
    const req = new FetchRequest(APIUrl +url, "POST");
    req.setBody(form);
    return req.callbackJSON(success, reject, true);
}

function consultar(url, form, success = (res) => { }, reject = (reason) => { }){
    const req = new FetchRequest(APIUrl +url, "GET");
    req.setBody(form);
    return req.callbackJSON(success, reject, true);
}
function eliminar(url, form, success = (res) => { }, reject = (reason) => { }){
    const req = new FetchRequest(APIUrl +url, "GET");
    req.setBody(form);
    return req.callbackJSON(success, reject, true);
}
function requestRules(tabla, success=(res)=>{}, reject=(reason)=>{}){
    const req = new FetchRequest(APIUrl +"api_getRuleData.php?tabla="+tabla, "GET");
    return req.callbackJSON(success, reject, true);
}