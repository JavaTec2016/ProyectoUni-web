class FetchRequest {

    url;
    method;
    body=null;
    /**
     * @type {Promise<Response>}
     */
    response = null;
    constructor(url=null, method=null){
        this.url = url;
        this.method = method;
    }
    /**
     * 
     * @param {HTMLFormElement | FormData | Map<String, any> | {}} data 
     */
    setBody(data){
        let finalUrl = this.url;
        if(data==null) return;
        if(data instanceof HTMLFormElement){
            this.body = new FormData(data);
        }else this.body = data;

        if(this.method == 'GET' && this.body != null){
            let urlParams = "?"+this.URLObject(this.body); 
            if(finalUrl == null) finalUrl = "";
            
            finalUrl += urlParams;
        }
        this.url = finalUrl;
    }
    send(){
        this.response = fetch(this.url, {
            method:this.method,
            body: this.method == 'GET' ? null : this.body
        })
        return this;
    }
    getJSON(){
        return this.response.then(res => res.json());
    }

    callbackJSON(success=(json)=>{}, reject=(reason)=>{}){
        this.send()
        .getJSON()
        .then(json => success(json))
        .catch(reason => reject(reason));
    }

    ///FORMATO

    /**
    * convierte un formulario a string URL
    * @param {HTMLFormElement} form 
    */
    URLForm(form) {
        return this.URLObject(new FormData(form));
    }
    /**
     * 
     * @param {FormData | Map | {}} obj 
     * @returns {string}
     */
    URLObject(obj) {
        if (obj == null) return "";
        return (new URLSearchParams(obj)).toString();
    }
}