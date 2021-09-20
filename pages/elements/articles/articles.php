<?php

require_once("../../../config/config.php" );
require_once("../../../admin/patchworks.php" );
require_once("../../../admin/website/classes/articles.cls.php");
require_once("../../../admin/website/classes/pageelements.cls.php");
require_once("../../../admin/system/classes/subcategories.cls.php");
require_once("../../../admin/gallery/classes/uploads.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$UplDao = new UplDAO();

$eleVarArr = json_decode($EleObj->elevar, true);

$ParSeo = $EleDao->getVariable($EleObj, 'fwdurl' );
if (empty($ParSeo)) $ParSeo = $_GET['seourl'];

$PerPag = $EleDao->getVariable($EleObj, 'perpag' );
$RemPag = $EleDao->getVariable($EleObj, 'rempag' );
$TmpLte = $EleDao->getVariable($EleObj, 'style' );

if (empty($TmpLte)) $TmpLte = 'Style 1';


//
// Columns
//
$NumCol = 2;
$NumCol = $EleDao->getVariable($EleObj, 'numcol');
if (!is_numeric($NumCol)) $NumCol = 3;
$ColWid = 12 / $NumCol;



$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : $seourl;
$ArtSeo = (isset($_GET['artseo'])) ? $_GET['artseo'] : NULL;

$TmpArt = new ArtDAO();
$Art_ID = (isset($_GET['art_id']) && is_numeric($_GET['art_id'])) ? $_GET['art_id'] : NULL;
$ArtTyp = (isset($_GET['arttyp'])) ? $_GET['arttyp'] : NULL;

$Art_Yr = (isset($_GET['year']) && is_numeric($_GET['year'])) ? $_GET['year'] : NULL;
$Art_Mn = (isset($_GET['month']) && is_numeric($_GET['month'])) ? $_GET['month'] : NULL;

$PerPag = (isset($_GET['perpag']) && is_numeric($_GET['perpag'])) ? $_GET['perpag'] : $PerPag;
$OffSet = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : NULL;
$Pag_No = (isset($_GET['pag_no']) && is_numeric($_GET['pag_no'])) ? $_GET['pag_no'] : 1;

if (!isset($OffSet) || !is_numeric($OffSet)) {
	$OffSet = ($Pag_No-1) * $PerPag;
}

$DspTyp = NULL;

if (!is_null($ArtSeo)) {
	
	$DspTyp = 'ARTICLE';
	
	$ArtSeo = (isset($_GET['artseo'])) ? $_GET['artseo'] : NULL;
	$articleRec = $TmpArt->select(NULL, $ArtSeo, NULL, NULL, true); 
	
} else {
	
	$DspTyp = 'LISTING';

    $ArtTyp = NULL;
    $ArtTyp = $EleDao->getVariable($EleObj, 'arttyp' );
	
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
			$articles = $TmpArt->selectByArchive($Art_Yr=NULL);
			$recordCount = count($articles);
		}
		
	} else {
		// complete listing
		$articles = $TmpArt->select($Art_ID, NULL, $PerPag, $Pag_No, false); 
		$recordCount = count($TmpArt->select($Art_ID, NULL, NULL, NULL, false)); 
	}
}
?>


<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">


<?php 
if ($DspTyp == 'LISTING') {
?>

<?php if ($TmpLte == 'Style 1' || empty($TmpLte)) { ?>

        <div class="newsList">
            <?php
            $tableLength = count($articles);
            for ($i=0;$i<$tableLength;++$i) {
                $uploads = $UplDao->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);
                ?>

                <?php if (($i % $NumCol) == 0 && $i > 0) echo '</div>'; ?>
                <?php if (($i % $NumCol) == 0) echo '<div class="row">'; ?>

                <div class="col-md-<?php echo $ColWid; ?>">

                    <div class="newsItem">

                            <h2>
                                <a href="<?php echo (!is_null($ParSeo)) ? $ParSeo : $SeoUrl; ?>/article/<?php echo $articles[$i]['seourl'] ?>"><?php echo $articles[$i]['artttl'] ?></a>
                            </h2>

                            <p><i class="fa fa-clock-o"></i> Posted on <?php echo date("jS M Y", strtotime($articles[$i]['artdat'])) ?></p>
                            <hr>

                            <?php
                            if (is_array($uploads) && count($uploads) > 0) {
                                ?>
                                <a href="<?php echo (!is_null($ParSeo)) ? $ParSeo : $SeoUrl; ?>/article/<?php echo $articles[$i]['seourl'] ?>">
                                    <img class="img-responsive img-hover" src="<?php echo 'uploads/images/850-600/'.$uploads[0]['filnam'] ?>" alt="">
                                </a>
                                <hr>
                            <?php } ?>
                            <p><?php echo nl2br($articles[$i]['artdsc']); ?></p>

                    </div>
                </div>

            <?php } ?>
            <?php if (($tableLength % $NumCol) == 0 && $tableLength > 0) echo '</div>'; ?>

        </div>

<?php } ?>

