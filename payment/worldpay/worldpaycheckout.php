<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');

function convertCurrency($amount, $from, $to){
    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
    $data = file_get_contents($url);
    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
    return round($converted, 2);
}

$currConv = 1;
$dispCurr = '&pound;';
$wpCurr = 'GBP';

if (
    isset($_POST['currency']) && $_POST['currency'] != 'GBP' ||
    isset($_SESSION['currency']) && $_SESSION['currency'] != 'GBP'
) {
    $currConv = convertCurrency(1,'GBP','USD');
    $dispCurr = '$';
    $wpCurr = 'USD';
}


function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

function randomString($length = 6) {
    $str = "";
    $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}

function correctDiscount($Ord_ID = NULL)
{

    if (!is_numeric($Ord_ID)) return false;

    $TmpOrd = new OrdDAO();
    $TmpDis = new DisDAO();
    $OlnDao = new OlnDAO();

    $orders = $TmpOrd->select($Ord_ID, NULL, NULL, NULL, false);

    $tableLength = count($orders);
    for ($i = 0; $i < $tableLength; ++$i) {

        if (!empty($orders[$i]['discod'])) {

            $orderlines = $OlnDao->select($orders[$i]['ord_id'], NULL, false);

            $discountRec = $TmpDis->selectByCode($orders[$i]['discod'], true);

        } else {
            continue;
        }

        // determine if line or basket based

        $basketAmount = 0;

        for ($l = 0; $l < count($orderlines); $l++) {

            $basketAmount = $basketAmount + ($orderlines[$l]['numuni'] * $orderlines[$l]['unipri']);

        }

        if (!$discountRec) continue;

        if (
            $discountRec->sub_id > 0 ||
            $discountRec->atr_id > 0 ||
            $discountRec->prt_id > 0 ||
            $discountRec->prd_id != ''
        ) {

            // BASKET ITEM DISCOUNT

            for ($l = 0; $l < count($orderlines); $l++) {

                $applyLineDiscount = false;

                if ($discountRec->sub_id > 0 && $discountRec->sub_id == $orderlines[$l]['sub_id']) {
                    $applyLineDiscount = true;
                }
                if ($discountRec->atr_id > 0 && $discountRec->atr_id == $orderlines[$l]['atr_id']) {
                    $applyLineDiscount = true;
                }
                if ($discountRec->prt_id > 0 && $discountRec->prt_id == $orderlines[$l]['prt_id']) {
                    $applyLineDiscount = true;
                }

                $prdArr = explode(",", $discountRec->prd_id);
                if (in_array($orderlines[$l]['prd_id'], $prdArr)) {
                    $applyLineDiscount = true;
                }

                //
                // DISCOUNT MATCHES PRODUCT
                //

                if ($applyLineDiscount == true) {

                    // GET ORIGINAL PRICE FROM PRODUCT
                    // APPLY DISCOUNT TO ORDER LINE

                    echo 'ORDER: ' . $orders[$i]['ord_id'] . ' ORDER LINE ID: ' . $orderlines[$l]['oln_id'] . ' DISCOUNT: ' . $discountRec->disnam . '<br>';

                }

            }

        } else {

            // BASKET DISCOUNT

            //
            // CREATE NEW DISCOUNT LINE WITH NO PRODUCT
            //

            echo 'ORDER: ' . $orders[$i]['ord_id'] . ' TOTAL: ' . $basketAmount . ' DISCOUNT: ' . $discountRec->disnam . '<br>';

            if ($discountRec->pctamt == 'A') {

                $discountAmount = number_format($discountRec->disamt, 2, '.', '');

                echo '-&pound;' . number_format($discountRec->disamt, 2, '.', '') . '<br>';
            } else {

                $discountAmount = number_format((($basketAmount / 100) * $discountRec->disamt), 2, '.', '');

                echo '-&pound;' . number_format((($basketAmount / 100) * $discountRec->disamt), 2, '.', '') . '<br>';
            }

            $discountAmount = $discountAmount * -1;

            $OlnObj = new stdClass();
            $OlnObj->oln_id = 0;
            $OlnObj->ord_id = $orders[$i]['ord_id'];
            $OlnObj->prd_id = 0;
            $OlnObj->numuni = 1;
            $OlnObj->unipri = $discountAmount;
            $OlnObj->vatrat = 20;
            $OlnObj->olndsc = $discountRec->disnam;
            $OlnObj->tblnam = 'DISCOUNT';
            $OlnObj->tbl_id = 0;
            $OlnObj->sta_id = 0;

            $OlnDao->update($OlnObj);


        }

    }

}

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/system/classes/places.cls.php");
require_once("../../admin/products/classes/product_types.cls.php");
require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");
require_once("../../admin/system/classes/related.cls.php");
require_once("../../admin/ecommerce/classes/delivery.cls.php");
//require_once("../admin/ecommerce/classes/deliveryextra.cls.php");
require_once("../../admin/ecommerce/classes/discounts.cls.php");
require_once("../../admin/ecommerce/classes/order.cls.php");
require_once("../../admin/ecommerce/classes/orderline.cls.php");


