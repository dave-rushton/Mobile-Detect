<?php

//$file = 'payments.txt';
//
//$current = file_get_contents($file);
//
//$current .= var_dump($_POST);
//
//file_put_contents($file, $current);
?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <?php
                $json = json_decode($eCommProp->ecoobj);
                echo $json->ordersuccesstext;
//                 echo $patchworks->getJSONVariable($eCommProp->ecoobj,'ordersuccesstext', true);

                ?>

            </div>
        </div>
    </div>
</div>

<?php

unset($shoppingCart);
$shoppingCart = array();
$_SESSION['cart'] = json_encode($shoppingCart);

?>
