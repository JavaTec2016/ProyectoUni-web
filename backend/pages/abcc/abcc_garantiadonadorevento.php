<?php require_once('backend/pages/auth.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include_once('backend/pages/addBootstrap.php') ?>
    <?php include_once('backend/pages/styles.php') ?>
</head>

<body>
    <?php
    require_once('nav_abcc.php');
    require_once('abcc_manager.php');

    echo buildTablaModal("tablaDetalles", "Detalles de la garantia");
    $campos = [
        Garantia_donador_evento::$aliases['donador'][Donador::NOMBRE],
        Garantia_donador_evento::$aliases['evento'][Evento::NOMBRE],
        Garantia::GARANTIA
    ];
    ?>
    <div style="display:flex; height:100%">

        <!-- BARRA LATERAL -->

        <div class="side-nav" style="z-index:1019;">
            <!-- NAV FORMULARIO -->

            <nav class="bg-light abcc scroller" style="overflow-x:hidden; height: 90vh;">
                <div clas="col">
                    <div class="row" id="form-header">
                        <?php echo FormCreador::makeFormGarantiaDonadorEvento("form"); ?>
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

<script src="js/scripTabla.js"></script>
<script src="js/request.js"></script>
<script src="js/showToast.js"></script>
<script src="js/scripForm.js"></script>
<script src="js/ABCCUtils.js"></script>
<script src="js/funcinoesValidacion/MetodosValidacion.js"></script>

<script type="text/javascript">
    ////DEFINICION DE DATOS
    let seleccion = null;
    let selectorTabla = "";
    console.log("A");
    const tablaSQL = "garantia_donador_evento";
    let validacionRules = {};

    const consultaURLGeneral = "api_mysql_consultas.php?";
    const consultaURL = "api_mysql_consultas.php?tabla=" + tablaSQL + "&";
    const form = document.getElementById("form");
    const formBody = document.getElementById("form-body");
    const tabla = document.getElementById("tablaResults");
    const tablaBody = document.getElementById("tablaResults-body");
    const garantiasSelect = {};
    const donadoresSelect = {};
    const eventosSelect = {};

    tablaBody.childNodes.forEach(n => tablaBody.removeChild(n));

    ///PHP DEFINICIONES

    const datos = [
        <?php echoArray($campos) ?>
    ];
    const camposIds = {
        id_garantia: "<?php echo Garantia_donador_evento::$aliases['garantia'][Garantia::ID] ?>",
        id_donador: "<?php echo Garantia::ID_DONADOR ?>",
        id_evento: "<?php echo Garantia::ID_EVENTO ?>",
        garantia: "<?php echo Garantia::GARANTIA ?>",
    }
    const estados = <?php echoAssoc(FormCreador::$garantia_estados) ?>;

    ///reglas auto

    function setFormFields(form, idAfter = "#modal") {
        FormBuilder.setFormFieldsDonador(form, idAfter, camposIds, estados, garantiasSelect, donadoresSelect, eventosSelect);
    }
    requestRules(tablaSQL,
        (json) => {
            validacionRules = json.rules;
            setupRules();
        },
        (reason) => {
            console.error("Reglas fail: \n", reason)
        }
    );

    form.onsubmit = (ev) => {
        ev.preventDefault();
        if (!MetodosValidacion.validarForm(validadorAgregar, form)) return;
        agregarRegistro(form);
    }

    ///SETUP DE VALIDACION

    function setupRules() {}

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
            row.id + "_detalles",
            consultaURL + "id_garantia=" + registro.id_garantia + "&id_donador=" + registro.id_donador + "&id_evento=" + registro.id_evento,
            "linkDetalles",
            "Detalles",
            "modal", "tablaDetalles"
        );
        ///ONCLICK BOTONES

        btnDetalles.onclick = (ev) => {
            ev.preventDefault();
            btnDetallesCallback(btnDetalles, document.getElementById("tablaDetalles"), (tabla, boton) => {
                console.log("DETALLES")
                setTablaModal(tabla, trimAPIUrl(boton.href))
            })
        }
        let _1 = document.createElement("td");
        _1.append(btnDetalles);
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
        return;
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
        return;
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
        return;
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
        return;
        consultar(consultaURL, {
                id: registro.id
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
        return;
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


    ///OPCIONES RELACIONALES


    let data = new FormData();
    data.append("tabla", "garantia");
    consultar(consultaURLGeneral, data,
        (result) => {
            let obj = {};
            let garantias = result.resultSet;
            garantias.forEach(claseObj => {
                garantiasSelect[claseObj.id] = "Monto: " + claseObj.garantia;
            })
        }
    )
    data = new FormData();
    data.append("tabla", "donador");
    consultar(consultaURLGeneral, data,
        (result) => {
            let obj = {};
            let donadores = result.resultSet;
            donadores.forEach(corpObj => {
                donadoresSelect[corpObj.id] = corpObj.nombre;
            })
        }
    )
    data = new FormData();
    data.append("tabla", "evento");
    consultar(consultaURLGeneral, data,
        (result) => {
            let obj = {};
            let eventos = result.resultSet;
            eventos.forEach(corpObj => {
                eventosSelect[corpObj.id] = corpObj.nombre;
            })
        }
    )
</script>


</html>