<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/system/classes/places.cls.php");
require_once("../admin/system/classes/related.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

if (!$loggedIn) {
    header('location: login');
    exit();
}

$PrdDao = new PrdDAO();

$RelDao = new RelDAO();
$relatedRecs = $RelDao->select(NULL,'CUS',$loggedIn->pla_id,'PRODUCT',NULL,false, NULL);

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">

                <div class="box">

                    <?php include('account.menu.php'); ?>

                </div>

            </div>
            <div class="col-sm-9">

                <h2 class="heading">Wish List</h2>

                <hr>

                <div class="wishlistlist">

                    <ul>

                        <?php

                        $tableLength = count($relatedRecs);
                        for ($i = 0; $i < $tableLength; ++$i) {

                            $PrdObj = $PrdDao->select($relatedRecs[$i]['ref_id'], NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL);

                            ?>


                            <li>

                                <?php
                                /// CHECK ECOMM DISPLAY TYPE FOR LINK CREATION !!!
                                ?>

                                <a href="<?php echo $patchworks->webRoot.$patchworks->productsURL.'/productfull/'.$PrdObj->prd_id.'/'.$PrdObj->seourl; ?>"><?php echo $PrdObj->prdnam.', '.$PrdObj->prtnam; ?></a>

                            </li>

                            <?php
                        }
                        ?>

                    </ul>

                </div>

            </div>
        </div>
    </div>
</div>