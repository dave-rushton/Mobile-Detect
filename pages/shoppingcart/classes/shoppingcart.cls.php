<?php


class shoppingCart extends db
{

    public $shoppingCart;
    public $totalPrice;
    public $orderToken;

    function shoppingCart()
    {

        // Pull in session to object
        if (isset($_POST['cart'])) {

            $this->shoppingCart = json_decode($_POST['cart'], true);
        } else {

            $this->shoppingCart = (isset($_SESSION['cart'])) ? json_decode($_SESSION['cart'], true) : array();
        }

        $this->totalPrice = 0;

    }

    function clearCart()
    {
    }


    function priceProduct($Prd_ID = NULL, $NumUni = NULL, $Cus_ID=NULL) {

        $deBug = false;
        $UniPri = 0;

        if ($deBug) echo '<h1>PRICE PRODUCT</h1>';

        if (!is_null($Prd_ID) && is_numeric($Prd_ID)) {

            $qryArray = array();
            $sql = 'SELECT p.*, pt.prtnam, pt.vat_id AS pt_vat FROM products p INNER JOIN producttypes pt ON pt.prt_id = p.prt_id WHERE p.prd_id = :prd_id';
            $qryArray["prd_id"] = $Prd_ID;
            $product = $this->run($sql, $qryArray, true);

            if ($product) {
                // set default price
                $UniPri = $product->unipri;

                if ($deBug) echo 'Default Price: '.$UniPri.'<br>';

                // PRICE TYPE CHECK

                // for each cart item create prt array of qty and price
                // read bands for this data and update price

                $prtArray = array();
                $priArray = array();
                $qtyArray = array();

                if (isset($this->shoppingCart['items']) && is_array($this->shoppingCart['items'])) {

                    for ($i = 0; $i < count($this->shoppingCart['items']); $i++) {

                        if ( $this->shoppingCart['items'][$i]['prt_id'] != $product->prt_id ) continue;

                        if ($deBug) echo $this->shoppingCart['items'][$i]['prt_id'] .' == '. $product->prt_id.'<br>';

                        if ( !in_array($this->shoppingCart['items'][$i]['prt_id'], $prtArray)) {

                            if ($deBug) echo 'NOT FOUND : '.$this->shoppingCart['items'][$i]['qty'].'<br>';

                            array_push($prtArray, $product->prt_id);
                            array_push($priArray, $UniPri);
                            array_push($qtyArray, $this->shoppingCart['items'][$i]['qty']);

                        } else {

                            if ($deBug) echo 'UPDATE : '.$this->shoppingCart['items'][$i]['qty'].'<br>';

                            $key = array_search($product->prt_id, $prtArray);
                            $priArray[$key] = $UniPri;
                            $qtyArray[$key] += $this->shoppingCart['items'][$i]['qty'];

                        }

                    }
                }

                for ( $p = 0; $p < count($prtArray); $p++ ) {

                    $qryArray = array();
                    $sql = 'SELECT * FROM pricebands WHERE prt_id = :prt_id AND numuni <= :numuni AND (prd_id IS NULL OR prd_id = "") ORDER BY unipri ASC';
                    $qryArray["prt_id"] = $prtArray[$p];
                    $qryArray["numuni"] = $qtyArray[$p];

                    //if ($deBug) $this->displayQuery($sql, $qryArray);
                    $priceBand = $this->run($sql, $qryArray, true);

                    if (isset($priceBand->unipri)) {
                        $priArray[$p] = $priceBand->unipri;
                    }

                }


//                if ($deBug) echo '<pre>'.var_dump($prtArray).'</pre>';
//                if ($deBug) echo '<pre>'.var_dump($priArray).'</pre>';
//                if ($deBug) echo '<pre>'.var_dump($qtyArray).'</pre>';

                // COMPARE TO PRODUCT

                for ( $p = 0; $p < count($prtArray); $p++ ) {

                    if ($deBug) echo 'Product Type Loop '.$product->prdnam.' ('.$prtArray[$p].':'.$product->prd_id.') x '.$qtyArray[$p].' @ &pound;'.$priArray[$p].'<br>';

                    if ($priArray[$p] < $UniPri) $UniPri = $priArray[$p];
                }

                // find price band for product type qty

                if ($deBug) echo 'Product Type Check '.$NumUni.' units at &pound;'.$UniPri.' (PRD: '.$Prd_ID.' ~ CUS: '.$Cus_ID.')<br>';

                $qryArray = array();
                $sql = 'SELECT * FROM pricebands WHERE prd_id = :prd_id AND numuni <= :numuni ORDER BY unipri ASC';
                $qryArray["prd_id"] = $Prd_ID;
                $qryArray["numuni"] = $NumUni;

                if ($deBug) $this->displayQuery($sql, $qryArray);
                $priceBand = $this->run($sql, $qryArray, true);

                if ($priceBand) {
                    //echo 'PRICEBAND: '.$priceBand->unipri;
                    $UniPri = $priceBand->unipri;
                }

                // find price band for customer

                if ($deBug) echo 'after prd check<br>';
                if ($deBug) echo $NumUni.' '.$UniPri.'<br>';

                if (is_numeric($Cus_ID) && $Cus_ID > 0) {
                    $qryArray = array();
                    $sql = 'SELECT * FROM pricebands WHERE prd_id = :prd_id AND numuni <= :numuni AND (cus_id IS NOT NULL AND cus_id != "" AND cus_id = :cus_id) ORDER BY unipri ASC ';
                    $qryArray["prd_id"] = $Prd_ID;
                    $qryArray["numuni"] = $NumUni;
                    $qryArray["cus_id"] = $Cus_ID;

                    if ($deBug) $this->displayQuery($sql, $qryArray);
                    $priceBand = $this->run($sql, $qryArray, true);

                    if ($priceBand) {
                        $UniPri = $priceBand->unipri;
                    }
                }

            }

            if ($deBug) echo 'after customer check<br>';
            if ($deBug) echo $UniPri.'<br>';

            if (isset($this->shoppingCart['items']) && is_array($this->shoppingCart['items'])) {

                for ($i = 0; $i < count($this->shoppingCart['items']); $i++) {
                    if ( $this->shoppingCart['items'][$i]['prt_id'] == $product->prt_id ) {
                        $this->shoppingCart['items'][$i]['unipri'] = $UniPri;
                    }
                }

                $this->updateCartSession();

            }

        }

        return number_format($UniPri,2,".","");

    }


