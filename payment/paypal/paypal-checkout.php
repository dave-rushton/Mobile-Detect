<?php

function basketVAT($items = [], $vatObj = null)
{
    $TmpPrd = new PrdDAO();

    if ( empty($items) ) {
        return 0.00;
    }

    if ( empty($vatObj) ) {
        return 0.00;
    }

    $basketVat = 0.00;

    $vatRate = $vatObj->vatrat;
//    echo 'VAT Rate: ' . $vatObj->vatrat . '<br/>';

    foreach ($items as $item) {
        $productRec = $TmpPrd->select(
            $item['prd_id'],
            null,
            null,
            null,
            null,
            null,
            null,
            true
        );

//        echo '-----------<br/>';
//        echo 'Item ID: ' . $item['prt_id'] . ' (' . $item['prd_id'] . ')<br/>';
//        echo 'No. Items: ' . $item['qty'] . '<br/>';
//        echo 'Item Price: &pound;' . $productRec->unipri . '<br/>';

        $itemVat = (floatval($productRec->unipri) / 100) * $vatRate;

//        echo 'Item VAT: &pound;' . $itemVat . '<br/>';
//        echo 'Price inc. VAT: &pound;' . ($productRec->unipri + $itemVat) . '<br/>';

        $basketVat += $itemVat * intval($item['qty']);
//        echo 'Total VAT: &pound;' . $basketVat . '<br/>';
    }

    return number_format($basketVat, 2);
}

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/ecommerce/classes/order.cls.php");
require_once("../../admin/ecommerce/classes/orderline.cls.php");
require_once("../../admin/ecommerce/classes/ecommprop.cls.php");
require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");
require_once("../../pages/shoppingcart/classes/shoppingcart.cls.php");
require_once("../../pages/email/email.php");
require_once("../../admin/ecommerce/classes/vat.cls.php");

$shoppingcart = new shoppingCart();

$TmpEco = new EcoDAO();
$ecoProp = $TmpEco->select(TRUE);

//
// SEND CONFIRMATION EMAIL
//
$TmpOrd = new OrdDAO();
$TmpOln = new OlnDAO();
$TmpPrd = new PrdDAO();
$TmpUpl = new UplDAO();
$TmpVat = new VatDAO();
$order = new stdClass();

$vatObj = $TmpVat->select(null, null, null, true, true);

