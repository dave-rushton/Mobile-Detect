<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/system/classes/places.cls.php");
require_once("../admin/ecommerce/classes/order.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

if (!$loggedIn) header('location: login');

$TmpOrd = new OrdDAO();
$orders = $TmpOrd->select(NULL, 'CUS', $loggedIn->pla_id, NULL, false);

?>

<div class="section">
    <div class="container contentbox">
        <div class="row">
            <div class="col-sm-12">

                <h1 class="heading">My Orders</h1>

                <table class="table table-nomargin table-striped" id="ordersTable">
                    <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Invoice Date</th>
                        <th>Due Date</th>
                        <th style="text-align: right;">Amount</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody id="ordersBody">

                    <?php

                    $tableLength = count($orders);
                    $totForAdv = 0;
                    $advPayDat = 0;
                    for ($i=0;$i<$tableLength;++$i) {

                        $ordTot = str_replace(',', '', number_format($orders[$i]['ordtot'], 2));
                        $totForAdv += (float)$ordTot;
                        ?>

                        <tr <?php if ($orders[$i]['sta_id'] < 20 && strtotime(date('Y-m-d')) > strtotime($orders[$i]['duedat']) ) echo 'class="error"'; ?>>
                            <td><a href="ecommerce/order-edit.php?ord_id=<?php echo $orders[$i]['ord_id']; ?>" class="editOrderLnk" data-ord_id="<?php echo $orders[$i]['ord_id']; ?>"><?php echo str_pad($orders[$i]['ord_id'], 8, "0", STR_PAD_LEFT); ?></a></td>
                            <td><?php echo date("jS M Y", strtotime($orders[$i]['invdat'])); ?></td>
                            <td><?php echo date("jS M Y", strtotime($orders[$i]['duedat'])); ?></td>
                            <td style="text-align: right;">&pound;<span class="orderTotalCalc"><?php echo $ordTot; ?></span></td>
                            <td>
                                <?php

                                $now = strtotime($orders[$i]['paydat']);
                                $your_date = strtotime($orders[$i]['duedat']);
                                $datediff = $your_date - $now;
                                $datediff = ceil($datediff/(60*60*24));

                                $status = 'UNKNOWN';
                                switch ($orders[$i]['sta_id']) {
                                    case 0:
                                        echo "Active";
                                        break;
                                    case 10:
                                        echo "Invoiced";
                                        break;
                                    case 20:
                                        $advPayDat += $datediff;
                                        echo "Paid"; //<br><small>".$datediff."</small>";
                                        break;
                                }

                                ?>
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>