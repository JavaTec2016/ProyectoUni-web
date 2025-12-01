<?php require_once('../auth.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include_once('../addBootstrap.php') ?>
    <?php include_once('../styles.php') ?>
</head>

<body>
    <?php
    require_once('nav_abcc.php');
    require_once('abcc_manager.php');
    require_once('../../model/model_evento.php');
    require_once('../toast.php');
    include_once('buildTablaModal.php');
    include_once('buildFormModal.php');
    require_once('form_creador.php');


    echo buildTablaModal("tablaDetalles", "Detalles del evento");
    echo buildFormModal("formCambiar", "Datos del evento", "PUT");

    $campos = [Evento::NOMBRE, Evento::TIPO, Evento::FECHA_INICIO];
    ?>
    <div style="display:flex; height:100%">

        <!-- BARRA LATERAL -->

        <div class="side-nav" style="z-index:1019;">
            <!-- NAV FORMULARIO -->

            <nav class="bg-light abcc scroller" style="overflow-x:hidden; height: 90vh;">
                <div clas="col">
                    <div class="row" id="form-header">
                        <?php echo FormCreador::makeFormEvento("form"); ?>
                    </div>
                </div>
            </nav>
        </div>

        <!-- CONTENEDOR DE TABLA Y ESAS COSAS -->

        <div class="container scroller" style="position: relative;">
            <?php echo tostar("toast", "toastBody", "toastBtnOK", "toastBtnCancel"); ?>
            <div class="row">
                <div class="col-sm-12">

                    <!-- la tabla en cuestion -->

                    <table class="table table-hover" id="tablaResults">
                        <thead id="tablaResults-header">
                            <tr>
                                <?php echo buildTableHeader(...$campos); ?>
                                <th scope="col">Acciones
                            </tr>
                        </thead>
                        <tbody id="tablaResults-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
</body>

<script src="../../middleware/scripTabla.js"></script>
<script src="../../middleware/request.js"></script>
<script src="../../middleware/showToast.js"></script>
<script src="../../middleware/scripForm.js"></script>
<script src="../../middleware/ABCCUtils.js"></script>
<script src="./funcinoesValidacion/MetodosValidacion.js"></script>

<script type="text/javascript">
    ////DEFINICION DE DATOS

    console.log("A");
    const tablaSQL = "evento";
    let validacionRules = {};
    const postSuccess = "Evento agregado";
    const postFail = "No se pudo agregar el evento";
    const putSuccess = "Evento actualizado";
    const putFail = "No se pudo modificar el evento";
    const deleteSuccess = "Evento eliminado";
    const deleteFail = "No se pudo eliminar el evento";

    const consultaURL = "../../API/api_mysql_consultas.php?tabla=" + tablaSQL + "&";
    const cambiosURL = "../../API/api_mysql_cambios.php?tabla=" + tablaSQL + "&";
    const bajasURL = "../../API/api_mysql_bajas.php?tabla=" + tablaSQL + "&";

    const validadorAgregar = new ValidadorRunner();
    const validadorModificar = new ValidadorRunner();

    const form = document.getElementById("form");
    const formModal = document.getElementById("formCambiar-form");
    const formBody = document.getElementById("form-body");
    const tabla = document.getElementById("tablaResults");
    const tablaBody = document.getElementById("tablaResults-body");
    tablaBody.childNodes.forEach(n => tablaBody.removeChild(n));

    ///PHP DEFINICIONES

    const datos = [
        <?php echoArray($campos) ?>
    ];
    const camposIds = {
        id: "<?php echo Evento::ID ?>",
        nombre: "<?php echo Evento::NOMBRE ?>",
        fechaInicio: "<?php echo Evento::FECHA_INICIO ?>",
        fechaFin: "<?php echo Evento::FECHA_FIN ?>",
        tipo: "<?php echo Evento::TIPO ?>",
        descripcion: "<?php echo Evento::DESCRIPCION ?>"
    }
    const eventosTipos = <?php echoAssoc(FormCreador::$evento_tipos) ?>;
    ///reglas auto

    requestRules(tablaSQL,
        (json) => {
            validacionRules = json.rules;
            setupRules();
        },
        (reason) => {
            console.error("Reglas fail: \n", reason)
        }
    )
    form.onsubmit = (ev) => {
        ev.preventDefault();
        if (!MetodosValidacion.validarForm(validadorAgregar, form)) return;
        agregarRegistro(form);
    }

    ///SETUP DE VALIDACION
    function validarForm(validador, form) {
        console.log(form);
        if (Object.keys(validacionRules).length == 0 || !validador.runValidadores(new FormData(form))) return false;
        return true;
    }

    function setupRules() {
        MetodosValidacion.makeMensajesEvento(validadorAgregar, "#", "_input", camposIds);
        MetodosValidacion.makeMensajesEvento(validadorModificar, "#modal", "_input", camposIds);

        MetodosValidacion.makeValidadoresEvento(validadorAgregar, form, camposIds, validacionRules, "#");
        MetodosValidacion.makeValidadoresEvento(validadorModificar, formModal, camposIds, validacionRules, "#modal");
    }

    //////=================== todo lo de la movedera ABCC con el backend (no moverle)

    ///CONSULTAS

    /**@param {HTMLTableElement} tablaModal*/
    function setTablaModal(tablaModal, url = "../../API/api_mysql_consultas.php") {
        const req = new FetchRequest(url, "GET");
        crearBody(tablaModal);
        setBodyHTML(tablaModal, "Buscando...");
        req.callbackJSON(
            (result) => {
                const modelo = result.resultSet[0];
                setBodyHTML(tablaModal, "");
                const headers = {};
                headers[camposIds.id] = "ID: ";
                headers[camposIds.nombre] = "Nombre: ";
                headers[camposIds.fechaInicio] = "fecha de inicio: ";
                headers[camposIds.fechaFin] = "fecha de fin: ";
                headers[camposIds.tipo] = "Tipo: ";
                headers[camposIds.descripcion] = "Descripcion: ";
                agregarRow(tablaModal, modelo, modelo.id, headers);
                console.log("Row")
            },
            (reason) => {
                console.error(reason);
            }
        )
    }

    function actualizarTablaMain(registros) {
        if (registros == 0) {
            tablaBody.innerHTML = "Sin resultados";
            return;
        }
        clearBody(tablaBody);
        registros.forEach(reg => {
            let row = agregarRowCompleta(tabla, reg, reg["id"], datos);
            configRowBotones(reg, row);
        })
    }

    function configRowBotones(registro, row) {
        const btnDetalles = makeBotonHref(
            row.id + "_detalles",
            consultaURL + "id=" + registro.id,
            "linkDetalles",
            "Detalles",
            "modal", "tablaDetalles"
        );
        const btnModificar = makeBotonHref(
            row.id + "_modificar",
            cambiosURL + "OLD_id=" + registro.id + "&",
            "linkModificar",
            "Modificar",
            "modal", "formCambiar",
            "btn-primary"
        );
        const btnEliminar = makeBotonHref(
            row.id + "_eliminar",
            bajasURL + "id=" + registro.id,
            "linkEliminar",
            "Eliminar",
            null, null,
            "btn-danger"
        );

        ///ONCLICK BOTONES

        btnDetalles.onclick = (ev) => {
            ev.preventDefault();
            btnDetallesCallback(btnDetalles, document.getElementById("tablaDetalles"), (tabla, boton) => {
                setTablaModal(tabla, boton)
            })
        }
        btnModificar.onclick = (ev) => {
            ev.preventDefault();
            prepararModal(btnModificar, registro, formModal);
        }
        btnEliminar.onclick = (ev) => {
            ev.preventDefault();
            eliminarRegistro(btnEliminar.href);
        }
        let _1 = document.createElement("td");
        _1.append(btnDetalles);
        _1.append(btnModificar);
        _1.append(btnEliminar);

        row.append(_1);

    }

    //ACCIONES PRINCIPALES

    function consultarFormulario() {
        consultar(consultaURL, form,
            (result) => {
                actualizarTablaMain(result.resultSet);
            },
            (reason) => {
                console.log(reason);
                setText("tablaResults-body", "Error al obtener los datos, intente mas tarde.");
                fireToast("toast", "Error del servidor, intentelo mas tarde", null, "cerrar");
            }
        );
    }

    function agregarRegistro(form) {
        let formData = new FormData(form);
        formData.append("tabla", tablaSQL);
        agregar("../../API/api_mysql_altas.php", formData,
            (response) => {
                if (response.status) {
                    fireToast("toast", postSuccess, "OK", "Deshacer");
                    consultarFormulario();
                } else {
                    fireToast("toast", postFail, null, "cerrar");
                }
            },
            (reason) => {
                fireToast("toast", "Error del servidor, intentelo mas tarde", null, "cerrar");
            }
        );
    }

    function eliminarRegistro(url) {
        eliminar(url, null,
            (response) => {
                if (response.status) {
                    fireToast("toast", deleteSuccess, "OK", "Deshacer");
                    consultarFormulario();
                } else {
                    fireToast("toast", deleteFail, null, "cerrar");
                }
            },
            (reason) => {
                fireToast("toast", "Error del servidor, intentelo mas tarde", null, "cerrar");
            }
        )
    }

    function actualizarRegistro(url, form) {
        actualizar(url, form,
            (result) => {
                if (result.status) {
                    fireToast("toast", putSuccess, "OK", "Deshacer");
                    consultarFormulario();
                } else {
                    fireToast("toast", putFail, null, "cerrar");
                }
            },
            (reason) => {
                fireToast("toast", "Error del servidor, intentelo mas tarde", null, "cerrar");
            }
        )
    }

    ///CONSULTAR DETALLES

    /**@param {HTMLAnchorElement} boton @param {HTMLDivElement} tablaObj  */
    function btnDetallesCallback(boton, tablaObj, callback = (tabla, boton) => {}) {
        if (tablaObj == null) return;
        const tabla = document.getElementById(tablaObj.id + "-table");
        document.getElementById(tablaObj.id + "-submit").hidden = true;
        crearBody(tabla);
        setBodyHTML(tabla, "Buscando...");
        callback(tabla, boton);
    }

    ///PREPARAR EDICION

    /**@param {HTMLAnchorElement} boton @param {any{}} registro @param {HTMLFormElement} form */
    function prepararModal(boton, registro, form) {
        consultar(consultaURL, {
                id: registro.id
            },
            (result) => {
                const old = result.resultSet[0];
                makeModal(registro, form, boton.href);
            },
            (reason) => {
                console.log(reason);
                formModal.innerHTML = "Error al obtener los datos del modelo, intentelo mas tarde";
                fireToast("toast", "Error al recuperar los datos del modelo", null, "cerrar");
            }
        )
    }

    ///PHP MODAL

    function makeModal(registro, form, url) {

        FormBuilder.setFormFieldsEvento(form, "#modal", camposIds, eventosTipos);

        fb.fillForm(form, registro, "#modal");

        form.onsubmit = (ev) => {
            ev.preventDefault();
            if (!MetodosValidacion.validarForm(validadorModificar, form)) return;
            actualizarRegistro(url, form);
            consultarFormulario();
        }
    }
    consultarFormulario();
</script>


</html>