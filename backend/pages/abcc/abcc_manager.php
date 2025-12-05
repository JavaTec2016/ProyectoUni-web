<?php

use function PHPSTORM_META\type;

include_once('backend/model/allModels.php');
require_once('backend/controller/DAO.php');
require_once('backend/pages/toast.php');
include_once('buildTablaModal.php');
include_once('buildFormModal.php');
require_once('form_creador.php');
function buildField(string $id, string $type, string $label, string|null $inputName = null, array|null $values = null, string $invalidMsg = "")
{
    $inputId = $id . "#_input";
    if (!isset($inputName) || $inputName == null) $inputName = $inputId;
    if (!isset($values) || $values == null) $values = array();

    ob_start();
?>

    <div class="mb-3" id="<?php echo $id ?>">
        <label for="<?php echo $inputName ?>" class="form-label"><?php echo $label ?> </label>
        <?php if ($type == 'select') { ?>
            <select class="form-control" name="<?php echo $inputName ?>" id="<?php echo $inputId ?>">
                <option value="">Seleccionar...</option>
                <?php foreach ($values as $value => $text) { ?>
                    <option value="<?php echo $value ?>"> <?php echo $text ?></option>
                <?php } ?>
            </select>

        <?php } else if ($type == 'textarea') { ?>
            <textarea class="form-control" name="<?php echo $inputName ?>" id="<?php echo $inputId ?>"></textarea>
        <?php } else if ($type == 'decimal') { ?>
            <input type="number" step="0.01" min="0" maxlength="13" class="form-control" id="<?php echo $inputId ?>" name="<?php echo $inputName ?>">
        <?php } else { ?>
            <input type="<?php echo $type ?>" class="form-control" id="<?php echo $inputId ?>" name="<?php echo $inputName ?>">
        <?php } ?>
        <div class="invalid-feedback" id="<?php echo $inputId ?>_invalid" hidden>
            <?php echo $invalidMsg ?>
        </div>
    </div>


<?php
    $elem = ob_get_clean();
    return $elem;
}

function buildTableHeader(mixed ...$headerValues)
{
    ob_start();
?>

    <?php
    foreach ($headerValues as $idx => $value) {
    ?>
        <th scope="col"> <?php echo $value ?> </th>
    <?php
    }
    ?>
<?php
    return ob_get_clean();
}

function echoArray(array $d, bool $checkTypes = false)
{
    foreach ($d as $idx => $value) {
        if ($checkTypes) {
            if (type($value) == "string") echo "\"" . $value . "\"";
            else echo $value;
        } else {
            echo "\"" . $value . "\"";
        }
        if ($idx < count($d) - 1) echo ", ";
    }
}
function echoAssoc(array $ass)
{
    echo json_encode($ass);
}

function getValidadorParams(string $tabla, string $campo)
{
    /**@var DataRow */
    $ruleset = Models::get($tabla)::$rules[$campo];

    echoArray([
        substr($ruleset[DataRow::TIPO], 0, 1),
        $ruleset[DataRow::NO_NULO],
        $ruleset[DataRow::UMBRAL],
        $ruleset[DataRow::LIMITE]
    ], true);
}
?>