    function addProduct($Prd_ID = NULL, $Qty = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT p.*, pt.prtnam, pt.vat_id AS pt_vat FROM products p INNER JOIN producttypes pt ON pt.prt_id = p.prt_id WHERE p.prd_id = :prd_id';
        $qryArray["prd_id"] = $Prd_ID;
        $product = $this->run($sql, $qryArray, true);

        $foundProduct = false;
        if (isset($this->shoppingCart['items']) && is_array($this->shoppingCart['items'])) {
            for ($i = 0; $i < count($this->shoppingCart['items']); $i++) {
                if ($this->shoppingCart['items'][$i]['prd_id'] == $Prd_ID) {
                    $foundProduct = true;

                    if (isset($Qty) && is_numeric($Qty)) {
                        $this->shoppingCart['items'][$i]['qty'] = $Qty;
                    } else {
                        $this->shoppingCart['items'][$i]['qty']++;
                    }

                }
            }
        }

        if (!$foundProduct) {

            $shoppingCartItem = array();
            $shoppingCartItem['atr_id'] = $product->atr_id;
            $shoppingCartItem['prt_id'] = $product->prt_id;
            $shoppingCartItem['prd_id'] = $product->prd_id;
            $shoppingCartItem['altref'] = $product->altref;
            $shoppingCartItem['prtnam'] = $product->prtnam;
            $shoppingCartItem['prdnam'] = $product->prdnam;
            $shoppingCartItem['qty'] = (isset($Qty) && is_numeric($Qty)) ? $Qty : 1;
            $shoppingCartItem['unipri'] = $this->priceProduct($product->prd_id, $shoppingCartItem['qty'], NULL); //$product->unipri;
            $shoppingCartItem['vat_id'] = $product->pt_vat;

            //if ( $shoppingCartItem['qty'] > $product->in_stk ) $shoppingCartItem['qty'] = $product->in_stk;

            if (!isset($this->shoppingCart['items'])) $this->shoppingCart['items'] = array();

            array_push($this->shoppingCart['items'], $shoppingCartItem);

        }

        $this->updateCartSession();

    }

    function removeProduct($Prd_ID)
    {

        for ($i = 0; $i < count($this->shoppingCart['items']); $i++) {
            if ($this->shoppingCart['items'][$i]['prd_id'] == $Prd_ID) {
                if ($this->shoppingCart['items'][$i]['qty'] > 1) {
                    $this->shoppingCart['items'][$i]['qty']--;
                } else {
                    unset($this->shoppingCart['items'][$i]);
                }
            }
        }

        $this->updateCartSession();

    }

    function updateQty($Prd_ID, $Qty)
    {

        $qryArray = array();
        $sql = 'SELECT p.*, pt.prtnam, pt.vat_id AS pt_vat FROM products p INNER JOIN producttypes pt ON pt.prt_id = p.prt_id WHERE p.prd_id = :prd_id';
        $qryArray["prd_id"] = $Prd_ID;
        $product = $this->run($sql, $qryArray, true);

        for ($b = 0; $b < count($this->shoppingCart['items']); $b++) {
            if ($this->shoppingCart['items'][$b]['prd_id'] == $Prd_ID) {
                if ($Qty == 0) {
                    unset($this->shoppingCart['items'][$b]);
                } else {
                    $this->shoppingCart['items'][$b]['qty'] = $Qty;

                    // CHECK IF USE STOCK
                    if ( $product->in_stk > 0 ) {

                        if ($this->shoppingCart['items'][$b]['qty'] > $product->in_stk) $this->shoppingCart['items'][$b]['qty'] = $product->in_stk;

                    }

                    // UPDATE PRICE ON BAND
                    $this->shoppingCart['items'][$b]['unipri'] = $this->priceProduct( $this->shoppingCart['items'][$b]['prd_id'], $Qty, NULL );


                }
            }
        }

        $this->updateCartSession();

    }

    function updateCartSession()
    {

        // Update object to session
        if (is_array($this->shoppingCart['items'])) $this->shoppingCart['items'] = array_values($this->shoppingCart['items']);
        $_SESSION['cart'] = json_encode($this->shoppingCart);

    }

    function setDelivery($Del_ID = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT * FROM delivery WHERE del_id = :del_id ';
        $qryArray["del_id"] = $Del_ID;
        $deliveryInfo = $this->run($sql, $qryArray, true);

        $this->shoppingCart['delivery'] = array();
        $this->shoppingCart['delivery']['del_id'] = $deliveryInfo->del_id;
        $this->shoppingCart['delivery']['delnam'] = $deliveryInfo->delnam;
        $this->shoppingCart['delivery']['delpri'] = $deliveryInfo->delpri;
        $this->shoppingCart['delivery']['delcod'] = $deliveryInfo->delcod;

        $this->updateCartSession();

    }