$TmpPrd = new PrdDAO();
$OrdDao = new OrdDAO();
$OlnDao = new OlnDAO();


$shoppingCart = (isset($_SESSION['cart'])) ? json_decode($_SESSION['cart'], true) : array();

//echo '<pre>'.print_r($_SESSION['cart']).'</pre>';

$orderToken = '';
$totalOrderPrice = 0;


//
// Build Basket
//


$HldAltRef = getGUID();

$basketItemCount = 0;

if (isset($shoppingCart['items']) && is_array($shoppingCart['items'])) {

    $basketItemCount = count($shoppingCart['items']);

    for ($i=0;$i<count($shoppingCart['items']);$i++) {

        $lineAmount = number_format($shoppingCart['items'][$i]['unipri'] * $shoppingCart['items'][$i]['qty'],2);
        $unitPrice = $shoppingCart['items'][$i]['unipri'];

        if (isset($shoppingCart['items'][$i]['discod'])) {

            if ( $shoppingCart['items'][$i]['pctamt'] == 'A' ) {

                $lineAmount -= ($shoppingCart['items'][$i]['disamt'] * $shoppingCart['items'][$i]['qty']);
                $unitPrice -= $shoppingCart['items'][$i]['disamt'];

            } else {

                $lineAmount -= ($lineAmount / 100) * $shoppingCart['items'][$i]['disamt'];
                $unitPrice -= ( $shoppingCart['items'][$i]['unipri'] / 100 ) * $shoppingCart['items'][$i]['disamt'];

            }

        }

        $totalOrderPrice += number_format($lineAmount,2);

        $vatAmount = ($unitPrice / 100) * 20;

    }

    if (isset($shoppingCart['discod'])) {

        if ($shoppingCart['pctamt'] == 'A') {
            $totalOrderPrice = $totalOrderPrice - $shoppingCart['disamt'];
        } else {
            $totalOrderPrice = $totalOrderPrice - (($totalOrderPrice / 100) * $shoppingCart['disamt']);
        }

    }

    if (isset($shoppingCart['del_id'])) {

        $totalOrderPrice += $shoppingCart['delpri'];

    }



    //
    // WORLDPAY does not return to website so create order to find on ordercomplete.
    //

    $totalAmount = 0;

    //
    // Create Order
    //





    $OrdDao = new OrdDAO();
    $OlnDao = new OlnDAO();

    $OrdObj = new stdClass();

    $OrdObj->ord_id = 0;
    $OrdObj->ordtyp = 0;
    $OrdObj->invdat = date('Y-m-d H:i:s');
    $OrdObj->duedat = date('Y-m-d H:i:s');
    $OrdObj->paydat = date('Y-m-d H:i:s');
    $OrdObj->cusnam = $shoppingCart['customer']['cusfna'] . ' ' . $shoppingCart['customer']['cussna'];
    $OrdObj->adr1 = $shoppingCart['customer']['adr1'];
    $OrdObj->adr2 = $shoppingCart['customer']['adr2'];
    $OrdObj->adr3 = $shoppingCart['customer']['adr3'];
    $OrdObj->adr4 = $shoppingCart['customer']['adr4'];
    $OrdObj->pstcod = $shoppingCart['customer']['pstcod'];
    $OrdObj->payadr1 = $shoppingCart['customer']['payadr1'];
    $OrdObj->payadr2 = $shoppingCart['customer']['payadr2'];
    $OrdObj->payadr3 = $shoppingCart['customer']['payadr3'];
    $OrdObj->payadr4 = $shoppingCart['customer']['payadr4'];
    $OrdObj->paypstcod = $shoppingCart['customer']['paypstcod'];
    $OrdObj->paytrm = 'TEL: ' . $shoppingCart['customer']['custel'] . ' MOB: ' . $shoppingCart['customer']['cusmob'];
    $OrdObj->vatrat = ($wpCurr == 'GBP') ? 20 : 0;
    $OrdObj->tblnam = 'CUS';
    $OrdObj->tbl_id = 0;
    $OrdObj->sta_id = 10;
    $OrdObj->altref = $HldAltRef;
    $OrdObj->altnam = '';
    $OrdObj->del_id = (isset($shoppingCart['del_id'])) ? $shoppingCart['del_id'] : 0; //$shoppingCart['del_id'];
    $OrdObj->discod = (isset($shoppingCart['discod'])) ? $shoppingCart['discod'] : '';
    $OrdObj->emaadr = $shoppingCart['customer']['cusema'];

    $Ord_ID = $OrdDao->update($OrdObj);

    $_SESSION['confirmationOrderID'] = $Ord_ID;

    unset($_SESSION['ordertoken']);

    if (is_array($shoppingCart['items'])) {
        for ($i = 0; $i < count($shoppingCart['items']); $i++) {

            //
            // Create Order Lines
            //

            $lineDescription = $shoppingCart['items'][$i]['prdnam'];
            $lineAmount = $shoppingCart['items'][$i]['unipri'];
            $discountSign = '�';

            if (isset($shoppingCart['items'][$i]['discod'])) {

                if ($shoppingCart['items'][$i]['pctamt'] == 'A') {

                    $lineAmount -= $shoppingCart['items'][$i]['disamt'];

                    $lineDescription .= ' (�' . $shoppingCart['items'][$i]['disamt'] . ' discount)';

                } else {

                    $lineAmount -= ($lineAmount / 100) * $shoppingCart['items'][$i]['disamt'];

                    $lineDescription .= ' (' . $shoppingCart['items'][$i]['disamt'] . '% discount)';

                }

            }


            $OlnObj = new stdClass();
            $OlnObj->oln_id = 0;
            $OlnObj->ord_id = $Ord_ID;
            $OlnObj->prd_id = $shoppingCart['items'][$i]['prd_id'];
            $OlnObj->numuni = $shoppingCart['items'][$i]['qty'];
            $OlnObj->unipri = $lineAmount; // $shoppingCart['items'][$i]['totamt'];

            //
            // FIND VAT RATE
            //


            $vatRat = 20;
            if (isset($vatRecord->vatrat)) {
                $vatRat = $vatRecord->vatrat;
            }


            if ($wpCurr != 'GBP') $vatRat = 0;


            $OlnObj->vatrat = $vatRat;
            $OlnObj->olndsc = $lineDescription;
            $OlnObj->tblnam = 'SALE';
            $OlnObj->tbl_id = 0;
            $OlnObj->sta_id = 0;

            $OlnDao->update($OlnObj);

        }
    }

    //
    // CORRECT DISCOUNT
    //

    correctDiscount($Ord_ID);

}