if ( isset($_REQUEST['orderID']) ) {

    $order->ord_id = 0;
    $order->ordtyp = 0;
    $order->invdat = date('Y-m-d h:i:s');
    $order->duedat = date('Y-m-d h:i:s');
    $order->paydat = date('Y-m-d h:i:s');
    $order->tblnam = 'CUS';
    $order->tbl_id = 0;
    $order->cusnam = $shoppingcart->shoppingCart['customer']['cusnam'];
    $order->custtl = $shoppingcart->shoppingCart['customer']['custtl'];
    $order->cusfna = $shoppingcart->shoppingCart['customer']['cusfna'];
    $order->cussna = $shoppingcart->shoppingCart['customer']['cussna'];
    $order->ordfao = $shoppingcart->shoppingCart['customer']['cusfao'];
    $order->adr1 = $shoppingcart->shoppingCart['customer']['adr1'];
    $order->adr2 = $shoppingcart->shoppingCart['customer']['adr2'];
    $order->adr3 = $shoppingcart->shoppingCart['customer']['adr3'];
    $order->adr4 = $shoppingcart->shoppingCart['customer']['adr4'];
    $order->pstcod = $shoppingcart->shoppingCart['customer']['pstcod'];
    $order->coucod = $shoppingcart->shoppingCart['customer']['coucod'];
    $order->paycus = $shoppingcart->shoppingCart['customer']['cusnam'];
    $order->payadr1 = $shoppingcart->shoppingCart['customer']['payadr1'];
    $order->payadr2 = $shoppingcart->shoppingCart['customer']['payadr2'];
    $order->payadr3 = $shoppingcart->shoppingCart['customer']['payadr3'];
    $order->payadr4 = $shoppingcart->shoppingCart['customer']['payadr4'];
    $order->paypstcod = $shoppingcart->shoppingCart['customer']['paypstcod'];
    $order->paycoucod = $shoppingcart->shoppingCart['customer']['paycoucod'];
    $order->paytrm = $shoppingcart->shoppingCart['customer']['cusmob'] . ' ' . $shoppingcart->shoppingCart['customer']['custel'];
    $order->vatrat = 0.00;
    $order->sta_id = 20;
    $order->altref = $_REQUEST['orderID'];
    $order->altnam = 'PayPal';
    $order->del_id = $shoppingcart->shoppingCart['delivery']['del_id'];
    $order->discod = '';
    $order->emaadr = $shoppingcart->shoppingCart['customer']['cusema'];
    $order->ordobj = json_encode([
        'items' => $shoppingcart->shoppingCart['items'],
        'tandcs' => $shoppingcart->shoppingCart['customer']['tandcs'],
        'marketing' => $shoppingcart->shoppingCart['customer']['marketing']
     ]);
    $order_id = $TmpOrd->update($order);

    $orderTotal = 0;
    foreach ( $shoppingcart->shoppingCart['items'] as $item ) {
        $productRec = $TmpPrd->select(
            $item['prd_id'],
            null,
            null,
            null,
            null,
            null,
            null,
            true
        );

        $orderTotal += $productRec->unipri * $item['qty'];

        $orderline = new stdClass();
        $orderline->oln_id = 0;
        $orderline->ord_id = $order_id;
        $orderline->prd_id = $item['prd_id'];
        $orderline->numuni = $item['qty'];
        $orderline->unipri = $item['unipri'];
        $orderline->vatrat = $item['vat_id'];
        $orderline->olndsc = $item['prtnam'] . ' - ' . $item['prdnam'];
        $orderline->tblnam = 'SALE';
        $orderline->tbl_id = 0;
        $orderline->sta_id = 0;

        $TmpOln->update($orderline);
    }

    $orderline = new stdClass();
    $orderline->oln_id = 0;
    $orderline->ord_id = $order_id;
    $orderline->prd_id = 0;
    $orderline->numuni = 1.00;
    $orderline->unipri = $shoppingcart->shoppingCart['delivery']['delpri'];
    $orderline->vatrat = 0.00;
    $orderline->olndsc = $shoppingcart->shoppingCart['delivery']['delnam'];
    $orderline->tblnam = 'DELIVERY';
    $orderline->tbl_id = $shoppingcart->shoppingCart['delivery']['del_id'];
    $orderline->sta_id = 0;
    $TmpOln->update($orderline);

    $vat = basketVAT($shoppingcart->shoppingCart['items'], $vatObj);
    $deliveryVat = ($shoppingcart->shoppingCart['delivery']['delpri'] / 100) * $vatObj->vatrat;
    $totalVat = $vat + $deliveryVat;

    $template = file_get_contents($patchworks->docRoot . 'pages/emails/order_confirmation.html');

    $company_address = "";
    $company_address .= $ecoProp->comnam."<br/>";
    $company_address .= (!empty($ecoProp->adr1)?$ecoProp->adr1:"");
    $company_address .= (!empty($ecoProp->adr2)?"<br/>".$ecoProp->adr2:"");
    $company_address .= (!empty($ecoProp->adr3)?"<br/>".$ecoProp->adr3:"");
    $company_address .= (!empty($ecoProp->adr4)?"<br/>".$ecoProp->adr4:"");
    $company_address .= (!empty($ecoProp->pstcod)?"<br/>".$ecoProp->pstcod:"");
    $company_address .= (!empty($ecoProp->emaadr)?"<br/><a href='mailto" . $ecoProp->emaadr . "'>".$ecoProp->emaadr."</a>":"");
    $company_address .= (!empty($ecoProp->comtel)?"<br/><a href='tel:+44" . str_replace(" ","",substr($ecoProp->comtel,1)) . "'>".$ecoProp->comtel."</a>":"");

    $replace = [
        'WEB_ROOT' => $patchworks->webRoot . 'pages',
        'ORDER_ID' => $order_id,
        'COMPANY_ADDRESS' => $company_address,

        'BILLING_NAME' => '<strong>' . $shoppingcart->shoppingCart['customer']['cusfna'] . ' ' . $shoppingcart->shoppingCart['customer']['cussna'] . '</strong>',
        'BILLING_ADDRESS' => (!empty($order->payadr1)?$order->payadr1:"") .
            (!empty($order->payadr2)?"<br/>".$order->payadr2:"") .
            (!empty($order->payadr3)?"<br/>".$order->payadr3:"") .
            (!empty($order->payadr4)?"<br/>".$order->payadr4:"") .
            (!empty($order->paypstcod)?"<br/>".$order->paypstcod:""),
        'BILLING_EMAIL' => $order->emaadr,
        'BILLING_TEL' => $order->paytrm,
        'COMPANY' => $ecoProp->comnam,

        'CONTACT_NAME' => '<strong>' . $shoppingcart->shoppingCart['customer']['cusfna'] . ' ' . $shoppingcart->shoppingCart['customer']['cussna'] . '</strong>',
        'CONTACT_ADDRESS' =>  (!empty($order->adr1)?$order->adr1:"") .
            (!empty($order->adr2)?"<br/>".$order->adr2:"") .
            (!empty($order->adr3)?"<br/>".$order->adr3:"") .
            (!empty($order->adr4)?"<br/>".$order->adr4:"") .
            (!empty($order->pstcod)?"<br/>".$order->pstcod:""),
        'CONTACT_EMAIL' => $order->emaadr,
        'CONTACT_TEL' => $order->paytrm,

        'ORDERLINES' => $shoppingcart->shoppingCart['items'],

        'SUBTOTAL' => number_format($orderTotal, 2),
        'SHIPPING' => number_format($shoppingcart->shoppingCart['delivery']['delpri'], 2),
        'VAT' => number_format($totalVat, 2),
        'TOTAL' => number_format(($orderTotal + $shoppingcart->shoppingCart['delivery']['delpri']) + $totalVat, 2),

        'PAYMENT' => 'Online - PayPal',
        'ORDER_DATE' => date('jS F Y'),
        'NOTE' => ''
    ];

    foreach ( $replace as $key => $value ) {
        if ( $key === 'ORDERLINES' ) {
            $orders = '';
            foreach ( $value as $key2 => $orderline ) {
                $productRec = $TmpPrd->select(
                    $orderline['prd_id'],
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    true
                );

                $padding = ($key2 === 0) ? 'padding: 1em 0 2em 0;' : 'padding: 2em 0;';
                $orders .= '<tr style="border-bottom: 1px solid #000">
                            <td valign="top" style=" color:#000; ' . $padding . '">' . $orderline['qty'] . '</td>
                            <td valign="top" style="color:#000;' . $padding . '">
                                <table>
                                    <tr>
                                        <td valign="top">
                                            <strong>' . strtoupper($orderline['prtnam']) . ' - ' . $orderline['prdnam'] . '</strong><br/><small><em style="color: #737373">Ref: ' . $orderline['prd_id'] . '</em></small>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td valign="top" style="color:#000;' . $padding . ' text-align: right">&pound;' . number_format($productRec->unipri, 2) . '</td>
                            <td valign="top" style="color:#000;' . $padding . ' text-align: right">&pound;' . number_format($productRec->unipri * $orderline['qty'], 2) . '</td>
                        </tr>';
            }

            $template = str_replace('{' . $key . '}', $orders, $template);
        } else {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
    }

    $headers = "From: " . $patchworks->adminEmail . "\r\n";
    $headers .= "Reply-To: " . $patchworks->adminEmail . "\r\n";
    $headers .= "CC: " . $patchworks->adminEmail . "\r\n";
    $headers .= "BCC: " . $patchworks->adminEmail . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if ( $_REQUEST['preview'] ) {
        echo $template;
    } else {
        $sendOK = email($order->emaadr, $ecoProp->comnam.' | Order Confirmation', $template, [
            'domain' => $patchworks->webRoot,
            'email' => $patchworks->adminEmail
        ]);

        if ( $sendOK ) {
            header('location: ' . $patchworks->webRoot . 'checkout/complete');
        }
    }
}
