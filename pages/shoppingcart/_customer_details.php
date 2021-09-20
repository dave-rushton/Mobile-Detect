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

        <?php
        if (!isset($loggedIn->planam)) {
            ?>

            <div class="row">
                <div class="col-sm-12">

                    <div class="alert alert-info">

                        <h4>Already a customer...?</h4>

                        <form action="<?php echo $patchworks->webRoot; ?>pages/account/account_login.php" method="post"
                              class="form-vertical" id="loginForm">
                            <div class="pw-form">


                                <input type="hidden" value="http://" name="httpChk">
                                <input type="hidden" name="fwdurl" value="checkout/details">

                                <?php
                                if (isset($_GET['error'])) {

                                    ?>

                                    <div class="alert alert-danger">
                                        <p><strong>ERROR:</strong> Your login details were not found.</p>

                                        <p>Please try again</p>
                                    </div>

                                    <?php

                                }
                                ?>

                                <div class="row">
                                    <div class="col-sm-6">

                                        <div class="control-group form-group">
                                            <div class="controls">
                                                <label>Your Email:</label>
                                                <input type="text" name="loginEmail" class="form-control" required
                                                       data-validation-required-message="Please enter your email.">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">

                                        <div class="control-group form-group">
                                            <div class="controls">
                                                <label>Password:</label>
                                                <input type="password" name="loginPassword" class="form-control"
                                                       required
                                                       data-validation-required-message="Please enter your password.">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-actions text-right">
                                    <button type="submit" class="btn btn-primary">Login <span><i
                                                class="fa fa-sign-in"></i></span></button>
                                </div>

                            </div>
                        </form>

                    </div>

                </div>
            </div>

            <?php
        }
        ?>


        <div class="row">

            <div class="col-sm-12">

                <form id="checkoutForm" action="shoppingcart/updatedetails/0" role="form" autocomplete="off">

                    <div class="row">
                        <div class="col-sm-6">

                            <h2 class="styled">Customer Details</h2>
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

                                    <input name="cussna" type="text" placeholder="Surame" class="form-control"
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

                            <div class="alert alert-info">

                                <p>
                                    <strong>Contact Permission:</strong>
                                    <br>
                                      </p>

                                <div class="radio">
                                    <label>
                                        <input type="radio" name="nomail" value="1" <?php if (isset($shoppingcart->shoppingCart['customer']['nomail']) && $shoppingcart->shoppingCart['customer']['nomail'] == 1) echo 'checked'; ?> required>
                                        Yes please, I'd like to hear about offers and services
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" name="nomail" value="0" <?php if (isset($shoppingcart->shoppingCart['customer']['nomail']) && $shoppingcart->shoppingCart['customer']['nomail'] == 0) echo 'checked'; ?> required>
                                        No thanks, I don't want to hear about offers and services
                                    </label>
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

                            <style>

                                input[name="postcodelookup"] {

                                    text-transform: uppercase;

                                }

                                ::-webkit-input-placeholder {
                                    /* Chrome/Opera/Safari */
                                    color: #ffffff;
                                }

                                ::-moz-placeholder {
                                    /* Firefox 19+ */
                                    color: #ffffff;
                                }

                                :-ms-input-placeholder {
                                    /* IE 10+ */
                                    color: #ffffff;
                                }

                                :-moz-placeholder {
                                    /* Firefox 18- */
                                    color: #ffffff;
                                }

                                #postcode_lookup {
                                    display: none;
                                }

                            </style>

                            <script>

                                var typeDelay;

                                function valid_postcode(postcode) {
                                    postcode = postcode.replace(/\s/g, "");
                                    var regex = /[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i;
                                    return regex.test(postcode);
                                }

                                function getAddress() {

                                    clearTimeout(typeDelay);

                                    if (valid_postcode($('[name="postcodelookup"]').val())) {

                                        $.ajax({
                                            url: 'postcodeanywhere/testpostcode.php',
                                            data: 'pstcod=' + $('[name="postcodelookup"]').val(),
                                            type: 'GET',
                                            async: false,
                                            success: function (data) {

                                                var addressLookup = JSON.parse(data);

                                                $('#adr1').val(addressLookup.adr1);
                                                $('#adr2').val(addressLookup.adr2);
                                                $('#adr3').val(addressLookup.adr3);
                                                $('#adr4').val(addressLookup.adr4);
                                                $('#town').val(addressLookup.town);
                                                $('#county').val(addressLookup.county);
                                                $('#postcode').val(addressLookup.postcode);

                                                $('#postcode_lookup').show();

                                                $('#housenumber').val('').focus();

                                            },
                                            error: function (x, e) {

                                            }
                                        });

                                    } else {

                                        $('#postcode_lookup').hide();

                                    }

                                }

                                $(function () {

                                    $('[name="postcodelookup"]').bind('change keyup paste', function () {

                                        clearTimeout(typeDelay);
                                        typeDelay = setTimeout(getAddress, 300);

                                    });

                                });

                            </script>



                            <div class="row" id="searchDetailsDiv">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label">Search By Postcode </label>

                                        <div class="control-input ">
                                            <input name="postcodelookup" required type="text" placeholder="Please enter your postcode"
                                                   class="form-control"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="postcode_lookup">

                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label class="control-label">House No/Name:</label>
                                        <input name="housenumber" required id="housenumber"
                                               data-validation-required-message="Please enter the house number/name."
                                               placeholder="House No./Name" type="text">
                                    </div>
                                </div>

                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label class="control-label">Address Line 1:</label>
                                        <input name="adr1" id="adr1" required data-validation-required-message="Please enter the address."
                                               placeholder="Address Line 1" type="text">
                                    </div>
                                </div>

                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label class="control-label">Address Line 2:</label>
                                        <input name="adr2" id="adr2" data-validation-required-message="Please enter the address."
                                               placeholder="Address Line 2" type="text">
                                    </div>
                                </div>

                                <div class="hide">

                                    <div class="control-group form-group">
                                        <div class="controls">
                                            <label class="control-label">Address Line 3:</label>
                                            <input name="adr3" id="adr3"
                                                   data-validation-required-message="Please enter the address."
                                                   placeholder="Address Line 3" type="text">
                                        </div>
                                    </div>

                                    <div class="control-group form-group">
                                        <div class="controls">
                                            <label class="control-label">Address Line 4:</label>
                                            <input name="adr4" id="adr4"
                                                   data-validation-required-message="Please enter the address."
                                                   placeholder="Address Line 4" type="text">
                                        </div>
                                    </div>

                                </div>

                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label class="control-label">Town:</label>
                                        <input name="town" id="town" data-validation-required-message="Please enter the town."
                                               placeholder="Town"
                                               type="text">
                                    </div>
                                </div>

                                <div class="hide">

                                    <div class="control-group form-group">
                                        <div class="controls">
                                            <label class="control-label">County:</label>
                                            <input name="county" id="county"
                                                   data-validation-required-message="Please enter the county."
                                                   placeholder="County" type="text">
                                        </div>
                                    </div>

                                </div>

                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label class="control-label">Postcode:</label>
                                        <input name="postcode" id="postcode"
                                               required
                                               data-validation-required-message="Please enter the postcode."
                                               placeholder="Postcode" type="text">
                                    </div>
                                </div>

                            </div>















                            <h4>Billing Address</h4>
                            <hr>

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

                            <!-- address-line1 input-->
                            <div class="form-group">
                                <label class="control-label">Address Line 1</label>

                                <div class="control-input ">

                                    <div class="formrequired">*</div>

                                    <input name="payadr1" type="text" placeholder="address line 1"
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
                                    <input name="payadr2" type="text" placeholder="address line 2"
                                           class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['payadr2']; ?>">
                                </div>
                            </div>


                            <!-- city input-->
                            <div class="form-group">
                                <label class="control-label">City / Town</label>

                                <div class="control-input ">

                                    <div class="formrequired">*</div>

                                    <input name="payadr3" type="text" placeholder="city / town" class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['payadr3']; ?>"
                                           required
                                           data-parsley-required-message="Address town is mandatory.">
                                </div>
                            </div>

                            <div class="form-group" id="countyDiv">
                                <label class="control-label">County</label>

                                <div class="control-input ">
                                    <input name="payadr4" type="text" placeholder="county"
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

                                    <input name="paypstcod" type="text" placeholder="postcode"
                                           class="form-control"
                                           value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['paypstcod']; ?>"
                                           required
                                           autocomplete="false"
                                           data-parsley-required-message="Postcode is mandatory.">
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

                                        <input name="adr1" type="text" placeholder="address line 1" class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['adr1']; ?>"
                                               required
                                               data-parsley-required-message="Address line 1 is mandatory.">
                                    </div>
                                </div>
                                <!-- address-line2 input-->
                                <div class="form-group">
                                    <label class="control-label">Address Line 2</label>

                                    <div class="control-input ">
                                        <input name="adr2" type="text" placeholder="address line 2"
                                               class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['adr2']; ?>">
                                    </div>
                                </div>
                                <!-- city input-->
                                <div class="form-group">
                                    <label class="control-label">City / Town</label>

                                    <div class="control-input ">

                                        <div class="formrequired">*</div>

                                        <input name="adr3" type="text" placeholder="city / town" class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['adr3']; ?>"
                                               required
                                               data-parsley-required-message="Town is mandatory.">
                                    </div>
                                </div>

                                <div class="form-group" id="delCountyDiv">
                                    <label class="control-label">County</label>

                                    <div class="control-input ">
                                        <input name="adr4" type="text" placeholder="county"
                                               class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['adr4']; ?>">
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

                                        <input name="pstcod" type="text" placeholder="postcode"
                                               class="form-control"
                                               value="<?php if (isset($shoppingcart->shoppingCart['customer'])) echo $shoppingcart->shoppingCart['customer']['pstcod']; ?>"
                                               required
                                               data-parsley-required-message="Postcode mandatory.">
                                    </div>
                                </div>

                            </fieldset>


                        </div>
                    </div>

                    <hr>

                    <div class="row" style="font-size: 0.8em;">
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

                            <div class="form-group">
                                <div class="control-input ">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="resize" value="1" <?php echo (isset($shoppingcart->shoppingCart['resizeinstructions']) && $shoppingcart->shoppingCart['resizeinstructions'] == 1) ? 'checked' : ''; ?>>
                                            If you have ordered more than two cones of the same yarn, in the same colour, please let us know if you would prefer us to supply one cone to the same weight.
                                        </label>
                                    </div>

                                    <label>Will your yarn be used for:</label>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="wooltype" value="H" <?php echo ((isset($shoppingcart->shoppingCart['wooltype']) && $shoppingcart->shoppingCart['wooltype'] == "H") || !isset($shoppingcart->shoppingCart['wooltype'])) ? 'checked' : ''; ?>>
                                            Hand Knitting
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="wooltype" value="M" <?php echo (isset($shoppingcart->shoppingCart['wooltype']) && $shoppingcart->shoppingCart['wooltype'] == "M") ? 'checked' : ''; ?>>
                                            Machine Knitting
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="wooltype" value="C" <?php echo (isset($shoppingcart->shoppingCart['wooltype']) && $shoppingcart->shoppingCart['wooltype'] == "C") ? 'checked' : ''; ?>>
                                            Crochet
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="wooltype" value="B" <?php echo (isset($shoppingcart->shoppingCart['wooltype']) && $shoppingcart->shoppingCart['wooltype'] == "B") ? 'checked' : ''; ?>>
                                            Both
                                        </label>

                                    </div>

                                </div>
                            </div>

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
                            <hr>
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

            });

        </script>

        <script>

            var checkoutForm;

            $(function () {

                checkoutForm = $('#checkoutForm');

                checkoutForm.submit(function (e) {

//                    var nameCheck;
//                    nameCheck = $('[name="payfao"]', checkoutForm).val().split(" ");
//
//                    if (nameCheck.length < 2) {
//                        alert('Please enter your full name, first name AND surname');
//                        return false;
//                    }
//
//
//                    if ( !$('[name="delisbill"]').prop('checked') ) {
//                        nameCheck = $('[name="fao"]', checkoutForm).val().split(" ");
//
//                        if (nameCheck.length < 2) {
//                            alert('Please enter the full delivery name, first name AND surname');
//                            return false;
//                        }
//
//                    }

                });

                window.ParsleyConfig = {excluded: "input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden"};

                checkoutForm.parsley();

            });

        </script>

    </div>
</div>