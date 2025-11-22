<?php
function tostar(string $idToast, string $idBody, string $idBoton1, string $idBoton2)
{
    ob_start();
?>
    <div class="position-fixed top-1 start-50 translate-middle-x" style="z-index: 1021;">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="<?php echo $idToast ?>">
            <div class="toast-body" id="<?php echo $idBody ?>">
                <div id="<?php echo $idBody . "-text" ?>">si</div>
                <div class="mt-2 pt-2 border-top">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="toast" id="<?php echo $idBoton1 ?>">dpeende</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast" id="<?php echo $idBoton2 ?>">depende</button>
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}
?>