<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once('../../../pages/shoppingcart/classes/shoppingcart.cls.php');
require_once("../../../admin/products/classes/product_types.cls.php");
require_once("../../../admin/products/classes/products.cls.php");
require_once("../../../admin/ecommerce/classes/order.cls.php");
require_once("../../../admin/ecommerce/classes/orderline.cls.php");
require_once("../../../admin/products/classes/discounts.cls.php");
require_once("../../../admin/ecommerce/classes/delivery.cls.php");
require_once("../../../admin/system/classes/messages.cls.php");
require_once("../../../admin/ecommerce/classes/ecommprop.cls.php");
require_once("../../../admin/system/classes/places.cls.php");

$shoppingcart = new shoppingCart();

$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn( (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : NULL);

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'search') {

    //$shoppingcart->addProduct($_REQUEST['prd_id'],$_REQUEST['qty']);

    $TmpPrd = new PrdDAO();
    $product = $TmpPrd->importCheck(NULL, NULL, $_REQUEST['searchterm']);

    if (isset($product->prd_id)) {

        $shoppingcart->addProduct($product->prd_id);

    }

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add') {

    $shoppingcart->addProduct($_REQUEST['prd_id'],$_REQUEST['qty']);

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'remove') {

    $shoppingcart->removeProduct($_REQUEST['prd_id']);

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'removerow') {

    $shoppingcart->removeRow($_REQUEST['rownum']);

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateqty') {

    $shoppingcart->updateQty($_REQUEST['prd_id'],$_REQUEST['qty']);

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updaterowqty') {

    $shoppingcart->updateRowQty($_REQUEST['rownum'],$_REQUEST['qty']);

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'clear') {

    $shoppingcart->clearCart();

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updatedetails') {

    $shoppingcart->shoppingCart['customer'] = array();

    if (isset($_REQUEST['delisbill']) && $_REQUEST['delisbill'] == 1) {

        $shoppingcart->shoppingCart['customer']['delisbill'] = 1;
        $shoppingcart->shoppingCart['customer']['cusnam']    = (!empty($_REQUEST['paycus'])) ? $_REQUEST['paycus'] : $_REQUEST['payfao'];
        $shoppingcart->shoppingCart['customer']['fao']       = $_REQUEST['payfao'];
        $shoppingcart->shoppingCart['customer']['adr1']      = $_REQUEST['payadr1'];
        $shoppingcart->shoppingCart['customer']['adr2']      = $_REQUEST['payadr2'];
        $shoppingcart->shoppingCart['customer']['adr3']      = $_REQUEST['payadr3'];
        $shoppingcart->shoppingCart['customer']['adr4']      = $_REQUEST['payadr4'];
        $shoppingcart->shoppingCart['customer']['pstcod']    = $_REQUEST['paypstcod'];
        $shoppingcart->shoppingCart['customer']['coucod']    = $_REQUEST['paycountry'];

    } else {

        $shoppingcart->shoppingCart['customer']['delisbill'] = 0;
        $shoppingcart->shoppingCart['customer']['cusnam']    = (!empty($_REQUEST['cusnam'])) ? $_REQUEST['cusnam'] : $_REQUEST['cusnam'];
        $shoppingcart->shoppingCart['customer']['fao']       = $_REQUEST['fao'];
        $shoppingcart->shoppingCart['customer']['adr1']      = $_REQUEST['adr1'];
        $shoppingcart->shoppingCart['customer']['adr2']      = $_REQUEST['adr2'];
        $shoppingcart->shoppingCart['customer']['adr3']      = $_REQUEST['adr3'];
        $shoppingcart->shoppingCart['customer']['adr4']      = $_REQUEST['adr4'];
        $shoppingcart->shoppingCart['customer']['pstcod']    = $_REQUEST['pstcod'];
        $shoppingcart->shoppingCart['customer']['coucod']    = $_REQUEST['country'];

    }

    $shoppingcart->shoppingCart['customer']['paycus']    = (!empty($_REQUEST['paycus'])) ? $_REQUEST['paycus'] : $_REQUEST['payfao'];
    $shoppingcart->shoppingCart['customer']['payfao']    = $_REQUEST['payfao'];
    $shoppingcart->shoppingCart['customer']['payadr1']   = $_REQUEST['payadr1'];
    $shoppingcart->shoppingCart['customer']['payadr2']   = $_REQUEST['payadr2'];
    $shoppingcart->shoppingCart['customer']['payadr3']   = $_REQUEST['payadr3'];
    $shoppingcart->shoppingCart['customer']['payadr4']   = $_REQUEST['payadr4'];
    $shoppingcart->shoppingCart['customer']['paypstcod'] = $_REQUEST['paypstcod'];
    $shoppingcart->shoppingCart['customer']['paycoucod'] = $_REQUEST['paycountry'];

    $shoppingcart->shoppingCart['customer']['cusema']    = $_REQUEST['cusema'];
    $shoppingcart->shoppingCart['customer']['cusmob']    = $_REQUEST['cusmob'];
    $shoppingcart->shoppingCart['customer']['custel']    = $_REQUEST['custel'];

    $shoppingcart->updateCartSession();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'collectorder') {

    //
    // clear down delivery
    //

    unset($shoppingcart->shoppingCart['delivery']);

    if (!isset($shoppingcart->shoppingCart['orderToken']) || !is_numeric($shoppingcart->shoppingCart['orderToken'])) {

        $Ord_ID = $shoppingcart->convertSessionToOrder(NULL, $loggedIn, 'Collect', 1);
        $orderToken = $Ord_ID;

    } else {

        $Ord_ID = $shoppingcart->convertSessionToOrder( $shoppingcart->shoppingCart['orderToken'], $loggedIn, 'Collect', 1 );
        $orderToken = $shoppingcart->shoppingCart['orderToken'];

    }

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'shoppayment') {

    $shoppingcart->shoppingCart['customer']['delisbill'] = 0;
    $shoppingcart->shoppingCart['customer']['cusnam']    = 'Shop Sale';
    $shoppingcart->shoppingCart['customer']['fao']       = 'Shop Sale';
    $shoppingcart->shoppingCart['customer']['adr1']      = '';
    $shoppingcart->shoppingCart['customer']['adr2']      = '';
    $shoppingcart->shoppingCart['customer']['adr3']      = '';
    $shoppingcart->shoppingCart['customer']['adr4']      = '';
    $shoppingcart->shoppingCart['customer']['pstcod']    = '';
    $shoppingcart->shoppingCart['customer']['coucod']    = '';
    $shoppingcart->shoppingCart['customer']['paycus']    = 'Shop Sale';
    $shoppingcart->shoppingCart['customer']['payfao']    = 'Shop Sale';
    $shoppingcart->shoppingCart['customer']['payadr1']   = '';
    $shoppingcart->shoppingCart['customer']['payadr2']   = '';
    $shoppingcart->shoppingCart['customer']['payadr3']   = '';
    $shoppingcart->shoppingCart['customer']['payadr4']   = '';
    $shoppingcart->shoppingCart['customer']['paypstcod'] = '';
    $shoppingcart->shoppingCart['customer']['paycoucod'] = '';
    $shoppingcart->shoppingCart['customer']['cusema']    = '';
    $shoppingcart->shoppingCart['customer']['cusmob']    = '';
    $shoppingcart->shoppingCart['customer']['custel']    = '';

    $Ord_ID = $shoppingcart->convertSessionToOrder( NULL, NULL, 'Shop' );

    $OrdDao = new OrdDAO();
    $OrdDao->confirmOrderPayment($Ord_ID);

    unset($shoppingCart);
    $shoppingCart = array();
    $_SESSION['cart'] = json_encode($shoppingCart);

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'manualadd') {

    $shoppingcart->manualAdd($_REQUEST['linedescription'], $_REQUEST['lineamount'],$_REQUEST['quantity']);
    $shoppingcart->updateCartSession();

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'table') {

    $dispCurr = '&pound;';
    $currConv = 1;
    $outputHTML = '';

    $totalAmount = 0;

    if (isset($shoppingcart->shoppingCart['items']) && is_array($shoppingcart->shoppingCart['items'])) {

        for ($i = 0; $i < count($shoppingcart->shoppingCart['items']); $i++) {

            $TmpPrd = new PrdDAO();
            $productRec = $TmpPrd->select($shoppingcart->shoppingCart['items'][$i]['prd_id'], NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL);

            $UniPri = $shoppingcart->shoppingCart['items'][$i]['unipri'];

            if (isset($productRec->unipri)) {
                $UniPri = $productRec->unipri;

                if ($productRec->delpri > 0 && $productRec->delpri < $UniPri) {

                    $UniPri = $productRec->delpri;

                }

            }

            $lineAmount = number_format($UniPri * $shoppingcart->shoppingCart['items'][$i]['qty'], 2, '.', '');

            $totalAmount += number_format($lineAmount, 2, '.', '');

            $outputHTML .= '<tr>';
            $outputHTML .= '<td style="font-weight: bold;;"><a href="" class="btn btn-small btn-primary removeRow" data-itemrow="'.$i.'"><i class="icon icon-remove"></i></a></td>';
            $outputHTML .= '    <td style="font-weight: bold;;">'.$shoppingcart->shoppingCart['items'][$i]['prtnam'].'</td>';
            $outputHTML .= '    <td style="text-align: right;">'.$UniPri.'</td>';
            $outputHTML .= '    <td style="text-align: right;"><input type="number" class="input-small" style="margin-bottom: 0; text-align: right;" value="'.$shoppingcart->shoppingCart['items'][$i]['qty'].'" data-prd_id="'.$shoppingcart->shoppingCart['items'][$i]['prd_id'].'" data-itemrow="'.$i.'"></td>';
            $outputHTML .= '    <td style="text-align: right; font-weight: bold;">'.$dispCurr . number_format($lineAmount * $currConv, 2, '.', '').'</td>';
            $outputHTML .= '</tr>';

        }
    }

    $outputHTML .= '<tr>';
    $outputHTML .= '    <td colspan="3"></td>';
    $outputHTML .= '    <td></td>';
    $outputHTML .= '<td style="text-align: right; font-weight: bold;"><input type="number" class="input-small" id="totalPrice" style="margin-bottom: 0; text-align: right;" value="'.number_format($totalAmount * $currConv, 2, '.', '').'" disabled></td>';
    $outputHTML .= '</tr>';

    die($outputHTML);

}

die();

?>