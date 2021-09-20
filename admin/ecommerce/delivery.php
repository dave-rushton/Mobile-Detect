<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/delivery.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpDel = new DelDAO();
$delivery = $TmpDel->select(NULL, NULL, NULL, NULL, false);




$countryArray = array();
$countryNameArray = array();


array_push($countryArray,"AF"); array_push($countryNameArray,"Afghanistan");
array_push($countryArray,"AL"); array_push($countryNameArray,"Albania");
array_push($countryArray,"DZ"); array_push($countryNameArray,"Algeria");
array_push($countryArray,"AS"); array_push($countryNameArray,"American Samoa");
array_push($countryArray,"AD"); array_push($countryNameArray,"Andorra");
array_push($countryArray,"AO"); array_push($countryNameArray,"Angola");
array_push($countryArray,"AI"); array_push($countryNameArray,"Anguilla");
array_push($countryArray,"AQ"); array_push($countryNameArray,"Antarctica");
array_push($countryArray,"AG"); array_push($countryNameArray,"Antigua and Barbuda");
array_push($countryArray,"AR"); array_push($countryNameArray,"Argentina");
array_push($countryArray,"AM"); array_push($countryNameArray,"Armenia");
array_push($countryArray,"AW"); array_push($countryNameArray,"Aruba");
array_push($countryArray,"AU"); array_push($countryNameArray,"Australia");
array_push($countryArray,"AT"); array_push($countryNameArray,"Austria");
array_push($countryArray,"AZ"); array_push($countryNameArray,"Azerbaijan");
array_push($countryArray,"BS"); array_push($countryNameArray,"Bahamas");
array_push($countryArray,"BH"); array_push($countryNameArray,"Bahrain");
array_push($countryArray,"BD"); array_push($countryNameArray,"Bangladesh");
array_push($countryArray,"BB"); array_push($countryNameArray,"Barbados");
array_push($countryArray,"BY"); array_push($countryNameArray,"Belarus");
array_push($countryArray,"BE"); array_push($countryNameArray,"Belgium");
array_push($countryArray,"BZ"); array_push($countryNameArray,"Belize");
array_push($countryArray,"BJ"); array_push($countryNameArray,"Benin");
array_push($countryArray,"BM"); array_push($countryNameArray,"Bermuda");
array_push($countryArray,"BT"); array_push($countryNameArray,"Bhutan");
array_push($countryArray,"BO"); array_push($countryNameArray,"Bolivia");
array_push($countryArray,"BA"); array_push($countryNameArray,"Bosnia and Herzegowina");
array_push($countryArray,"BW"); array_push($countryNameArray,"Botswana");
array_push($countryArray,"BV"); array_push($countryNameArray,"Bouvet Island");
array_push($countryArray,"BR"); array_push($countryNameArray,"Brazil");
array_push($countryArray,"IO"); array_push($countryNameArray,"British Indian Ocean Territory");
array_push($countryArray,"BN"); array_push($countryNameArray,"Brunei Darussalam");
array_push($countryArray,"BG"); array_push($countryNameArray,"Bulgaria");
array_push($countryArray,"BF"); array_push($countryNameArray,"Burkina Faso");
array_push($countryArray,"BI"); array_push($countryNameArray,"Burundi");
array_push($countryArray,"KH"); array_push($countryNameArray,"Cambodia");
array_push($countryArray,"CM"); array_push($countryNameArray,"Cameroon");
array_push($countryArray,"CA"); array_push($countryNameArray,"Canada");
array_push($countryArray,"CV"); array_push($countryNameArray,"Cape Verde");
array_push($countryArray,"KY"); array_push($countryNameArray,"Cayman Islands");
array_push($countryArray,"CF"); array_push($countryNameArray,"Central African Republic");
array_push($countryArray,"TD"); array_push($countryNameArray,"Chad");
array_push($countryArray,"CL"); array_push($countryNameArray,"Chile");
array_push($countryArray,"CN"); array_push($countryNameArray,"China");
array_push($countryArray,"CX"); array_push($countryNameArray,"Christmas Island");
array_push($countryArray,"CC"); array_push($countryNameArray,"Cocos (Keeling) Islands");
array_push($countryArray,"CO"); array_push($countryNameArray,"Colombia");
array_push($countryArray,"KM"); array_push($countryNameArray,"Comoros");
array_push($countryArray,"CG"); array_push($countryNameArray,"Congo");
array_push($countryArray,"CD"); array_push($countryNameArray,"Congo, the Democratic Republic of the");
array_push($countryArray,"CK"); array_push($countryNameArray,"Cook Islands");
array_push($countryArray,"CR"); array_push($countryNameArray,"Costa Rica");
array_push($countryArray,"CI"); array_push($countryNameArray,"Cote d'Ivoire");
array_push($countryArray,"HR"); array_push($countryNameArray,"Croatia (Hrvatska)");
array_push($countryArray,"CU"); array_push($countryNameArray,"Cuba");
array_push($countryArray,"CY"); array_push($countryNameArray,"Cyprus");
array_push($countryArray,"CZ"); array_push($countryNameArray,"Czech Republic");
array_push($countryArray,"DK"); array_push($countryNameArray,"Denmark");
array_push($countryArray,"DJ"); array_push($countryNameArray,"Djibouti");
array_push($countryArray,"DM"); array_push($countryNameArray,"Dominica");
array_push($countryArray,"DO"); array_push($countryNameArray,"Dominican Republic");
array_push($countryArray,"TP"); array_push($countryNameArray,"East Timor");
array_push($countryArray,"EC"); array_push($countryNameArray,"Ecuador");
array_push($countryArray,"EG"); array_push($countryNameArray,"Egypt");
array_push($countryArray,"SV"); array_push($countryNameArray,"El Salvador");
array_push($countryArray,"GQ"); array_push($countryNameArray,"Equatorial Guinea");
array_push($countryArray,"ER"); array_push($countryNameArray,"Eritrea");
array_push($countryArray,"EE"); array_push($countryNameArray,"Estonia");
array_push($countryArray,"ET"); array_push($countryNameArray,"Ethiopia");
array_push($countryArray,"FK"); array_push($countryNameArray,"Falkland Islands (Malvinas)");
array_push($countryArray,"FO"); array_push($countryNameArray,"Faroe Islands");
array_push($countryArray,"FJ"); array_push($countryNameArray,"Fiji");
array_push($countryArray,"FI"); array_push($countryNameArray,"Finland");
array_push($countryArray,"FR"); array_push($countryNameArray,"France");
array_push($countryArray,"FX"); array_push($countryNameArray,"France, Metropolitan");
array_push($countryArray,"GF"); array_push($countryNameArray,"French Guiana");
array_push($countryArray,"PF"); array_push($countryNameArray,"French Polynesia");
array_push($countryArray,"TF"); array_push($countryNameArray,"French Southern Territories");
array_push($countryArray,"GA"); array_push($countryNameArray,"Gabon");
array_push($countryArray,"GM"); array_push($countryNameArray,"Gambia");
array_push($countryArray,"GE"); array_push($countryNameArray,"Georgia");
array_push($countryArray,"DE"); array_push($countryNameArray,"Germany");
array_push($countryArray,"GH"); array_push($countryNameArray,"Ghana");
array_push($countryArray,"GI"); array_push($countryNameArray,"Gibraltar");
array_push($countryArray,"GR"); array_push($countryNameArray,"Greece");
array_push($countryArray,"GL"); array_push($countryNameArray,"Greenland");
array_push($countryArray,"GD"); array_push($countryNameArray,"Grenada");
array_push($countryArray,"GP"); array_push($countryNameArray,"Guadeloupe");
array_push($countryArray,"GU"); array_push($countryNameArray,"Guam");
array_push($countryArray,"GT"); array_push($countryNameArray,"Guatemala");
array_push($countryArray,"GN"); array_push($countryNameArray,"Guinea");
array_push($countryArray,"GW"); array_push($countryNameArray,"Guinea-Bissau");
array_push($countryArray,"GY"); array_push($countryNameArray,"Guyana");
array_push($countryArray,"HT"); array_push($countryNameArray,"Haiti");
array_push($countryArray,"HM"); array_push($countryNameArray,"Heard and Mc Donald Islands");
array_push($countryArray,"VA"); array_push($countryNameArray,"Holy See (Vatican City State)");
array_push($countryArray,"HN"); array_push($countryNameArray,"Honduras");
array_push($countryArray,"HK"); array_push($countryNameArray,"Hong Kong");
array_push($countryArray,"HU"); array_push($countryNameArray,"Hungary");
array_push($countryArray,"IS"); array_push($countryNameArray,"Iceland");
array_push($countryArray,"IN"); array_push($countryNameArray,"India");
array_push($countryArray,"ID"); array_push($countryNameArray,"Indonesia");
array_push($countryArray,"IR"); array_push($countryNameArray,"Iran (Islamic Republic of)");
array_push($countryArray,"IQ"); array_push($countryNameArray,"Iraq");
array_push($countryArray,"IE"); array_push($countryNameArray,"Ireland");
array_push($countryArray,"IL"); array_push($countryNameArray,"Israel");
array_push($countryArray,"IT"); array_push($countryNameArray,"Italy");
array_push($countryArray,"JM"); array_push($countryNameArray,"Jamaica");
array_push($countryArray,"JP"); array_push($countryNameArray,"Japan");
array_push($countryArray,"JO"); array_push($countryNameArray,"Jordan");
array_push($countryArray,"KZ"); array_push($countryNameArray,"Kazakhstan");
array_push($countryArray,"KE"); array_push($countryNameArray,"Kenya");
array_push($countryArray,"KI"); array_push($countryNameArray,"Kiribati");
array_push($countryArray,"KP"); array_push($countryNameArray,"Korea, Democratic People's Republic of");
array_push($countryArray,"KR"); array_push($countryNameArray,"Korea, Republic of");
array_push($countryArray,"KW"); array_push($countryNameArray,"Kuwait");
array_push($countryArray,"KG"); array_push($countryNameArray,"Kyrgyzstan");
array_push($countryArray,"LA"); array_push($countryNameArray,"Lao People's Democratic Republic");
array_push($countryArray,"LV"); array_push($countryNameArray,"Latvia");
array_push($countryArray,"LB"); array_push($countryNameArray,"Lebanon");
array_push($countryArray,"LS"); array_push($countryNameArray,"Lesotho");
array_push($countryArray,"LR"); array_push($countryNameArray,"Liberia");
array_push($countryArray,"LY"); array_push($countryNameArray,"Libyan Arab Jamahiriya");
array_push($countryArray,"LI"); array_push($countryNameArray,"Liechtenstein");
array_push($countryArray,"LT"); array_push($countryNameArray,"Lithuania");
array_push($countryArray,"LU"); array_push($countryNameArray,"Luxembourg");
array_push($countryArray,"MO"); array_push($countryNameArray,"Macau");
array_push($countryArray,"MK"); array_push($countryNameArray,"Macedonia, The Former Yugoslav Republic of");
array_push($countryArray,"MG"); array_push($countryNameArray,"Madagascar");
array_push($countryArray,"MW"); array_push($countryNameArray,"Malawi");
array_push($countryArray,"MY"); array_push($countryNameArray,"Malaysia");
array_push($countryArray,"MV"); array_push($countryNameArray,"Maldives");
array_push($countryArray,"ML"); array_push($countryNameArray,"Mali");
array_push($countryArray,"MT"); array_push($countryNameArray,"Malta");
array_push($countryArray,"MH"); array_push($countryNameArray,"Marshall Islands");
array_push($countryArray,"MQ"); array_push($countryNameArray,"Martinique");
array_push($countryArray,"MR"); array_push($countryNameArray,"Mauritania");
array_push($countryArray,"MU"); array_push($countryNameArray,"Mauritius");
array_push($countryArray,"YT"); array_push($countryNameArray,"Mayotte");
array_push($countryArray,"MX"); array_push($countryNameArray,"Mexico");
array_push($countryArray,"FM"); array_push($countryNameArray,"Micronesia, Federated States of");
array_push($countryArray,"MD"); array_push($countryNameArray,"Moldova, Republic of");
array_push($countryArray,"MC"); array_push($countryNameArray,"Monaco");
array_push($countryArray,"MN"); array_push($countryNameArray,"Mongolia");
array_push($countryArray,"MS"); array_push($countryNameArray,"Montserrat");
array_push($countryArray,"MA"); array_push($countryNameArray,"Morocco");
array_push($countryArray,"MZ"); array_push($countryNameArray,"Mozambique");
array_push($countryArray,"MM"); array_push($countryNameArray,"Myanmar");
array_push($countryArray,"NA"); array_push($countryNameArray,"Namibia");
array_push($countryArray,"NR"); array_push($countryNameArray,"Nauru");
array_push($countryArray,"NP"); array_push($countryNameArray,"Nepal");
array_push($countryArray,"NL"); array_push($countryNameArray,"Netherlands");
array_push($countryArray,"AN"); array_push($countryNameArray,"Netherlands Antilles");
array_push($countryArray,"NC"); array_push($countryNameArray,"New Caledonia");
array_push($countryArray,"NZ"); array_push($countryNameArray,"New Zealand");
array_push($countryArray,"NI"); array_push($countryNameArray,"Nicaragua");
array_push($countryArray,"NE"); array_push($countryNameArray,"Niger");
array_push($countryArray,"NG"); array_push($countryNameArray,"Nigeria");
array_push($countryArray,"NU"); array_push($countryNameArray,"Niue");
array_push($countryArray,"NF"); array_push($countryNameArray,"Norfolk Island");
array_push($countryArray,"MP"); array_push($countryNameArray,"Northern Mariana Islands");
array_push($countryArray,"NO"); array_push($countryNameArray,"Norway");
array_push($countryArray,"OM"); array_push($countryNameArray,"Oman");
array_push($countryArray,"PK"); array_push($countryNameArray,"Pakistan");
array_push($countryArray,"PW"); array_push($countryNameArray,"Palau");
array_push($countryArray,"PA"); array_push($countryNameArray,"Panama");
array_push($countryArray,"PG"); array_push($countryNameArray,"Papua New Guinea");
array_push($countryArray,"PY"); array_push($countryNameArray,"Paraguay");
array_push($countryArray,"PE"); array_push($countryNameArray,"Peru");
array_push($countryArray,"PH"); array_push($countryNameArray,"Philippines");
array_push($countryArray,"PN"); array_push($countryNameArray,"Pitcairn");
array_push($countryArray,"PL"); array_push($countryNameArray,"Poland");
array_push($countryArray,"PT"); array_push($countryNameArray,"Portugal");
array_push($countryArray,"PR"); array_push($countryNameArray,"Puerto Rico");
array_push($countryArray,"QA"); array_push($countryNameArray,"Qatar");
array_push($countryArray,"RE"); array_push($countryNameArray,"Reunion");
array_push($countryArray,"RO"); array_push($countryNameArray,"Romania");
array_push($countryArray,"RU"); array_push($countryNameArray,"Russian Federation");
array_push($countryArray,"RW"); array_push($countryNameArray,"Rwanda");
array_push($countryArray,"KN"); array_push($countryNameArray,"Saint Kitts and Nevis");
array_push($countryArray,"LC"); array_push($countryNameArray,"Saint LUCIA");
array_push($countryArray,"VC"); array_push($countryNameArray,"Saint Vincent and the Grenadines");
array_push($countryArray,"WS"); array_push($countryNameArray,"Samoa");
array_push($countryArray,"SM"); array_push($countryNameArray,"San Marino");
array_push($countryArray,"ST"); array_push($countryNameArray,"Sao Tome and Principe");
array_push($countryArray,"SA"); array_push($countryNameArray,"Saudi Arabia");
array_push($countryArray,"SN"); array_push($countryNameArray,"Senegal");
array_push($countryArray,"SC"); array_push($countryNameArray,"Seychelles");
array_push($countryArray,"SL"); array_push($countryNameArray,"Sierra Leone");
array_push($countryArray,"SG"); array_push($countryNameArray,"Singapore");
array_push($countryArray,"SK"); array_push($countryNameArray,"Slovakia (Slovak Republic)");
array_push($countryArray,"SI"); array_push($countryNameArray,"Slovenia");
array_push($countryArray,"SB"); array_push($countryNameArray,"Solomon Islands");
array_push($countryArray,"SO"); array_push($countryNameArray,"Somalia");
array_push($countryArray,"ZA"); array_push($countryNameArray,"South Africa");
array_push($countryArray,"GS"); array_push($countryNameArray,"South Georgia and the South Sandwich Islands");
array_push($countryArray,"ES"); array_push($countryNameArray,"Spain");
array_push($countryArray,"LK"); array_push($countryNameArray,"Sri Lanka");
array_push($countryArray,"SH"); array_push($countryNameArray,"St. Helena");
array_push($countryArray,"PM"); array_push($countryNameArray,"St. Pierre and Miquelon");
array_push($countryArray,"SD"); array_push($countryNameArray,"Sudan");
array_push($countryArray,"SR"); array_push($countryNameArray,"Suriname");
array_push($countryArray,"SJ"); array_push($countryNameArray,"Svalbard and Jan Mayen Islands");
array_push($countryArray,"SZ"); array_push($countryNameArray,"Swaziland");
array_push($countryArray,"SE"); array_push($countryNameArray,"Sweden");
array_push($countryArray,"CH"); array_push($countryNameArray,"Switzerland");
array_push($countryArray,"SY"); array_push($countryNameArray,"Syrian Arab Republic");
array_push($countryArray,"TW"); array_push($countryNameArray,"Taiwan, Province of China");
array_push($countryArray,"TJ"); array_push($countryNameArray,"Tajikistan");
array_push($countryArray,"TZ"); array_push($countryNameArray,"Tanzania, United Republic of");
array_push($countryArray,"TH"); array_push($countryNameArray,"Thailand");
array_push($countryArray,"TG"); array_push($countryNameArray,"Togo");
array_push($countryArray,"TK"); array_push($countryNameArray,"Tokelau");
array_push($countryArray,"TO"); array_push($countryNameArray,"Tonga");
array_push($countryArray,"TT"); array_push($countryNameArray,"Trinidad and Tobago");
array_push($countryArray,"TN"); array_push($countryNameArray,"Tunisia");
array_push($countryArray,"TR"); array_push($countryNameArray,"Turkey");
array_push($countryArray,"TM"); array_push($countryNameArray,"Turkmenistan");
array_push($countryArray,"TC"); array_push($countryNameArray,"Turks and Caicos Islands");
array_push($countryArray,"TV"); array_push($countryNameArray,"Tuvalu");
array_push($countryArray,"UG"); array_push($countryNameArray,"Uganda");
array_push($countryArray,"UA"); array_push($countryNameArray,"Ukraine");
array_push($countryArray,"AE"); array_push($countryNameArray,"United Arab Emirates");
array_push($countryArray,"GB"); array_push($countryNameArray,"United Kingdom");
array_push($countryArray,"US"); array_push($countryNameArray,"United States");
array_push($countryArray,"UM"); array_push($countryNameArray,"United States Minor Outlying Islands");
array_push($countryArray,"UY"); array_push($countryNameArray,"Uruguay");
array_push($countryArray,"UZ"); array_push($countryNameArray,"Uzbekistan");
array_push($countryArray,"VU"); array_push($countryNameArray,"Vanuatu");
array_push($countryArray,"VE"); array_push($countryNameArray,"Venezuela");
array_push($countryArray,"VN"); array_push($countryNameArray,"Viet Nam");
array_push($countryArray,"VG"); array_push($countryNameArray,"Virgin Islands (British)");
array_push($countryArray,"VI"); array_push($countryNameArray,"Virgin Islands (U.S.)");
array_push($countryArray,"WF"); array_push($countryNameArray,"Wallis and Futuna Islands");
array_push($countryArray,"EH"); array_push($countryNameArray,"Western Sahara");
array_push($countryArray,"YE"); array_push($countryNameArray,"Yemen");
array_push($countryArray,"YU"); array_push($countryNameArray,"Yugoslavia");
array_push($countryArray,"ZM"); array_push($countryNameArray,"Zambia");
array_push($countryArray,"ZW"); array_push($countryNameArray,"Zimbabwe");



