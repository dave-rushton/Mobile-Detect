<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/system/classes/places.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

if (!$loggedIn) {
    header('location: login');
    exit();
}

$DspTyp = 'listing';

if (isset($_GET['editaddress']) && is_numeric($_GET['editaddress'])) {

    $deliveryRec = $PlaDao->select($_GET['editaddress'], 'DELADR', $loggedIn->pla_id, NULL, NULL, true);

    $DspTyp = 'address';

    if ($_GET['editaddress'] == 0) {

        $deliveryRec = new stdClass();
        $deliveryRec->pla_id = $_GET['editaddress'];
        $deliveryRec->tblnam = '';
        $deliveryRec->tbl_id = 0;
        $deliveryRec->comnam = '';
        $deliveryRec->planam = '';
        $deliveryRec->adr1 = '';
        $deliveryRec->adr2 = '';
        $deliveryRec->adr3 = '';
        $deliveryRec->adr4 = '';
        $deliveryRec->pstcod = '';
        $deliveryRec->ctynam = '';
        $deliveryRec->plaema = '';
        $deliveryRec->platel = '';
        $deliveryRec->plamob = '';
        $deliveryRec->sta_id = 0;

    } else {

        if (!isset($deliveryRec->pla_id) || $deliveryRec->tbl_id != $loggedIn->pla_id) {

            $DspTyp = 'listing';

        }

    }

}

if ($DspTyp == 'listing') {
    $customerAddress = $PlaDao->select(NULL, 'DELADR', $loggedIn->pla_id, NULL, NULL, false);
}



?>



<?php
if ($DspTyp == 'listing') {
    ?>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">

                    <div class="box">

                        <?php include('account.menu.php'); ?>

                    </div>

                </div>
                <div class="col-sm-9">

                    <h2 class="heading">Delivery Addreses</h2>

                    <hr>

                    <p><a href="useraccount/addresses?editaddress=0" class="button">Add New Address</a></p>

                    <div class="addresslist">

                        <ul>

                            <?php

                            $tableLength = count($customerAddress);
                            for ($i = 0; $i < $tableLength; ++$i) {
                                ?>


                                <li>

                                    <div class="addressrecord">

                                    <?php
                                    $displayAddress = '<p><a href="useraccount/addresses?editaddress='.$customerAddress[$i]['pla_id'].'" class="editDeliveryAddressLink" data-pla_id="' . $customerAddress[$i]['pla_id'] . '">' . $customerAddress[$i]['planam'] . '</a><br>';
                                    $displayAddress .= '<small style="font-size: 10px; line-height: 1em;">' . $customerAddress[$i]['adr1'];
                                    $displayAddress .= (!empty($customerAddress[$i]['adr2'])) ? '<br>' . $customerAddress[$i]['adr2'] : '';
                                    $displayAddress .= (!empty($customerAddress[$i]['adr3'])) ? '<br>' . $customerAddress[$i]['adr3'] : '';
                                    $displayAddress .= (!empty($customerAddress[$i]['adr4'])) ? '<br>' . $customerAddress[$i]['adr4'] : '';
                                    $displayAddress .= (!empty($customerAddress[$i]['pstcod'])) ? '<br>' . $customerAddress[$i]['pstcod'] : '';
                                    $displayAddress .= '</small></p>';
                                    $displayAddress .= '<a href="pages/account/remove_address.php?pla_id=' . $customerAddress[$i]['pla_id'] . '" class="deleteDeliveryAddressLink button" data-pla_id="' . $customerAddress[$i]['pla_id'] . '">Remove</a>';

                                    echo $displayAddress;
                                    ?>

                                    </div>

                                </li>

                                <?php
                            }
                            ?>

                        </ul>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>

        $(function () {

            $('.addresslist').find('a.deleteDeliveryAddressLink').click(function (e) {

                if (confirm('Delete Delivery Address')) {



                } else {
                    e.preventDefault();
                }

            });

        })

    </script>

    <?php
}
?>



<?php
if ($DspTyp == 'address') {
    ?>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">

                    <div class="box">

                        <?php include('account.menu.php'); ?>

                    </div>

                </div>
                <div class="col-sm-9">

                    <h2 class="heading">Manage Delivery Addreses</h2>

                    <div class="box">


                        <form action="pages/account/address_update.php" method="post" class="form-vertical" id="loginForm"
                              data-parsley-validate>
                            <div class="box">
                                <div class="pw-form">

                                    <div class="pw-form-content">
                                        <fieldset>


                                            <input type="hidden" name="tblnam" value="DELADR">
                                            <input type="hidden" name="tbl_id" value="<?php echo $loggedIn->pla_id; ?>">
                                            <input type="hidden" name="pla_id" value="<?php echo $deliveryRec->pla_id; ?>">

                                            <div class="form-group">
                                                <label>Place Name</label>

                                                <div class="controls">
                                                    <input type="text" value="<?php echo $deliveryRec->planam; ?>"
                                                           class="form-control input-block-level required" name="planam">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Company Name</label>

                                                <div class="controls">
                                                    <input type="text" value="<?php echo $deliveryRec->comnam; ?>"
                                                           class="form-control input-block-level" name="comnam">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label>Address Line 1</label>

                                                <div class="controls">
                                                    <input type="text" value="<?php echo $deliveryRec->adr1; ?>"
                                                           class="form-control input-block-level required" name="adr1">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Address Line 2</label>

                                                <div class="controls">
                                                    <input type="text" value="<?php echo $deliveryRec->adr2; ?>"
                                                           class="form-control input-block-level" name="adr2">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Town / City</label>

                                                <div class="controls">
                                                    <input type="text" value="<?php echo $deliveryRec->adr3; ?>"
                                                           class="form-control input-block-level" name="adr3">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Address Line 4</label>

                                                <div class="controls">
                                                    <input type="text" value="<?php echo $deliveryRec->adr4; ?>"
                                                           class="form-control input-block-level" name="adr4">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Post Code</label>

                                                <div class="controls">
                                                    <input type="text" value="<?php echo $deliveryRec->pstcod; ?>"
                                                           class="form-control input-block-level required" name="pstcod">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Country</label>

                                                <div class="controls">


                                                    <select id="paycountry" name="paycountry" class="form-control"required data-parsley-required-message="Country is mandatory.">
                                                        <option value="">(please select a country)</option>

                                                        <?php

                                                        $deliveryCountries = $TmpDel->getCountryRecords();

                                                        for ($i=0;$i<count($deliveryCountries);$i++) {
                                                            ?>

                                                            <option value="<?php echo $deliveryCountries[$i]->coucod; ?>" <?php if (isset($deliveryRec->coucod) && $deliveryRec->coucod == $deliveryCountries[$i]->coucod) echo ' selected'; ?> ><?php echo $deliveryCountries[$i]->counam; ?></option>

                                                            <?php
                                                        }

                                                        ?>

                                                    </select>

                                                </div>
                                            </div>


                                        </fieldset>
                                    </div>
                                </div>
                            </div>


                            <div class="box text-right">
                                <button type="submit" class="button">Update</button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
