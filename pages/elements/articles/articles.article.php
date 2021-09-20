<?php

$ArtSeo = (isset($_GET['artseo'])) ? $_GET['artseo'] : NULL;

$articleRec = $TmpArt->select(NULL, $ArtSeo, NULL, NULL, true);
$uploads = $TmpUpl->select(NULL, 'ARTICLE', $articleRec->art_id, NULL, false);

$imgUrl = 'pages/img/noimg.png';

if (
    file_exists($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam']) &&
    !is_dir($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam'])
) {
    $imgUrl = $patchworks->webRoot.'uploads/images/'.$uploads[0]['filnam'];
}

?>

<div class="newsDetail">

    <div class="imageheader" style="background-image: url('<?php echo $imgUrl; ?>')">
        <h1><?php echo $articleRec->artttl; ?></h1>
    </div>

    <p>
        <a href="<?php echo $_GET['seourl']; ?>">Back to articles</a>
    </p>


    <h3><i class="icon-calendar"></i><?php echo date("jS M Y", strtotime($articleRec->artdat)); ?></h3>

    <div class="row">
        <div class="col-sm-8">
            <h2><?php $articleRec->artdsc; ?></h2>
            <div class="articleText"><?php echo $articleRec->arttxt; ?></div>
        </div>
        <div class="col-sm-4">

            <div class="socialshare">

                <p>Share this item</p>
                <ul>
                    <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $patchworks->webRoot.$_GET['seourl'].'/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-facebook"></i> </a></li>
                    <li><a href="https://twitter.com/home?status=<?php echo $patchworks->webRoot.$_GET['seourl'].'/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-twitter"></i> </a></li>
                    <li><a href="https://plus.google.com/share?url=<?php echo $patchworks->webRoot.$_GET['seourl'].'/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-google-plus"></i> </a></li>
                    <li><a href="https://pinterest.com/pin/create/button/?url=&media=<?php echo $patchworks->webRoot.$_GET['seourl'].'/article/'.$articleRec->seourl;?>" target="_blank"> <i class="fa fa-pinterest"></i> </a></li>
                </ul>
            </div>

        </div>
    </div>

</div>