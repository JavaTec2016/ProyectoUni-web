<?php

function buildFormModal(string $idNombre, string $header, string $metodo = "post")
{
    ob_start();
?>
    <div class="modal" tabindex="-1" id=<?php echo $idNombre ?> aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content" id=<?php echo $idNombre . "-content" ?>>
                <div class="modal-header" id=<?php echo $idNombre . "-header" ?>>
                    <h5 class="modal-title" id=<?php echo $idNombre . "-title" ?>> <?php echo $header ?> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id=<?php echo $idNombre . "-body" ?>>
                    <form action="" method=<?php echo $metodo ?> id=<?php echo $idNombre . "-form" ?>>

                    </form>
                </div>
                <div class="modal-footer" id=<?php echo $idNombre . "-footer" ?>>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id=<?php echo $idNombre . "-close" ?>>Cerrar</button>
                    <input type="submit" form=<?php echo $idNombre . "-form" ?> class="btn btn-primary" id=<?php echo $idNombre . "-submit" ?> value="Guardar"/>
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}
?>