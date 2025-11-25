<?php

function buildBuscadorModal(string $idNombre, string $header, string $metodo = "post")
{
    ob_start();
?>
    <div class="modal" tabindex="-1" id=<?php echo $idNombre ?> aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id=<?php echo $idNombre . "-content" ?>>
                <div class="modal-header" id=<?php echo $idNombre . "-header" ?>>
                    <h5 class="modal-title" id=<?php echo $idNombre . "-title" ?>> <?php echo $header ?> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row" id=<?php echo $idNombre . "-body" ?>>
                    <div class="row">
                        <form class="col-4 scroller" action="" method=<?php echo $metodo ?> id=<?php echo $idNombre . "-form" ?> style="max-height: 90vh;">

                        </form>
                        <table class="table table-hover col-8 scroller" id="<?php echo $idNombre . "-table" ?>"" style=" max-height: 90vh;">
                            <thead id="<?php echo $idNombre . "-table-head" ?>">
                            </thead>
                            <tbody id="<?php echo $idNombre . "-table-body" ?>">
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer" id=<?php echo $idNombre . "-footer" ?>>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id=<?php echo $idNombre . "-close" ?>>Cerrar</button>
                    <input type="submit" form=<?php echo $idNombre . "-form" ?> class="btn btn-primary" id=<?php echo $idNombre . "-submit" ?> value="Seleccionar" />
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}
?>