    function isFreeDelivery()
    {

        $freeDelivery = false;
        $free24 = false;
        for ($i = 0; $i < count($this->shoppingCart['items']); $i++) {

            $qryArray = array();
            $sql = 'SELECT * FROM producttypes WHERE prt_id = :prt_id';
            $qryArray["prt_id"] = $this->shoppingCart['items'][$i]['prt_id'];
            $productRec = $this->run($sql, $qryArray, true);

            if (isset($productRec->prtobj)) {

                if (
                    $this->getJSONVariable($productRec->prtobj, 'fredel', false) == 1 ||
                    $this->getJSONVariable($productRec->prtobj, 'fre24d', false) == 1
                ) {

                    $freeDelivery = true;

                    if ( $this->getJSONVariable($productRec->prtobj, 'fre24d', false) == 1 ) $free24 = true;

                    break;

                }

            }

        }

        if ($freeDelivery == true) {

            $this->shoppingCart['delivery'] = array();
            $this->shoppingCart['delivery']['del_id'] = 0;
            $this->shoppingCart['delivery']['delnam'] = ($free24 == true) ? 'FREE 24hr DELIVERY' : 'FREE DELIVERY';
            $this->shoppingCart['delivery']['delpri'] = 0;
            $this->shoppingCart['delivery']['delcod'] = '';

        }

        $this->updateCartSession();

        return $freeDelivery;

    }


    function getCustomer($Pla_ID = NULL)
    {

        if (is_numeric($Pla_ID)) {
            $qryArray = array();
            $sql = 'SELECT * FROM places WHERE pla_id = :pla_id ';
            $qryArray["pla_id"] = $Pla_ID;
            $customerRec = $this->run($sql, $qryArray, true);

            if ($customerRec) {

                $this->shoppingCart['customer'] = array();
                $this->shoppingCart['customer']['cusnam'] = $customerRec->comnam;

                $this->shoppingCart['customer']['custtl'] = $customerRec->plattl;
                $this->shoppingCart['customer']['cusfna'] = $customerRec->plafna;
                $this->shoppingCart['customer']['cussna'] = $customerRec->plasna;

                $this->shoppingCart['customer']['fao'] = $customerRec->planam;
                $this->shoppingCart['customer']['ordfao'] = $customerRec->planam;

                $this->shoppingCart['customer']['delisbill'] = (isset($shoppingCart['customer']['delisbill'])) ? $shoppingCart['customer']['delisbill'] : 1;

                $this->shoppingCart['customer']['adr1'] = $customerRec->adr1;
                $this->shoppingCart['customer']['adr2'] = $customerRec->adr2;
                $this->shoppingCart['customer']['adr3'] = $customerRec->adr3;
                $this->shoppingCart['customer']['adr4'] = $customerRec->adr4;
                $this->shoppingCart['customer']['pstcod'] = $customerRec->pstcod;
                $this->shoppingCart['customer']['coucod'] = $customerRec->coucod;

                $this->shoppingCart['customer']['paycus'] = $customerRec->comnam;
                $this->shoppingCart['customer']['payfao'] = $customerRec->planam;
                $this->shoppingCart['customer']['payadr1'] = $customerRec->adr1;
                $this->shoppingCart['customer']['payadr2'] = $customerRec->adr2;
                $this->shoppingCart['customer']['payadr3'] = $customerRec->adr3;
                $this->shoppingCart['customer']['payadr4'] = $customerRec->adr4;
                $this->shoppingCart['customer']['paypstcod'] = $customerRec->pstcod;
                $this->shoppingCart['customer']['paycoucod'] = $customerRec->coucod;
                $this->shoppingCart['customer']['cusema'] = $customerRec->plaema;
                $this->shoppingCart['customer']['cusmob'] = $customerRec->plamob;
                $this->shoppingCart['customer']['custel'] = $customerRec->platel;
                $this->shoppingCart['customer']['pla_id'] = $customerRec->pla_id;

            }

        }

        $this->updateCartSession();

    }

    function setCustomer($CusObj = NULL)
    {

        if (!is_null($CusObj)) {


        }

        $this->updateCartSession();

    }

    function setDiscount($DisCod = NULL)
    {

        unset($this->shoppingCart['discount']);
        $this->shoppingCart['discount'] = array();

        $qryArray = array();
        $sql = 'SELECT * FROM discounts WHERE discod = :discod AND sta_id = 0';
        $qryArray["discod"] = $DisCod;
        $discountRec = $this->run($sql, $qryArray, true);

        if (!isset($discountRec->sta_id)) return false;

        $cartAmount = $this->calcCartPrice();

        $discountOK = true;

        if ($discountRec->sta_id != 0) {
            // Not active discount
            $discountOK = false;
            $this->shoppingCart['discount']['disnam'] = $discountRec->disnam;
            $this->shoppingCart['discount']['distxt'] = 'Discount Code Not Active';
        }

        if ($cartAmount < $discountRec->minamt) {
            // Minimum
            $discountOK = false;
            $this->shoppingCart['discount']['disnam'] = $discountRec->disnam;
            $this->shoppingCart['discount']['distxt'] = 'Minimum spend is ' . $discountRec->minamt;
        }

        if ($discountRec->totuse != -1 && $discountRec->totuse == 0) {
            // Maximum uses used
            $discountOK = false;
            $this->shoppingCart['discount']['disnam'] = $discountRec->disnam;
            $this->shoppingCart['discount']['distxt'] = 'Discount Code Limit Reached';
        }

        if ($discountRec->begdat != '' && strtotime($discountRec->begdat) > time()) {
            // Code available from
            $discountOK = false;
            $this->shoppingCart['discount']['disnam'] = $discountRec->disnam;
            $this->shoppingCart['discount']['distxt'] = 'Discount Code Unavailable Until ' . date("d-m-Y", strtotime($discountRec->begdat));
        }

        if ($discountRec->begdat != '' && strtotime($discountRec->enddat . ' 23:59:59') < time()) {
            // Code expired
            $discountOK = false;
            $this->shoppingCart['discount']['disnam'] = $discountRec->disnam;
            $this->shoppingCart['discount']['distxt'] = 'Discount Code Expired';
        }

        if ($discountOK) {

            $this->shoppingCart['discount']['dis_id'] = $discountRec->dis_id;
            $this->shoppingCart['discount']['discod'] = $discountRec->discod;
            $this->shoppingCart['discount']['disnam'] = $discountRec->disnam;
            $this->shoppingCart['discount']['pctamt'] = $discountRec->pctamt;
            $this->shoppingCart['discount']['disamt'] = $discountRec->disamt;
            $this->shoppingCart['discount']['distxt'] = 'Discount Code Applied';
        }

        $this->updateCartSession();

    }

