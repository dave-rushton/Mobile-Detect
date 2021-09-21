<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../custom/classes/baskets.cls.php");

$TmpBsk = new BskDAO();
$TmpBpg = new BpgDAO();
$TmpBpr = new BprDAO();
$TmpBex = new BexDAO();

$Bsk_ID = (isset($_REQUEST['bsk_id']) && is_numeric($_REQUEST['bsk_id'])) ? $_REQUEST['bsk_id'] : NULL;
$basketRec = NULL;

if (!is_null($Bsk_ID)) {
    $basketRec = $TmpBsk->select($Bsk_ID, NULL, NULL, NULL, true);
    $basketExtras = $TmpBsk->selectExtras($Bsk_ID);
}

for ($i = 0; $i < count($basketExtras); $i++) {
    ?>

    <tr>
        <td><input type="text" name="bexttl"
                   value="<?php echo $basketExtras[$i]['bexttl']; ?>"></td>
        <td><input type="text" name="bextxt"
                   value="<?php echo $basketExtras[$i]['bextxt']; ?>"></td>
        <td style="text-align: center;"><input type="checkbox" name="bexdef"
                                               value="1" <?php if ($basketExtras[$i]['bexdef'] == 1) echo 'checked'; ?>>
        </td>
        <td style="text-align: center;"><input type="checkbox" name="bexman"
                                               value="1" <?php if ($basketExtras[$i]['bexman'] == 1) echo 'checked'; ?>>
        </td>
        <td><input type="text" name="bexpri"
                   value="<?php echo $basketExtras[$i]['bexpri']; ?>"></td>
        <td><a href="#" class="btn btn-danger deleteExtraBtn" data-bex_id="<?php echo $basketExtras[$i]['bex_id']; ?>"><i
                    class="icon icon-trash"></i></a></td>
        <td><a href="#" class="btn btn-success sortExtraBtn""><i
                class="icon icon-sort"></i></a></td>
    </tr>

    <?php
}
?>