<?php if ($TmpLte == 'Style 2' || empty($TmpLte)) { ?>

        <div class="newsList">
            <?php
            $tableLength = count($articles);
            for ($i=0;$i<$tableLength;++$i) {
                $uploads = $UplDao->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);
                ?>


                <?php if (($i % $NumCol) == 0 && $i > 0) echo '</div>'; ?>
                <?php if (($i % $NumCol) == 0) echo '<div class="row">'; ?>

                <div class="col-md-<?php echo $ColWid; ?>">

                    <div class="newsItem">

                            <div class="row">
                                <div class="col-md-4">

                                    <?php
                                    if (is_array($uploads) && count($uploads) > 0) {
                                        ?>
                                        <a href="<?php echo (!is_null($ParSeo)) ? $ParSeo : $SeoUrl; ?>/article/<?php echo $articles[$i]['seourl'] ?>">
                                            <img class="img-responsive img-hover" src="<?php echo 'uploads/images/850-600/'.$uploads[0]['filnam'] ?>" alt="">
                                        </a>
                                    <?php } ?>

                                </div>
                                <div class="col-md-8">
                                    <h3><a href="<?php echo (!is_null($ParSeo)) ? $ParSeo : $SeoUrl; ?>/article/<?php echo $articles[$i]['seourl'] ?>"><?php echo $articles[$i]['artttl'] ?></a>
                                    </h3>
                                    <p><small><?php echo date("jS M Y", strtotime($articles[$i]['artdat'])) ?></small></p>

                                    <p><?php echo nl2br($articles[$i]['artdsc']); ?></p>
                                </div>
                            </div>

                            <hr />

                    </div>
                </div>
            <?php } ?>
            <?php if (($tableLength % $NumCol) == 0 && $tableLength > 0) echo '</div>'; ?>

        </div>

<?php } ?>

<?php if ($TmpLte == 'Style 3' || empty($TmpLte)) { ?>

    <div class="newsList">
        <div class="isowrapper" id="isocontainer">
        <?php
        $tableLength = count($articles);
        for ($i=0;$i<$tableLength;++$i) {
            $uploads = $UplDao->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);
            ?>

                <a href="<?php echo (!is_null($ParSeo)) ? $ParSeo : $SeoUrl; ?>/article/<?php echo $articles[$i]['seourl'] ?>" class="isobox" data-srtord="0">
                    <?php
                    if (is_array($uploads) && count($uploads) > 0) {
                        ?>

                        <img class="img-responsive img-hover" src="<?php echo 'uploads/images/850-600/'.$uploads[0]['filnam'] ?>" alt="">

                        <p>
                            <small><?php echo date("jS M Y", strtotime($articles[$i]['artdat'])) ?></small><br>
                            <?php echo nl2br($articles[$i]['artdsc']); ?>
                        </p>

                    <?php } ?>
                </a>

        <?php } ?>

    </div>
        </div>

