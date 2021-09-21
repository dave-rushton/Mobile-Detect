<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../custom/classes/baskets.cls.php");
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../system/classes/subcategories.cls.php");

$TmpBsk = new BskDAO();
$TmpBpg = new BpgDAO();
$TmpBpr = new BprDAO();

$Bsk_ID = (isset($_GET['bsk_id']) && is_numeric($_GET['bsk_id'])) ? $_GET['bsk_id'] : NULL;
$basketRec = NULL;
$basketProducts = NULL;
if (!is_null($Bsk_ID)) {
    $basketRec = $TmpBsk->select($Bsk_ID, NULL, NULL, NULL, true);
    $basketExtras = $TmpBsk->selectExtras($Bsk_ID);
}

if (!isset($basketRec->bsk_id) || !is_numeric($basketRec->bsk_id)) die();

$basketGroups = $TmpBpg->select(NULL, $basketRec->bsk_id, NULL, false);

if (isset($basketGroups) && is_array($basketGroups)) {

for ($g = 0; $g < count($basketGroups); $g++) {
?>

<table class="table table-bordered table-striped table-highlight sortGroups">

    <thead class="deleteOption-<?php echo $basketGroups[$g]['bpg_id']; ?>">
    <tr>
        <th colspan="3">
            <input type="text" name="bpgttl" value="<?php echo $basketGroups[$g]['bpgttl']; ?>">
        </th>
        <th style="width: 40px; text-align: center;">
            <input type="checkbox" class="multipleOption"
                   value="<?php echo $basketGroups[$g]['bpg_id']; ?>" <?php if ($basketGroups[$g]['mulsel'] == 1) echo 'checked'; ?>
                   rel="tooltip" title="Multiple Select Products">
        </th>
        <th style="width: 80px;">
            <input type="text" name="bpgmin" style="width: 75px;" value="<?php echo $basketGroups[$g]['bpgmin']; ?>" title="Mininum Selection">
        </th>
        <th style="width: 80px;">
            <input type="text" name="bpgmax" style="width: 75px;" value="<?php echo $basketGroups[$g]['bpgmax']; ?>" title="Maximum Selection">
        </th>
        <th style="width: 80px;">

        </th>
        <th width="30"><a href="#" class="btn btn-danger deleteGroupBtn" rel="tooltip" title="Delete Group"
                          data-bpg_id="<?php echo $basketGroups[$g]['bpg_id']; ?>"><i class="icon icon-trash"></i></a>
        </th>
        <th width="30"><a href="#" class="btn btn-success updateGroupBtn" rel="tooltip" title="Update Group"
                          data-bpg_id="<?php echo $basketGroups[$g]['bpg_id']; ?>"><i class="icon icon-save"></i></a>
        </th>
        <th width="30"><a href="#" class="btn btn-success addProductBtn" rel="tooltip" title="Add Product To Group"
                          data-bpg_id="<?php echo $basketGroups[$g]['bpg_id']; ?>"><i class="icon icon-plus"></i></a>
        </th>
        <th width="30"><a href="#" class="btn btn-warning sortProductBtn" rel="tooltip" title="Sort Product Group"
                          data-bpg_id="<?php echo $basketGroups[$g]['bpg_id']; ?>"><i class="icon icon-sort"></i></a>
        </th>
    </tr>
    </thead>
    <tbody class="sortproducts deleteOption-<?php echo $basketGroups[$g]['bpg_id']; ?>">


    <?php

    $basketProducts = NULL;
    $basketProducts = $TmpBpr->select(NULL, $basketRec->bsk_id, $basketGroups[$g]['bpg_id'], false);

    for ($i = 0; $i < count($basketProducts); $i++) {
        ?>

        <tr data-bpr_id="<?php echo $basketProducts[$i]['bpr_id']; ?>">

            <td><?php echo $basketProducts[$i]['prdnam']; ?></td>
            <td style="width: 80px; text-align: right"><span
                    class="selectedPrice"><?php echo $basketProducts[$i]['unipri']; ?></span>
            </td>
            <td style="width: 40px; text-align: center;"><input type="checkbox" class="mandatoryProduct" name="bprman"
                                                                value="<?php echo $basketProducts[$i]['bpr_id']; ?>" <?php if ($basketProducts[$i]['bprman'] == 1) echo 'checked'; ?>
                                                                rel="tooltip" title="Mandatory Product">
            </td> Product
            <td style="width: 40px; text-align: center;"><input type="checkbox" class="defaultProduct" name="defsel"
                                                                value="<?php echo $basketProducts[$i]['bpr_id']; ?>" <?php if ($basketProducts[$i]['defsel'] == 1) echo 'checked'; ?>
                                                                rel="tooltip" title="Default Products">
            </td>

            <td style="width: 80px;"><input type="text" name="exttxt" style="width: 75px;"
                                            value="<?php echo $basketProducts[$i]['exttxt']; ?>" title="Additional Price Text"></td>
            <td style="width: 40px;"><input type="text" name="extpri" style="width: 35px;"
                                            value="<?php echo $basketProducts[$i]['extpri']; ?>" title="Additional Price Amount"></td>

            <td style="width: 40px;"><input type="text" name="bprqty" style="width: 35px;"
                                            value="<?php echo $basketProducts[$i]['bprqty']; ?>" title="Basket Product Quantity"></td>

            <td><a href="#" class="btn btn-danger deleteProductBtn"
                   data-bpr_id="<?php echo $basketProducts[$i]['bpr_id']; ?>"><i
                        class="icon icon-trash"></i></a></td>
            <td><a href="#" class="btn btn-success updateProductBtn"
                   data-bpr_id="<?php echo $basketProducts[$i]['bpr_id']; ?>"
                   data-prd_id="<?php echo $basketProducts[$i]['prd_id']; ?>"><i
                        class="icon icon-save"></i></a></td>


            <td></td>
            <td></td>

            <!--            <td><a href="#" class="btn btn-success sortproductsbtn"-->
            <!--                   data-prd_id="--><?php //echo $basketProducts[$i]['prd_id']; ?><!--"><i-->
            <!--                        class="icon icon-sort"></i></a></td>-->
        </tr>

        <?php
    }
    ?>

    </tbody>

    <?php
    }
    }
?>