    function calcCartPrice()
    {

        $cartAmount = 0;
        if (isset($this->shoppingCart['items']) && is_array($this->shoppingCart['items'])) {
            for ($i = 0; $i < count($this->shoppingCart['items']); $i++) {

                $this->priceProduct($this->shoppingCart['items'][$i]['prd_id'], $this->shoppingCart['items'][$i]['qty']);

                $cartAmount += $this->shoppingCart['items'][$i]['unipri'] * $this->shoppingCart['items'][$i]['qty'];
            }
        }
        if (isset($this->shoppingCart['delivery'])) {

            $cartAmount += $this->shoppingCart['delivery']['delpri'];

        }
        // DISCOUNT

        $this->totalPrice = number_format($cartAmount, 2);

        return number_format($cartAmount, 2, '.', '');

    }
    function calcDiscount () {

        $cartAmount = $this->calcCartPrice();


        //
        // NEED TO REMOVE DELIVERY FROJM DISCOUNT
        //

        if (isset($this->shoppingCart['delivery'])) {

            $cartAmount -= $this->shoppingCart['delivery']['delpri'];

        }


        $discountAmount = 0;

        if (isset($this->shoppingCart['discount'])) {

            if ($this->shoppingCart['discount']['pctamt'] == 'A') {
                $discountAmount = $this->shoppingCart['discount']['disamt'];
            } else {
                $discountAmount = (($cartAmount / 100) * $this->shoppingCart['discount']['disamt']);
            }

        }

        return number_format($discountAmount, 2, '.', '');

    }


    function checkMultibuy()
    {

        unset($this->shoppingCart['multibuy']);

        $this->shoppingCart['multibuy'] = 0;

        if (!is_array($this->shoppingCart['items'])) return;

        $tableLength = count($this->shoppingCart['items']);
        $productList = '';
        $totalPrice = 0;
        $totalQty = 0;

        for ($i = 0; $i < $tableLength; $i++) {
            $productList .= ($productList == '') ? $this->shoppingCart['items'][$i]['prt_id'] : ',' . $this->shoppingCart['items'][$i]['prt_id'];
            $totalPrice = $totalPrice + ($this->shoppingCart['items'][$i]['qty'] * $this->shoppingCart['items'][$i]['unipri']);
            $totalQty = $totalQty + $this->shoppingCart['items'][$i]['qty'];
        }

        $multibuyRec = $this->multibuyAvailable($productList, $totalQty, $totalPrice, date("Y-m-d"));

        for ($m = 0; $m < count($multibuyRec); $m++) {

            // validate date
            $CurDat = strtotime(date("Y-m-d"));
            if (
                (empty($multibuyRec[$m]['enddat']) && empty($multibuyRec[$m]['enddat'])) ||
                ($CurDat > strtotime($multibuyRec[$m]['begdat']) && $CurDat < strtotime($multibuyRec[$m]['enddat']))
            ) {

                $qryArray = array();
                $sql = 'SELECT r.*, p.prtnam FROM related r INNER JOIN producttypes p ON p.prt_id = r.ref_id WHERE r.tblnam = "MULTIBUY" AND r.refnam = "PRDTYPE" AND r.tbl_id = ' . $multibuyRec[$m]['mul_id'];
                $related = $this->run($sql, $qryArray, false);

                $multiPrice = 0;
                $multiQty = 0;

                for ($r = 0; $r < count($related); $r++) {

                    // look for product in basket
                    // if there add qty and price

                    if (isset($this->shoppingCart['items']) && is_array($this->shoppingCart['items'])) {
                        for ($i = 0; $i < count($this->shoppingCart['items']); $i++) {

                            if ($this->shoppingCart['items'][$i]['prt_id'] == $related[$r]['ref_id']) {

                                // Find Product - Use Correct Price
                                $qryArray = array();
                                $sql = 'SELECT unipri,delpri FROM products WHERE prd_id = ' . $this->shoppingCart['items'][$i]['prd_id'];
                                $productRec = $this->run($sql, $qryArray, true);

                                $UniPri = ($productRec->delpri > 0 && $productRec->delpri < $productRec->unipri) ? $productRec->delpri : $productRec->unipri;

                                $multiPrice += floatval($UniPri) * floatval($this->shoppingCart['items'][$i]['qty']);
                                $multiQty += floatval($this->shoppingCart['items'][$i]['qty']);
                            }

                        }
                    }

                }


                //echo $multiPrice.' @ '.$multiQty.'<br>';

                // validate price and qty

                if ($multiPrice >= $multibuyRec[$m]['minpri'] && $multiQty >= $multibuyRec[$m]['minbuy']) {

                    if (!isset($this->shoppingCart['multibuy'])) $this->shoppingCart['multibuy'] = 0;

                    if ($multibuyRec[$m]['pctamt'] == 'A') $this->shoppingCart['multibuy'] += $multibuyRec[$m]['disamt'];
                    if ($multibuyRec[$m]['pctamt'] == 'P') $this->shoppingCart['multibuy'] += (($multiPrice / 100) * $multibuyRec[$m]['disamt']);

                }

                // if ok add multibuy to shoppingcart

            }

        }

        return $this->shoppingCart['multibuy'];

    }

