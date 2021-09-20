<?php

$Art_ID = (isset($_GET['art_id']) && is_numeric($_GET['art_id'])) ? $_GET['art_id'] : NULL;
$ArtTyp = (isset($_GET['arttyp'])) ? $_GET['arttyp'] : NULL;

$Art_Yr = (isset($_GET['year']) && is_numeric($_GET['year'])) ? $_GET['year'] : NULL;
$Art_Mn = (isset($_GET['month']) && is_numeric($_GET['month'])) ? $_GET['month'] : NULL;

$PerPag = (isset($_GET['perpag']) && is_numeric($_GET['perpag'])) ? $_GET['perpag'] : 8;
$OffSet = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : NULL;
$Pag_No = (isset($_GET['pag_no']) && is_numeric($_GET['pag_no'])) ? $_GET['pag_no'] : 1;

if (!is_null($ArtTyp) && !empty($ArtTyp)) {
    // by sub category
    $SubDao = new SubDAO();
    $subCategory = $SubDao->selectByCategory(NULL, $ArtTyp);
    $articles = $TmpArt->selectByCategory($subCategory->sub_id, false);
    $recordCount = count($articles);
} else if (!is_null($Art_Yr)) {
    if (!is_null($Art_Mn)) {
        // by year and month
        $articles = $TmpArt->selectByArchive($Art_Yr, $Art_Mn);
        $recordCount = count($articles);
    } else {
        // by year
        $articles = $TmpArt->selectByArchive($Art_Yr = NULL);
        $recordCount = count($articles);
    }

} else {
    // complete listing
    $articles = $TmpArt->select($Art_ID, NULL, $PerPag, $Pag_No, false);
    $recordCount = count($TmpArt->select($Art_ID, NULL, NULL, NULL, false));
}

$FwdUrl = $_GET['seourl'];

?>

<div id="newsList">

    <div class="row">
        <div class="col-sm-12">

            <div class="row">

                <?php
                $tableLength = count($articles);
                for ($i=0;$i<$tableLength;++$i) {

                    $uploads = $TmpUpl->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);

                    $imgUrl = 'pages/img/noimg.png';

                    if (
                        file_exists($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam']) &&
                        !is_dir($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam'])
                    ) {
                        $imgUrl = $patchworks->webRoot.'uploads/images/'.$uploads[0]['filnam'];
                    }

                    ?>

                    <div class="col-sm-6">
                        <div class="homenewsitem">

                            <a href="<?php echo $patchworks->webRoot.$FwdUrl.'/article/'.$articles[$i]['seourl']; ?>" class="newsimage" style="background-image: url('<?php echo $imgUrl; ?>');">

                                <div class="date">
                                    <?php echo date("d", strtotime($articles[$i]['artdat'])) ?>
                                    <span><?php echo date("M", strtotime($articles[$i]['artdat'])) ?></span>
                                </div>

                            </a>

                            <h3 class="newstitle">
                                <?php echo $articles[$i]['artttl'] ?>
                            </h3>
                            <p class="newsdescription">
                                <?php echo nl2br($articles[$i]['artdsc']); ?>
                            </p>

                        </div>
                    </div>

                <?php } ?>


            </div>




            <?php
            $pageCount = (!is_null($PerPag) && is_numeric($PerPag)) ? $PerPag : $recordCount;
            $MaxPag = ceil($recordCount / $pageCount);

            if ($MaxPag > 1) {

                ?>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="pagination">
                            <ul class="pagination">
                                <li><a href="<?php echo $_GET['seourl']; ?>/page/1">first</a></li>

                                <?php


                                for ($p = 1; $p <= $MaxPag; $p++) {
                                    ?>
                                    <li<?php if ($p == $Pag_No) echo ' class="active"'; ?>><a
                                            href="<?php echo $_GET['seourl']; ?>/page/<?php echo $p; ?>"><?php echo $p; ?></a>
                                    </li>
                                    <?php
                                }
                                ?>

                                <li><a href="<?php echo $_GET['seourl']; ?>/page/<?php echo $MaxPag; ?>">last</a></li>
                            </ul>
                        </div>


                    </div>
                </div>

                <?php
            }
            ?>

        </div>
        <div class="col-sm-4">

            <ul>

                <?php

                $TmpSub = new SubDAO();
                $subCategories = $TmpSub->selectByTableName('article-types');

                $tableLength = count($subCategories);
                for ($i = 0; $i < $tableLength; ++$i) {
                    echo '<li><a href="', $_GET['seourl'], '/categories/' . $subCategories[$i]['seourl'] . '">', $subCategories[$i]['subnam'], '</a></li>';
                }
                ?>
            </ul>

        </div>
    </div>

</div>
