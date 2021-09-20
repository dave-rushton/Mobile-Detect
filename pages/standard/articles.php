<?php

require_once("../../config/config.php" );
require_once("../../admin/patchworks.php" );
require_once("../../admin/website/classes/articles.cls.php");
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/system/classes/subcategories.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$UplDao = new UplDAO();

$eleVarArr = json_decode($EleObj->elevar, true);

$ParSeo = $EleDao->getVariable($EleObj, 'fwdurl' );
if (empty($ParSeo)) $ParSeo = $_GET['seourl'];


$content_first = $EleDao->getVariable($EleObj, 'content_first',false);
$graphic_type = $EleDao->getVariable($EleObj, 'graphic_type',false);
$PerPag = $EleDao->getVariable($EleObj, 'perpag',false);
$RemPag = $EleDao->getVariable($EleObj, 'rempag',false);
$listing_template = $EleDao->getVariable($EleObj, 'listing_template',false );


$article_template = $EleDao->getVariable($EleObj, 'article_template',false);
$hide_date = $EleDao->getVariable($EleObj, 'hide_date',false);
$hide_title = $EleDao->getVariable($EleObj, 'hide_title',false);
$hide_image = $EleDao->getVariable($EleObj, 'hide_image',false);
$hide_description = $EleDao->getVariable($EleObj, 'hide_description',false);

if (empty($listing_template)) $listing_template = 'Style 1';
if (empty($article_template)) $article_template = 'Style 1';
$sidebar = $EleDao->getVariable($EleObj, 'sidebar' ,false);
//
// Columns
//
$NumCol = 2;
$NumCol = $EleDao->getVariable($EleObj, 'numcol');
if (!is_numeric($NumCol)) $NumCol = 3;
$ColWid = 12 / $NumCol;



$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : '';
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

function get_image($file){

}
function latest_articles($limit = NULL, $title = true,  $description = true, $image = true, $date = true){
    global $articles;
    global $subCategory;
    global $TmpArt;
    global $UplDao;
    global $ParSeo;
    global $SeoUrl;
    global $sidebar;

    if(empty($limit)){
        $limit = 10;
    }

    $string = "";
    $string .= '<div class="recent-posts">';
    $articles = $TmpArt->selectByCategory($subCategory->sub_id, false,$limit,1);

    $tableLength = count($articles);
    for ($i=0;$i<$tableLength;++$i) {

        $uploads = $UplDao->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);
        $seo = (!is_null($ParSeo)) ? $ParSeo : $SeoUrl;

            $string .= '<div class="recent-post">';
            $string .= '<a href="'.$seo.'/article/'.$articles[$i]['seourl'].'" class="isobox" data-srtord="0">';
            if($title == true){
                $string .= '<div class="title-wrapper">';
                $string .= $articles[$i]['artttl'];
                $string .= '</div>';
            }
            if($image == true){
                if (is_array($uploads) && count($uploads) > 0)
                {
                    $string .= '<div class="image-wrapper">';
                    echo '<img src="uploads/images/1170-750/'.$uploads[0]['filnam'] .'" alt="'.$uploads[0]['alttxt'].'">';
                    $string .= '</div>';
                }
            }
            if($date == true){
                $string .= '<div class="date-wrapper">';
                $string .= '<p>';
                $string .= '<small>';
                $string .= date("jS M Y", strtotime($articles[$i]['artdat']));
                $string .= '</small>';
                $string .= '</p>';
                $string .= '</div>';
            }
            if($description == true){
                $string .= '<div class="description-wrapper">';
                $string .= '<p>';
                $string .=  nl2br($articles[$i]['artdsc']);
                $string .= '</p>';
                $string .= '</div>';
            }
            $string .= '</a>';
            $string .= '</div>';

    }
    $string .= '</div>';
    return $string;
}

?>