    function multibuyAvailable($Prd_ID = NULL, $NumItm = 0, $TotPri = NULL, $CurDat = NULL)
    {

        //
        // Find multibuy
        //

        $qryArray = array();
        $sql = 'SELECT * FROM multibuy INNER JOIN related r ON r.tblnam = "MULTIBUY" AND r.refnam = "PRDTYPE" AND r.ref_id IN (' . $Prd_ID . ') WHERE TRUE GROUP BY mul_id';

        return $this->run($sql, $qryArray, false);

    }

    function convertSessionToOrder($Ord_ID = 0, $loggedIn = NULL, $PayTyp = '')
    {

        if (isset($this->shoppingCart['orderToken']) && is_numeric($this->shoppingCart['orderToken']) && $this->shoppingCart['orderToken'] != 0) {

            // Delete Order / Order Lines

            $qryArray = array();
            $qryArray["ord_id"] = $Ord_ID;
            $sql = 'DELETE FROM orders WHERE ord_id = :ord_id';
            $this->run($sql, $qryArray, true);
            $sql = 'DELETE FROM orderlines WHERE ord_id = :ord_id';
            $this->run($sql, $qryArray, true);

        }

        if (is_array($this->shoppingCart['items'])) {

            //
            // ORDER TOKEN MATCHES SAGEPAY SCRIPT GENERATED ID
            //

            $totalAmount = 0;

            //
            // Create Order
            //

            $OrdDao = new OrdDAO();
            $OlnDao = new OlnDAO();

            $OrdObj = new stdClass();
            $OrdObj->ord_id = $Ord_ID;
            $OrdObj->ordtyp = 0;
            $OrdObj->invdat = date('Y-m-d H:i:s');
            $OrdObj->duedat = date('Y-m-d H:i:s');
            $OrdObj->paydat = date('Y-m-d H:i:s');
            $OrdObj->cusnam = $this->shoppingCart['customer']['cusnam'];

            $OrdObj->custtl = $this->shoppingCart['customer']['custtl'];
            $OrdObj->cusfna = $this->shoppingCart['customer']['cusfna'];
            $OrdObj->cussna = $this->shoppingCart['customer']['cussna'];

            $OrdObj->ordfao = $this->shoppingCart['customer']['ordfao'];
            $OrdObj->adr1 = $this->shoppingCart['customer']['adr1'];
            $OrdObj->adr2 = $this->shoppingCart['customer']['adr2'];
            $OrdObj->adr3 = $this->shoppingCart['customer']['adr3'];
            $OrdObj->adr4 = $this->shoppingCart['customer']['adr4'];
            $OrdObj->pstcod = $this->shoppingCart['customer']['pstcod'];
            $OrdObj->coucod = $this->shoppingCart['customer']['coucod'];

            $OrdObj->paycus = $this->shoppingCart['customer']['paycus'];
            $OrdObj->payadr1 = $this->shoppingCart['customer']['payadr1'];
            $OrdObj->payadr2 = $this->shoppingCart['customer']['payadr2'];
            $OrdObj->payadr3 = $this->shoppingCart['customer']['payadr3'];
            $OrdObj->payadr4 = $this->shoppingCart['customer']['payadr4'];
            $OrdObj->paypstcod = $this->shoppingCart['customer']['paypstcod'];
            $OrdObj->paycoucod = $this->shoppingCart['customer']['paycoucod'];

            $OrdObj->paytrm = 'TEL: ' . $this->shoppingCart['customer']['custel'] . ' MOB: ' . $this->shoppingCart['customer']['cusmob'];
            $OrdObj->vatrat = 0;
            $OrdObj->tblnam = 'CUS';
            $OrdObj->tbl_id = (isset($loggedIn) && $loggedIn != false && is_numeric($loggedIn->pla_id)) ? $loggedIn->pla_id : 0;
            $OrdObj->sta_id = 0;
            $OrdObj->altref = '';
            $OrdObj->altnam = $PayTyp;
            $OrdObj->del_id = (isset($this->shoppingCart['delivery'])) ? $this->shoppingCart['delivery']['del_id'] : 0;
            $OrdObj->discod = (isset($this->shoppingCart['discount']['discod'])) ? $this->shoppingCart['discount']['discod'] : '';
            $OrdObj->emaadr = $this->shoppingCart['customer']['cusema'];
            $OrdObj->ordobj = json_encode($this->shoppingCart);

            $qryArray = array();
            $qryArray["ordtyp"] = $OrdObj->ordtyp;
            $qryArray["invdat"] = $OrdObj->invdat;
            $qryArray["duedat"] = $OrdObj->duedat;
            $qryArray["paydat"] = $OrdObj->paydat;

            $qryArray["cusnam"] = $OrdObj->cusnam;

            $qryArray["custtl"] = $OrdObj->custtl;
            $qryArray["cusfna"] = $OrdObj->cusfna;
            $qryArray["cussna"] = $OrdObj->cussna;

            $qryArray["ordfao"] = $OrdObj->ordfao;
            $qryArray["adr1"] = $OrdObj->adr1;
            $qryArray["adr2"] = $OrdObj->adr2;
            $qryArray["adr3"] = $OrdObj->adr3;
            $qryArray["adr4"] = $OrdObj->adr4;
            $qryArray["pstcod"] = $OrdObj->pstcod;
            $qryArray["coucod"] = $OrdObj->coucod;

            $qryArray["paycus"] = $OrdObj->paycus;
            $qryArray["payadr1"] = $OrdObj->payadr1;
            $qryArray["payadr2"] = $OrdObj->payadr2;
            $qryArray["payadr3"] = $OrdObj->payadr3;
            $qryArray["payadr4"] = $OrdObj->payadr4;
            $qryArray["paypstcod"] = $OrdObj->paypstcod;
            $qryArray["paycoucod"] = $OrdObj->paycoucod;

            $qryArray["paytrm"] = $OrdObj->paytrm;
            $qryArray["vatrat"] = $OrdObj->vatrat;
            $qryArray["tblnam"] = $OrdObj->tblnam;
            $qryArray["tbl_id"] = $OrdObj->tbl_id;
            $qryArray["sta_id"] = $OrdObj->sta_id;
            $qryArray["altref"] = $OrdObj->altref;
            $qryArray["altnam"] = $OrdObj->altnam;
            $qryArray["del_id"] = $OrdObj->del_id;
            $qryArray["discod"] = $OrdObj->discod;
            $qryArray["emaadr"] = $OrdObj->emaadr;
            $qryArray["ordobj"] = $OrdObj->ordobj;

            $sql = "INSERT INTO orders
					(
					ordtyp,
					invdat,
					duedat,
					paydat,

					cusnam,
					custtl,
					cusfna,
					cussna,

					ordfao,
					adr1,
					adr2,
					adr3,
					adr4,
					pstcod,
					coucod,

					paycus,
					payadr1,
					payadr2,
					payadr3,
					payadr4,
					paypstcod,
					paycoucod,

					paytrm,
					vatrat,
					tblnam,
					tbl_id,
					sta_id,
					altref,
					altnam,
					del_id,
					discod,
					emaadr,
					ordobj
					)
					VALUES
					(
					:ordtyp,
					:invdat,
					:duedat,
					:paydat,

					:cusnam,
					:custtl,
					:cusfna,
					:cussna,

					:ordfao,
					:adr1,
					:adr2,
					:adr3,
					:adr4,
					:pstcod,
					:coucod,

					:paycus,
					:payadr1,
					:payadr2,
					:payadr3,
					:payadr4,
					:paypstcod,
					:paycoucod,
					:paytrm,
					:vatrat,
					:tblnam,
					:tbl_id,
					:sta_id,
					:altref,
					:altnam,
					:del_id,
					:discod,
					:emaadr,
					:ordobj
					);";

            //$this->displayQuery($sql, $qryArray);
            $this->run($sql, $qryArray, true);
            $Ord_ID = $this->dbConn->lastInsertId('ord_id');

            $this->shoppingCart['orderToken'] = $Ord_ID;

            // Update Object
            $qryArray = array();
            $qryArray["ordobj"] = json_encode($this->shoppingCart);
            $qryArray["ord_id"] = $Ord_ID;
            $sql = 'UPDATE orders SET ordobj = :ordobj WHERE ord_id = :ord_id';
            $this->run($sql, $qryArray, true);

            $_SESSION['confirmationOrderID'] = $Ord_ID;

            for ($i = 0; $i < count($this->shoppingCart['items']); $i++) {

                //
                // Create Order Lines
                //


                // FIND PRODUCT
                $qryArray = array();
                $sql = 'SELECT p.*, pt.prtnam, pt.vat_id AS pt_vat FROM products p INNER JOIN producttypes pt ON pt.prt_id = p.prt_id WHERE p.prd_id = :prd_id';
                $qryArray["prd_id"] = $this->shoppingCart['items'][$i]['prd_id'];
                $productRec = $this->run($sql, $qryArray, true);

                $UniPri = $productRec->unipri;

                // CHECK BAND PRICE

                //if ($productRec->delpri > 0 && $productRec->delpri < $UniPri) {
                //    $UniPri = $productRec->delpri;
                //}

                $lineDescription = $this->shoppingCart['items'][$i]['prtnam'].' > '.$this->shoppingCart['items'][$i]['prdnam']; // . ' [SKU: ' . $this->shoppingCart['items'][$i]['altref'] . ']';
                $lineAmount = $UniPri;
                $discountSign = '�';

                if (isset($this->shoppingCart['items'][$i]['discod'])) {

                    if ($this->shoppingCart['items'][$i]['pctamt'] == 'A') {

                        $lineAmount -= $this->shoppingCart['items'][$i]['disamt'];

                        $lineDescription .= ' (�' . $this->shoppingCart['items'][$i]['disamt'] . ' discount)';

                    } else {

                        $lineAmount -= ($lineAmount / 100) * $this->shoppingCart['items'][$i]['disamt'];

                        $lineDescription .= ' (' . $this->shoppingCart['items'][$i]['disamt'] . '% discount)';

                    }

                }


                $OlnObj = new stdClass();
                $OlnObj->oln_id = 0;
                $OlnObj->ord_id = $Ord_ID;
                $OlnObj->prd_id = $this->shoppingCart['items'][$i]['prd_id'];
                $OlnObj->numuni = $this->shoppingCart['items'][$i]['qty'];
                $OlnObj->unipri = $this->priceProduct($this->shoppingCart['items'][$i]['prd_id'], $this->shoppingCart['items'][$i]['qty'], NULL);

                //
                // FIND VAT RATE
                //

                $sql = 'SELECT vatnam, vatrat FROM vat WHERE vat_id = :vat_id ';
                $qryArray = array();
                $qryArray["vat_id"] = $this->shoppingCart['items'][$i]['vat_id'];
                $vatRecord = $this->run($sql, $qryArray, true);

                $vatRat = 0;
                if (isset($vatRecord->vatrat)) {
                    $vatRat = $vatRecord->vatrat;
                }

                $OlnObj->vatrat = $vatRat;
                $OlnObj->olndsc = $lineDescription;
                $OlnObj->tblnam = 'SALE';
                $OlnObj->tbl_id = 0;
                $OlnObj->sta_id = 0;

                //$OlnDao->update($OlnObj);

                $qryArray = array();
                $qryArray["ord_id"] = $OlnObj->ord_id;
                $qryArray["prd_id"] = $OlnObj->prd_id;
                $qryArray["numuni"] = $OlnObj->numuni;
                $qryArray["unipri"] = $OlnObj->unipri;
                $qryArray["vatrat"] = $OlnObj->vatrat;
                $qryArray["olndsc"] = $OlnObj->olndsc;
                $qryArray["tblnam"] = $OlnObj->tblnam;
                $qryArray["tbl_id"] = $OlnObj->tbl_id;
                $qryArray["sta_id"] = $OlnObj->sta_id;

                $sql = "INSERT INTO orderline
					(
					ord_id,
					prd_id,
					numuni,
					unipri,
					vatrat,
					olndsc,
					tblnam,
					tbl_id,
					sta_id
					)
					VALUES
					(
					:ord_id,
					:prd_id,
					:numuni,
					:unipri,
					:vatrat,
					:olndsc,
					:tblnam,
					:tbl_id,
					:sta_id
					);";

                $this->run($sql, $qryArray, true);
                $Oln_ID = $this->dbConn->lastInsertId('oln_id');


                //
                // Update Stock
                //




            }


            //
            // Delivery Information
            //
            if (isset($this->shoppingCart['delivery']) && is_array($this->shoppingCart['delivery'])) {

                $qryArray = array();
                $qryArray["ord_id"] = $Ord_ID;
                $qryArray["prd_id"] = 0;
                $qryArray["numuni"] = 1;
                $qryArray["unipri"] = $this->shoppingCart['delivery']['delpri'];
                $qryArray["vatrat"] = 0;
                $qryArray["olndsc"] = 'DELIVERY > '.$this->shoppingCart['delivery']['delnam'];
                $qryArray["tblnam"] = 'DELIVERY';
                $qryArray["tbl_id"] = $Ord_ID;
                $qryArray["sta_id"] = 0;

                $sql = "INSERT INTO orderline
					(
					ord_id,
					prd_id,
					numuni,
					unipri,
					vatrat,
					olndsc,
					tblnam,
					tbl_id,
					sta_id
					)
					VALUES
					(
					:ord_id,
					:prd_id,
					:numuni,
					:unipri,
					:vatrat,
					:olndsc,
					:tblnam,
					:tbl_id,
					:sta_id
					);";

                $this->run($sql, $qryArray, true);
                $Oln_ID = $this->dbConn->lastInsertId('oln_id');

            }

            //
            // DISCOUNT
            //

            if (isset($this->shoppingCart['discount']) && is_array($this->shoppingCart['discount'])) {

                $qryArray = array();
                $qryArray["ord_id"] = $Ord_ID;
                $qryArray["prd_id"] = 0;
                $qryArray["numuni"] = 1;
                $qryArray["unipri"] = $this->calcDiscount()*-1;
                $qryArray["vatrat"] = 0;
                $qryArray["olndsc"] = 'DISCOUNT > '.$this->shoppingCart['discount']['disnam'];
                $qryArray["tblnam"] = 'DISCOUNT';
                $qryArray["tbl_id"] = $Ord_ID;
                $qryArray["sta_id"] = 0;

                $sql = "INSERT INTO orderline
					(
					ord_id,
					prd_id,
					numuni,
					unipri,
					vatrat,
					olndsc,
					tblnam,
					tbl_id,
					sta_id
					)
					VALUES
					(
					:ord_id,
					:prd_id,
					:numuni,
					:unipri,
					:vatrat,
					:olndsc,
					:tblnam,
					:tbl_id,
					:sta_id
					);";

                $this->run($sql, $qryArray, true);
                $Oln_ID = $this->dbConn->lastInsertId('oln_id');

            }

            // MULTIBUY

            $multiBuyAmount = $this->checkMultibuy();
            if ($multiBuyAmount > 0) {

                $qryArray = array();
                $qryArray["ord_id"] = $Ord_ID;
                $qryArray["prd_id"] = 0;
                $qryArray["numuni"] = 1;
                $qryArray["unipri"] = $multiBuyAmount * -1;
                $qryArray["vatrat"] = 0;
                $qryArray["olndsc"] = 'MULTIBUY';
                $qryArray["tblnam"] = 'MULTIBUY';
                $qryArray["tbl_id"] = $Ord_ID;
                $qryArray["sta_id"] = 0;

                $sql = "INSERT INTO orderline
					(
					ord_id,
					prd_id,
					numuni,
					unipri,
					vatrat,
					olndsc,
					tblnam,
					tbl_id,
					sta_id
					)
					VALUES
					(
					:ord_id,
					:prd_id,
					:numuni,
					:unipri,
					:vatrat,
					:olndsc,
					:tblnam,
					:tbl_id,
					:sta_id
					);";

                $this->run($sql, $qryArray, true);
                $Oln_ID = $this->dbConn->lastInsertId('oln_id');

            }



            //
            // Outside EU
            //

            if (!$this->inEurope($this->shoppingCart['customer']['paycoucod'])) {

                // $totalOrderPrice needs delivery

                //echo $totalOrderPrice.' ~ '.$this->calcVAT($totalOrderPrice);

                $totalOrderPrice = $this->calcCartPrice() - $this->calcDiscount();

                $unitPrice = number_format($this->calcVAT($totalOrderPrice), 2, '.', '');

                $qryArray = array();
                $qryArray["ord_id"] = $OlnObj->ord_id;
                $qryArray["prd_id"] = 0;
                $qryArray["numuni"] = 1;
                $qryArray["unipri"] = -$unitPrice;
                $qryArray["vatrat"] = 0;
                $qryArray["olndsc"] = 'VAT Reduction Outside EU';
                $qryArray["tblnam"] = 'NONEU';
                $qryArray["tbl_id"] = 0;
                $qryArray["sta_id"] = 0;

                $sql = "INSERT INTO orderline
					(
					ord_id,
					prd_id,
					numuni,
					unipri,
					vatrat,
					olndsc,
					tblnam,
					tbl_id,
					sta_id
					)
					VALUES
					(
					:ord_id,
					:prd_id,
					:numuni,
					:unipri,
					:vatrat,
					:olndsc,
					:tblnam,
					:tbl_id,
					:sta_id
					);";

                $this->run($sql, $qryArray, true);

            }


        }

        $this->updateCartSession();

        return $Ord_ID;

    }


