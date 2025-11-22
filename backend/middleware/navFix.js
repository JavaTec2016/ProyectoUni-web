function setFiller(id){
    const nav = document.getElementById(id);
    document.getElementsByName('nav-filler').forEach(elem => {
        elem.style.height = nav.offsetHeight + "px";
        console.log(nav.offsetHeight);
    })
}
    