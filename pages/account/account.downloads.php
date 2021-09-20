<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/system/classes/places.cls.php");
require_once("../admin/system/classes/related.cls.php");
require_once("../admin/gallery/classes/uploads.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

if (!$loggedIn) {
    header('location: login');
    exit();
}

$RelDao = new RelDAO();
$relatedRecs = $RelDao->select(NULL,'CUS',$loggedIn->pla_id,'UPLOAD',NULL,false);

$UplDao = new UplDAO();

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

                <h2 class="heading">Your Downloads</h2>

                <hr>

                <ul>

                <?php
                for ($d=0;$d<count($relatedRecs);$d++) {

                    $uploadRec = $UplDao->select($relatedRecs[$d]['ref_id'], NULL, NULL, NULL, true);

                    ?>

                    <li>
                        <a href="pages/products/download.upload.php?upl_id=<?php echo $uploadRec->upl_id; ?>" target="_blank"><?php echo $uploadRec->uplttl; ?></a>
                    </li>

                    <?php

                }
                ?>

                    </ul>

            </div>
        </div>
    </div>
</div>