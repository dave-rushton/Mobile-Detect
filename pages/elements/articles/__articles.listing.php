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

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>Our Blog</h1>
            </div>
        </div>
    </div>
</div>

<div class="section nogutter">
    <div class="container">
        <div class="row">

            <?php

            $alt = ' alt';
            $colorArray = ['green','purple','grey'];

            $tableLength = count($articles);
            for ($i = 0; $i < $tableLength; ++$i) {

                $uploads = $TmpUpl->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);
                $filename = 'pages/img/noimg.png';
                if (is_array($uploads) && count($uploads) > 0) $filename = 'uploads/images/540-440/' . $uploads[0]['filnam'];

                $color = $colorArray[rand(0,2)];

                //$alt = ($alt == '') ? ' alt' : '';

                if ($i % 4 == 0) { $alt = ''; }
                if ($i % 4 == 1) { $alt = ' alt'; }
                if ($i % 4 == 2) { $alt = ' alt'; }
                if ($i % 4 == 3) { $alt = ''; }

            ?>
            <div class="col-md-6">
                <div class="pagelink <?php echo $alt; ?>">
                    <div class="pagecontent">
                        <div class="pageimage <?php echo ($filename == 'pages/img/noimg.png') ? ' noimg ' : ''; ?>" style="background-image: url('<?php echo $filename; ?>')"></div>
                        <div class="pagetext <?php echo $color; ?>">
                            <div class="date"><?php echo date("jS M Y", strtotime($articles[$i]['artdat'])) ?></div>
                            <div class="centertext">
                                <h3><?php echo $articles[$i]['artttl'] ?></h3>
                                <p>
                                    <?php

                                    $output = $articles[$i]['artdsc'];
                                    if (strlen($articles[$i]['artdsc']) > 30) {
                                        $output = substr($articles[$i]['artdsc'], 0, strpos($articles[$i]['artdsc'], ' ', 30)).'...';
                                    }

                                    //echo $output;

                                    ?>
                                </p>
                            </div>
                            <a href="<?php echo $_GET['seourl']; ?>/article/<?php echo $articles[$i]['seourl'] ?>">read more</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>


        </div>

        <div class="row hide">
            <div class="col-sm-12">

                <div class="pagination">
                    <ul class="pagination">
                        <li><a href="<?php echo $_GET['seourl']; ?>/page/1">first</a></li>

                        <?php
                        $pageCount = (!is_null($PerPag) && is_numeric($PerPag)) ? $PerPag : $recordCount;
                        $MaxPag = ceil($recordCount / $pageCount);

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

    </div>
</div>

<ul class="hide">

    <?php

    $TmpSub = new SubDAO();
    $subCategories = $TmpSub->selectByTableName('article-types');

    $tableLength = count($subCategories);
    for ($i = 0; $i < $tableLength; ++$i) {
        echo '<li><a href="', $_GET['seourl'], '/categories/' . $subCategories[$i]['seourl'] . '">', $subCategories[$i]['subnam'], '</a></li>';
    }
    ?>
</ul>