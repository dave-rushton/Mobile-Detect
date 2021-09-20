<?php

$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn( (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : NULL );

$createSession = false;

if (!isset($shoppingcart->shoppingCart['customer']['cusnam'])) {

    if (isset($loggedIn->pla_id)) {
        $createSession = true;
    } else {
        $createSession = false;
//        header('location: login');
//        exit();
    }
} else {

    if (isset($loggedIn->pla_id)) {
        //$createSession = true;
    } else {
        //header('location: ../useraccount/login');
        //exit();
    }
}

if ($createSession == true) {

    $shoppingcart->getCustomer($loggedIn->pla_id);

}

?>
<div class="section">
    <div class="container">

        <div class="row">

            <div class="col-sm-12">

                <form id="checkoutForm" action="shoppingcart/updatedetails/0" role="form" autocomplete="off">

                    <div class="row">
                        <div class="col-sm-6">

                            <h2 class=" styled">Customer Details</h2>
                            <hr>

                            <div class="form-group">
                                <label class="control-label">Company <small>(if applicable)</small></label>

                                <div class="control-input ">
                                    <input name="paycus" type="text" placeholder="Company Name" class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['paycus']; ?>"
                                           data-parsley-required-message="Please enter your company name.">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Title</label>

                                <div class="control-input ">

                                    <select name="custtl" class="form-control" required>
                                        <option <?php if (isset($shoppingcart->shoppingCart['customer']['custtl']) && $shoppingcart->shoppingCart['customer']['custtl'] == '') echo 'selected'; ?> value="">N/A</option>
                                        <option <?php if (isset($shoppingcart->shoppingCart['customer']['custtl']) && $shoppingcart->shoppingCart['customer']['custtl'] == 'Mr') echo 'selected'; ?> value="Mr">Mr</option>
                                        <option <?php if (isset($shoppingcart->shoppingCart['customer']['custtl']) && $shoppingcart->shoppingCart['customer']['custtl'] == 'Mrs') echo 'selected'; ?> value="Mrs">Mrs</option>
                                        <option <?php if (isset($shoppingcart->shoppingCart['customer']['custtl']) && $shoppingcart->shoppingCart['customer']['custtl'] == 'Ms') echo 'selected'; ?> value="Ms">Ms</option>
                                        <option <?php if (isset($shoppingcart->shoppingCart['customer']['custtl']) && $shoppingcart->shoppingCart['customer']['custtl'] == 'Miss') echo 'selected'; ?> value="Miss">Miss</option>
                                        <option <?php if (isset($shoppingcart->shoppingCart['customer']['custtl']) && $shoppingcart->shoppingCart['customer']['custtl'] == 'Dr') echo 'selected'; ?> value="Dr">Dr</option>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">First Name</label>

                                <div class="control-input ">

                                    <div class="formrequired">*</div>

                                    <input name="cusfna" type="text" placeholder="First Name" class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer']['cusfna'])) echo $shoppingcart->shoppingCart['customer']['cusfna']; ?>"
                                           required
                                           data-parsley-required-message="Please enter your first name.">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label">Surname</label>

                                <div class="control-input ">

                                    <div class="formrequired">*</div>

                                    <input name="cussna" type="text" placeholder="Surname" class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer']['cussna'])) echo $shoppingcart->shoppingCart['customer']['cussna']; ?>"
                                           required
                                           data-parsley-required-message="Please enter your surname.">
                                </div>
                            </div>

                            <hr>

                            <p>Please enter your email to receive confirmation of your order.</p>

                            <div class="form-group">
                                <label>Email Address</label>

                                <div class="controls">

                                    <div class="formrequired">*</div>

                                    <input type="text"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['cusema']; ?>"
                                           class="form-control input-block-level " name="cusema" required
                                           data-parsley-error-message="Please enter email address to receive confirmation">
                                </div>
                            </div>


                            <div class="form-group">
                                <label>Contact Telephone Number</label>
                                <div class="formrequired">*</div>

                                <div class="controls">
                                    <input type="text"
                                           required
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['cusmob']; ?>"
                                           class="form-control input-block-level " name="cusmob" placeholder="Contact Telephone Number">
                                </div>
                            </div>

                            <div class="form-group hide">
                                <label>Home Telephone</label>

                                <div class="controls">
                                    <input type="text"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['custel']; ?>"
                                           class="form-control input-block-level " name="custel"
                                           data-parsley-error-message="Please enter your contact telephone number">
                                </div>
                            </div>

                            <div class="alert alert-info">

                                <p>
                                    <strong>Telephone Numbers:</strong><br>
                                    This information will only be used for queries about your order.
                                </p>

                            </div>


                        </div>
                        <div class="col-sm-6">

                            <h2 class="styled">Billing Address</h2>
                            <hr>



                            <!-- address-line1 input-->
                            <div class="form-group">
                                <label class="control-label">Address Line 1</label>

                                <div class="control-input ">

                                    <div class="formrequired">*</div>

                                    <input name="payadr1" id="payadr1" type="text" placeholder="Address Line 1"
                                           class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['payadr1']; ?>"
                                           required
                                           data-parsley-required-message="Address line 1 is mandatory.">
                                </div>
                            </div>
                            <!-- address-line2 input-->
                            <div class="form-group">
                                <label class="control-label">Address Line 2</label>

                                <div class="control-input ">
                                    <input name="payadr2" id="payadr2" type="text" placeholder="Address Line 2"
                                           class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['payadr2']; ?>">
                                </div>
                            </div>


                            <!-- city input-->
                            <div class="form-group">
                                <label class="control-label">City / Town</label>

                                <div class="control-input ">

                                    <div class="formrequired">*</div>

                                    <input name="payadr3" id="payadr3" type="text" placeholder="City / Town" class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['payadr3']; ?>"
                                           required
                                           data-parsley-required-message="Address town is mandatory.">
                                </div>
                            </div>

                            <div class="form-group" id="countyDiv">
                                <label class="control-label">County</label>

                                <div class="control-input ">
                                    <input name="payadr4" id="payadr4" type="text" placeholder="County"
                                           class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['payadr4']; ?>">
                                </div>
                            </div>

                            <div class="form-group" id="stateDiv">
                                <label class="control-label">State</label>

                                <div class="control-input">

                                    <select name="payadr4state" class="form-control">
                                        <option value="AL" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'AL') echo ' selected'; ?> >Alabama</option>
                                        <option value="AK" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'AK') echo ' selected'; ?> >Alaska</option>
                                        <option value="AZ" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'AZ') echo ' selected'; ?> >Arizona</option>
                                        <option value="AR" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'AR') echo ' selected'; ?> >Arkansas</option>
                                        <option value="CA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'CA') echo ' selected'; ?> >California</option>
                                        <option value="CO" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'CO') echo ' selected'; ?> >Colorado</option>
                                        <option value="CT" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'CT') echo ' selected'; ?> >Connecticut</option>
                                        <option value="DE" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'DE') echo ' selected'; ?> >Delaware</option>
                                        <option value="DC" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'DC') echo ' selected'; ?> >District of Columbia</option>
                                        <option value="FL" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'FL') echo ' selected'; ?> >Florida</option>
                                        <option value="GA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'GA') echo ' selected'; ?> >Georgia</option>
                                        <option value="HI" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'HI') echo ' selected'; ?> >Hawaii</option>
                                        <option value="ID" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'ID') echo ' selected'; ?> >Idaho</option>
                                        <option value="IL" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'IL') echo ' selected'; ?> >Illinois</option>
                                        <option value="IN" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'IN') echo ' selected'; ?> >Indiana</option>
                                        <option value="IA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'IA') echo ' selected'; ?> >Iowa</option>
                                        <option value="KS" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'KS') echo ' selected'; ?> >Kansas</option>
                                        <option value="KY" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'KY') echo ' selected'; ?> >Kentucky</option>
                                        <option value="LA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'LA') echo ' selected'; ?> >Louisiana</option>
                                        <option value="ME" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'ME') echo ' selected'; ?> >Maine</option>
                                        <option value="MD" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MD') echo ' selected'; ?> >Maryland</option>
                                        <option value="MA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MA') echo ' selected'; ?> >Massachusetts</option>
                                        <option value="MI" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MI') echo ' selected'; ?> >Michigan</option>
                                        <option value="MN" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MN') echo ' selected'; ?> >Minnesota</option>
                                        <option value="MS" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MS') echo ' selected'; ?> >Mississippi</option>
                                        <option value="MO" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MO') echo ' selected'; ?> >Missouri</option>
                                        <option value="MT" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MT') echo ' selected'; ?> >Montana</option>
                                        <option value="NE" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NE') echo ' selected'; ?> >Nebraska</option>
                                        <option value="NV" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NV') echo ' selected'; ?> >Nevada</option>
                                        <option value="NH" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NH') echo ' selected'; ?> >New Hampshire</option>
                                        <option value="NJ" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NJ') echo ' selected'; ?> >New Jersey</option>
                                        <option value="NM" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NM') echo ' selected'; ?> >New Mexico</option>
                                        <option value="NY" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NY') echo ' selected'; ?> >New York</option>
                                        <option value="NC" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NC') echo ' selected'; ?> >North Carolina</option>
                                        <option value="ND" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'ND') echo ' selected'; ?> >North Dakota</option>
                                        <option value="OH" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'OH') echo ' selected'; ?> >Ohio</option>
                                        <option value="OK" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'OK') echo ' selected'; ?> >Oklahoma</option>
                                        <option value="OR" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'OR') echo ' selected'; ?> >Oregon</option>
                                        <option value="PA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'PA') echo ' selected'; ?> >Pennsylvania</option>
                                        <option value="RI" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'RI') echo ' selected'; ?> >Rhode Island</option>
                                        <option value="SC" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'SC') echo ' selected'; ?> >South Carolina</option>
                                        <option value="SD" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'SD') echo ' selected'; ?> >South Dakota</option>
                                        <option value="TN" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'TN') echo ' selected'; ?> >Tennessee</option>
                                        <option value="TX" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'TX') echo ' selected'; ?> >Texas</option>
                                        <option value="UT" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'UT') echo ' selected'; ?> >Utah</option>
                                        <option value="VT" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'VT') echo ' selected'; ?> >Vermont</option>
                                        <option value="VA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'VA') echo ' selected'; ?> >Virginia</option>
                                        <option value="WA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'WA') echo ' selected'; ?> >Washington</option>
                                        <option value="WV" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'WV') echo ' selected'; ?> >West Virginia</option>
                                        <option value="WI" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'WI') echo ' selected'; ?> >Wisconsin</option>
                                        <option value="WY" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'WY') echo ' selected'; ?> >Wyoming</option>
                                    </select>

                                </div>
                            </div>
                            <!-- postal-code input-->
                            <div class="form-group">
                                <label class="control-label">Postal Code / ZIP</label>

                                <div class="control-input ">

                                    <div class="formrequired">*</div>

                                    <input name="paypstcod" type="text" placeholder="Postcode"
                                           class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['paypstcod']; ?>"
                                           required
                                           data-parsley-requred-message="Postcode is mandatory."
                                           autocomplete="new-password">
                                </div>
                            </div>

                            <!-- country select -->
                            <div class="form-group">
                                <label class="control-label">Country</label>

                                <div class="control-input ">

                                    <div class="formrequired">*</div>

                                    <select id="paycountry" name="paycountry" class="form-control"required data-parsley-required-message="Country is mandatory.">

                                        <?php
                                        echo $TmpDel->getCountrySelect($shoppingcart->shoppingCart['customer']['paycoucod']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>

                                <div class="control-input ">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="delisbill"
                                                   value="1" <?php if ((isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['delisbill'] == 1) || !isset($shoppingcart->shoppingCart['customer'])) echo 'checked'; ?>>
                                            Delivery address is the same as
                                            billing address
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <fieldset id="delAdrDiv" style="display: none;">

                                <legend>Delivery Address</legend>

                                <div class="deliveryaddresses">

                                    <a href="#" class="showhide">Select Address</a>

                                    <ul>

                                    <?php

                                    $customerAddress = $PlaDao->select(NULL, 'DELADR', $loggedIn->pla_id, NULL, NULL, false);

                                    $tableLength = count($customerAddress);
                                    for ($i = 0; $i < $tableLength; ++$i) {

                                        $adrObj = new stdClass();
                                        $adrObj->planam = $customerAddress[$i]['planam'];
                                        $adrObj->adr1   = $customerAddress[$i]['adr1'];
                                        $adrObj->adr2   = $customerAddress[$i]['adr2'];
                                        $adrObj->adr3   = $customerAddress[$i]['adr3'];
                                        $adrObj->adr4   = $customerAddress[$i]['adr4'];
                                        $adrObj->pstcod = $customerAddress[$i]['pstcod'];
                                        $adrObj->coucod = $customerAddress[$i]['coucod'];

                                        ?>


                                        <li>

                                            <div class="addressrecord">

                                                <?php
                                                $displayAddress = '<p><a href="" class="selectAddressLink" ';

                                                $displayAddress .= ' data-planam="' . htmlentities( $customerAddress[$i]['planam'] ) . '" ';
                                                $displayAddress .= ' data-adr1="' . htmlentities( $customerAddress[$i]['adr1'] ) . '" ';
                                                $displayAddress .= ' data-adr2="' . htmlentities( $customerAddress[$i]['adr2'] ) . '" ';
                                                $displayAddress .= ' data-adr3="' . htmlentities( $customerAddress[$i]['adr3'] ) . '" ';
                                                $displayAddress .= ' data-adr4="' . htmlentities( $customerAddress[$i]['adr4'] ) . '" ';
                                                $displayAddress .= ' data-pstcod="' . htmlentities( $customerAddress[$i]['pstcod'] ) . '" ';


                                                $displayAddress .= '>'.$customerAddress[$i]['planam'] . '<br>';


                                                $displayAddress .= '<small style="font-size: 10px; line-height: 1em;">' . $customerAddress[$i]['adr1'];
                                                $displayAddress .= (!empty($customerAddress[$i]['adr2'])) ? '<br>' . $customerAddress[$i]['adr2'] : '';
                                                $displayAddress .= (!empty($customerAddress[$i]['adr3'])) ? '<br>' . $customerAddress[$i]['adr3'] : '';
                                                $displayAddress .= (!empty($customerAddress[$i]['adr4'])) ? '<br>' . $customerAddress[$i]['adr4'] : '';
                                                $displayAddress .= (!empty($customerAddress[$i]['pstcod'])) ? '<br>' . $customerAddress[$i]['pstcod'] : '';
                                                $displayAddress .= '</small></a></p>';

                                                echo $displayAddress;
                                                ?>

                                            </div>

                                        </li>

                                        <?php
                                    }
                                    ?>

                                    </ul>

                                </div>


                                <div class="form-group">
                                    <label class="control-label">Delivery Company</label>

                                    <div class="control-input ">
                                        <input name="cusnam" type="text" placeholder="Company Name" class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['cusnam']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">F.A.O.</label>
                                    <div class="control-input ">
                                        <input name="fao" type="text" placeholder="Delivery To." class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer']['ordfao'])) echo $shoppingcart->shoppingCart['customer']['ordfao']; ?>">
                                    </div>
                                </div>

                                <!-- address-line1 input-->
                                <div class="form-group">
                                    <label class="control-label">Address Line 1</label>

                                    <div class="control-input ">

                                        <div class="formrequired">*</div>

                                        <input name="adr1" type="text" placeholder="Address Line 1" class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['adr1']; ?>"
                                               required
                                               data-parsley-required-message="Address line 1 is mandatory.">
                                    </div>
                                </div>
                                <!-- address-line2 input-->
                                <div class="form-group">
                                    <label class="control-label">Address Line 2</label>

                                    <div class="control-input ">
                                        <input name="adr2" type="text" placeholder="Address Line 2"
                                               class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['adr2']; ?>">
                                    </div>
                                </div>
                                <!-- city input-->
                                <div class="form-group">
                                    <label class="control-label">City / Town</label>

                                    <div class="control-input ">

                                        <div class="formrequired">*</div>

                                        <input name="adr3" type="text" placeholder="City / Town" class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['adr3']; ?>"
                                               required
                                               data-parsley-required-message="Town is mandatory.">
                                    </div>
                                </div>

                                <div class="form-group" id="delCountyDiv">
                                    <label class="control-label">County</label>

                                    <div class="control-input ">
                                        <input name="adr4" type="text" placeholder="County"
                                               class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['adr4']; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Country</label>

                                    <div class="control-input">

                                        <div class="formrequired">*</div>

                                        <select id="country" name="country" class="form-control">

                                            <?php
                                            echo $TmpDel->getCountrySelect($shoppingcart->shoppingCart['customer']['coucod']);
                                            ?>

                                        </select>

                                        <input type="hidden" name="delcoucod">

                                    </div>
                                </div>

                                <div class="form-group" id="delStateDiv">
                                    <label class="control-label">State</label>

                                    <div class="control-input">

                                        <select name="adr4state" class="form-control">
                                            <option value="AL" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'AL') echo ' selected'; ?> >Alabama</option>
                                            <option value="AK" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'AK') echo ' selected'; ?> >Alaska</option>
                                            <option value="AZ" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'AZ') echo ' selected'; ?> >Arizona</option>
                                            <option value="AR" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'AR') echo ' selected'; ?> >Arkansas</option>
                                            <option value="CA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'CA') echo ' selected'; ?> >California</option>
                                            <option value="CO" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'CO') echo ' selected'; ?> >Colorado</option>
                                            <option value="CT" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'CT') echo ' selected'; ?> >Connecticut</option>
                                            <option value="DE" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'DE') echo ' selected'; ?> >Delaware</option>
                                            <option value="DC" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'DC') echo ' selected'; ?> >District of Columbia</option>
                                            <option value="FL" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'FL') echo ' selected'; ?> >Florida</option>
                                            <option value="GA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'GA') echo ' selected'; ?> >Georgia</option>
                                            <option value="HI" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'HI') echo ' selected'; ?> >Hawaii</option>
                                            <option value="ID" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'ID') echo ' selected'; ?> >Idaho</option>
                                            <option value="IL" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'IL') echo ' selected'; ?> >Illinois</option>
                                            <option value="IN" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'IN') echo ' selected'; ?> >Indiana</option>
                                            <option value="IA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'IA') echo ' selected'; ?> >Iowa</option>
                                            <option value="KS" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'KS') echo ' selected'; ?> >Kansas</option>
                                            <option value="KY" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'KY') echo ' selected'; ?> >Kentucky</option>
                                            <option value="LA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'LA') echo ' selected'; ?> >Louisiana</option>
                                            <option value="ME" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'ME') echo ' selected'; ?> >Maine</option>
                                            <option value="MD" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MD') echo ' selected'; ?> >Maryland</option>
                                            <option value="MA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MA') echo ' selected'; ?> >Massachusetts</option>
                                            <option value="MI" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MI') echo ' selected'; ?> >Michigan</option>
                                            <option value="MN" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MN') echo ' selected'; ?> >Minnesota</option>
                                            <option value="MS" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MS') echo ' selected'; ?> >Mississippi</option>
                                            <option value="MO" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MO') echo ' selected'; ?> >Missouri</option>
                                            <option value="MT" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'MT') echo ' selected'; ?> >Montana</option>
                                            <option value="NE" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NE') echo ' selected'; ?> >Nebraska</option>
                                            <option value="NV" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NV') echo ' selected'; ?> >Nevada</option>
                                            <option value="NH" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NH') echo ' selected'; ?> >New Hampshire</option>
                                            <option value="NJ" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NJ') echo ' selected'; ?> >New Jersey</option>
                                            <option value="NM" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NM') echo ' selected'; ?> >New Mexico</option>
                                            <option value="NY" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NY') echo ' selected'; ?> >New York</option>
                                            <option value="NC" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'NC') echo ' selected'; ?> >North Carolina</option>
                                            <option value="ND" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'ND') echo ' selected'; ?> >North Dakota</option>
                                            <option value="OH" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'OH') echo ' selected'; ?> >Ohio</option>
                                            <option value="OK" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'OK') echo ' selected'; ?> >Oklahoma</option>
                                            <option value="OR" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'OR') echo ' selected'; ?> >Oregon</option>
                                            <option value="PA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'PA') echo ' selected'; ?> >Pennsylvania</option>
                                            <option value="RI" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'RI') echo ' selected'; ?> >Rhode Island</option>
                                            <option value="SC" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'SC') echo ' selected'; ?> >South Carolina</option>
                                            <option value="SD" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'SD') echo ' selected'; ?> >South Dakota</option>
                                            <option value="TN" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'TN') echo ' selected'; ?> >Tennessee</option>
                                            <option value="TX" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'TX') echo ' selected'; ?> >Texas</option>
                                            <option value="UT" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'UT') echo ' selected'; ?> >Utah</option>
                                            <option value="VT" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'VT') echo ' selected'; ?> >Vermont</option>
                                            <option value="VA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'VA') echo ' selected'; ?> >Virginia</option>
                                            <option value="WA" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'WA') echo ' selected'; ?> >Washington</option>
                                            <option value="WV" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'WV') echo ' selected'; ?> >West Virginia</option>
                                            <option value="WI" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'WI') echo ' selected'; ?> >Wisconsin</option>
                                            <option value="WY" <?php if (isset($shoppingcart->shoppingCart['customer']) && $shoppingcart->shoppingCart['customer']['adr4'] == 'WY') echo ' selected'; ?> >Wyoming</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Postal Code / ZIP</label>

                                    <div class="control-input ">

                                        <div class="formrequired">*</div>

                                        <input name="pstcod" type="text" placeholder="Postcode"
                                               class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['pstcod']; ?>"
                                               required
                                               data-parsley-required-message="Postcode mandatory.">
                                    </div>
                                </div>
                            </fieldset>
                            <br/><br/>
                            <div class="form-group">
                                <label class="control-label" style="width: 98%">Terms & Conditions</label>
                                <div class="control-input">
                                    <div class="formrequired">*</div>
                                    <div class="payment-indent">
                                        <input type="checkbox" required id="tandcs"  class="form-control input-block-level " name="tandcs" data-parsley-required-message="<br/>Acceptance is mandatory." />
                                        <label for="tandcs">
                                            By checking this box you are agreeing to the Terms and Conditions as laid out  <a href="/terms-and-conditions">here</a>.
                                        </label>

                                    </div>
                                </div>
                            </div>
                            <br/><br/>
                            <div class="form-group">
                                <label class="control-label" style="width: 98%">Contact Permission</label>
                                <div class="control-input">
                                    <div class="payment-indent">
                                        <input type="checkbox" class="form-control input-block-level " id="marketing" name="marketing" />
                                        <label for="marketing">
                                            From time to time Tailby would like to send you marketing emails relating to our products, if you would like to receive these - please, tick this box.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row" style="font-size: 0.8em; display: none;">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>How did you hear about us?</label>
                                <div class="controls">

                                    <select name="hearabout" class="form-control input-block-level ">
                                        <option value="" <?php echo (isset($shoppingcart->shoppingCart['hearabout']) && $shoppingcart->shoppingCart['hearabout'] == '') ? 'selected' : ''; ?>></option>
                                        <option value="Internet" <?php echo (isset($shoppingcart->shoppingCart['hearabout']) && $shoppingcart->shoppingCart['hearabout'] == 'Internet') ? 'selected' : ''; ?>>Internet</option>
                                        <option value="Recommendation" <?php echo (isset($shoppingcart->shoppingCart['hearabout']) && $shoppingcart->shoppingCart['hearabout'] == 'Recommendation') ? 'selected' : ''; ?>>Recommendation</option>
                                        <option value="Magazine" <?php echo (isset($shoppingcart->shoppingCart['hearabout']) && $shoppingcart->shoppingCart['hearabout'] == 'Magazine') ? 'selected' : ''; ?>>Magazine (please provide below)</option>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group" id="hearAboutInfo" style="display: none">
                                <label>Which Magazine</label>
                                <div class="controls">

                                    <input type="text" name="hearaboutname" class="form-control input-block-level" value="<?php echo (isset($shoppingcart->shoppingCart['hearaboutname'])) ? $shoppingcart->shoppingCart['hearaboutname'] : ''; ?>">

                                </div>
                            </div>

                            <script>

                                $(function(){

                                    $('[name="hearabout"]').change(function(){

                                        if ($(this).val() == 'Magazine') {
                                            $('#hearAboutInfo').show();
                                        } else {
                                            $('#hearAboutInfo').hide();
                                        }

                                    });
                                    $('[name="hearabout"]').change();

                                })

                            </script>

                        </div>
                        <div class="col-sm-3">



                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Special Instructions</label>
                                <div class="controls">
                                    <textarea class="form-control input-block-level " name="spcins" rows="6"><?php echo (isset($shoppingcart->shoppingCart['specialinstructions'])) ? $shoppingcart->shoppingCart['specialinstructions'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Delivery Instructions</label>
                                <div class="controls">
                                    <textarea class="form-control input-block-level " name="delins" rows="6"><?php echo (isset($shoppingcart->shoppingCart['deliveryinstructions'])) ? $shoppingcart->shoppingCart['deliveryinstructions'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-actions">
                                <button id="submitFormBtn" class="btn btn-primary pull-right">Submit <i class="fa fa-chevron-right"></i></button>
                                <a href="checkout/shoppingcart" class="btn"><i class="fa fa-chevron-left"></i> Cancel</a>
                            </div>
                            <div id="map_country_lookup" style="height: 0px; width:0px; border: none;"></div>
                        </div>
                    </div>

                </form>

            </div>

        </div>

        <script>

            $(function () {

                window.ParsleyConfig = {excluded: "input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden"};

                $('#checkoutForm').parsley();

                $('[name="delisbill"]').change(function () {

                    if ($(this).prop('checked')) {
                        $('#delAdrDiv').hide();
                    } else {
                        $('#delAdrDiv').show();
                    }

                });

                $('[name="paycountry"]').change(function(){

                    if ($(this).val() == 'US') {
                        $('#stateDiv').show();
                        $('#countyDiv').hide();
                    } else {
                        $('#stateDiv').hide();
                        $('#countyDiv').show();
                    }

                });

                $('[name="country"]').change(function(){

                    if ($(this).val() == 'US') {
                        $('#delStateDiv').show();
                        $('#delCountyDiv').hide();
                    } else {
                        $('#delStateDiv').hide();
                        $('#delCountyDiv').show();
                    }

                })

                $('[name="delisbill"]').change();
                $('[name="paycountry"]').change();
                $('[name="country"]').change();


                $('.selectAddressLink').click(function(e){
                    e.preventDefault();

                    $('[name="cusnam"]').val( $(this).data('planam') );
                    $('[name="adr1"]').val( $(this).data('adr1') );
                    $('[name="adr2"]').val( $(this).data('adr2') );
                    $('[name="adr3"]').val( $(this).data('adr3') );
                    $('[name="adr4"]').val( $(this).data('adr4') );
                    $('[name="pstcod"]').val( $(this).data('pstcod') );
                    $('[name="coucod"]').val( $(this).data('coucod') );

                    $('.showhide').click();

                });

                $('.showhide').click(function(e){

                    e.preventDefault();

                    $(this).toggleClass('active').blur().next().slideToggle();

                });


            });

        </script>

        <script>

//            var checkoutForm;
//
//            $(function () {
//
//                checkoutForm = $('#checkoutForm');
//
//                checkoutForm.submit(function (e) {
//
//                });
//
//                window.ParsleyConfig = {excluded: "input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden"};
//
//                checkoutForm.parsley();
//
//            });

        </script>

    </div>
</div>