    /************************/
    /*** VAT CALCULATIONS ***/
    /************************/

    function withoutVAT($amount = 0, $vatrate = 20)
    {
        $vatCalc = ((100 + $vatrate) / 100);
        return number_format(($amount / $vatCalc), 2);
    }

    function calcVAT($amount = 0, $vatrate = 20)
    {
        $vatCalc = ((100 + $vatrate) / 100);
        return number_format(round($amount - ($amount / $vatCalc), 2, PHP_ROUND_HALF_DOWN), 2);
    }

    function addVAT($amount = 0, $vatrate = 20)
    {
        $amount = $amount + (($amount / 100) * $vatrate);
        return number_format($amount, 2);
    }

    function getJSONVariable($JSONstr = NULL, $VarNam = NULL, $strip = true)
    {
        if ($strip == true) {
            $eleVarArr = json_decode(stripslashes($JSONstr), true);
        } else {
            $eleVarArr = json_decode($JSONstr, true);
        }
        if (is_array($eleVarArr) && !is_null($VarNam)) {
            for ($i = 0; $i < count($eleVarArr); ++$i) {
                foreach ($eleVarArr[$i] as $key => $item) {
                    if ($item === $VarNam) {
                        return $eleVarArr[$i]['value'];
                    }
                }
            }
        }
        return '';
    }