?>


<script src="../js/jquery.js"></script>
<script>

    $(function(){
        $('#WorldPayForm').submit();
    });

</script>

<!--<form action="https://secure-test.worldpay.com/wcc/purchase" method="post" name="BuyForm" id="WorldPayForm">-->
<form action="https://secure.worldpay.com/wcc/purchase" method="post" name="BuyForm" id="WorldPayForm">
    <input type="hidden" name="instId"  value="1003816"><!-- The "instId" value "211616" should be replaced with the Merchant's own installation Id -->
    <input type="hidden" name="cartId"  value="ONLINEORDER-<?php echo randomString(10); ?>"><!-- This is a unique identifier for merchants use. Example: PRODUCT123 -->
    <input type="hidden" name="currency"  value="<?php echo $wpCurr; ?>"><!-- Choose appropriate currency that you would like to use -->
    <input type="hidden" name="desc"  value="ONLINE PURCHASE">
    <input type="hidden" name="amount"  value="<?php echo $totalOrderPrice * $currConv; ?>">
    <input type="hidden" name="testMode"  value="0">
<!--    <input type="hidden" name="testMode"  value="0">-->
    <input type="hidden" name="MC_altref" value="<?php echo $HldAltRef; //echo $shoppingCart['payaltref']; ?>">
    <!-- This generates a button that submits the information and sends the user to the Worldpay payment pages. -->
    <p align="center"><input type="submit" value="Purchase"></p>
</form>

<?php

//
// AT POINT OF SALE - CLEAR SHOPPING CART. NOT IDEAL BUT WORLDPAY DOES NOT SEE SESSIONS
//

unset($_SESSION['cart']);

?>