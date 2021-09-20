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

?>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-2">

                <div class="box">

                    <?php include('account.menu.php'); ?>

                </div>

            </div>
            <div class="col-sm-10">

                <h2 class="heading">Your Account</h2>

                <?php
                if (isset($_GET['update']) && $_GET['update'] == 'ok') {
                    ?>

                    <div class="alert alert-info">
                        <p><strong>Success</strong></p>

                        <p>Your account details have been updated</p>
                    </div>

                    <?php
                }
                ?>

                <form action="pages/account/account_update.php" method="post" class="form-vertical" id="loginForm"
                      data-parsley-validate>

                    <input type="hidden" value="http://" name="httpChk">
                    <input type="hidden" value="user-account" name="fwdurl">


                    <div class="row">
                        <div class="col-sm-6">

                            <hr>
                            <h5>Your Details</h5>
                            <hr>

                            <div class="form-group">
                                <label class="control-label">Company Name</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->comnam; ?>"
                                           class="form-control input-block-level" name="comnam">
                                </div>
                            </div>

                            <div class="form-group hide">
                                <label class="control-label">Your Name</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->planam; ?>"
                                           class="form-control input-block-level required" name="planam">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Title</label>

                                <div class="control-input ">

                                    <select name="plattl" class="form-control" required>
                                        <option <?php if (isset($loggedIn->planam) && $loggedIn->plattl == '') echo 'selected'; ?> value="">N/A</option>
                                        <option <?php if (isset($loggedIn->planam) && $loggedIn->plattl == 'Mr') echo 'selected'; ?> value="Mr">Mr</option>
                                        <option <?php if (isset($loggedIn->planam) && $loggedIn->plattl == 'Mrs') echo 'selected'; ?> value="Mrs">Mrs</option>
                                        <option <?php if (isset($loggedIn->planam) && $loggedIn->plattl == 'Ms') echo 'selected'; ?> value="Ms">Ms</option>
                                        <option <?php if (isset($loggedIn->planam) && $loggedIn->plattl == 'Miss') echo 'selected'; ?> value="Miss">Miss</option>
                                        <option <?php if (isset($loggedIn->planam) && $loggedIn->plattl == 'Dr') echo 'selected'; ?> value="Dr">Dr</option>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">First Name</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->plafna; ?>"
                                           class="form-control input-block-level required" name="plafna">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Surname</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->plasna; ?>"
                                           class="form-control input-block-level required" name="plasna">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Your Email</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->plaema; ?>"
                                           class="form-control input-block-level required" name="plaema">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Your Mobile</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->plamob; ?>"
                                           class="form-control input-block-level" name="plamob">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Your Home Telephone</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->platel; ?>"
                                           class="form-control input-block-level" name="platel">
                                </div>
                            </div>

                            <hr/>

                            <div class="alert alert-warning">
                                <p>These boxes are left blank for security reasons<br>
                                    Only update this section if you wish to update your password</p>

                                <div class="form-group">
                                    <label class="control-label">Password</label>

                                    <div class="controls">
                                        <input type="password" value="" class="form-control input-block-level"
                                               name="paswrd">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Confirm Password</label>

                                    <div class="controls">
                                        <input type="password" value="" class="form-control input-block-level"
                                               id="cnfpwd">
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-sm-6">

                            <hr>
                            <h5>Billing Address</h5>
                            <hr>

                            <div class="form-group">
                                <label class="control-label">Address Line 1</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->adr1; ?>"
                                           class="form-control input-block-level required" name="adr1">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Address Line 2</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->adr2; ?>"
                                           class="form-control input-block-level" name="adr2">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Town / City</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->adr3; ?>"
                                           class="form-control input-block-level" name="adr3">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">State / Province / Region</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->adr4; ?>"
                                           class="form-control input-block-level" name="adr4">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Postal Code / ZIP</label>

                                <div class="controls">
                                    <input type="text" value="<?php echo $loggedIn->pstcod; ?>"
                                           class="form-control input-block-level required" name="pstcod">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Country</label>

                                <div class="control-input ">
                                    <select id="country" name="coucod" class="form-control">

                                        <option value=""   <?php echo ($loggedIn->coucod == ''  ) ? 'selected="selected"' : ''; ?>>(please select a country)</option>
                                        <option value="GB" <?php echo ($loggedIn->coucod == 'GB') ? 'selected="selected"' : ''; ?>>United Kingdom</option>
                                        <option value="AF" <?php echo ($loggedIn->coucod == 'AF') ? 'selected="selected"' : ''; ?>>Afghanistan</option>
                                        <option value="AL" <?php echo ($loggedIn->coucod == 'AL') ? 'selected="selected"' : ''; ?>>Albania</option>
                                        <option value="DZ" <?php echo ($loggedIn->coucod == 'DZ') ? 'selected="selected"' : ''; ?>>Algeria</option>
                                        <option value="AS" <?php echo ($loggedIn->coucod == 'AS') ? 'selected="selected"' : ''; ?>>American Samoa</option>
                                        <option value="AD" <?php echo ($loggedIn->coucod == 'AD') ? 'selected="selected"' : ''; ?>>Andorra</option>
                                        <option value="AO" <?php echo ($loggedIn->coucod == 'AO') ? 'selected="selected"' : ''; ?>>Angola</option>
                                        <option value="AI" <?php echo ($loggedIn->coucod == 'AI') ? 'selected="selected"' : ''; ?>>Anguilla</option>
                                        <option value="AQ" <?php echo ($loggedIn->coucod == 'AQ') ? 'selected="selected"' : ''; ?>>Antarctica</option>
                                        <option value="AG" <?php echo ($loggedIn->coucod == 'AG') ? 'selected="selected"' : ''; ?>>Antigua and Barbuda</option>
                                        <option value="AR" <?php echo ($loggedIn->coucod == 'AR') ? 'selected="selected"' : ''; ?>>Argentina</option>
                                        <option value="AM" <?php echo ($loggedIn->coucod == 'AM') ? 'selected="selected"' : ''; ?>>Armenia</option>
                                        <option value="AW" <?php echo ($loggedIn->coucod == 'AW') ? 'selected="selected"' : ''; ?>>Aruba</option>
                                        <option value="AU" <?php echo ($loggedIn->coucod == 'AU') ? 'selected="selected"' : ''; ?>>Australia</option>
                                        <option value="AT" <?php echo ($loggedIn->coucod == 'AT') ? 'selected="selected"' : ''; ?>>Austria</option>
                                        <option value="AZ" <?php echo ($loggedIn->coucod == 'AZ') ? 'selected="selected"' : ''; ?>>Azerbaijan</option>
                                        <option value="BS" <?php echo ($loggedIn->coucod == 'BS') ? 'selected="selected"' : ''; ?>>Bahamas</option>
                                        <option value="BH" <?php echo ($loggedIn->coucod == 'BH') ? 'selected="selected"' : ''; ?>>Bahrain</option>
                                        <option value="BD" <?php echo ($loggedIn->coucod == 'BD') ? 'selected="selected"' : ''; ?>>Bangladesh</option>
                                        <option value="BB" <?php echo ($loggedIn->coucod == 'BB') ? 'selected="selected"' : ''; ?>>Barbados</option>
                                        <option value="BY" <?php echo ($loggedIn->coucod == 'BY') ? 'selected="selected"' : ''; ?>>Belarus</option>
                                        <option value="BE" <?php echo ($loggedIn->coucod == 'BE') ? 'selected="selected"' : ''; ?>>Belgium</option>
                                        <option value="BZ" <?php echo ($loggedIn->coucod == 'BZ') ? 'selected="selected"' : ''; ?>>Belize</option>
                                        <option value="BJ" <?php echo ($loggedIn->coucod == 'BJ') ? 'selected="selected"' : ''; ?>>Benin</option>
                                        <option value="BM" <?php echo ($loggedIn->coucod == 'BM') ? 'selected="selected"' : ''; ?>>Bermuda</option>
                                        <option value="BT" <?php echo ($loggedIn->coucod == 'BT') ? 'selected="selected"' : ''; ?>>Bhutan</option>
                                        <option value="BO" <?php echo ($loggedIn->coucod == 'BO') ? 'selected="selected"' : ''; ?>>Bolivia</option>
                                        <option value="BA" <?php echo ($loggedIn->coucod == 'BA') ? 'selected="selected"' : ''; ?>>Bosnia and Herzegowina</option>
                                        <option value="BW" <?php echo ($loggedIn->coucod == 'BW') ? 'selected="selected"' : ''; ?>>Botswana</option>
                                        <option value="BV" <?php echo ($loggedIn->coucod == 'BV') ? 'selected="selected"' : ''; ?>>Bouvet Island</option>
                                        <option value="BR" <?php echo ($loggedIn->coucod == 'BR') ? 'selected="selected"' : ''; ?>>Brazil</option>
                                        <option value="IO" <?php echo ($loggedIn->coucod == 'IO') ? 'selected="selected"' : ''; ?>>British Indian Ocean Territory</option>
                                        <option value="BN" <?php echo ($loggedIn->coucod == 'BN') ? 'selected="selected"' : ''; ?>>Brunei Darussalam</option>
                                        <option value="BG" <?php echo ($loggedIn->coucod == 'BG') ? 'selected="selected"' : ''; ?>>Bulgaria</option>
                                        <option value="BF" <?php echo ($loggedIn->coucod == 'BF') ? 'selected="selected"' : ''; ?>>Burkina Faso</option>
                                        <option value="BI" <?php echo ($loggedIn->coucod == 'BI') ? 'selected="selected"' : ''; ?>>Burundi</option>
                                        <option value="KH" <?php echo ($loggedIn->coucod == 'KH') ? 'selected="selected"' : ''; ?>>Cambodia</option>
                                        <option value="CM" <?php echo ($loggedIn->coucod == 'CM') ? 'selected="selected"' : ''; ?>>Cameroon</option>
                                        <option value="CA" <?php echo ($loggedIn->coucod == 'CA') ? 'selected="selected"' : ''; ?>>Canada</option>
                                        <option value="CV" <?php echo ($loggedIn->coucod == 'CV') ? 'selected="selected"' : ''; ?>>Cape Verde</option>
                                        <option value="KY" <?php echo ($loggedIn->coucod == 'KY') ? 'selected="selected"' : ''; ?>>Cayman Islands</option>
                                        <option value="CF" <?php echo ($loggedIn->coucod == 'CF') ? 'selected="selected"' : ''; ?>>Central African Republic</option>
                                        <option value="TD" <?php echo ($loggedIn->coucod == 'TD') ? 'selected="selected"' : ''; ?>>Chad</option>
                                        <option value="CL" <?php echo ($loggedIn->coucod == 'CL') ? 'selected="selected"' : ''; ?>>Chile</option>
                                        <option value="CN" <?php echo ($loggedIn->coucod == 'CN') ? 'selected="selected"' : ''; ?>>China</option>
                                        <option value="CX" <?php echo ($loggedIn->coucod == 'CX') ? 'selected="selected"' : ''; ?>>Christmas Island</option>
                                        <option value="CC" <?php echo ($loggedIn->coucod == 'CC') ? 'selected="selected"' : ''; ?>>Cocos (Keeling) Islands</option>
                                        <option value="CO" <?php echo ($loggedIn->coucod == 'CO') ? 'selected="selected"' : ''; ?>>Colombia</option>
                                        <option value="KM" <?php echo ($loggedIn->coucod == 'KM') ? 'selected="selected"' : ''; ?>>Comoros</option>
                                        <option value="CG" <?php echo ($loggedIn->coucod == 'CG') ? 'selected="selected"' : ''; ?>>Congo</option>
                                        <option value="CD" <?php echo ($loggedIn->coucod == 'CD') ? 'selected="selected"' : ''; ?>>Congo, the Democratic Republic of the</option>
                                        <option value="CK" <?php echo ($loggedIn->coucod == 'CK') ? 'selected="selected"' : ''; ?>>Cook Islands</option>
                                        <option value="CR" <?php echo ($loggedIn->coucod == 'CR') ? 'selected="selected"' : ''; ?>>Costa Rica</option>
                                        <option value="CI" <?php echo ($loggedIn->coucod == 'CI') ? 'selected="selected"' : ''; ?>>Cote d'Ivoire</option>
                                        <option value="HR" <?php echo ($loggedIn->coucod == 'HR') ? 'selected="selected"' : ''; ?>>Croatia (Hrvatska)</option>
                                        <option value="CU" <?php echo ($loggedIn->coucod == 'CU') ? 'selected="selected"' : ''; ?>>Cuba</option>
                                        <option value="CY" <?php echo ($loggedIn->coucod == 'CY') ? 'selected="selected"' : ''; ?>>Cyprus</option>
                                        <option value="CZ" <?php echo ($loggedIn->coucod == 'CZ') ? 'selected="selected"' : ''; ?>>Czech Republic</option>
                                        <option value="DK" <?php echo ($loggedIn->coucod == 'DK') ? 'selected="selected"' : ''; ?>>Denmark</option>
                                        <option value="DJ" <?php echo ($loggedIn->coucod == 'DJ') ? 'selected="selected"' : ''; ?>>Djibouti</option>
                                        <option value="DM" <?php echo ($loggedIn->coucod == 'DM') ? 'selected="selected"' : ''; ?>>Dominica</option>
                                        <option value="DO" <?php echo ($loggedIn->coucod == 'DO') ? 'selected="selected"' : ''; ?>>Dominican Republic</option>
                                        <option value="TP" <?php echo ($loggedIn->coucod == 'TP') ? 'selected="selected"' : ''; ?>>East Timor</option>
                                        <option value="EC" <?php echo ($loggedIn->coucod == 'EC') ? 'selected="selected"' : ''; ?>>Ecuador</option>
                                        <option value="EG" <?php echo ($loggedIn->coucod == 'EG') ? 'selected="selected"' : ''; ?>>Egypt</option>
                                        <option value="SV" <?php echo ($loggedIn->coucod == 'SV') ? 'selected="selected"' : ''; ?>>El Salvador</option>
                                        <option value="GQ" <?php echo ($loggedIn->coucod == 'GQ') ? 'selected="selected"' : ''; ?>>Equatorial Guinea</option>
                                        <option value="ER" <?php echo ($loggedIn->coucod == 'ER') ? 'selected="selected"' : ''; ?>>Eritrea</option>
                                        <option value="EE" <?php echo ($loggedIn->coucod == 'EE') ? 'selected="selected"' : ''; ?>>Estonia</option>
                                        <option value="ET" <?php echo ($loggedIn->coucod == 'ET') ? 'selected="selected"' : ''; ?>>Ethiopia</option>
                                        <option value="FK" <?php echo ($loggedIn->coucod == 'FK') ? 'selected="selected"' : ''; ?>>Falkland Islands (Malvinas)</option>
                                        <option value="FO" <?php echo ($loggedIn->coucod == 'FO') ? 'selected="selected"' : ''; ?>>Faroe Islands</option>
                                        <option value="FJ" <?php echo ($loggedIn->coucod == 'FJ') ? 'selected="selected"' : ''; ?>>Fiji</option>
                                        <option value="FI" <?php echo ($loggedIn->coucod == 'FI') ? 'selected="selected"' : ''; ?>>Finland</option>
                                        <option value="FR" <?php echo ($loggedIn->coucod == 'FR') ? 'selected="selected"' : ''; ?>>France</option>
                                        <option value="FX" <?php echo ($loggedIn->coucod == 'FX') ? 'selected="selected"' : ''; ?>>France, Metropolitan</option>
                                        <option value="GF" <?php echo ($loggedIn->coucod == 'GF') ? 'selected="selected"' : ''; ?>>French Guiana</option>
                                        <option value="PF" <?php echo ($loggedIn->coucod == 'PF') ? 'selected="selected"' : ''; ?>>French Polynesia</option>
                                        <option value="TF" <?php echo ($loggedIn->coucod == 'TF') ? 'selected="selected"' : ''; ?>>French Southern Territories</option>
                                        <option value="GA" <?php echo ($loggedIn->coucod == 'GA') ? 'selected="selected"' : ''; ?>>Gabon</option>
                                        <option value="GM" <?php echo ($loggedIn->coucod == 'GM') ? 'selected="selected"' : ''; ?>>Gambia</option>
                                        <option value="GE" <?php echo ($loggedIn->coucod == 'GE') ? 'selected="selected"' : ''; ?>>Georgia</option>
                                        <option value="DE" <?php echo ($loggedIn->coucod == 'DE') ? 'selected="selected"' : ''; ?>>Germany</option>
                                        <option value="GH" <?php echo ($loggedIn->coucod == 'GH') ? 'selected="selected"' : ''; ?>>Ghana</option>
                                        <option value="GI" <?php echo ($loggedIn->coucod == 'GI') ? 'selected="selected"' : ''; ?>>Gibraltar</option>
                                        <option value="GR" <?php echo ($loggedIn->coucod == 'GR') ? 'selected="selected"' : ''; ?>>Greece</option>
                                        <option value="GL" <?php echo ($loggedIn->coucod == 'GL') ? 'selected="selected"' : ''; ?>>Greenland</option>
                                        <option value="GD" <?php echo ($loggedIn->coucod == 'GD') ? 'selected="selected"' : ''; ?>>Grenada</option>
                                        <option value="GP" <?php echo ($loggedIn->coucod == 'GP') ? 'selected="selected"' : ''; ?>>Guadeloupe</option>
                                        <option value="GU" <?php echo ($loggedIn->coucod == 'GU') ? 'selected="selected"' : ''; ?>>Guam</option>
                                        <option value="GT" <?php echo ($loggedIn->coucod == 'GT') ? 'selected="selected"' : ''; ?>>Guatemala</option>
                                        <option value="GN" <?php echo ($loggedIn->coucod == 'GN') ? 'selected="selected"' : ''; ?>>Guinea</option>
                                        <option value="GW" <?php echo ($loggedIn->coucod == 'GW') ? 'selected="selected"' : ''; ?>>Guinea-Bissau</option>
                                        <option value="GY" <?php echo ($loggedIn->coucod == 'GY') ? 'selected="selected"' : ''; ?>>Guyana</option>
                                        <option value="HT" <?php echo ($loggedIn->coucod == 'HT') ? 'selected="selected"' : ''; ?>>Haiti</option>
                                        <option value="HM" <?php echo ($loggedIn->coucod == 'HM') ? 'selected="selected"' : ''; ?>>Heard and Mc Donald Islands</option>
                                        <option value="VA" <?php echo ($loggedIn->coucod == 'VA') ? 'selected="selected"' : ''; ?>>Holy See (Vatican City State)</option>
                                        <option value="HN" <?php echo ($loggedIn->coucod == 'HN') ? 'selected="selected"' : ''; ?>>Honduras</option>
                                        <option value="HK" <?php echo ($loggedIn->coucod == 'HK') ? 'selected="selected"' : ''; ?>>Hong Kong</option>
                                        <option value="HU" <?php echo ($loggedIn->coucod == 'HU') ? 'selected="selected"' : ''; ?>>Hungary</option>
                                        <option value="IS" <?php echo ($loggedIn->coucod == 'IS') ? 'selected="selected"' : ''; ?>>Iceland</option>
                                        <option value="IN" <?php echo ($loggedIn->coucod == 'IN') ? 'selected="selected"' : ''; ?>>India</option>
                                        <option value="ID" <?php echo ($loggedIn->coucod == 'ID') ? 'selected="selected"' : ''; ?>>Indonesia</option>
                                        <option value="IR" <?php echo ($loggedIn->coucod == 'IR') ? 'selected="selected"' : ''; ?>>Iran (Islamic Republic of)</option>
                                        <option value="IQ" <?php echo ($loggedIn->coucod == 'IQ') ? 'selected="selected"' : ''; ?>>Iraq</option>
                                        <option value="IE" <?php echo ($loggedIn->coucod == 'IE') ? 'selected="selected"' : ''; ?>>Ireland</option>
                                        <option value="IL" <?php echo ($loggedIn->coucod == 'IL') ? 'selected="selected"' : ''; ?>>Israel</option>
                                        <option value="IT" <?php echo ($loggedIn->coucod == 'IT') ? 'selected="selected"' : ''; ?>>Italy</option>
                                        <option value="JM" <?php echo ($loggedIn->coucod == 'JM') ? 'selected="selected"' : ''; ?>>Jamaica</option>
                                        <option value="JP" <?php echo ($loggedIn->coucod == 'JP') ? 'selected="selected"' : ''; ?>>Japan</option>
                                        <option value="JO" <?php echo ($loggedIn->coucod == 'JO') ? 'selected="selected"' : ''; ?>>Jordan</option>
                                        <option value="KZ" <?php echo ($loggedIn->coucod == 'KZ') ? 'selected="selected"' : ''; ?>>Kazakhstan</option>
                                        <option value="KE" <?php echo ($loggedIn->coucod == 'KE') ? 'selected="selected"' : ''; ?>>Kenya</option>
                                        <option value="KI" <?php echo ($loggedIn->coucod == 'KI') ? 'selected="selected"' : ''; ?>>Kiribati</option>
                                        <option value="KP" <?php echo ($loggedIn->coucod == 'KP') ? 'selected="selected"' : ''; ?>>Korea, Democratic People's Republic of</option>
                                        <option value="KR" <?php echo ($loggedIn->coucod == 'KR') ? 'selected="selected"' : ''; ?>>Korea, Republic of</option>
                                        <option value="KW" <?php echo ($loggedIn->coucod == 'KW') ? 'selected="selected"' : ''; ?>>Kuwait</option>
                                        <option value="KG" <?php echo ($loggedIn->coucod == 'KG') ? 'selected="selected"' : ''; ?>>Kyrgyzstan</option>
                                        <option value="LA" <?php echo ($loggedIn->coucod == 'LA') ? 'selected="selected"' : ''; ?>>Lao People's Democratic Republic</option>
                                        <option value="LV" <?php echo ($loggedIn->coucod == 'LV') ? 'selected="selected"' : ''; ?>>Latvia</option>
                                        <option value="LB" <?php echo ($loggedIn->coucod == 'LB') ? 'selected="selected"' : ''; ?>>Lebanon</option>
                                        <option value="LS" <?php echo ($loggedIn->coucod == 'LS') ? 'selected="selected"' : ''; ?>>Lesotho</option>
                                        <option value="LR" <?php echo ($loggedIn->coucod == 'LR') ? 'selected="selected"' : ''; ?>>Liberia</option>
                                        <option value="LY" <?php echo ($loggedIn->coucod == 'LY') ? 'selected="selected"' : ''; ?>>Libyan Arab Jamahiriya</option>
                                        <option value="LI" <?php echo ($loggedIn->coucod == 'LI') ? 'selected="selected"' : ''; ?>>Liechtenstein</option>
                                        <option value="LT" <?php echo ($loggedIn->coucod == 'LT') ? 'selected="selected"' : ''; ?>>Lithuania</option>
                                        <option value="LU" <?php echo ($loggedIn->coucod == 'LU') ? 'selected="selected"' : ''; ?>>Luxembourg</option>
                                        <option value="MO" <?php echo ($loggedIn->coucod == 'MO') ? 'selected="selected"' : ''; ?>>Macau</option>
                                        <option value="MK" <?php echo ($loggedIn->coucod == 'MK') ? 'selected="selected"' : ''; ?>>Macedonia, The Former Yugoslav Republic of</option>
                                        <option value="MG" <?php echo ($loggedIn->coucod == 'MG') ? 'selected="selected"' : ''; ?>>Madagascar</option>
                                        <option value="MW" <?php echo ($loggedIn->coucod == 'MW') ? 'selected="selected"' : ''; ?>>Malawi</option>
                                        <option value="MY" <?php echo ($loggedIn->coucod == 'MY') ? 'selected="selected"' : ''; ?>>Malaysia</option>
                                        <option value="MV" <?php echo ($loggedIn->coucod == 'MV') ? 'selected="selected"' : ''; ?>>Maldives</option>
                                        <option value="ML" <?php echo ($loggedIn->coucod == 'ML') ? 'selected="selected"' : ''; ?>>Mali</option>
                                        <option value="MT" <?php echo ($loggedIn->coucod == 'MT') ? 'selected="selected"' : ''; ?>>Malta</option>
                                        <option value="MH" <?php echo ($loggedIn->coucod == 'MH') ? 'selected="selected"' : ''; ?>>Marshall Islands</option>
                                        <option value="MQ" <?php echo ($loggedIn->coucod == 'MQ') ? 'selected="selected"' : ''; ?>>Martinique</option>
                                        <option value="MR" <?php echo ($loggedIn->coucod == 'MR') ? 'selected="selected"' : ''; ?>>Mauritania</option>
                                        <option value="MU" <?php echo ($loggedIn->coucod == 'MU') ? 'selected="selected"' : ''; ?>>Mauritius</option>
                                        <option value="YT" <?php echo ($loggedIn->coucod == 'YT') ? 'selected="selected"' : ''; ?>>Mayotte</option>
                                        <option value="MX" <?php echo ($loggedIn->coucod == 'MX') ? 'selected="selected"' : ''; ?>>Mexico</option>
                                        <option value="FM" <?php echo ($loggedIn->coucod == 'FM') ? 'selected="selected"' : ''; ?>>Micronesia, Federated States of</option>
                                        <option value="MD" <?php echo ($loggedIn->coucod == 'MD') ? 'selected="selected"' : ''; ?>>Moldova, Republic of</option>
                                        <option value="MC" <?php echo ($loggedIn->coucod == 'MC') ? 'selected="selected"' : ''; ?>>Monaco</option>
                                        <option value="MN" <?php echo ($loggedIn->coucod == 'MN') ? 'selected="selected"' : ''; ?>>Mongolia</option>
                                        <option value="MS" <?php echo ($loggedIn->coucod == 'MS') ? 'selected="selected"' : ''; ?>>Montserrat</option>
                                        <option value="MA" <?php echo ($loggedIn->coucod == 'MA') ? 'selected="selected"' : ''; ?>>Morocco</option>
                                        <option value="MZ" <?php echo ($loggedIn->coucod == 'MZ') ? 'selected="selected"' : ''; ?>>Mozambique</option>
                                        <option value="MM" <?php echo ($loggedIn->coucod == 'MM') ? 'selected="selected"' : ''; ?>>Myanmar</option>
                                        <option value="NA" <?php echo ($loggedIn->coucod == 'NA') ? 'selected="selected"' : ''; ?>>Namibia</option>
                                        <option value="NR" <?php echo ($loggedIn->coucod == 'NR') ? 'selected="selected"' : ''; ?>>Nauru</option>
                                        <option value="NP" <?php echo ($loggedIn->coucod == 'NP') ? 'selected="selected"' : ''; ?>>Nepal</option>
                                        <option value="NL" <?php echo ($loggedIn->coucod == 'NL') ? 'selected="selected"' : ''; ?>>Netherlands</option>
                                        <option value="AN" <?php echo ($loggedIn->coucod == 'AN') ? 'selected="selected"' : ''; ?>>Netherlands Antilles</option>
                                        <option value="NC" <?php echo ($loggedIn->coucod == 'NC') ? 'selected="selected"' : ''; ?>>New Caledonia</option>
                                        <option value="NZ" <?php echo ($loggedIn->coucod == 'NZ') ? 'selected="selected"' : ''; ?>>New Zealand</option>
                                        <option value="NI" <?php echo ($loggedIn->coucod == 'NI') ? 'selected="selected"' : ''; ?>>Nicaragua</option>
                                        <option value="NE" <?php echo ($loggedIn->coucod == 'NE') ? 'selected="selected"' : ''; ?>>Niger</option>
                                        <option value="NG" <?php echo ($loggedIn->coucod == 'NG') ? 'selected="selected"' : ''; ?>>Nigeria</option>
                                        <option value="NU" <?php echo ($loggedIn->coucod == 'NU') ? 'selected="selected"' : ''; ?>>Niue</option>
                                        <option value="NF" <?php echo ($loggedIn->coucod == 'NF') ? 'selected="selected"' : ''; ?>>Norfolk Island</option>
                                        <option value="MP" <?php echo ($loggedIn->coucod == 'MP') ? 'selected="selected"' : ''; ?>>Northern Mariana Islands</option>
                                        <option value="NO" <?php echo ($loggedIn->coucod == 'NO') ? 'selected="selected"' : ''; ?>>Norway</option>
                                        <option value="OM" <?php echo ($loggedIn->coucod == 'OM') ? 'selected="selected"' : ''; ?>>Oman</option>
                                        <option value="PK" <?php echo ($loggedIn->coucod == 'PK') ? 'selected="selected"' : ''; ?>>Pakistan</option>
                                        <option value="PW" <?php echo ($loggedIn->coucod == 'PW') ? 'selected="selected"' : ''; ?>>Palau</option>
                                        <option value="PA" <?php echo ($loggedIn->coucod == 'PA') ? 'selected="selected"' : ''; ?>>Panama</option>
                                        <option value="PG" <?php echo ($loggedIn->coucod == 'PG') ? 'selected="selected"' : ''; ?>>Papua New Guinea</option>
                                        <option value="PY" <?php echo ($loggedIn->coucod == 'PY') ? 'selected="selected"' : ''; ?>>Paraguay</option>
                                        <option value="PE" <?php echo ($loggedIn->coucod == 'PE') ? 'selected="selected"' : ''; ?>>Peru</option>
                                        <option value="PH" <?php echo ($loggedIn->coucod == 'PH') ? 'selected="selected"' : ''; ?>>Philippines</option>
                                        <option value="PN" <?php echo ($loggedIn->coucod == 'PN') ? 'selected="selected"' : ''; ?>>Pitcairn</option>
                                        <option value="PL" <?php echo ($loggedIn->coucod == 'PL') ? 'selected="selected"' : ''; ?>>Poland</option>
                                        <option value="PT" <?php echo ($loggedIn->coucod == 'PT') ? 'selected="selected"' : ''; ?>>Portugal</option>
                                        <option value="PR" <?php echo ($loggedIn->coucod == 'PR') ? 'selected="selected"' : ''; ?>>Puerto Rico</option>
                                        <option value="QA" <?php echo ($loggedIn->coucod == 'QA') ? 'selected="selected"' : ''; ?>>Qatar</option>
                                        <option value="RE" <?php echo ($loggedIn->coucod == 'RE') ? 'selected="selected"' : ''; ?>>Reunion</option>
                                        <option value="RO" <?php echo ($loggedIn->coucod == 'RO') ? 'selected="selected"' : ''; ?>>Romania</option>
                                        <option value="RU" <?php echo ($loggedIn->coucod == 'RU') ? 'selected="selected"' : ''; ?>>Russian Federation</option>
                                        <option value="RW" <?php echo ($loggedIn->coucod == 'RW') ? 'selected="selected"' : ''; ?>>Rwanda</option>
                                        <option value="KN" <?php echo ($loggedIn->coucod == 'KN') ? 'selected="selected"' : ''; ?>>Saint Kitts and Nevis</option>
                                        <option value="LC" <?php echo ($loggedIn->coucod == 'LC') ? 'selected="selected"' : ''; ?>>Saint LUCIA</option>
                                        <option value="VC" <?php echo ($loggedIn->coucod == 'VC') ? 'selected="selected"' : ''; ?>>Saint Vincent and the Grenadines</option>
                                        <option value="WS" <?php echo ($loggedIn->coucod == 'WS') ? 'selected="selected"' : ''; ?>>Samoa</option>
                                        <option value="SM" <?php echo ($loggedIn->coucod == 'SM') ? 'selected="selected"' : ''; ?>>San Marino</option>
                                        <option value="ST" <?php echo ($loggedIn->coucod == 'ST') ? 'selected="selected"' : ''; ?>>Sao Tome and Principe</option>
                                        <option value="SA" <?php echo ($loggedIn->coucod == 'SA') ? 'selected="selected"' : ''; ?>>Saudi Arabia</option>
                                        <option value="SN" <?php echo ($loggedIn->coucod == 'SN') ? 'selected="selected"' : ''; ?>>Senegal</option>
                                        <option value="SC" <?php echo ($loggedIn->coucod == 'SC') ? 'selected="selected"' : ''; ?>>Seychelles</option>
                                        <option value="SL" <?php echo ($loggedIn->coucod == 'SL') ? 'selected="selected"' : ''; ?>>Sierra Leone</option>
                                        <option value="SG" <?php echo ($loggedIn->coucod == 'SG') ? 'selected="selected"' : ''; ?>>Singapore</option>
                                        <option value="SK" <?php echo ($loggedIn->coucod == 'SK') ? 'selected="selected"' : ''; ?>>Slovakia (Slovak Republic)</option>
                                        <option value="SI" <?php echo ($loggedIn->coucod == 'SI') ? 'selected="selected"' : ''; ?>>Slovenia</option>
                                        <option value="SB" <?php echo ($loggedIn->coucod == 'SB') ? 'selected="selected"' : ''; ?>>Solomon Islands</option>
                                        <option value="SO" <?php echo ($loggedIn->coucod == 'SO') ? 'selected="selected"' : ''; ?>>Somalia</option>
                                        <option value="ZA" <?php echo ($loggedIn->coucod == 'ZA') ? 'selected="selected"' : ''; ?>>South Africa</option>
                                        <option value="GS" <?php echo ($loggedIn->coucod == 'GS') ? 'selected="selected"' : ''; ?>>South Georgia and the South Sandwich Islands</option>
                                        <option value="ES" <?php echo ($loggedIn->coucod == 'ES') ? 'selected="selected"' : ''; ?>>Spain</option>
                                        <option value="LK" <?php echo ($loggedIn->coucod == 'LK') ? 'selected="selected"' : ''; ?>>Sri Lanka</option>
                                        <option value="SH" <?php echo ($loggedIn->coucod == 'SH') ? 'selected="selected"' : ''; ?>>St. Helena</option>
                                        <option value="PM" <?php echo ($loggedIn->coucod == 'PM') ? 'selected="selected"' : ''; ?>>St. Pierre and Miquelon</option>
                                        <option value="SD" <?php echo ($loggedIn->coucod == 'SD') ? 'selected="selected"' : ''; ?>>Sudan</option>
                                        <option value="SR" <?php echo ($loggedIn->coucod == 'SR') ? 'selected="selected"' : ''; ?>>Suriname</option>
                                        <option value="SJ" <?php echo ($loggedIn->coucod == 'SJ') ? 'selected="selected"' : ''; ?>>Svalbard and Jan Mayen Islands</option>
                                        <option value="SZ" <?php echo ($loggedIn->coucod == 'SZ') ? 'selected="selected"' : ''; ?>>Swaziland</option>
                                        <option value="SE" <?php echo ($loggedIn->coucod == 'SE') ? 'selected="selected"' : ''; ?>>Sweden</option>
                                        <option value="CH" <?php echo ($loggedIn->coucod == 'CH') ? 'selected="selected"' : ''; ?>>Switzerland</option>
                                        <option value="SY" <?php echo ($loggedIn->coucod == 'SY') ? 'selected="selected"' : ''; ?>>Syrian Arab Republic</option>
                                        <option value="TW" <?php echo ($loggedIn->coucod == 'TW') ? 'selected="selected"' : ''; ?>>Taiwan, Province of China</option>
                                        <option value="TJ" <?php echo ($loggedIn->coucod == 'TJ') ? 'selected="selected"' : ''; ?>>Tajikistan</option>
                                        <option value="TZ" <?php echo ($loggedIn->coucod == 'TZ') ? 'selected="selected"' : ''; ?>>Tanzania, United Republic of</option>
                                        <option value="TH" <?php echo ($loggedIn->coucod == 'TH') ? 'selected="selected"' : ''; ?>>Thailand</option>
                                        <option value="TG" <?php echo ($loggedIn->coucod == 'TG') ? 'selected="selected"' : ''; ?>>Togo</option>
                                        <option value="TK" <?php echo ($loggedIn->coucod == 'TK') ? 'selected="selected"' : ''; ?>>Tokelau</option>
                                        <option value="TO" <?php echo ($loggedIn->coucod == 'TO') ? 'selected="selected"' : ''; ?>>Tonga</option>
                                        <option value="TT" <?php echo ($loggedIn->coucod == 'TT') ? 'selected="selected"' : ''; ?>>Trinidad and Tobago</option>
                                        <option value="TN" <?php echo ($loggedIn->coucod == 'TN') ? 'selected="selected"' : ''; ?>>Tunisia</option>
                                        <option value="TR" <?php echo ($loggedIn->coucod == 'TR') ? 'selected="selected"' : ''; ?>>Turkey</option>
                                        <option value="TM" <?php echo ($loggedIn->coucod == 'TM') ? 'selected="selected"' : ''; ?>>Turkmenistan</option>
                                        <option value="TC" <?php echo ($loggedIn->coucod == 'TC') ? 'selected="selected"' : ''; ?>>Turks and Caicos Islands</option>
                                        <option value="TV" <?php echo ($loggedIn->coucod == 'TV') ? 'selected="selected"' : ''; ?>>Tuvalu</option>
                                        <option value="UG" <?php echo ($loggedIn->coucod == 'UG') ? 'selected="selected"' : ''; ?>>Uganda</option>
                                        <option value="UA" <?php echo ($loggedIn->coucod == 'UA') ? 'selected="selected"' : ''; ?>>Ukraine</option>
                                        <option value="AE" <?php echo ($loggedIn->coucod == 'AE') ? 'selected="selected"' : ''; ?>>United Arab Emirates</option>
                                        <option value="US" <?php echo ($loggedIn->coucod == 'US') ? 'selected="selected"' : ''; ?>>United States</option>
                                        <option value="UM" <?php echo ($loggedIn->coucod == 'UM') ? 'selected="selected"' : ''; ?>>United States Minor Outlying Islands</option>
                                        <option value="UY" <?php echo ($loggedIn->coucod == 'UY') ? 'selected="selected"' : ''; ?>>Uruguay</option>
                                        <option value="UZ" <?php echo ($loggedIn->coucod == 'UZ') ? 'selected="selected"' : ''; ?>>Uzbekistan</option>
                                        <option value="VU" <?php echo ($loggedIn->coucod == 'VU') ? 'selected="selected"' : ''; ?>>Vanuatu</option>
                                        <option value="VE" <?php echo ($loggedIn->coucod == 'VE') ? 'selected="selected"' : ''; ?>>Venezuela</option>
                                        <option value="VN" <?php echo ($loggedIn->coucod == 'VN') ? 'selected="selected"' : ''; ?>>Viet Nam</option>
                                        <option value="VG" <?php echo ($loggedIn->coucod == 'VG') ? 'selected="selected"' : ''; ?>>Virgin Islands (British)</option>
                                        <option value="VI" <?php echo ($loggedIn->coucod == 'VI') ? 'selected="selected"' : ''; ?>>Virgin Islands (U.S.)</option>
                                        <option value="WF" <?php echo ($loggedIn->coucod == 'WF') ? 'selected="selected"' : ''; ?>>Wallis and Futuna Islands</option>
                                        <option value="EH" <?php echo ($loggedIn->coucod == 'EH') ? 'selected="selected"' : ''; ?>>Western Sahara</option>
                                        <option value="YE" <?php echo ($loggedIn->coucod == 'YE') ? 'selected="selected"' : ''; ?>>Yemen</option>
                                        <option value="YU" <?php echo ($loggedIn->coucod == 'YU') ? 'selected="selected"' : ''; ?>>Yugoslavia</option>
                                        <option value="ZM" <?php echo ($loggedIn->coucod == 'ZM') ? 'selected="selected"' : ''; ?>>Zambia</option>
                                        <option value="ZW" <?php echo ($loggedIn->coucod == 'ZW') ? 'selected="selected"' : ''; ?>>Zimbabwe</option>
                                    </select>

                                    <input type="hidden" name="delcoucod">

                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>

                    <p style="text-align: right;">
                        <button type="submit" class="button">Update</button>
                    </p>

                </form>


            </div>
        </div>
    </div>
</div>