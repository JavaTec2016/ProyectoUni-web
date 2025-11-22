
/**
 * muestra un toast si existe
 * @param {string} id 
 */
function showToast(id) {
    console.log(id);
    
    if (document.getElementById(id)){
        
        new bootstrap.Toast(document.getElementById(id)).show();
    }else {
        alert("no hay toasts");
    }
}
/**
 * 
 * @param {string} id 
 * @param {string} text 
 */
function setText(id, text){
    let element = document.getElementById(id);
    if(!element) return;
    element.innerText = text;
}
function hide(id){
    let e = document.getElementById(id);
    if (e) e.hidden = true;
}

function fireToast(id, text, okButtonText, cancelButtonText, okCallback=null, cancelCallback=null){
    setText(id+"Body-text", text);
    
    if(okButtonText == null) hide(id+"BtnOK");
    else setText(id + "BtnOK", okButtonText);

    if(cancelButtonText == null) hide(id + "BtnCancel");
    else setText(id + "BtnCancel", cancelButtonText);

    document.getElementById(id+"BtnOK").onclick = okCallback;
    document.getElementById(id + "BtnCancel").onclick = cancelCallback;

    showToast(id);
}