    function inEurope($iCouCod=NULL) {

        if ($iCouCod=="DE") return true; //'Germany';
        if ($iCouCod=="IT") return true; //'Italy';
        if ($iCouCod=="PL") return true; //'Poland';
        if ($iCouCod=="GB") return true; //'United Kingdom';
        if ($iCouCod=="FR") return true; //'France';
        if ($iCouCod=="FX") return true; //'France, Metropolitan';
        if ($iCouCod=="GF") return true; //'French Guiana';
        if ($iCouCod=="PF") return true; //'French Polynesia';
        if ($iCouCod=="TF") return true; //'French Southern Territories';
        if ($iCouCod=="RO") return true; //'Romania';
        if ($iCouCod=="SE") return true; //'Sweden';
        if ($iCouCod=="GR") return true; //'Greece';
        if ($iCouCod=="ES") return true; //'Spain';
        if ($iCouCod=="AT") return true; //'Austria';
        if ($iCouCod=="HU") return true; //'Hungary';
        if ($iCouCod=="BG") return true; //'Bulgaria';
        if ($iCouCod=="FI") return true; //'Finland';
        if ($iCouCod=="CZ") return true; //'Czech Republic';
        if ($iCouCod=="NL") return true; //'Netherlands';
        if ($iCouCod=="NO") return true; //'Norway';
        if ($iCouCod=="HR") return true; //'Croatia (Hrvatska)';
        if ($iCouCod=="LT") return true; //'Lithuania';
        if ($iCouCod=="IE") return true; //'Ireland';
        if ($iCouCod=="BE") return true; //'Belgium';
        if ($iCouCod=="CY") return true; //'Cyprus';
        if ($iCouCod=="SK") return true; //'Slovakia (Slovak Republic)';
        if ($iCouCod=="SI") return true; //'Slovenia';
        if ($iCouCod=="MT") return true; //'Malta';
        if ($iCouCod=="PT") return true; //'Portugal';
        if ($iCouCod=="EE") return true; //'Estonia';
        if ($iCouCod=="SI") return true; //'Slovenia';
        if ($iCouCod=="LV") return true; //'Latvia';
        if ($iCouCod=="DK") return true; //'Denmark';

        return false;

    }

}