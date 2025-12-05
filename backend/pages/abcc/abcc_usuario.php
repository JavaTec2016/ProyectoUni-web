<?php require_once('backend/pages/auth.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABCC corporaciones</title>
    <?php include_once('backend/pages/addBootstrap.php') ?>
    <?php include_once('backend/pages/styles.php') ?>
</head>

<body>
    <?php
    require_once('nav_abcc.php');
    require_once('abcc_manager.php');

    echo buildTablaModal("tablaDetalles", "Detalles del usuario");
    echo buildFormModal("formCambiar", "Datos del Usuario", "PUT");

    $campos = [Usuario::NOMBRE, Usuario::PASS, Usuario::ROL];
    ?>
    <div style="display:flex; height:100%">

        <!-- BARRA LATERAL -->

        <div class="side-nav" style="z-index:1019;">
            <!-- NAV FORMULARIO -->

            <nav class="bg-light abcc scroller" style="overflow-x:hidden; height: 90vh;">
                <div clas="col">
                    <div class="row" id="form-header">
                        <?php echo FormCreador::makeFormUsuario("form"); ?>
                    </div>
                </div>
            </nav>
        </div>

        <!-- CONTENEDOR DE TABLA Y ESAS COSAS -->

        <div class="container scroller" style="position: relative; height:90vh;">
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

<script src="js/scripTabla.js"></script>
<script src="js/request.js"></script>
<script src="js/showToast.js"></script>
<script src="js/scripForm.js"></script>
<script src="js/ABCCUtils.js"></script>
<script src="js/funcinoesValidacion/MetodosValidacion.js"></script>

<script type="text/javascript">
    ////DEFINICION DE DATOS

    console.log("A");
    const tablaSQL = "usuario";
    let validacionRules = {};
    const postSuccess = "Usuario agregado";
    const postFail = "No se pudo agregar el usuario";
    const putSuccess = "Usuario actualizado";
    const putFail = "No se pudo modificar el usuario";
    const deleteSuccess = "Usuario eliminado";
    const deleteFail = "No se pudo eliminar el usuario";

    const consultaURLGeneral = "api_mysql_consultas.php?";
    const consultaURL = "api_mysql_consultas.php?tabla=" + tablaSQL + "&";
    const cambiosURL = "api_mysql_cambios.php?tabla=" + tablaSQL + "&";
    const bajasURL = "api_mysql_bajas.php?tabla=" + tablaSQL + "&";

    const validadorAgregar = new ValidadorRunner();
    const validadorModificar = new ValidadorRunner();

    const form = document.getElementById("form");
    const formModal = document.getElementById("formCambiar-form");
    const formBody = document.getElementById("form-body");
    const tabla = document.getElementById("tablaResults");
    const tablaBody = document.getElementById("tablaResults-body");
    tablaBody.childNodes.forEach(n => tablaBody.removeChild(n));

    ///PHP DEFINICIONES

    const datos = [<?php echoArray($campos) ?>];
    const camposIds = {
        nombre: "<?php echo Usuario::NOMBRE ?>",
        pass: "<?php echo Usuario::PASS ?>",
        rol: "<?php echo Usuario::ROL ?>",
    }
    const roles = <?php echoAssoc(FormCreador::$usuario_roles) ?>;

    function setFormFields(form, idAfter = "#modal") {
        console.log(FormBuilder.setFormFieldsClase)
        FormBuilder.setFormFieldsUsuario(form, idAfter, camposIds, roles);
    }
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
    function setupRules() {
        MetodosValidacion.makeMensajesUsuario(validadorAgregar, "#", "_input", camposIds);
        MetodosValidacion.makeMensajesUsuario(validadorModificar, "#modal", "_input", camposIds);

        MetodosValidacion.makeValidadoresUsuario(validadorAgregar, form, camposIds, validacionRules, "#");
        MetodosValidacion.makeValidadoresUsuario(validadorModificar, formModal, camposIds, validacionRules, "#modal");
    }

    //////=================== todo lo de la movedera ABCC con el backend (no moverle)

    ///CONSULTAS

    /**@param {HTMLTableElement} tablaModal*/
    function setTablaModal(tablaModal, url = "api_mysql_consultas.php") {
        const req = new FetchRequest(APIUrl + url, "GET");
        crearBody(tablaModal);
        setBodyHTML(tablaModal, "Buscando...");
        req.callbackJSON(
            (result) => {
                setBodyHTML(tablaModal, "");
                agregarRows(tablaModal, result.resultSet, null);
            },
            (reason) => {

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
            row.nombre + "_detalles",
            consultaURL + "nombre=" + registro.nombre,
            "linkDetalles",
            "Detalles",
            "modal", "tablaDetalles"
        );
        const btnEliminar = makeBotonHref(
            row.nombre + "_eliminar",
            bajasURL + "nombre=" + registro.nombre,
            "linkEliminar",
            "Eliminar",
            null, null,
            "btn-danger"
        );

        ///ONCLICK BOTONES

        btnDetalles.onclick = (ev) => {
            ev.preventDefault();
            btnDetallesCallback(btnDetalles, document.getElementById("tablaDetalles"), (tabla, boton) => {
                setTablaModal(tabla, trimAPIUrl(boton.href))
            })
        }
        btnEliminar.onclick = (ev) => {
            ev.preventDefault();
            eliminarRegistro(trimAPIUrl(btnEliminar.href));
        }
        let _1 = document.createElement("td");
        _1.append(btnDetalles);
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
        agregar("api_mysql_altas.php", formData,
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
                nombre: registro.nombre
            },
            (result) => {
                const old = result.resultSet[0];
                makeModal(registro, form, trimAPIUrl(boton.href));
            },
            (reason) => {
                console.log(reason);
                formModal.innerHTML = "Error al obtener los datos del modelo, intentelo mas tarde";
                fireToast("toast", "Error al recuperar los datos del modelo", null, "cerrar");
            }
        )
    }

    function makeModal(registro, form, url) {
        setFormFields(form);
        console.log("modal: ", form);
        fb.fillForm(form, registro, "#modal");

        form.onsubmit = (ev) => {
            ev.preventDefault();
            if (!MetodosValidacion.validarForm(validadorModificar, form)) return;
            actualizarRegistro(url, form);
            consultarFormulario();
        }
    }
    consultarFormulario();
    fb.formOnInput(form, (field, ev) => {
        consultarFormulario()
    });
    form.onreset = (ev) => {
        form.reset();
        consultarFormulario()
    };
</script>


</html>