?>
<!doctype html>
<html>
<head>
    <title>Delivery Info</title>
    <?php include('../webparts/headdata.php'); ?>
    <!-- dataTables -->
    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>

    <script src="ecommerce/js/delivery.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/ecommerce-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Delivery Info</h1>
                </div>
                <div class="pull-right">
                    <?php include('../webparts/index-info.php'); ?>
                </div>
            </div>
            <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="index.php">Dashboard</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="ecommerce/dashboard.php">eCommerce</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="ecommerce/delivery.php">Delivery Info</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-truck"></i> Delivery Info</h3>
                            <div class="actions">
                                <a href="ecommerce/delivery-edit.php" class="btn btn-mini" rel="tooltip" title="New Delivery item"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <table class="table" id="deliveryTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Country Codes</th>
                                    <th>Range</th>
                                    <th>Price</th>
                                    <th>Type</th>
                                </tr>
                                </thead>
                                <tbody id="deliveryBody">
                                <?php
                                $tableLength = count($delivery);
                                for ($i=0;$i<$tableLength;++$i) {
                                    ?>
                                    <tr>
                                        <td><a href="ecommerce/delivery-edit.php?del_id=<?php echo $delivery[$i]['del_id']; ?>"><?php echo $delivery[$i]['delnam']; ?></a></td>
                                        <td>

                                            <?php

                                            $deliveryList = explode(",",$delivery[$i]['delcod']);

                                            for ($d=0;$d<count($deliveryList);$d++) {

                                                $arrIdx = array_search($deliveryList[$d],$countryArray);

                                                echo $countryNameArray[$arrIdx].' ';

                                            }

                                            //echo $delivery[$i]['delcod'];
                                            ?>

                                        </td>
                                        <td><?php echo $delivery[$i]['deldis'].' - '.$delivery[$i]['maxdis']; ?></td>
                                        <td><?php echo $delivery[$i]['delpri']; ?></td>
                                        <td><?php echo $delivery[$i]['deltyp']; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
