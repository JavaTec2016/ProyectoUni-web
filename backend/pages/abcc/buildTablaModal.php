<?php

function buildTablaModal(string $idNombre, string $header)
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
                <div class="modal-body" id=<?php echo $idNombre . "-body" ?>>
                    <table class="table table-hover" id=<?php echo $idNombre . "-table" ?>>

                    </table>
                </div>
                <div class="modal-footer" id=<?php echo $idNombre . "-footer" ?>>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id=<?php echo $idNombre . "-close" ?>>Cerrar</button>
                    <button type="button" class="btn btn-primary" id=<?php echo $idNombre . "-submit" ?>>Guardar</button>
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}
?>