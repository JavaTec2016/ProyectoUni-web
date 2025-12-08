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
     * @param {FormData} data 
     * @returns 
     */
    parseFormData(data){
        const out = {};
        data.forEach((k, v) => {
            out[v] = k;
            //console.log("parsea ", v, k);
            
        })
        return out;
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
            let urlParams = this.URLObject(this.body); 
            if(finalUrl == null) finalUrl = "";
            
            finalUrl += urlParams;
        }
        this.url = finalUrl;
        return this;
    }
    send(){
        console.log(JSON.stringify(this.body));
        
        this.response = fetch(this.url, {
            method:this.method,
            body: this.method == 'GET' ? null : this.body,
        })
        return this;
    }
    getJSON(log=false){
        return this.response.then(res => {
            if(log){
                let k = res.clone();
                k.text().then(txt=>console.log(txt)).catch(reas=>{console.log(reas)});
            }
            return res.json();
        }).catch(reas=>{console.log(reas)});
    }
    getText(){
        return this.response.then(res => res.text());
    }
    callbackJSON(success=(json)=>{}, reject=(reason)=>{}, log=false){
        return this.send()
        .getJSON(log)
        .then(json => success(json))
        .catch(reason => reject(reason));
    }
    log(){
        return this.send()
        .getText()
        .then(txt => console.log(txt))
        .catch(rs => console.error(rs));
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