<?php } ?>



    <?php if ($TmpLte == 'Style 4' || empty($TmpLte)) { ?>

        <div class="newsList">
            <?php
            $tableLength = count($articles);
            for ($i=0;$i<$tableLength;++$i) {
                $uploads = $UplDao->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);
                ?>

                <?php if (($i % $NumCol) == 0 && $i > 0) echo '</div>'; ?>
                <?php if (($i % $NumCol) == 0) echo '<div class="row">'; ?>

                <div class="col-md-<?php echo $ColWid; ?>">

                    <div class="newsItem">

                        <?php
                        if (is_array($uploads) && count($uploads) > 0) {
                            ?>
                            <a href="<?php echo (!is_null($ParSeo)) ? $ParSeo : $SeoUrl; ?>/article/<?php echo $articles[$i]['seourl'] ?>">
                                <img class="img-responsive img-hover" src="<?php echo 'uploads/images/850-600/'.$uploads[0]['filnam'] ?>" alt="">
                                <span class="textwrapper">
                                      <span class="table">
                                    <span class="cell">
                                        <?php echo $articles[$i]['artttl'] ?>
                                    </span>
                                </span>
                                </span>


                            </a>
                        <?php } ?>

                    </div>
                </div>

            <?php } ?>
            <?php if (($tableLength % $NumCol) == 0 && $tableLength > 0) echo '</div>'; ?>

        </div>

    <?php } ?>



	<?php if ($RemPag != 'on' && $recordCount > 0) { ?>
	
	<div class="pagination">
		<ul class="pagination">
			<li><a href="<?php echo $SeoUrl; ?>/page/1">first</a></li>
			
			<?php 
			$pageCount = (!is_null($PerPag) && is_numeric($PerPag)) ? $PerPag : $recordCount;
			$MaxPag = ceil($recordCount / $pageCount);
			
			for ($p=1;$p<=$MaxPag;$p++) {
			?>
			<li<?php if ($p == $Pag_No) echo ' class="active"'; ?>><a href="<?php echo $SeoUrl; ?>/page/<?php echo $p; ?>"><?php echo $p; ?></a></li>
			<?php 
			}
			?>
			
			<li><a href="<?php echo $SeoUrl; ?>/page/<?php echo $MaxPag; ?>">last</a></li>
		</ul>
	</div>
	<?php } ?>


<?php } ?>


<?php 
if ($DspTyp == 'ARTICLE') { 
	$uploads = $UplDao->select(NULL, 'ARTICLE', $articleRec->art_id, NULL, false);
?>

<!--    <script>-->
<!--        window.fbAsyncInit = function() {-->
<!--            FB.init({-->
<!--                appId      : '1515606485404909',-->
<!--                xfbml      : true,-->
<!--                version    : 'v2.5'-->
<!--            });-->
<!--        };-->
<!---->
<!--        (function(d, s, id){-->
<!--            var js, fjs = d.getElementsByTagName(s)[0];-->
<!--            if (d.getElementById(id)) {return;}-->
<!--            js = d.createElement(s); js.id = id;-->
<!--            js.src = "//connect.facebook.net/en_US/sdk.js";-->
<!--            fjs.parentNode.insertBefore(js, fjs);-->
<!--        }(document, 'script', 'facebook-jssdk'));-->
<!--    </script>-->

	<div class="newsDetail">
		<h2><?php echo $articleRec->artttl; ?></h2>
		<h6><i class="icon-calendar"></i><?php echo date("jS M Y", strtotime($articleRec->artdat)); ?></h6>
		
		<div id="imageslider" class="flexslider">
			<ul class="slides">
				<?php 
				for ($i=0;$i<count($uploads);$i++) {
				?>
				<li><?php echo '<img src="uploads/images/850-600/'.$uploads[$i]['filnam'].'" />'; ?></li>
				<?php } ?>
			</ul>
		</div>
		
		<h4><?php $articleRec->artdsc; ?></h4>
		<div class="articleText"><?php echo $articleRec->arttxt; ?></div>

<!--        <div-->
<!--            class="fb-like"-->
<!--            data-share="true"-->
<!--            data-width="450"-->
<!--            data-href="--><?php //echo $patchworks->webRoot.$patchworks->articlesURL.$patchworks->articleURL.$articleRec->seourl; ?><!--"-->
<!--            data-layout="standard"-->
<!--            data-action="like"-->
<!--            data-show-faces="true">-->
<!--        </div>-->


        <div class="socialshare">

            <p>Share this item</p>

            <ul>
                <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-facebook"></i> </a></li>
                <li><a href="https://twitter.com/home?status=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-twitter"></i> </a></li>
                <li><a href="https://plus.google.com/share?url=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-google-plus"></i> </a></li>
                <li><a href="https://pinterest.com/pin/create/button/?url=&media=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-pinterest"></i> </a></li>
            </ul>
        </div>


	</div>
	
<?php } ?>

            </div>
        </div>
    </div>
</div>