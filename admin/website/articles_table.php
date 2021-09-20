<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../ecommerce/classes/order.cls.php");
require_once("../ecommerce/classes/delivery.cls.php");
require_once("../website/classes/articles.cls.php");
require_once("../system/classes/subcategories.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die('#');

$Sta_ID = (isset($_GET['sta_id'])) ? $_GET['sta_id'] : NULL;
$BegDat = (isset($_GET['begdat']) && !empty($_GET['begdat'])) ? $_GET['begdat'] : NULL;
$EndDat = (isset($_GET['enddat']) && !empty($_GET['enddat'])) ? $_GET['enddat'] : NULL;
$TblNam = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$CusNam = (isset($_GET['cusnam'])) ? $_GET['cusnam'] : NULL;
$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$Ord_ID = (isset($_GET['ord_id']) && is_numeric($_GET['ord_id'])) ? $_GET['ord_id'] : NULL;

$TmpDel = new DelDAO();
$ArtDao = new ArtDAO();
$TmpOrd = new OrdDAO();
$SubCat = new SubDAO();
//$orders = $TmpOrd->select(NULL, $TblNam, $Tbl_ID, $Sta_ID, false);


require_once("../website/classes/pages.cls.php");
$PagDAO = new PagDAO();
$artpag = $PagDAO->select(NULL,NULL,NULL,18);
$arturl="";
if(!empty($artpag[0])){
    $artpag = $artpag[0];
    $arturl = $artpag['seourl'];
}





$articles= $ArtDao->select();
$tableLength = count($articles);
for ($i=0;$i<$tableLength;++$i) {

?>
<tr class="">
    <td>


        <a href="website/articles-edit.php?art_id=<?php echo $articles[$i]['art_id'] ?>">
            <?php echo strtotime($articles[$i]['artdat']); ?>
        </a>
    </td>
    <td>
        <a href="website/articles-edit.php?art_id=<?php echo $articles[$i]['art_id'] ?>">
            <?php echo date("jS M Y", strtotime($articles[$i]['artdat'])) ?>
        </a>
    </td>
    <td>
        <a href="website/articles-edit.php?art_id=<?php echo $articles[$i]['art_id'] ?>">
            <?php echo $articles[$i]['artttl'];?>
        </a>
    </td>
    <td>
        <a href="website/articles-edit.php?art_id=<?php echo $articles[$i]['art_id'] ?>">
        <?php
        if(!empty($articles[$i]['arttyp'])){
            $typ = $articles[$i]['arttyp'];
            $typ = preg_replace('/\s+/', '', $typ);
            $typ = str_replace("||",",",$typ);
            $typ = substr($typ,1,(strlen($typ)-2));
            $categories = $SubCat->selectByIDs($typ);
            $str="";
            foreach ($categories as $category){
                $str.= $category['subnam'].", ";
            }
            $str = substr($str,0,(strlen($str)-2));
            echo $str;
        }
        ?>
        </a>
    </td>
    <td>
        <a class="btn" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $patchworks->webRoot.$patchworks->articlesURL.$patchworks->articleURL;?><?php echo $articles[$i]['seourl'] ?>">
            <i class="icon-facebook icon"></i>
        </a>
        <a class="btn" target="_blank" href="https://twitter.com/home?status=<?php echo $patchworks->webRoot.$patchworks->articlesURL.$patchworks->articleURL;?><?php echo $articles[$i]['seourl'] ?>">
            <i class="icon-twitter icon"></i>
        </a>
        <a class="btn" target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $patchworks->webRoot.$patchworks->articlesURL.$patchworks->articleURL;?><?php echo $articles[$i][
        'seourl'] ?>
               &title=<?php echo $articles[$i]['artttl'] ?>&summary<?php echo $articles[$i]['artdsc'] ?>=&source=<?php echo $patchworks->webRoot.$patchworks->articlesURL.$patchworks->articleURL;?><?php echo $articles[$i]['seourl'] ?>">
            <i class="icon-linkedin icon"></i>
        </a>
        <a class="btn" target="_blank" href="https://pinterest.com/pin/create/button/?url=&media=<?php echo $patchworks->webRoot.$patchworks->articlesURL.$patchworks->articleURL;?><?php echo $articles[$i][
        'seourl'] ?>">
            <i class="icon-pinterest icon"></i>
        </a>
    </td>
    <td>
        <?php
        if(!empty($arturl)){
            ?>
            <a class="btn" target="_blank" href="/<?php echo $arturl."/article/".$articles[$i]['seourl'];?>">
                <i class="icon-eye-open icon"></i>
            </a>
            <?php
        }else{
            echo "Article Template Required";
        }
        ?>

    </td>
</tr>
<?php } ?>