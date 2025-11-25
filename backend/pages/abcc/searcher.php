<?php
class tabData
{
    public $id;
    public $text;
    public $href;

    public function __construct(string $id, string $text, string $href) {
        $this->id = $id;
        $this->text = $text;
        $this->href = $href;
    }
}
function makeSearchModal(string $idNombre, string $header, string $cardHeader = "card head")
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
function makeCardNavModal(string $idNombre, array $tabData, array $tabBodies)
{
    ob_start();

?>
    <div class="modal-body" id="<?php echo $idNombre ?>">

        <div class="card text-center">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <?php 
                        foreach ($tabData as $key => $value) {
                            
                        }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="true" href="#">Active</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>

            </div>
        </div>

    </div>

<?php

    return ob_get_clean();
}
?>