<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
include_once("../../admin/products/classes/products.cls.php");
include_once("../../admin/products/classes/product_types.cls.php");

$PrtDao = new PrtDAO();

?>


<ul>

    <?php

    $shoppingCart = (isset($_POST['cart'])) ? json_decode($_POST['cart'], true) : array();

    if (isset($_SESSION['cart'])) $shoppingCart = json_decode($_SESSION['cart'], true);

    $totalItems = 0;
    $totalAmount = 0;

    if (isset($shoppingCart['items']) && is_array($shoppingCart['items'])) {
        for ($i=0;$i<count($shoppingCart['items']);$i++) {

            $uploads = $PrtDao->getProductVariantImage($shoppingCart['items'][$i]['prd_id']);
            $imageURL = $uploads[0]['filnam'];

            $totalItems += $shoppingCart['items'][$i]['qty'];
            @$totalAmount += ($shoppingCart['items'][$i]['totamt'] * $shoppingCart['items'][$i]['qty']);

            ?>

            <li>
                <div class="floatcartitem">

                    <div class="imgwrapper">
                        <div class="image" style="background-image: url('uploads/images/products/169-130/<?php echo $imageURL; ?>');"></div>
                    </div>

                    <h2><?php echo $shoppingCart['items'][$i]['prdnam']; ?></h2>

                    <p class="price">&pound;<?php echo $shoppingCart['items'][$i]['unipri']; ?></p>

                    <p class="qty">x <?php echo $shoppingCart['items'][$i]['qty']; ?></p>
                </div>
            </li>

            <?php

        }
    }
    ?>

</ul>