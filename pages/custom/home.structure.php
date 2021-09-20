<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/products/classes/structure.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$TmpStr = new StrDAO();
$TmpUpl = new UplDAO();


$StrSeo = $EleDao->getVariable($EleObj, 'str_id');
$structureRec = $TmpStr->selectBySeo($StrSeo, NULL, NULL, true);
$Str_ID = (empty($structureRec)) ? 0 : $structureRec->str_id;
if (empty($StrSeo)) $Str_ID = 0;

$FwdUrl = 'products';

?>

<div class="section sectionfade">

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>Products</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">

                <div class="row">

                    <div class="homePageCategories">
                        <?php
                        //echo $TmpStr->buildStructure2($Str_ID, $_GET['seourl']);
                        $homePage = $TmpStr->selectLevel($Str_ID, NULL, NULL, false, [0, 2, 3, 4]);

                        for ($i = 0; $i < count($homePage); $i++) {

                            $uploads = $TmpUpl->select(NULL, 'STRUCTURE', $homePage[$i]['str_id'], NULL, false);

                            $className = 'noimg';
                            $fileName = 'pages/img/noimg.png';
                            if (isset($uploads) && isset($uploads[0])) {
                                $fileName = $patchworks->webRoot . 'uploads/images/products/' . $uploads[0]['filnam'];
                                $className = '';
                            }

                            $description = $patchworks->getJSONVariable($homePage[$i]['strobj'], 'strdsc', false);

                            if (in_array($homePage[$i]['sta_id'], [0, 2])) {
                                $link = $FwdUrl . '/category/' . $homePage[$i]['str_id'] . '/' . $homePage[$i]['seourl'];
                            } else {
                                $link = '#';
                            }
                            ?>
                            <div class="col-sm-3 col-xs-6">

                                <?php if ($link != '#') { ?>

                                <a href="<?php echo $link; ?>"
                                   class="linkbutton">

                                    <?php }

                                    //Replace with div on 26/11/2018 to stop user from being able to click on them
                                    else { ?>
                                        <div class="linkbutton">
                                    <?php }

                                    ?>
                                    <span class="imagewrapper">
                                        <span class="image"
                                              style="background-image: url('<?php echo $fileName; ?>');"></span>

                                        <?php

                                        if (!empty($description)) { ?>
                                            <span class="overlay">
                                                <span class="text-wrapper">
                                                    <?php echo $description; ?>
                                                </span>
                                            </span>
                                        <?php }
                                        ?>
                                    </span>
                                    <span class="title">
                                        <?php echo $homePage[$i]['strnam']; ?>
                                    </span>

                                    <?php if ($link != '#'){ ?>
                                </a>
                            <?php } else { ?>
                                </div>
                            <?php } ?>

                            </div>
                            <?php

                        }

                        ?>

                    </div>
                </div>

            </div>


        </div>
    </div>

</div>