<div class="section">
    <div class="articles-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">

                    <?php

                    if ($DspTyp == 'LISTING') {
                        $seo = (!is_null($ParSeo)) ? $ParSeo : $SeoUrl;

                        if ($listing_template == 'Style 1' || empty($listing_template)) {
                            echo '<div class="news-items">';
                                $tableLength = count($articles);
                                for ($i=0;$i<$tableLength;++$i) {
                                    $uploads = $UplDao->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);
                                    echo '<div class="news-item">';
                                        echo'<a href="'.$seo.'/article/'.$articles[$i]['seourl'].'">';
                                            if(empty($hide_title)){
                                                echo '<span class="main-title">';
                                                    echo '<h2>'.$articles[$i]['artttl'].'</h2>';
                                                echo '</span>';
                                            }
                                            if(empty($hide_date)){
                                                echo '<span class="date-wrapper">';
                                                    echo '<p>'.date("jS M Y", strtotime($articles[$i]['artdat'])).'</p>';
                                                echo '</span>';
                                            }

                                            if(empty($hide_image)){
                                                echo '<div class="image-wrapper">';
                                                    if (is_array($uploads) && count($uploads) > 0) {
                                                        echo '<img src="uploads/images/1170-750/'.$uploads[0]['filnam'] .'" alt="'.$uploads[0]['alttxt'].'>';
                                                    }else{
                                                        echo '<img src="pages/img/article-holding.png" alt="">';
                                                    }
                                                echo '</div>';
                                            }

                                            if(empty($hide_description)){
                                                echo '<span class="description-wrapper">';
                                                    echo '<p>'.nl2br($articles[$i]['artdsc']).'</p>';
                                                echo '</span>';
                                            }
                                        echo '</a>';
                                    echo '</div>';
                                 }
                            echo'</div>';
                         }

                        if ($listing_template == 'Style 2') {
                            echo '<div class="news-items listing-type-2">';
                                $tableLength = count($articles);
                                echo '<div class="row">';
                                    echo '<div class="col-lg-9 col-md-8">';
                                    for ($i=0;$i<$tableLength;++$i) {
                                        $uploads = $UplDao->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);

                                        echo '<div class="news-item">';
                                            echo'<a href="'.$seo.'/article/'.$articles[$i]['seourl'].'">';
                                                 if(empty($hide_image)){
                                                    echo '<div class="image-wrapper">';
                                                     if (is_array($uploads) && count($uploads) > 0) {
                                                         echo '<img src="uploads/images/1170-750/'.$uploads[0]['filnam'] .'" alt="'.$uploads[0]['alttxt'].'">';
                                                     }else{
                                                         echo '<img src="pages/img/article-listing-placeholder.png" alt="Placeholder">';
                                                     }
                                                    echo '</div>';
                                                }
                                                echo '<span class="content-wrapper">';
                                                    if(empty($hide_title)){
                                                        echo '<span class="main-title">';
                                                            echo '<h2>'.$articles[$i]['artttl'].'</h2>';
                                                        echo '</span>';
                                                    }
                                                    if(empty($hide_date)){
                                                        echo '<span class="date-wrapper">';
                                                            echo '<p>'.date("jS M Y", strtotime($articles[$i]['artdat'])).'</p>';
                                                        echo '</span>';
                                                    }

                                                    if(empty($hide_description)){
                                                        echo '<span class="description-wrapper">';
                                                            echo '<p>'.nl2br($articles[$i]['artdsc']).'</p>';
                                                        echo '</span>';
                                                    }
                                                echo '</span>';
                                            echo '</a>';
                                        echo '</div>';
                                    }
                                echo '</div>';
                                echo '<div class="col-lg-3 col-md-4">';
                                    echo '<div class="article-sidebar">';
                                        echo '<div class="newsList">';
                                            echo '<div class="isowrapper" id="isocontainer">';
                                                echo '<h2>Recent Posts</h2>';
                                                echo latest_articles(NULL,true,false,false,false);
                                                echo $sidebar;
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                         }

                        if ($listing_template == 'Style 3') {
                            echo '<div class="news-items listing-type-3">';
                                $tableLength = count($articles);
                                echo '<div class="row">';
                                    echo '<div class="col-lg-9 col-md-8">';
                                    for ($i=0;$i<$tableLength;++$i) {
                                        $uploads = $UplDao->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);

                                        echo '<div class="news-item">';
                                            echo'<a href="'.$seo.'/article/'.$articles[$i]['seourl'].'">';
                                                 if(empty($hide_image)){
                                                    echo '<div class="image-wrapper">';
                                                     if (is_array($uploads) && count($uploads) > 0) {
                                                         echo '<img src="uploads/images/1170-475/'.$uploads[0]['filnam'] .'" alt="'.$uploads[0]['alttxt'].'">';
                                                     }else{
                                                         echo '<img src="pages/img/article-listing-placeholder.png" alt="Placeholder">';
                                                     }
                                                    echo '</div>';
                                                }
                                                echo '<span class="content-wrapper">';
                                                    if(empty($hide_title)){
                                                        echo '<span class="main-title">';
                                                            echo '<h2>'.$articles[$i]['artttl'].'</h2>';
                                                        echo '</span>';
                                                    }
                                                    if(empty($hide_date)){
                                                        echo '<span class="date-wrapper">';
                                                            echo '<p>'.date("jS M Y", strtotime($articles[$i]['artdat'])).'</p>';
                                                        echo '</span>';
                                                    }

                                                    if(empty($hide_description)){
                                                        echo '<span class="description-wrapper">';
                                                            echo '<p>'.nl2br($articles[$i]['artdsc']).'</p>';
                                                        echo '</span>';
                                                    }
                                                echo '</span>';
                                            echo '</a>';
                                        echo '</div>';
                                    }
                                echo '</div>';
                                echo '<div class="col-lg-3 col-md-4">';
                                    echo '<div class="article-sidebar">';
                                        echo '<div class="newsList">';
                                            echo '<div class="isowrapper" id="isocontainer">';
                                                echo '<h2>Recent Posts</h2>';
                                                echo latest_articles(NULL,true,false,false,false);
                                                echo $sidebar;
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';

                         }

                        ?>




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

                        function slider(){
                            global $articleRec;
                            global $UplDao;
                            $uploads = $UplDao->select(NULL, 'ARTICLE', $articleRec->art_id, NULL, false);
                            $string = "";

                            $string .= '<div id="imageslider" class="flexslider">';
                            $string .= '<ul class="slides">';
                            for ($i=0;$i<count($uploads);$i++) {
                                $string .= '<li>';
                                $string .= '<img src="uploads/images/1170-750/'.$uploads[$i]['filnam'].'" />';
                                $string .= '</li>';
                            }
                            $string .= '</ul>';
                            $string .= '</div>';

                            return $string;
                        }
                        function image_list(){
                            global $articleRec;
                            global $UplDao;
                            $uploads = $UplDao->select(NULL, 'ARTICLE', $articleRec->art_id, NULL, false);
                            $string = "";

                            $string .= '<div class="image-list">';
                            for ($i=0;$i<count($uploads);$i++) {
                                $string .= '<img src="uploads/images/1170-750/'.$uploads[$i]['filnam'].'" />';
                            }
                            $string .= '</div>';

                            return $string;
                        }


                        if($article_template == "Style 1"){
                            ?>

                            <div class="main-article">
                                <h2><?php echo $articleRec->artttl; ?></h2>
                                <p><i class="icon-calendar"></i><?php echo date("jS M Y", strtotime($articleRec->artdat)); ?></p>

                                <h4><?php $articleRec->artdsc; ?></h4>
                                <div class="articleText"><?php echo $articleRec->arttxt; ?></div>

                                <?php
                                if(!empty($content_first)){
                                    echo '<div class="articleText">';
                                    echo $articleRec->arttxt;
                                    echo '</div>';
                                }

                                switch ($graphic_type){
                                    case "slider":
                                        echo slider();
                                        break;
                                    case "image-list";
                                        echo image_list();
                                        break;
                                    default:

                                        break;
                                }


                                if(empty($content_first)){
                                    echo '<div class="articleText">';
                                    echo $articleRec->arttxt;
                                    echo '</div>';
                                }
                                ?>

                                <div class="socialshare">
                                    <p>Share this item</p>
                                    <ul>
                                        <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-facebook"></i> </a></li>
                                        <li><a href="https://twitter.com/home?status=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-twitter"></i> </a></li>
                                        <li><a href="https://pinterest.com/pin/create/button/?url=&media=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-pinterest"></i> </a></li>
                                    </ul>
                                </div>
                            </div>
                            <p>
                                <a class="cta" href="/news">
                                    Back to News
                                </a>
                            </p>
                            <?php
                        }

                        if($article_template == "Style 2"){
                            ?>
                            <div class="row">
                                <div class="col-md-8 col-lg-9">
                                    <div class="main-article">
                                        <div class="main-title">
                                            <h1><?php echo $articleRec->artttl; ?></h1>
                                        </div>
                                        <div class="date">
                                            <p><i class="icon-calendar"></i><?php echo date("jS M Y", strtotime($articleRec->artdat)); ?></p>
                                        </div>
                                        <div class="inner-content">
                                            <?php
                                            if(!empty($content_first)){
                                                echo '<div class="articleText">';
                                                echo $articleRec->arttxt;
                                                echo '</div>';
                                            }

                                            switch ($graphic_type){
                                                case "slider":
                                                    echo slider();
                                                    break;
                                                case "image-list";
                                                    echo image_list();
                                                    break;
                                                default:

                                                    break;
                                            }


                                            if(empty($content_first)){
                                                echo '<div class="articleText">';
                                                echo $articleRec->arttxt;
                                                echo '</div>';
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <div class="article-sidebar">
                                        <div class="newsList">
                                            <div class="isowrapper" id="isocontainer">
                                                <h2>Recent Posts</h2>
                                                <?php
                                                echo latest_articles(NULL,true,false,false,false);
                                                echo $sidebar;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="socialshare">
                                        <p>Share this item</p>
                                        <ul>
                                            <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-facebook"></i> </a></li>
                                            <li><a href="https://twitter.com/home?status=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-twitter"></i> </a></li>
                                            <li><a href="https://pinterest.com/pin/create/button/?url=&media=<?php echo $patchworks->webRoot.'articles/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-pinterest"></i> </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                        ?>

                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>