<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");

$RegTyp = 'F';

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <h2 class="heading">REGISTER</h2>
                <hr>

                <div class="box">


                    <?php
                    if (isset($_GET['error']) && $_GET['error'] == 'foundemail') {
                        ?>
                        <div class="alert alert-danger">

                            <p><strong>Email Exists: </strong> the email address you supplied already exists on our system</p>
                            <p>Please <a href="useraccount/login"><u>click here</u></a> to login with this email</p>

                        </div>
                        <?php
                    }
                    ?>

                <form action="<?php echo $patchworks->webRoot; ?>pages/account/register_account.php" method="post" class="form-vertical" id="registerForm" data-parsley-validate>
                    <div class="pw-form">

                        <ul>
                            <li><a href="useraccount/login">Already a member? Click here to login</a></li>
                        </ul>

                        <div class="pw-form-content">
                            <fieldset>
                                <input type="hidden" value="http://" name="httpChk">
                                <input type="hidden" name="fwdurl" value="<?php echo (isset($_GET['fwdurl'])) ? $_GET['fwdurl'] : 'useraccount/account'; ?>">

                                <div class="row">
                                    <div class="col-sm-6">

                                        <hr/>
                                        <h5>Your Details</h5>
                                        <hr/>

                                        <div class="form-group">
                                            <label class="control-label">Title</label>

                                            <div class="control-input ">

                                                <select name="title" class="form-control" required>
                                                    <option value="">N/A</option>
                                                    <option value="Mr">Mr</option>
                                                    <option value="Mrs">Mrs</option>
                                                    <option value="Ms">Ms</option>
                                                    <option value="Miss">Miss</option>
                                                    <option value="Dr">Dr</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">First Name</label>

                                            <div class="controls">
                                                <input type="text" value="" class="form-control input-block-level required"
                                                       name="firstname">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Surname</label>

                                            <div class="controls">
                                                <input type="text" value="" class="form-control input-block-level required"
                                                       name="surname">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Your Email</label>

                                            <div class="controls">
                                                <input type="email" data-parsley-type="email" value="" class="form-control input-block-level required"
                                                       name="registerEmail">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Password</label>

                                            <div class="controls">
                                                <input type="password" value="" id="userPassword" class="form-control input-block-level required" data-parsley-minlength="6" data-parsley-maxlength="20"
                                                       name="registerPassword">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Confirm Password</label>

                                            <div class="controls">
                                                <input type="password" value="" class="form-control input-block-level required" data-parsley-equalto="#userPassword" data-parsley-minlength="6" data-parsley-maxlength="20"
                                                       id="confirmPassword">
                                            </div>
                                        </div>

                                        <h2 class="heading">COMPANY DETAILS</h2>

                                        <hr>

                                        <div class="form-group">
                                            <label class="control-label">Company Name <small>when aplicable</small></label>

                                            <div class="controls">
                                                <input type="text" value="" class="form-control input-block-level required"
                                                       name="registerName">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Trading Name</label>

                                            <div class="controls">
                                                <input type="text" value="" class="form-control input-block-level required"
                                                       name="tradeName">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Company Number</label>

                                            <div class="controls">
                                                <input type="text" value="" class="form-control input-block-level required"
                                                       name="companyNumber">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>VAT Number</label>

                                            <div class="controls">
                                                <input type="text" value="" class="form-control input-block-level required"
                                                       name="vatNumber">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Website URL</label>

                                            <div class="controls">
                                                <input type="text" value="" class="form-control input-block-level"
                                                       name="plaurl">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label>Required Credit?</label>

                                            <div class="controls">
                                                <input type="text" value="" class="form-control input-block-level required"
                                                       name="reqcrd">
                                            </div>
                                        </div>


                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="garden" name="type_paint_dec">
                                                Painter and Decorator
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="online" name="type_online">
                                                Online Retailer
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="other" name="type_other">
                                                Other
                                            </label>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">

                                        <?php if ($RegTyp == 'F') { ?>

                                            <hr/>
                                            <h5>Billing Address</h5>
                                            <hr/>

                                            <div class="form-group">
                                                <label  class="control-label">Address Line 1</label>

                                                <div class="controls">
                                                    <input type="text" value="" class="form-control input-block-level required"
                                                           name="adr1">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="control-label">Address Line 2</label>

                                                <div class="controls">
                                                    <input type="text" value="" class="form-control input-block-level"
                                                           name="adr2">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="control-label">City / Town</label>

                                                <div class="controls">
                                                    <input type="text" value="" class="form-control input-block-level required"
                                                           name="adr3">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="control-label">Address Line 4</label>

                                                <div class="controls">
                                                    <input type="text" value="" class="form-control input-block-level"
                                                           name="adr4">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="control-label">Post Code</label>

                                                <div class="controls">
                                                    <input type="text" value="" class="form-control input-block-level required"
                                                           name="pstcod">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Country</label>

                                                <div class="control-input ">
                                                    <select id="country" name="country" class="form-control">

                                                        <option value="">(please select a country)</option>

                                                        <?php

                                                        $deliveryCountries = $TmpDel->getCountryRecords();

                                                        for ($i=0;$i<count($deliveryCountries);$i++) {
                                                            ?>

                                                            <option value="<?php echo $deliveryCountries[$i]->coucod; ?>" ><?php echo $deliveryCountries[$i]->counam; ?></option>

                                                            <?php
                                                        }

                                                        ?>


                                                        <!--                                        <option value="" selected="selected">(please select a country)</option>-->
                                                        <!--                                        <option value="AF">Afghanistan</option>-->
                                                        <!--                                        <option value="AL">Albania</option>-->
                                                        <!--                                        <option value="DZ">Algeria</option>-->
                                                        <!--                                        <option value="AS">American Samoa</option>-->
                                                        <!--                                        <option value="AD">Andorra</option>-->
                                                        <!--                                        <option value="AO">Angola</option>-->
                                                        <!--                                        <option value="AI">Anguilla</option>-->
                                                        <!--                                        <option value="AQ">Antarctica</option>-->
                                                        <!--                                        <option value="AG">Antigua and Barbuda</option>-->
                                                        <!--                                        <option value="AR">Argentina</option>-->
                                                        <!--                                        <option value="AM">Armenia</option>-->
                                                        <!--                                        <option value="AW">Aruba</option>-->
                                                        <!--                                        <option value="AU">Australia</option>-->
                                                        <!--                                        <option value="AT">Austria</option>-->
                                                        <!--                                        <option value="AZ">Azerbaijan</option>-->
                                                        <!--                                        <option value="BS">Bahamas</option>-->
                                                        <!--                                        <option value="BH">Bahrain</option>-->
                                                        <!--                                        <option value="BD">Bangladesh</option>-->
                                                        <!--                                        <option value="BB">Barbados</option>-->
                                                        <!--                                        <option value="BY">Belarus</option>-->
                                                        <!--                                        <option value="BE">Belgium</option>-->
                                                        <!--                                        <option value="BZ">Belize</option>-->
                                                        <!--                                        <option value="BJ">Benin</option>-->
                                                        <!--                                        <option value="BM">Bermuda</option>-->
                                                        <!--                                        <option value="BT">Bhutan</option>-->
                                                        <!--                                        <option value="BO">Bolivia</option>-->
                                                        <!--                                        <option value="BA">Bosnia and Herzegowina</option>-->
                                                        <!--                                        <option value="BW">Botswana</option>-->
                                                        <!--                                        <option value="BV">Bouvet Island</option>-->
                                                        <!--                                        <option value="BR">Brazil</option>-->
                                                        <!--                                        <option value="IO">British Indian Ocean Territory</option>-->
                                                        <!--                                        <option value="BN">Brunei Darussalam</option>-->
                                                        <!--                                        <option value="BG">Bulgaria</option>-->
                                                        <!--                                        <option value="BF">Burkina Faso</option>-->
                                                        <!--                                        <option value="BI">Burundi</option>-->
                                                        <!--                                        <option value="KH">Cambodia</option>-->
                                                        <!--                                        <option value="CM">Cameroon</option>-->
                                                        <!--                                        <option value="CA">Canada</option>-->
                                                        <!--                                        <option value="CV">Cape Verde</option>-->
                                                        <!--                                        <option value="KY">Cayman Islands</option>-->
                                                        <!--                                        <option value="CF">Central African Republic</option>-->
                                                        <!--                                        <option value="TD">Chad</option>-->
                                                        <!--                                        <option value="CL">Chile</option>-->
                                                        <!--                                        <option value="CN">China</option>-->
                                                        <!--                                        <option value="CX">Christmas Island</option>-->
                                                        <!--                                        <option value="CC">Cocos (Keeling) Islands</option>-->
                                                        <!--                                        <option value="CO">Colombia</option>-->
                                                        <!--                                        <option value="KM">Comoros</option>-->
                                                        <!--                                        <option value="CG">Congo</option>-->
                                                        <!--                                        <option value="CD">Congo, the Democratic Republic of the</option>-->
                                                        <!--                                        <option value="CK">Cook Islands</option>-->
                                                        <!--                                        <option value="CR">Costa Rica</option>-->
                                                        <!--                                        <option value="CI">Cote d'Ivoire</option>-->
                                                        <!--                                        <option value="HR">Croatia (Hrvatska)</option>-->
                                                        <!--                                        <option value="CU">Cuba</option>-->
                                                        <!--                                        <option value="CY">Cyprus</option>-->
                                                        <!--                                        <option value="CZ">Czech Republic</option>-->
                                                        <!--                                        <option value="DK">Denmark</option>-->
                                                        <!--                                        <option value="DJ">Djibouti</option>-->
                                                        <!--                                        <option value="DM">Dominica</option>-->
                                                        <!--                                        <option value="DO">Dominican Republic</option>-->
                                                        <!--                                        <option value="TP">East Timor</option>-->
                                                        <!--                                        <option value="EC">Ecuador</option>-->
                                                        <!--                                        <option value="EG">Egypt</option>-->
                                                        <!--                                        <option value="SV">El Salvador</option>-->
                                                        <!--                                        <option value="GQ">Equatorial Guinea</option>-->
                                                        <!--                                        <option value="ER">Eritrea</option>-->
                                                        <!--                                        <option value="EE">Estonia</option>-->
                                                        <!--                                        <option value="ET">Ethiopia</option>-->
                                                        <!--                                        <option value="FK">Falkland Islands (Malvinas)</option>-->
                                                        <!--                                        <option value="FO">Faroe Islands</option>-->
                                                        <!--                                        <option value="FJ">Fiji</option>-->
                                                        <!--                                        <option value="FI">Finland</option>-->
                                                        <!--                                        <option value="FR">France</option>-->
                                                        <!--                                        <option value="FX">France, Metropolitan</option>-->
                                                        <!--                                        <option value="GF">French Guiana</option>-->
                                                        <!--                                        <option value="PF">French Polynesia</option>-->
                                                        <!--                                        <option value="TF">French Southern Territories</option>-->
                                                        <!--                                        <option value="GA">Gabon</option>-->
                                                        <!--                                        <option value="GM">Gambia</option>-->
                                                        <!--                                        <option value="GE">Georgia</option>-->
                                                        <!--                                        <option value="DE">Germany</option>-->
                                                        <!--                                        <option value="GH">Ghana</option>-->
                                                        <!--                                        <option value="GI">Gibraltar</option>-->
                                                        <!--                                        <option value="GR">Greece</option>-->
                                                        <!--                                        <option value="GL">Greenland</option>-->
                                                        <!--                                        <option value="GD">Grenada</option>-->
                                                        <!--                                        <option value="GP">Guadeloupe</option>-->
                                                        <!--                                        <option value="GU">Guam</option>-->
                                                        <!--                                        <option value="GT">Guatemala</option>-->
                                                        <!--                                        <option value="GN">Guinea</option>-->
                                                        <!--                                        <option value="GW">Guinea-Bissau</option>-->
                                                        <!--                                        <option value="GY">Guyana</option>-->
                                                        <!--                                        <option value="HT">Haiti</option>-->
                                                        <!--                                        <option value="HM">Heard and Mc Donald Islands</option>-->
                                                        <!--                                        <option value="VA">Holy See (Vatican City State)</option>-->
                                                        <!--                                        <option value="HN">Honduras</option>-->
                                                        <!--                                        <option value="HK">Hong Kong</option>-->
                                                        <!--                                        <option value="HU">Hungary</option>-->
                                                        <!--                                        <option value="IS">Iceland</option>-->
                                                        <!--                                        <option value="IN">India</option>-->
                                                        <!--                                        <option value="ID">Indonesia</option>-->
                                                        <!--                                        <option value="IR">Iran (Islamic Republic of)</option>-->
                                                        <!--                                        <option value="IQ">Iraq</option>-->
                                                        <!--                                        <option value="IE">Ireland</option>-->
                                                        <!--                                        <option value="IL">Israel</option>-->
                                                        <!--                                        <option value="IT">Italy</option>-->
                                                        <!--                                        <option value="JM">Jamaica</option>-->
                                                        <!--                                        <option value="JP">Japan</option>-->
                                                        <!--                                        <option value="JO">Jordan</option>-->
                                                        <!--                                        <option value="KZ">Kazakhstan</option>-->
                                                        <!--                                        <option value="KE">Kenya</option>-->
                                                        <!--                                        <option value="KI">Kiribati</option>-->
                                                        <!--                                        <option value="KP">Korea, Democratic People's Republic of</option>-->
                                                        <!--                                        <option value="KR">Korea, Republic of</option>-->
                                                        <!--                                        <option value="KW">Kuwait</option>-->
                                                        <!--                                        <option value="KG">Kyrgyzstan</option>-->
                                                        <!--                                        <option value="LA">Lao People's Democratic Republic</option>-->
                                                        <!--                                        <option value="LV">Latvia</option>-->
                                                        <!--                                        <option value="LB">Lebanon</option>-->
                                                        <!--                                        <option value="LS">Lesotho</option>-->
                                                        <!--                                        <option value="LR">Liberia</option>-->
                                                        <!--                                        <option value="LY">Libyan Arab Jamahiriya</option>-->
                                                        <!--                                        <option value="LI">Liechtenstein</option>-->
                                                        <!--                                        <option value="LT">Lithuania</option>-->
                                                        <!--                                        <option value="LU">Luxembourg</option>-->
                                                        <!--                                        <option value="MO">Macau</option>-->
                                                        <!--                                        <option value="MK">Macedonia, The Former Yugoslav Republic of</option>-->
                                                        <!--                                        <option value="MG">Madagascar</option>-->
                                                        <!--                                        <option value="MW">Malawi</option>-->
                                                        <!--                                        <option value="MY">Malaysia</option>-->
                                                        <!--                                        <option value="MV">Maldives</option>-->
                                                        <!--                                        <option value="ML">Mali</option>-->
                                                        <!--                                        <option value="MT">Malta</option>-->
                                                        <!--                                        <option value="MH">Marshall Islands</option>-->
                                                        <!--                                        <option value="MQ">Martinique</option>-->
                                                        <!--                                        <option value="MR">Mauritania</option>-->
                                                        <!--                                        <option value="MU">Mauritius</option>-->
                                                        <!--                                        <option value="YT">Mayotte</option>-->
                                                        <!--                                        <option value="MX">Mexico</option>-->
                                                        <!--                                        <option value="FM">Micronesia, Federated States of</option>-->
                                                        <!--                                        <option value="MD">Moldova, Republic of</option>-->
                                                        <!--                                        <option value="MC">Monaco</option>-->
                                                        <!--                                        <option value="MN">Mongolia</option>-->
                                                        <!--                                        <option value="MS">Montserrat</option>-->
                                                        <!--                                        <option value="MA">Morocco</option>-->
                                                        <!--                                        <option value="MZ">Mozambique</option>-->
                                                        <!--                                        <option value="MM">Myanmar</option>-->
                                                        <!--                                        <option value="NA">Namibia</option>-->
                                                        <!--                                        <option value="NR">Nauru</option>-->
                                                        <!--                                        <option value="NP">Nepal</option>-->
                                                        <!--                                        <option value="NL">Netherlands</option>-->
                                                        <!--                                        <option value="AN">Netherlands Antilles</option>-->
                                                        <!--                                        <option value="NC">New Caledonia</option>-->
                                                        <!--                                        <option value="NZ">New Zealand</option>-->
                                                        <!--                                        <option value="NI">Nicaragua</option>-->
                                                        <!--                                        <option value="NE">Niger</option>-->
                                                        <!--                                        <option value="NG">Nigeria</option>-->
                                                        <!--                                        <option value="NU">Niue</option>-->
                                                        <!--                                        <option value="NF">Norfolk Island</option>-->
                                                        <!--                                        <option value="MP">Northern Mariana Islands</option>-->
                                                        <!--                                        <option value="NO">Norway</option>-->
                                                        <!--                                        <option value="OM">Oman</option>-->
                                                        <!--                                        <option value="PK">Pakistan</option>-->
                                                        <!--                                        <option value="PW">Palau</option>-->
                                                        <!--                                        <option value="PA">Panama</option>-->
                                                        <!--                                        <option value="PG">Papua New Guinea</option>-->
                                                        <!--                                        <option value="PY">Paraguay</option>-->
                                                        <!--                                        <option value="PE">Peru</option>-->
                                                        <!--                                        <option value="PH">Philippines</option>-->
                                                        <!--                                        <option value="PN">Pitcairn</option>-->
                                                        <!--                                        <option value="PL">Poland</option>-->
                                                        <!--                                        <option value="PT">Portugal</option>-->
                                                        <!--                                        <option value="PR">Puerto Rico</option>-->
                                                        <!--                                        <option value="QA">Qatar</option>-->
                                                        <!--                                        <option value="RE">Reunion</option>-->
                                                        <!--                                        <option value="RO">Romania</option>-->
                                                        <!--                                        <option value="RU">Russian Federation</option>-->
                                                        <!--                                        <option value="RW">Rwanda</option>-->
                                                        <!--                                        <option value="KN">Saint Kitts and Nevis</option>-->
                                                        <!--                                        <option value="LC">Saint LUCIA</option>-->
                                                        <!--                                        <option value="VC">Saint Vincent and the Grenadines</option>-->
                                                        <!--                                        <option value="WS">Samoa</option>-->
                                                        <!--                                        <option value="SM">San Marino</option>-->
                                                        <!--                                        <option value="ST">Sao Tome and Principe</option>-->
                                                        <!--                                        <option value="SA">Saudi Arabia</option>-->
                                                        <!--                                        <option value="SN">Senegal</option>-->
                                                        <!--                                        <option value="SC">Seychelles</option>-->
                                                        <!--                                        <option value="SL">Sierra Leone</option>-->
                                                        <!--                                        <option value="SG">Singapore</option>-->
                                                        <!--                                        <option value="SK">Slovakia (Slovak Republic)</option>-->
                                                        <!--                                        <option value="SI">Slovenia</option>-->
                                                        <!--                                        <option value="SB">Solomon Islands</option>-->
                                                        <!--                                        <option value="SO">Somalia</option>-->
                                                        <!--                                        <option value="ZA">South Africa</option>-->
                                                        <!--                                        <option value="GS">South Georgia and the South Sandwich Islands</option>-->
                                                        <!--                                        <option value="ES">Spain</option>-->
                                                        <!--                                        <option value="LK">Sri Lanka</option>-->
                                                        <!--                                        <option value="SH">St. Helena</option>-->
                                                        <!--                                        <option value="PM">St. Pierre and Miquelon</option>-->
                                                        <!--                                        <option value="SD">Sudan</option>-->
                                                        <!--                                        <option value="SR">Suriname</option>-->
                                                        <!--                                        <option value="SJ">Svalbard and Jan Mayen Islands</option>-->
                                                        <!--                                        <option value="SZ">Swaziland</option>-->
                                                        <!--                                        <option value="SE">Sweden</option>-->
                                                        <!--                                        <option value="CH">Switzerland</option>-->
                                                        <!--                                        <option value="SY">Syrian Arab Republic</option>-->
                                                        <!--                                        <option value="TW">Taiwan, Province of China</option>-->
                                                        <!--                                        <option value="TJ">Tajikistan</option>-->
                                                        <!--                                        <option value="TZ">Tanzania, United Republic of</option>-->
                                                        <!--                                        <option value="TH">Thailand</option>-->
                                                        <!--                                        <option value="TG">Togo</option>-->
                                                        <!--                                        <option value="TK">Tokelau</option>-->
                                                        <!--                                        <option value="TO">Tonga</option>-->
                                                        <!--                                        <option value="TT">Trinidad and Tobago</option>-->
                                                        <!--                                        <option value="TN">Tunisia</option>-->
                                                        <!--                                        <option value="TR">Turkey</option>-->
                                                        <!--                                        <option value="TM">Turkmenistan</option>-->
                                                        <!--                                        <option value="TC">Turks and Caicos Islands</option>-->
                                                        <!--                                        <option value="TV">Tuvalu</option>-->
                                                        <!--                                        <option value="UG">Uganda</option>-->
                                                        <!--                                        <option value="UA">Ukraine</option>-->
                                                        <!--                                        <option value="AE">United Arab Emirates</option>-->
                                                        <!--                                        <option value="GB">United Kingdom</option>-->
                                                        <!--                                        <option value="US">United States</option>-->
                                                        <!--                                        <option value="UM">United States Minor Outlying Islands</option>-->
                                                        <!--                                        <option value="UY">Uruguay</option>-->
                                                        <!--                                        <option value="UZ">Uzbekistan</option>-->
                                                        <!--                                        <option value="VU">Vanuatu</option>-->
                                                        <!--                                        <option value="VE">Venezuela</option>-->
                                                        <!--                                        <option value="VN">Viet Nam</option>-->
                                                        <!--                                        <option value="VG">Virgin Islands (British)</option>-->
                                                        <!--                                        <option value="VI">Virgin Islands (U.S.)</option>-->
                                                        <!--                                        <option value="WF">Wallis and Futuna Islands</option>-->
                                                        <!--                                        <option value="EH">Western Sahara</option>-->
                                                        <!--                                        <option value="YE">Yemen</option>-->
                                                        <!--                                        <option value="YU">Yugoslavia</option>-->
                                                        <!--                                        <option value="ZM">Zambia</option>-->
                                                        <!--                                        <option value="ZW">Zimbabwe</option>-->
                                                    </select>

                                                    <input type="hidden" name="delcoucod">

                                                </div>
                                            </div>

                                        <?php } ?>

                                    </div>
                                </div>

                            </fieldset>
                        </div>
                        <div class="form-actions text-right">
                            <button type="submit" class="button">Register</button>
                        </div>
                    </div>
                </form>

                </div>


            </div>
        </div>
    </div>
</div>