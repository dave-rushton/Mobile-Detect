<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/delivery.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpDel = new DelDAO();
$editDeliveryID = (isset($_GET['del_id']) && is_numeric($_GET['del_id'])) ? $_GET['del_id'] : NULL;
$deliveryRec = NULL;
if (!is_null($editDeliveryID)) $deliveryRec = $TmpDel->select($editDeliveryID, NULL, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
    <title><?php echo ($deliveryRec) ? $deliveryRec->delnam : 'New Delivery Item'; ?> : PatchWorks Delivery Item
        Maintenance </title>
    <?php include('../webparts/headdata.php'); ?>
    <script src="ecommerce/js/delivery-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/ecommerce-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Delivery Item : <?php echo ($deliveryRec) ? $deliveryRec->delnam : 'New Delivery Item'; ?></h1>
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
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a><?php echo ($deliveryRec) ? $deliveryRec->delnam : 'New Delivery item'; ?></a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <form action="ecommerce/delivery_script.php" id="deliveryForm" class="form-horizontal form-bordered"
                      data-returnurl="ecommerce/delivery.php">
                    <div class="span6">
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-truck"></i> Delivery Item</h3>

                                <div class="actions">
                                    <a href="#" id="updateDeliveryBtn" class="btn btn-mini" rel="tooltip"
                                       title="Update"><i class="icon-save"></i></a>
                                    <a href="#" id="deleteDeliveryBtn" class="btn btn-mini" rel="tooltip"
                                       title="Delete"><i class="icon-trash"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">
                                <input type="hidden" name="del_id" id="id"
                                       value="<?php echo ($deliveryRec) ? $deliveryRec->del_id : '0'; ?>">

                                <div class="control-group">
                                    <label class="control-label">Delivery Name
                                        <small>identifying name</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="delnam"
                                               value="<?php echo ($deliveryRec) ? $deliveryRec->delnam : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Price
                                        <small>delivery price</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="delpri"
                                               value="<?php echo ($deliveryRec) ? $deliveryRec->delpri : '0.00'; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Delivery Country
                                        <small>Select countries for this delivery code</small>
                                    </label>

                                    <div class="controls">
                                        <input type="hidden" class="input-large" name="delcod"
                                               value="<?php echo ($deliveryRec) ? $deliveryRec->delcod : ''; ?>"/>

                                        <select multiple="multiple" class="input-large" name="cou_idselect" id="cou_idselect">

                                            <option value="AF" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AF'))) ? 'selected' : ''; ?>>Afghanistan</option>
                                            <option value="AL" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AL'))) ? 'selected' : ''; ?>>Albania</option>
                                            <option value="DZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'DZ'))) ? 'selected' : ''; ?>>Algeria</option>
                                            <option value="AS" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AS'))) ? 'selected' : ''; ?>>American Samoa</option>
                                            <option value="AD" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AD'))) ? 'selected' : ''; ?>>Andorra</option>
                                            <option value="AO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AO'))) ? 'selected' : ''; ?>>Angola</option>
                                            <option value="AI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AI'))) ? 'selected' : ''; ?>>Anguilla</option>
                                            <option value="AQ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AQ'))) ? 'selected' : ''; ?>>Antarctica</option>
                                            <option value="AG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AG'))) ? 'selected' : ''; ?>>Antigua and Barbuda</option>
                                            <option value="AR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AR'))) ? 'selected' : ''; ?>>Argentina</option>
                                            <option value="AM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AM'))) ? 'selected' : ''; ?>>Armenia</option>
                                            <option value="AW" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AW'))) ? 'selected' : ''; ?>>Aruba</option>
                                            <option value="AU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AU'))) ? 'selected' : ''; ?>>Australia</option>
                                            <option value="AT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AT'))) ? 'selected' : ''; ?>>Austria</option>
                                            <option value="AZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AZ'))) ? 'selected' : ''; ?>>Azerbaijan</option>
                                            <option value="BS" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BS'))) ? 'selected' : ''; ?>>Bahamas</option>
                                            <option value="BH" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BH'))) ? 'selected' : ''; ?>>Bahrain</option>
                                            <option value="BD" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BD'))) ? 'selected' : ''; ?>>Bangladesh</option>
                                            <option value="BB" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BB'))) ? 'selected' : ''; ?>>Barbados</option>
                                            <option value="BY" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BY'))) ? 'selected' : ''; ?>>Belarus</option>
                                            <option value="BE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BE'))) ? 'selected' : ''; ?>>Belgium</option>
                                            <option value="BZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BZ'))) ? 'selected' : ''; ?>>Belize</option>
                                            <option value="BJ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BJ'))) ? 'selected' : ''; ?>>Benin</option>
                                            <option value="BM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BM'))) ? 'selected' : ''; ?>>Bermuda</option>
                                            <option value="BT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BT'))) ? 'selected' : ''; ?>>Bhutan</option>
                                            <option value="BO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BO'))) ? 'selected' : ''; ?>>Bolivia</option>
                                            <option value="BA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BA'))) ? 'selected' : ''; ?>>Bosnia and Herzegowina</option>
                                            <option value="BW" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BW'))) ? 'selected' : ''; ?>>Botswana</option>
                                            <option value="BV" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BV'))) ? 'selected' : ''; ?>>Bouvet Island</option>
                                            <option value="BR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BR'))) ? 'selected' : ''; ?>>Brazil</option>
                                            <option value="IO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'IO'))) ? 'selected' : ''; ?>>British Indian Ocean Territory</option>
                                            <option value="BN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BN'))) ? 'selected' : ''; ?>>Brunei Darussalam</option>
                                            <option value="BG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BG'))) ? 'selected' : ''; ?>>Bulgaria</option>
                                            <option value="BF" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BF'))) ? 'selected' : ''; ?>>Burkina Faso</option>
                                            <option value="BI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'BI'))) ? 'selected' : ''; ?>>Burundi</option>
                                            <option value="KH" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KH'))) ? 'selected' : ''; ?>>Cambodia</option>
                                            <option value="CM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CM'))) ? 'selected' : ''; ?>>Cameroon</option>
                                            <option value="CA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CA'))) ? 'selected' : ''; ?>>Canada</option>
                                            <option value="CV" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CV'))) ? 'selected' : ''; ?>>Cape Verde</option>
                                            <option value="KY" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KY'))) ? 'selected' : ''; ?>>Cayman Islands</option>
                                            <option value="CF" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CF'))) ? 'selected' : ''; ?>>Central African Republic</option>
                                            <option value="TD" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TD'))) ? 'selected' : ''; ?>>Chad</option>
                                            <option value="CL" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CL'))) ? 'selected' : ''; ?>>Chile</option>
                                            <option value="CN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CN'))) ? 'selected' : ''; ?>>China</option>
                                            <option value="CX" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CX'))) ? 'selected' : ''; ?>>Christmas Island</option>
                                            <option value="CC" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CC'))) ? 'selected' : ''; ?>>Cocos (Keeling) Islands</option>
                                            <option value="CO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CO'))) ? 'selected' : ''; ?>>Colombia</option>
                                            <option value="KM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KM'))) ? 'selected' : ''; ?>>Comoros</option>
                                            <option value="CG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CG'))) ? 'selected' : ''; ?>>Congo</option>
                                            <option value="CD" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CD'))) ? 'selected' : ''; ?>>Congo, the Democratic Republic of the</option>
                                            <option value="CK" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CK'))) ? 'selected' : ''; ?>>Cook Islands</option>
                                            <option value="CR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CR'))) ? 'selected' : ''; ?>>Costa Rica</option>
                                            <option value="CI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CI'))) ? 'selected' : ''; ?>>Cote d'Ivoire</option>
                                            <option value="HR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'HR'))) ? 'selected' : ''; ?>>Croatia (Hrvatska)</option>
                                            <option value="CU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CU'))) ? 'selected' : ''; ?>>Cuba</option>
                                            <option value="CY" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CY'))) ? 'selected' : ''; ?>>Cyprus</option>
                                            <option value="CZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CZ'))) ? 'selected' : ''; ?>>Czech Republic</option>
                                            <option value="DK" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'DK'))) ? 'selected' : ''; ?>>Denmark</option>
                                            <option value="DJ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'DJ'))) ? 'selected' : ''; ?>>Djibouti</option>
                                            <option value="DM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'DM'))) ? 'selected' : ''; ?>>Dominica</option>
                                            <option value="DO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'DO'))) ? 'selected' : ''; ?>>Dominican Republic</option>
                                            <option value="TP" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TP'))) ? 'selected' : ''; ?>>East Timor</option>
                                            <option value="EC" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'EC'))) ? 'selected' : ''; ?>>Ecuador</option>
                                            <option value="EG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'EG'))) ? 'selected' : ''; ?>>Egypt</option>
                                            <option value="SV" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SV'))) ? 'selected' : ''; ?>>El Salvador</option>
                                            <option value="GQ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GQ'))) ? 'selected' : ''; ?>>Equatorial Guinea</option>
                                            <option value="ER" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'ER'))) ? 'selected' : ''; ?>>Eritrea</option>
                                            <option value="EE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'EE'))) ? 'selected' : ''; ?>>Estonia</option>
                                            <option value="ET" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'ET'))) ? 'selected' : ''; ?>>Ethiopia</option>
                                            <option value="FK" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'FK'))) ? 'selected' : ''; ?>>Falkland Islands (Malvinas)</option>
                                            <option value="FO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'FO'))) ? 'selected' : ''; ?>>Faroe Islands</option>
                                            <option value="FJ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'FJ'))) ? 'selected' : ''; ?>>Fiji</option>
                                            <option value="FI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'FI'))) ? 'selected' : ''; ?>>Finland</option>
                                            <option value="FR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'FR'))) ? 'selected' : ''; ?>>France</option>
                                            <option value="FX" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'FX'))) ? 'selected' : ''; ?>>France, Metropolitan</option>
                                            <option value="GF" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GF'))) ? 'selected' : ''; ?>>French Guiana</option>
                                            <option value="PF" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PF'))) ? 'selected' : ''; ?>>French Polynesia</option>
                                            <option value="TF" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TF'))) ? 'selected' : ''; ?>>French Southern Territories</option>
                                            <option value="GA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GA'))) ? 'selected' : ''; ?>>Gabon</option>
                                            <option value="GM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GM'))) ? 'selected' : ''; ?>>Gambia</option>
                                            <option value="GE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GE'))) ? 'selected' : ''; ?>>Georgia</option>
                                            <option value="DE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'DE'))) ? 'selected' : ''; ?>>Germany</option>
                                            <option value="GH" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GH'))) ? 'selected' : ''; ?>>Ghana</option>
                                            <option value="GI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GI'))) ? 'selected' : ''; ?>>Gibraltar</option>
                                            <option value="GR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GR'))) ? 'selected' : ''; ?>>Greece</option>
                                            <option value="GL" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GL'))) ? 'selected' : ''; ?>>Greenland</option>
                                            <option value="GD" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GD'))) ? 'selected' : ''; ?>>Grenada</option>
                                            <option value="GP" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GP'))) ? 'selected' : ''; ?>>Guadeloupe</option>
                                            <option value="GU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GU'))) ? 'selected' : ''; ?>>Guam</option>
                                            <option value="GT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GT'))) ? 'selected' : ''; ?>>Guatemala</option>
                                            <option value="GN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GN'))) ? 'selected' : ''; ?>>Guinea</option>
                                            <option value="GW" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GW'))) ? 'selected' : ''; ?>>Guinea-Bissau</option>
                                            <option value="GY" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GY'))) ? 'selected' : ''; ?>>Guyana</option>
                                            <option value="HT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'HT'))) ? 'selected' : ''; ?>>Haiti</option>
                                            <option value="HM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'HM'))) ? 'selected' : ''; ?>>Heard and Mc Donald Islands</option>
                                            <option value="VA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'VA'))) ? 'selected' : ''; ?>>Holy See (Vatican City State)</option>
                                            <option value="HN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'HN'))) ? 'selected' : ''; ?>>Honduras</option>
                                            <option value="HK" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'HK'))) ? 'selected' : ''; ?>>Hong Kong</option>
                                            <option value="HU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'HU'))) ? 'selected' : ''; ?>>Hungary</option>
                                            <option value="IS" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'IS'))) ? 'selected' : ''; ?>>Iceland</option>
                                            <option value="IN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'IN'))) ? 'selected' : ''; ?>>India</option>
                                            <option value="ID" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'ID'))) ? 'selected' : ''; ?>>Indonesia</option>
                                            <option value="IR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'IR'))) ? 'selected' : ''; ?>>Iran (Islamic Republic of)</option>
                                            <option value="IQ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'IQ'))) ? 'selected' : ''; ?>>Iraq</option>
                                            <option value="IE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'IE'))) ? 'selected' : ''; ?>>Ireland</option>
                                            <option value="IL" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'IL'))) ? 'selected' : ''; ?>>Israel</option>
                                            <option value="IT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'IT'))) ? 'selected' : ''; ?>>Italy</option>
                                            <option value="JM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'JM'))) ? 'selected' : ''; ?>>Jamaica</option>
                                            <option value="JP" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'JP'))) ? 'selected' : ''; ?>>Japan</option>
                                            <option value="JO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'JO'))) ? 'selected' : ''; ?>>Jordan</option>
                                            <option value="KZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KZ'))) ? 'selected' : ''; ?>>Kazakhstan</option>
                                            <option value="KE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KE'))) ? 'selected' : ''; ?>>Kenya</option>
                                            <option value="KI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KI'))) ? 'selected' : ''; ?>>Kiribati</option>
                                            <option value="KP" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KP'))) ? 'selected' : ''; ?>>Korea, Democratic People's Republic of</option>
                                            <option value="KR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KR'))) ? 'selected' : ''; ?>>Korea, Republic of</option>
                                            <option value="KW" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KW'))) ? 'selected' : ''; ?>>Kuwait</option>
                                            <option value="KG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KG'))) ? 'selected' : ''; ?>>Kyrgyzstan</option>
                                            <option value="LA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LA'))) ? 'selected' : ''; ?>>Lao People's Democratic Republic</option>
                                            <option value="LV" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LV'))) ? 'selected' : ''; ?>>Latvia</option>
                                            <option value="LB" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LB'))) ? 'selected' : ''; ?>>Lebanon</option>
                                            <option value="LS" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LS'))) ? 'selected' : ''; ?>>Lesotho</option>
                                            <option value="LR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LR'))) ? 'selected' : ''; ?>>Liberia</option>
                                            <option value="LY" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LY'))) ? 'selected' : ''; ?>>Libyan Arab Jamahiriya</option>
                                            <option value="LI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LI'))) ? 'selected' : ''; ?>>Liechtenstein</option>
                                            <option value="LT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LT'))) ? 'selected' : ''; ?>>Lithuania</option>
                                            <option value="LU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LU'))) ? 'selected' : ''; ?>>Luxembourg</option>
                                            <option value="MO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MO'))) ? 'selected' : ''; ?>>Macau</option>
                                            <option value="MK" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MK'))) ? 'selected' : ''; ?>>Macedonia, The Former Yugoslav Republic of</option>
                                            <option value="MG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MG'))) ? 'selected' : ''; ?>>Madagascar</option>
                                            <option value="MW" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MW'))) ? 'selected' : ''; ?>>Malawi</option>
                                            <option value="MY" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MY'))) ? 'selected' : ''; ?>>Malaysia</option>
                                            <option value="MV" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MV'))) ? 'selected' : ''; ?>>Maldives</option>
                                            <option value="ML" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'ML'))) ? 'selected' : ''; ?>>Mali</option>
                                            <option value="MT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MT'))) ? 'selected' : ''; ?>>Malta</option>
                                            <option value="MH" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MH'))) ? 'selected' : ''; ?>>Marshall Islands</option>
                                            <option value="MQ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MQ'))) ? 'selected' : ''; ?>>Martinique</option>
                                            <option value="MR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MR'))) ? 'selected' : ''; ?>>Mauritania</option>
                                            <option value="MU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MU'))) ? 'selected' : ''; ?>>Mauritius</option>
                                            <option value="YT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'YT'))) ? 'selected' : ''; ?>>Mayotte</option>
                                            <option value="MX" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MX'))) ? 'selected' : ''; ?>>Mexico</option>
                                            <option value="FM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'FM'))) ? 'selected' : ''; ?>>Micronesia, Federated States of</option>
                                            <option value="MD" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MD'))) ? 'selected' : ''; ?>>Moldova, Republic of</option>
                                            <option value="MC" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MC'))) ? 'selected' : ''; ?>>Monaco</option>
                                            <option value="MN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MN'))) ? 'selected' : ''; ?>>Mongolia</option>
                                            <option value="MS" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MS'))) ? 'selected' : ''; ?>>Montserrat</option>
                                            <option value="MA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MA'))) ? 'selected' : ''; ?>>Morocco</option>
                                            <option value="MZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MZ'))) ? 'selected' : ''; ?>>Mozambique</option>
                                            <option value="MM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MM'))) ? 'selected' : ''; ?>>Myanmar</option>
                                            <option value="NA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NA'))) ? 'selected' : ''; ?>>Namibia</option>
                                            <option value="NR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NR'))) ? 'selected' : ''; ?>>Nauru</option>
                                            <option value="NP" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NP'))) ? 'selected' : ''; ?>>Nepal</option>
                                            <option value="NL" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NL'))) ? 'selected' : ''; ?>>Netherlands</option>
                                            <option value="AN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AN'))) ? 'selected' : ''; ?>>Netherlands Antilles</option>
                                            <option value="NC" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NC'))) ? 'selected' : ''; ?>>New Caledonia</option>
                                            <option value="NZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NZ'))) ? 'selected' : ''; ?>>New Zealand</option>
                                            <option value="NI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NI'))) ? 'selected' : ''; ?>>Nicaragua</option>
                                            <option value="NE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NE'))) ? 'selected' : ''; ?>>Niger</option>
                                            <option value="NG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NG'))) ? 'selected' : ''; ?>>Nigeria</option>
                                            <option value="NU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NU'))) ? 'selected' : ''; ?>>Niue</option>
                                            <option value="NF" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NF'))) ? 'selected' : ''; ?>>Norfolk Island</option>
                                            <option value="MP" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'MP'))) ? 'selected' : ''; ?>>Northern Mariana Islands</option>
                                            <option value="NO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'NO'))) ? 'selected' : ''; ?>>Norway</option>
                                            <option value="OM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'OM'))) ? 'selected' : ''; ?>>Oman</option>
                                            <option value="PK" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PK'))) ? 'selected' : ''; ?>>Pakistan</option>
                                            <option value="PW" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PW'))) ? 'selected' : ''; ?>>Palau</option>
                                            <option value="PA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PA'))) ? 'selected' : ''; ?>>Panama</option>
                                            <option value="PG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PG'))) ? 'selected' : ''; ?>>Papua New Guinea</option>
                                            <option value="PY" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PY'))) ? 'selected' : ''; ?>>Paraguay</option>
                                            <option value="PE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PE'))) ? 'selected' : ''; ?>>Peru</option>
                                            <option value="PH" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PH'))) ? 'selected' : ''; ?>>Philippines</option>
                                            <option value="PN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PN'))) ? 'selected' : ''; ?>>Pitcairn</option>
                                            <option value="PL" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PL'))) ? 'selected' : ''; ?>>Poland</option>
                                            <option value="PT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PT'))) ? 'selected' : ''; ?>>Portugal</option>
                                            <option value="PR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PR'))) ? 'selected' : ''; ?>>Puerto Rico</option>
                                            <option value="QA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'QA'))) ? 'selected' : ''; ?>>Qatar</option>
                                            <option value="RE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'RE'))) ? 'selected' : ''; ?>>Reunion</option>
                                            <option value="RO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'RO'))) ? 'selected' : ''; ?>>Romania</option>
                                            <option value="RU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'RU'))) ? 'selected' : ''; ?>>Russian Federation</option>
                                            <option value="RW" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'RW'))) ? 'selected' : ''; ?>>Rwanda</option>
                                            <option value="KN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'KN'))) ? 'selected' : ''; ?>>Saint Kitts and Nevis</option>
                                            <option value="LC" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LC'))) ? 'selected' : ''; ?>>Saint LUCIA</option>
                                            <option value="VC" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'VC'))) ? 'selected' : ''; ?>>Saint Vincent and the Grenadines</option>
                                            <option value="WS" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'WS'))) ? 'selected' : ''; ?>>Samoa</option>
                                            <option value="SM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SM'))) ? 'selected' : ''; ?>>San Marino</option>
                                            <option value="ST" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'ST'))) ? 'selected' : ''; ?>>Sao Tome and Principe</option>
                                            <option value="SA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SA'))) ? 'selected' : ''; ?>>Saudi Arabia</option>
                                            <option value="SN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SN'))) ? 'selected' : ''; ?>>Senegal</option>
                                            <option value="SC" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SC'))) ? 'selected' : ''; ?>>Seychelles</option>
                                            <option value="SL" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SL'))) ? 'selected' : ''; ?>>Sierra Leone</option>
                                            <option value="SG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SG'))) ? 'selected' : ''; ?>>Singapore</option>
                                            <option value="SK" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SK'))) ? 'selected' : ''; ?>>Slovakia (Slovak Republic)</option>
                                            <option value="SI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SI'))) ? 'selected' : ''; ?>>Slovenia</option>
                                            <option value="SB" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SB'))) ? 'selected' : ''; ?>>Solomon Islands</option>
                                            <option value="SO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SO'))) ? 'selected' : ''; ?>>Somalia</option>
                                            <option value="ZA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'ZA'))) ? 'selected' : ''; ?>>South Africa</option>
                                            <option value="GS" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GS'))) ? 'selected' : ''; ?>>South Georgia and the South Sandwich Islands</option>
                                            <option value="ES" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'ES'))) ? 'selected' : ''; ?>>Spain</option>
                                            <option value="LK" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'LK'))) ? 'selected' : ''; ?>>Sri Lanka</option>
                                            <option value="SH" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SH'))) ? 'selected' : ''; ?>>St. Helena</option>
                                            <option value="PM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'PM'))) ? 'selected' : ''; ?>>St. Pierre and Miquelon</option>
                                            <option value="SD" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SD'))) ? 'selected' : ''; ?>>Sudan</option>
                                            <option value="SR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SR'))) ? 'selected' : ''; ?>>Suriname</option>
                                            <option value="SJ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SJ'))) ? 'selected' : ''; ?>>Svalbard and Jan Mayen Islands</option>
                                            <option value="SZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SZ'))) ? 'selected' : ''; ?>>Swaziland</option>
                                            <option value="SE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SE'))) ? 'selected' : ''; ?>>Sweden</option>
                                            <option value="CH" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'CH'))) ? 'selected' : ''; ?>>Switzerland</option>
                                            <option value="SY" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'SY'))) ? 'selected' : ''; ?>>Syrian Arab Republic</option>
                                            <option value="TW" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TW'))) ? 'selected' : ''; ?>>Taiwan, Province of China</option>
                                            <option value="TJ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TJ'))) ? 'selected' : ''; ?>>Tajikistan</option>
                                            <option value="TZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TZ'))) ? 'selected' : ''; ?>>Tanzania, United Republic of</option>
                                            <option value="TH" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TH'))) ? 'selected' : ''; ?>>Thailand</option>
                                            <option value="TG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TG'))) ? 'selected' : ''; ?>>Togo</option>
                                            <option value="TK" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TK'))) ? 'selected' : ''; ?>>Tokelau</option>
                                            <option value="TO" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TO'))) ? 'selected' : ''; ?>>Tonga</option>
                                            <option value="TT" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TT'))) ? 'selected' : ''; ?>>Trinidad and Tobago</option>
                                            <option value="TN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TN'))) ? 'selected' : ''; ?>>Tunisia</option>
                                            <option value="TR" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TR'))) ? 'selected' : ''; ?>>Turkey</option>
                                            <option value="TM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TM'))) ? 'selected' : ''; ?>>Turkmenistan</option>
                                            <option value="TC" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TC'))) ? 'selected' : ''; ?>>Turks and Caicos Islands</option>
                                            <option value="TV" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'TV'))) ? 'selected' : ''; ?>>Tuvalu</option>
                                            <option value="UG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'UG'))) ? 'selected' : ''; ?>>Uganda</option>
                                            <option value="UA" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'UA'))) ? 'selected' : ''; ?>>Ukraine</option>
                                            <option value="AE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'AE'))) ? 'selected' : ''; ?>>United Arab Emirates</option>
                                            <option value="GB" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'GB'))) ? 'selected' : ''; ?>>United Kingdom</option>
                                            <option value="US" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'US'))) ? 'selected' : ''; ?>>United States</option>
                                            <option value="UM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'UM'))) ? 'selected' : ''; ?>>United States Minor Outlying Islands</option>
                                            <option value="UY" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'UY'))) ? 'selected' : ''; ?>>Uruguay</option>
                                            <option value="UZ" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'UZ'))) ? 'selected' : ''; ?>>Uzbekistan</option>
                                            <option value="VU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'VU'))) ? 'selected' : ''; ?>>Vanuatu</option>
                                            <option value="VE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'VE'))) ? 'selected' : ''; ?>>Venezuela</option>
                                            <option value="VN" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'VN'))) ? 'selected' : ''; ?>>Viet Nam</option>
                                            <option value="VG" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'VG'))) ? 'selected' : ''; ?>>Virgin Islands (British)</option>
                                            <option value="VI" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'VI'))) ? 'selected' : ''; ?>>Virgin Islands (U.S.)</option>
                                            <option value="WF" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'WF'))) ? 'selected' : ''; ?>>Wallis and Futuna Islands</option>
                                            <option value="EH" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'EH'))) ? 'selected' : ''; ?>>Western Sahara</option>
                                            <option value="YE" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'YE'))) ? 'selected' : ''; ?>>Yemen</option>
                                            <option value="YU" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'YU'))) ? 'selected' : ''; ?>>Yugoslavia</option>
                                            <option value="ZM" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'ZM'))) ? 'selected' : ''; ?>>Zambia</option>
                                            <option value="ZW" <?php echo (isset($deliveryRec) && is_numeric(strpos($deliveryRec->delcod, 'ZW'))) ? 'selected' : ''; ?>>Zimbabwe</option>

                                        </select>

                                        <input type="hidden" name="prd_id">


                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Delivery Unit
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <select class="input-large" name="deltyp">

                                            <option value="PRICE" <?php echo ($deliveryRec && $deliveryRec->deltyp == 'PRICE') ? 'selected' : ''; ?>>Price</option>
                                            <option value="WEIGHT" <?php echo ($deliveryRec && $deliveryRec->deltyp == 'WEIGHT') ? 'selected' : ''; ?>>Weight</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Min. Weight
                                        <small>minimum weight for delivery</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="deldis"
                                               value="<?php echo ($deliveryRec) ? $deliveryRec->deldis : '0'; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Max. Weight
                                        <small>maximum weight for delivery</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="maxdis"
                                               value="<?php echo ($deliveryRec) ? $deliveryRec->maxdis : '0'; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Status
                                        <small>current delivery item status</small>
                                    </label>

                                    <div class="controls">
                                        <label class="radio">
                                            <input type="radio" name="sta_id"
                                                   value="0" <?php echo ($deliveryRec && $deliveryRec->sta_id == 0) ? 'checked' : ''; ?>>
                                            Active</label>
                                        <label class="radio">
                                            <input type="radio" name="sta_id"
                                                   value="1" <?php echo ($deliveryRec && $deliveryRec->sta_id == 1) ? 'checked' : ''; ?>>
                                            In-Active </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
</body>
</html>
