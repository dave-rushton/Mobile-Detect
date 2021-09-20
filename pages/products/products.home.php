<?php

$TmpPrt = new PrtDAO();
$TmpUpl = new UplDAO();


?>

<div class="section nopadding nomargin">
    <div class="row">
        <div class="col-sm-12">


            <div class="row">

                <?php

                $structureRecs = $TmpStr->selectLevel(0, NULL, NULL, false);

                for ($i = 0; $i < count($structureRecs); $i++) {

                    $uploads = $TmpUpl->select(NULL, 'STRUCTURE', $structureRecs[$i]['str_id'], NULL, false);

                    $class = 'noimg';
                    $fileName = 'pages/img/noimg.png';
                    if (isset($uploads) && isset($uploads[0])) {
                        $fileName = $patchworks->webRoot . 'uploads/images/products/' . $uploads[0]['filnam'];
                        $class = '';
                    }

                    ?>
                    <div class="col-xs-6 col-sm-3">

                        <a href="<?php echo $_GET['seourl'] . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl']; ?>" class="shopCategoryLink">
                        <span class="imagewrapper">
                            <span class="image" style="background-image: url('<?php echo $fileName; ?>')">

                            </span>
                        </span>
                        <span class="content">
                            <h2><?php echo $structureRecs[$i]['strnam']; ?></h2>
                        </span>
                        </a>

                    </div>
                    <?php

                }

                ?>

            </div>

        </div>


    </div>
</div>


<div class="section">
    <div class="row">
        <?php

        $homeProducts = $TmpPrt->selectHomePage(NULL, NULL, false);

        $tableLength = count($homeProducts);

        for ($i = 0; $i < $tableLength; ++$i) {

            $uploads = $TmpPrt->getProductImage($homeProducts[$i]['prt_id']);

            $productPage = 'productlist';

            if (
                isset($uploads[0]) &&
                file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
            ) {
                $fileName = 'uploads/images/products/169-130/' . $uploads[0]['filnam'];
            } else {
                $fileName = 'pages/img/noimg.png';
            }

            ?>

            <div class="col-xs-6 col-md-3">

                <a href="<?php echo $_GET['seourl']; ?>/<?php echo $productPage; ?>/<?php echo $homeProducts[$i]['prt_id'] ?>/<?php echo $homeProducts[$i]['seourl'] ?>" class="shopCategoryLink">
                    <span class="imagewrapper">
                        <span class="image" style="background-image: url('<?php echo $fileName; ?>')">

                        </span>
                    </span>
                    <span class="content">
                        <h2><?php echo $homeProducts[$i]['prtnam']; ?></h2>
                        <p>from &pound;<?php echo $homeProducts[$i]['unipri']; ?></p>
                    </span>
                </a>

            </div>

            <?php
        }
        ?>
    </div>
</div>