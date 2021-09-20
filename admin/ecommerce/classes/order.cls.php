<?php

//
// Ordces class
//

class OrdDAO extends db
{

    function select($Ord_ID = NULL, $TblNam = NULL, $Tbl_ID = NULL, $Sta_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT
				o.*,
				p.planam,
				p.comnam,
				SUM(ol.unipri * ol.numuni) AS ordtot
				FROM orders o 
				LEFT OUTER JOIN places p ON p.pla_id = o.tbl_id
				LEFT OUTER JOIN orderline ol ON o.ord_id = ol.ord_id
				WHERE TRUE';

        if (!is_null($Ord_ID)) {
            $sql .= ' AND o.ord_id = :ord_id ';
            $qryArray["ord_id"] = $Ord_ID;
        } else {
            if (!is_null($TblNam)) {
                $sql .= ' AND o.tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }
            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND o.tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }
            if (!is_null($Sta_ID)) {

                if (is_numeric($Sta_ID)) {
                    $sql .= " AND o.sta_id = :sta_id ";
                } else {
                    $sql .= " AND find_in_set(cast(o.sta_id as char), :sta_id) ";
                }

                $qryArray["sta_id"] = $Sta_ID;
            }
        }

        $sql .= ' GROUP BY o.ord_id ORDER BY o.invdat DESC';

        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function searchOrders($Ord_ID = NULL, $Sta_ID = NULL, $BegDat = NULL, $EndDat = NULL, $CusNam = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT
				o.ord_id,
				o.ordtyp,
				o.invdat,
				o.duedat,
				o.paydat,
				o.cusnam,
				o.adr1,
				o.adr2,
				o.adr3,
				o.adr4,
				o.pstcod,
				o.payadr1,
				o.payadr2,
				o.payadr3,
				o.payadr4,
				o.paypstcod,
				o.paytrm,
				o.vatrat,
				o.tblnam,
				o.tbl_id,
				o.sta_id,
				o.altref,
				o.altnam,
				o.del_id,
				o.discod,
				o.emaadr,
				o.ordobj,
				p.planam,
				p.comnam,
				SUM(ol.unipri * ol.numuni) AS ordtot
				FROM orders o
				LEFT OUTER JOIN places p ON p.pla_id = o.tbl_id
				LEFT OUTER JOIN orderline ol ON o.ord_id = ol.ord_id
				WHERE TRUE';

        if (!is_null($Ord_ID)) {
            $sql .= ' AND o.ord_id = :ord_id ';
            $qryArray["ord_id"] = $Ord_ID;
        } else {

            if (!is_null($BegDat) && checkdate(date("m", strtotime($BegDat)), date("d", strtotime($BegDat)), date("Y", strtotime($BegDat)))) {
                $sql .= ' AND o.invdat >= :begdat ';
                $qryArray["begdat"] = $BegDat . ' 00:00:00';
            }
            if (!is_null($EndDat) && checkdate(date("m", strtotime($EndDat)), date("d", strtotime($EndDat)), date("Y", strtotime($EndDat)))) {
                $sql .= ' AND o.invdat <= :enddat ';
                $qryArray["enddat"] = $EndDat . ' 23:59:59';
            }

            if (!is_null($Sta_ID)) {

                if (is_numeric($Sta_ID)) {
                    $sql .= " AND o.sta_id = :sta_id ";
                } else {
                    $sql .= " AND find_in_set(cast(o.sta_id as char), :sta_id) ";
                }

                $qryArray["sta_id"] = $Sta_ID;
            }

            if (!is_null($CusNam)) {

                $sql .= " AND o.cusnam LIKE :cusnam ";
                $qryArray["cusnam"] = '%' . $CusNam . '%';
            }

        }

        $sql .= ' GROUP BY o.ord_id ORDER BY o.invdat DESC';

        //echo $sql;

        return $this->run($sql, $qryArray, false);

    }

    function selectOrderLines($Ord_ID = NULL, $Oln_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT
				o.oln_id,
				o.ord_id,
				o.prd_id,
				o.numuni,
				o.unipri,
				o.vatrat,
				o.olndsc,
				o.tblnam,
				o.tbl_id,
				o.sta_id,
				p.prdnam
				FROM orderline o
				LEFT OUTER JOIN products p ON o.prd_id = p.prd_id
				WHERE TRUE';

        if (!is_null($Oln_ID)) {
            $sql .= ' AND oln_id = :oln_id ';
            $qryArray["oln_id"] = $Oln_ID;
        } else {
            if (!is_null($Ord_ID) && is_numeric($Ord_ID)) {
                $sql .= ' AND ord_id = :ord_id ';
                $qryArray["ord_id"] = $Ord_ID;
            }
        }

        return $this->run($sql, $qryArray, $ReqObj);

    }


    function checkDiscount($CusEma = NULL, $DisCod = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT *
				FROM orders o
				WHERE ';

        $sql .= ' o.emaadr = :cusema ';
        $qryArray["cusema"] = $CusEma;
        $sql .= ' AND o.discod = :discod ';
        $qryArray["discod"] = $DisCod;

        $sql .= ' GROUP BY o.ord_id ORDER BY o.invdat DESC';

        echo $sql;

        return $this->run($sql, $qryArray, true);

    }


    function selectFinancial($Sta_ID = "10,20")
    {

        $sql = "SELECT
    EXTRACT(MONTH FROM o.invdat) as month, 
	MONTHNAME(o.invdat) as monthname,
    EXTRACT(YEAR FROM o.invdat) as year,
	SUM(ol.numuni * ol.unipri) as total
FROM 
    orders o
INNER JOIN orderline ol ON ol.ord_id = o.ord_id
WHERE o.sta_id IN (" . $Sta_ID . ")
GROUP BY 
    month, 
    year
ORDER BY 
    year DESC, 
    month DESC";
        $qryArray = array();
        return $this->run($sql, $qryArray, false);

    }

    function update($OrdCls = NULL)
    {

        if (is_null($OrdCls) || !$OrdCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($OrdCls->ord_id == 0) {

            $qryArray["ordtyp"] = $OrdCls->ordtyp;
            $qryArray["invdat"] = $OrdCls->invdat;
            $qryArray["duedat"] = $OrdCls->duedat;
            $qryArray["paydat"] = $OrdCls->paydat;
            $qryArray["cusnam"] = $OrdCls->cusnam;
            $qryArray["adr1"] = $OrdCls->adr1;
            $qryArray["adr2"] = $OrdCls->adr2;
            $qryArray["adr3"] = $OrdCls->adr3;
            $qryArray["adr4"] = $OrdCls->adr4;
            $qryArray["pstcod"] = $OrdCls->pstcod;
            $qryArray["payadr1"] = $OrdCls->payadr1;
            $qryArray["payadr2"] = $OrdCls->payadr2;
            $qryArray["payadr3"] = $OrdCls->payadr3;
            $qryArray["payadr4"] = $OrdCls->payadr4;
            $qryArray["paypstcod"] = $OrdCls->paypstcod;
            $qryArray["paytrm"] = $OrdCls->paytrm;
            $qryArray["vatrat"] = $OrdCls->vatrat;
            $qryArray["tblnam"] = $OrdCls->tblnam;
            $qryArray["tbl_id"] = $OrdCls->tbl_id;
            $qryArray["sta_id"] = $OrdCls->sta_id;

            $qryArray["altref"] = $OrdCls->altref;
            $qryArray["altnam"] = $OrdCls->altnam;
            $qryArray["del_id"] = $OrdCls->del_id;
            $qryArray["discod"] = $OrdCls->discod;
            $qryArray["emaadr"] = $OrdCls->emaadr;

            $qryArray["ordobj"] = $OrdCls->ordobj;

            $sql = "INSERT INTO orders
					(
					
					ordtyp,
					invdat,
					duedat,
					paydat,
					cusnam,
					adr1,
					adr2,
					adr3,
					adr4,
					pstcod,
					payadr1,
					payadr2,
					payadr3,
					payadr4,
					paypstcod,
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
					:adr1,
					:adr2,
					:adr3,
					:adr4,
					:pstcod,
					:payadr1,
					:payadr2,
					:payadr3,
					:payadr4,
					:paypstcod,
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

        } else {

            $qryArray["ordtyp"] = $OrdCls->ordtyp;
            $qryArray["invdat"] = $OrdCls->invdat;
            $qryArray["duedat"] = $OrdCls->duedat;
            $qryArray["paydat"] = $OrdCls->paydat;
            $qryArray["cusnam"] = $OrdCls->cusnam;
            $qryArray["adr1"] = $OrdCls->adr1;
            $qryArray["adr2"] = $OrdCls->adr2;
            $qryArray["adr3"] = $OrdCls->adr3;
            $qryArray["adr4"] = $OrdCls->adr4;
            $qryArray["pstcod"] = $OrdCls->pstcod;
            $qryArray["payadr1"] = $OrdCls->payadr1;
            $qryArray["payadr2"] = $OrdCls->payadr2;
            $qryArray["payadr3"] = $OrdCls->payadr3;
            $qryArray["payadr4"] = $OrdCls->payadr4;
            $qryArray["paypstcod"] = $OrdCls->paypstcod;
            $qryArray["paytrm"] = $OrdCls->paytrm;
            $qryArray["vatrat"] = $OrdCls->vatrat;
            $qryArray["tblnam"] = $OrdCls->tblnam;
            $qryArray["tbl_id"] = $OrdCls->tbl_id;
            $qryArray["sta_id"] = $OrdCls->sta_id;

            $qryArray["altref"] = $OrdCls->altref;
            $qryArray["altnam"] = $OrdCls->altnam;
            $qryArray["del_id"] = $OrdCls->del_id;
            $qryArray["discod"] = $OrdCls->discod;
            $qryArray["emaadr"] = $OrdCls->emaadr;

            $qryArray["ordobj"] = $OrdCls->ordobj;

            $sql = "UPDATE orders
					SET
					
					ordtyp = :ordtyp,
					invdat = :invdat,
					duedat = :duedat,
					paydat = :paydat,
					cusnam = :cusnam,
					adr1 = :adr1,
					adr2 = :adr2,
					adr3 = :adr3,
					adr4 = :adr4,
					pstcod = :pstcod,
					payadr1 = :payadr1,
					payadr2 = :payadr2,
					payadr3 = :payadr3,
					payadr4 = :payadr4,
					paypstcod = :paypstcod,
					paytrm = :paytrm,
					vatrat = :vatrat,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					sta_id = :sta_id,
					altref = :altref,
					altnam = :altnam,
					del_id = :del_id,
					discod = :discod,
					emaadr = :emaadr,
					ordobj = :ordobj";

            $sql .= " WHERE ord_id = :ord_id";
            $qryArray["ord_id"] = $OrdCls->ord_id;

        }

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($OrdCls->ord_id == 0) ? $this->dbConn->lastInsertId('ord_id') : $OrdCls->ord_id;
    }

    function delete($Ord_ID = NULL)
    {

        try {

            if (!is_null($Ord_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM orders WHERE ord_id = :ord_id ';
                $qryArray["ord_id"] = $Ord_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                $qryArray = array();
                $sql = 'DELETE FROM orderlines WHERE ord_id = :ord_id ';
                $qryArray["ord_id"] = $Ord_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                //
                // DELETE ATTRIBUTES
                //

                //
                // DELETE IMAGES
                //

                return $Ord_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function selectByAltRef($AltRef = NULL, $ReqObj = false)
    {

        if (!is_null($AltRef)) {

            $qryArray = array();
            $sql = 'SELECT
				*
				FROM orders
				WHERE altref = :altref ';

            $qryArray["altref"] = $AltRef;

            return $this->run($sql, $qryArray, $ReqObj);

        } else {

            return NULL;

        }

    }

    function getEcommProp($ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT
                *
				FROM ecommprop WHERE TRUE';

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function getDelivery($Del_ID = NULL)
    {

        if (!is_null($Del_ID)) {
            $qryArray = array();
            $sql = 'SELECT
                *
				FROM delivery WHERE del_id = :del_id';
            $qryArray["del_id"] = $Del_ID;

            return $this->run($sql, $qryArray, true);

        } else {
            return false;
        }

    }

    function getProduct($Prd_ID = NULL)
    {

        if (!is_null($Prd_ID)) {
            $qryArray = array();
            $sql = 'SELECT
                p.*,
                pt.prtnam
				FROM products p INNER JOIN producttypes pt ON pt.prt_id = p.prt_id WHERE prd_id = :prd_id';
            $qryArray["prd_id"] = $Prd_ID;

            return $this->run($sql, $qryArray, true);

        } else {
            return false;
        }

    }

    function confirmOrderPayment($Ord_ID = NULL)
    {

        if (is_numeric($Ord_ID)) {

            $qryArray = array();
            $sql = 'UPDATE orders SET sta_id = 20 WHERE ord_id = :ord_id';
            $qryArray['ord_id'] = $Ord_ID;
            $this->run($sql, $qryArray, false);

            //
            // FIND ORDER LINES
            //

            $orderLines = $this->selectOrderLines($Ord_ID, NULL, false);

            for ($i = 0; $i < count($orderLines); $i++) {

                //
                // FIND PRODUCT AND DECREASE QTY
                //

                $qryArray = array();
                $sql = 'UPDATE products SET in_stk = in_stk - :ordqty WHERE prd_id = :prd_id';
                $qryArray['ordqty'] = $orderLines[$i]['numuni'];
                $qryArray['prd_id'] = $orderLines[$i]['prd_id'];
                $this->run($sql, $qryArray, false);

            }

        } else {

            return NULL;

        }

    }

    function emailEnquiry($Ord_ID = NULL, $To_Ema = NULL, $FrmEma = NULL)
    {

        if (!is_null($Ord_ID)) {

            $order = $this->select($Ord_ID, NULL, NULL, NULL, true);

            $ecoProp = $this->getEcommProp(true);

            if (isset($order)) {

                $orderlines = $this->selectOrderLines($order->ord_id, NULL, false);

                $delivery = $this->getDelivery($order->del_id);

                $mailto = $order->emaadr;

                //if (isset($_REQUEST['emaadr'])) $mailto = $_REQUEST['emaadr'];

                if (!is_null($To_Ema)) $mailto = $To_Ema;

                $subject = "ENQUIRY REQUEST";
                $message = "ENQUIRY DETAILS:";

                //$FrmEma = $patchworks->adminEmail;

                $body = '';

                $body .= '<table cellspacing="0" cellpadding="10" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px;">';
                $body .= '<tr>';
                $body .= '<td align="left" width="50%">';

                $body .= '</td>';
                $body .= '<td align="left" width="50%" style="text-align: right">';

                $body .= '<img src="' . $this->webRoot . '/pages/img/logo.png" style="background:#000;padding:10px;" width="193" height="60">';
                $body .= '<h2>ENQUIRY REQUEST</h2>';
                $body .= '<p> Enquiry No: ' . str_pad($order->ord_id, 8, '0', STR_PAD_LEFT);
                $body .= '<br> Date: ' . date("jS M Y", strtotime($order->invdat));

                $body .= '</td>';
                $body .= '</tr>';


                $body .= '<tr>';
                $body .= '<td width="50%" valign="top">';

                $body .= '<h4>ENQUIRY FROM:</h4>';
                $body .= '<p>';
                $body .= '<b>' . $order->cusnam . '</b><br>';
                $body .= '<a href="mailto:'.$order->emaadr.'">' . $order->emaadr . '</a><br>';
                $body .= $order->paytrm ;
                $body .= '</p>';
                $body .= '<p>' . $order->payadr1 . '<br />';
                $body .= (!empty($order->payadr2)) ? $order->payadr2 . '<br />' : '';
                $body .= (!empty($order->payadr3)) ? $order->payadr3 . '<br />' : '';
                $body .= (!empty($order->payadr4)) ? $order->payadr4 . '<br />' : '';
                $body .= $order->paypstcod;
                $body .= '</p>';

                //$body .= '<p>'.$order->paytrm.'</p>';

                $body .= '</td>';
                $body .= '<td width="50%" valign="top">';


                $body .= '</td>';
                $body .= '</tr>';
                $body .= '</table>';

//    $body .= '<table cellspacing="0" cellpadding="10" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px; padding-top: 50px;">';
//    $body .= '<tr>';
//    $body .= '<td align="center">';
//    $body .= '<h3>FOR ALL YOUR FUTURE CLIPPING NEEDS PLEASE<br>VISIT US AT MASTERCLIP.CO.UK</h3>';
//    $body .= '</td>';
//    $body .= '</tr>';
//    $body .= '</table>';

                $body .= '<table cellspacing="0" cellpadding="3" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px; padding-top: 50px;">';

                $body .= '<tr>';
                $body .= '<td align="left" style="border-bottom: solid 1px #666; vertical-align: top;"> <b>Qty</b> </td>';
                $body .= '<td align="left" style="border-bottom: solid 1px #666; vertical-align: top;"> <b>Included Products</b> </td>';
                $body .= '<td align="left" style="border-bottom: solid 1px #666; vertical-align: top; text-align: right;"> </td>';
                $body .= '<td align="left" style="border-bottom: solid 1px #666; vertical-align: top; text-align: right;"> </td>';
                $body .= '<td align="right" style="border-bottom: solid 1px #666; vertical-align: top; text-align: right;"> </td>';
                $body .= '</tr>';

                $orderTotal = 0;

                $vatTotal = 0;

                $tableLength = count($orderlines);
                for ($i = 0; $i < $tableLength; ++$i) {

                    $product = $this->getProduct($orderlines[$i]['prd_id']);

                    // parse olndsc to products
                    // loop products
                    $products = json_decode($orderlines[$i]['olndsc'], true);
                    $formInfo = '';
                    if (isset($products['products']) && is_array($products['products'])) {

                        $formInfo = $products['forminfo'];
                        $products = $products['products'];

                    }

                    $orderTotal = $orderTotal + $orderlines[$i]['unipri'] * $orderlines[$i]['numuni'];

                    $clscol = '';
                    if ($i % 2 == 0) {
                        $clscol = '#ffffff';
                    }

                    $body .= '<tr style="background: ' . $clscol . '">';
                    $body .= '<td width="50" style="vertical-align: top;"> ' . $orderlines[$i]['numuni'] . '</td>';

                    if (isset($product->prtnam)) {
                        $body .= '<td><strong>' . $product->prtnam . '</strong><br>' . $orderlines[$i]['olndsc'] . '<i>';
                    } else {
                        $body .= '<td>' . $orderlines[$i]['olndsc'];
                    }

                    if (is_array($products)) {
                        for ($p = 0; $p < count($products); $p++) {

                            $body .= '&bull; ' . $products[$p]['prdnam'] . '<br>';

                        }
                    }

                    $body .= '</i>';

                    if (isset($formInfo['lbl']) && is_array($formInfo['lbl'])) {

                        $body .= '<br><strong>Custom Data</strong><br>';
                        for ($p = 0; $p < count($formInfo['lbl']); $p++) {
                            $body .= '<i>' . $formInfo['lbl'][$p] . '</i> = ' . $formInfo['lbl'][$p] . ' <br>';
                        }
                        $body .= '<br>';
                    }

                    $body .= '</td>';

                    $body .= '<td width="80" align="right" style="vertical-align: top;"> </td>';
                    $body .= '<td width="80" align="right" style="vertical-align: top;"> </td>';


                    $body .= '<td width="80" align="right" style="vertical-align: top;"> </td>';
                    $body .= '</tr>';

                }

//                if (isset($delivery->delnam)) {
//
//                    $body .= '<tr>';
//                    $body .= '<td colspan="1" style="border-top: solid 1px #666;">';
//                    $body .= '<td colspan="1" style="border-top: solid 1px #666;">DELIVERY: ' . $delivery->delnam . ' </td>';
//                    $body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;' . $delivery->delpri . '</td>';
//                    $body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;' . $this->calcVAT($delivery->delpri) . '</td>';
//
//                    $vatTotal += $this->calcVAT($delivery->delpri);
//
//                    $body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;' . number_format($delivery->delpri, 2) . '</td>';
//                    $body .= '</tr>';
//
//                    $orderTotal += $delivery->delpri;
//
//                }


                $body .= '</table>';

                //die($body);

                $message = '<html><body>';
                $message .= $body;
                $message .= "</body></html>";

                return $message;

//                $headers = "From: " . $FrmEma . "\r\n";
//                $headers .= "Reply-To: " . $FrmEma . "\r\n";
//
//                $headers .= "MIME-Version: 1.0\r\n";
//                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//
//                $message = '<html><body>';
//
//                $message .= $body;
//
//                $message .= "</body></html>";
//
//                $sendOK = mail($mailto, 'THANK YOU FOR YOUR ORDER', $message, $headers);
//                $sendOK = mail($FrmEma, 'WEBSITE ORDER', $message, $headers);
//
//                if (!$sendOK) {
//
//
//                }

            }


        }

    }

    function emailOrder($Ord_ID = NULL, $To_Ema = NULL, $FrmEma = NULL)
    {

        if (!is_null($Ord_ID)) {

            $order = $this->select($Ord_ID, NULL, NULL, NULL, true);

            $ecoProp = $this->getEcommProp(true);

            if (isset($order)) {

                $orderlines = $this->selectOrderLines($order->ord_id, NULL, false);

                $delivery = $this->getDelivery($order->del_id);

                $mailto = $order->emaadr;

                //if (isset($_REQUEST['emaadr'])) $mailto = $_REQUEST['emaadr'];

                if (!is_null($To_Ema)) $mailto = $To_Ema;

                $subject = "ORDER CONFIRMATION";
                $message = "ORDER DETAILS:";

                //$FrmEma = $patchworks->adminEmail;

                $body = '';

                $body .= '<table cellspacing="0" cellpadding="10" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px;">';
                $body .= '<tr>';
                $body .= '<td align="left" width="50%">';

                $body .= '<h4>' . $ecoProp->comnam . '</h4>';
                $body .= '<p>' . $ecoProp->adr1 . '<br>';
                $body .= '' . $ecoProp->adr2 . '<br>';
                $body .= '' . $ecoProp->adr3 . '<br>';
                $body .= '' . $ecoProp->adr4 . '<br>';
                $body .= '' . $ecoProp->pstcod . '</p>';

                $body .= '<p>' . $ecoProp->comtel . '<br>';
                $body .= 'E-mail: ' . $ecoProp->emaadr . '</p>';

                $body .= '</td>';
                $body .= '<td align="left" width="50%" style="text-align: right">';

                $body .= '<img src="' . $this->webRoot . '/pages/img/logo.png" width="193" height="60">';
                $body .= '<h2>INVOICE</h2>';
                $body .= '<p> Order No: ' . str_pad($order->ord_id, 8, '0', STR_PAD_LEFT);
                $body .= '<br> Date: ' . date("jS M Y", strtotime($order->invdat));

                $body .= '</td>';
                $body .= '</tr>';


                $body .= '<tr>';
                $body .= '<td width="50%" valign="top">';

                $body .= '<h4>TO:</h4>';
                $body .= '<p>';
                $body .= '<b>' . $order->cusnam . '</b>';
                $body .= '</p>';
                $body .= '<p>' . $order->payadr1 . '<br />';
                $body .= (!empty($order->payadr2)) ? $order->payadr2 . '<br />' : '';
                $body .= (!empty($order->payadr3)) ? $order->payadr3 . '<br />' : '';
                $body .= (!empty($order->payadr4)) ? $order->payadr4 . '<br />' : '';
                $body .= $order->paypstcod;
                $body .= '</p>';

                //$body .= '<p>'.$order->paytrm.'</p>';

                $body .= '</td>';
                $body .= '<td width="50%" valign="top">';

                $body .= '<h4>SHIP TO:</h4>';
                $body .= '<p>';
                $body .= '<b>' . $order->cusnam . '</b>';
                $body .= '</p>';
                $body .= '<p>' . $order->adr1 . '<br />';
                $body .= (!empty($order->adr2)) ? $order->adr2 . '<br />' : '';
                $body .= (!empty($order->adr3)) ? $order->adr3 . '<br />' : '';
                $body .= (!empty($order->adr4)) ? $order->adr4 . '<br />' : '';
                $body .= $order->pstcod;
                $body .= '</p>';

                $body .= '</td>';
                $body .= '</tr>';
                $body .= '</table>';

//    $body .= '<table cellspacing="0" cellpadding="10" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px; padding-top: 50px;">';
//    $body .= '<tr>';
//    $body .= '<td align="center">';
//    $body .= '<h3>FOR ALL YOUR FUTURE CLIPPING NEEDS PLEASE<br>VISIT US AT MASTERCLIP.CO.UK</h3>';
//    $body .= '</td>';
//    $body .= '</tr>';
//    $body .= '</table>';

                $body .= '<table cellspacing="0" cellpadding="3" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px; padding-top: 50px;">';

                $body .= '<tr>';
                $body .= '<td align="left" style="border-bottom: solid 1px #666; vertical-align: top;"> <b>Qty</b> </td>';
                $body .= '<td align="left" style="border-bottom: solid 1px #666; vertical-align: top;"> <b>Included Products</b> </td>';
                $body .= '<td align="left" style="border-bottom: solid 1px #666; vertical-align: top; text-align: right;"> <b>Price</b> </td>';
                $body .= '<td align="left" style="border-bottom: solid 1px #666; vertical-align: top; text-align: right;"> <b>VAT</b> </td>';
                $body .= '<td align="right" style="border-bottom: solid 1px #666; vertical-align: top; text-align: right;"> <b>Total</b> </td>';
                $body .= '</tr>';

                $orderTotal = 0;

                $vatTotal = 0;

                $tableLength = count($orderlines);
                for ($i = 0; $i < $tableLength; ++$i) {

                    $product = $this->getProduct($orderlines[$i]['prd_id']);

                    // parse olndsc to products
                    // loop products
                    $products = json_decode($orderlines[$i]['olndsc'], true);
                    $formInfo = '';
                    if (isset($products['products']) && is_array($products['products'])) {

                        $formInfo = $products['forminfo'];
                        $products = $products['products'];

                    }

                    $orderTotal = $orderTotal + $orderlines[$i]['unipri'] * $orderlines[$i]['numuni'];

                    $clscol = '';
                    if ($i % 2 == 0) {
                        $clscol = '#ffffff';
                    }

                    $body .= '<tr style="background: ' . $clscol . '">';
                    $body .= '<td width="50" style="vertical-align: top;"> ' . $orderlines[$i]['numuni'] . '</td>';

                    if (isset($product->prtnam)) {
                        $body .= '<td><strong>' . $product->prtnam . '</strong><br>' . $orderlines[$i]['olndsc'] . '<i>';
                    } else {
                        $body .= '<td>' . $orderlines[$i]['olndsc'];
                    }

                    if (is_array($products)) {
                        for ($p = 0; $p < count($products); $p++) {

                            $body .= '&bull; ' . $products[$p]['prdnam'] . '<br>';

                        }
                    }

                    $body .= '</i>';

                    if (isset($formInfo['lbl']) && is_array($formInfo['lbl'])) {

                        $body .= '<br><strong>Custom Data</strong><br>';
                        for ($p = 0; $p < count($formInfo['lbl']); $p++) {
                            $body .= '<i>' . $formInfo['lbl'][$p] . '</i> = ' . $formInfo['lbl'][$p] . ' <br>';
                        }
                        $body .= '<br>';
                    }

                    $body .= '</td>';

                    $body .= '<td width="80" align="right" style="vertical-align: top;"> &pound;' . $orderlines[$i]['unipri'] . '</td>';
                    $body .= '<td width="80" align="right" style="vertical-align: top;"> &pound;' . $this->calcVAT($orderlines[$i]['unipri'], $orderlines[$i]['vatrat']) . '</td>';

                    $vatTotal += $this->calcVAT($orderlines[$i]['unipri'], $orderlines[$i]['vatrat']) * $orderlines[$i]['numuni'];

                    $body .= '<td width="80" align="right" style="vertical-align: top;"> &pound;' . number_format($orderlines[$i]['unipri'] * $orderlines[$i]['numuni'], 2) . '</td>';
                    $body .= '</tr>';

                }

//                if (isset($delivery->delnam)) {
//
//                    $body .= '<tr>';
//                    $body .= '<td colspan="1" style="border-top: solid 1px #666;">';
//                    $body .= '<td colspan="1" style="border-top: solid 1px #666;">DELIVERY: ' . $delivery->delnam . ' </td>';
//                    $body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;' . $delivery->delpri . '</td>';
//                    $body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;' . $this->calcVAT($delivery->delpri) . '</td>';
//
//                    $vatTotal += $this->calcVAT($delivery->delpri);
//
//                    $body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;' . number_format($delivery->delpri, 2) . '</td>';
//                    $body .= '</tr>';
//
//                    $orderTotal += $delivery->delpri;
//
//                }

                $body .= '<tr class="orderTotals">';
                $body .= '<td colspan="1" style="border-top: solid 1px #666;">';
                $body .= '<td colspan="3" style="border-top: solid 1px #666;" align="right"> SUB TOTAL </td>';
                $body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;' . number_format($orderTotal, 2, '.', '') . '</td>';
                $body .= '</tr>';


                $body .= '<tr>';
                $body .= '<td colspan="3">';
                $body .= '<td colspan="1" align="right"> VAT </td>';
                //$body .= '<td align="right"> &pound;' . number_format((($orderTotal / 100) * $order->vatrat) + $orderTotal, 2) . '</td>';
                $body .= '<td align="right"> &pound;' . number_format($vatTotal, 2, '.', '') . '</td>';
                $body .= '</tr>';

                $body .= '<tr>';
                $body .= '<td colspan="3">';
                $body .= '<td colspan="1" align="right"> <b>TOTAL PAID</b> </td>';
                $body .= '<td align="right"> <b>&pound;' . number_format($orderTotal, 2, '.', '') . '</b></td>';
                $body .= '</tr>';
                $body .= '</table>';

                //die($body);

                $message = '<html><body>';
                $message .= $body;
                $message .= "</body></html>";

                return $message;

//                $headers = "From: " . $FrmEma . "\r\n";
//                $headers .= "Reply-To: " . $FrmEma . "\r\n";
//
//                $headers .= "MIME-Version: 1.0\r\n";
//                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//
//                $message = '<html><body>';
//
//                $message .= $body;
//
//                $message .= "</body></html>";
//
//                $sendOK = mail($mailto, 'THANK YOU FOR YOUR ORDER', $message, $headers);
//                $sendOK = mail($FrmEma, 'WEBSITE ORDER', $message, $headers);
//
//                if (!$sendOK) {
//
//
//                }

            }


        }

    }

    function withoutVAT($amount = 0, $vatRate = 20)
    {
        $vatCalc = ((100 + $vatRate) / 100);
        return number_format(($amount / $vatCalc), 2);
    }

    function calcVAT($amount = 0, $vatRate = 20)
    {
        $vatCalc = ((100 + $vatRate) / 100);
        return number_format(round($amount - ($amount / $vatCalc), 2, PHP_ROUND_HALF_DOWN), 2);
    }

    function vatAmount($amount = 0, $vatRate = 20)
    {
        return number_format(($amount / 100) * $vatRate, 2);
    }

    function addVAT($amount = 0, $vatRate = 20)
    {
        $amount = $amount + (($amount / 100) * $vatRate);
        return number_format($amount, 2);
    }

}

?>