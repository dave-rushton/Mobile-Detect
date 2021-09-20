


require_once('../../config/config.php');

require_once('../../admin/patchworks.php');

require_once("../../admin/website/classes/pageelements.cls.php");

require_once("../../admin/website/classes/page.handler.php");

require_once("../../admin/products/classes/product_types.cls.php");

require_once("../../admin/products/classes/products.cls.php");

require_once("../../admin/products/classes/structure.cls.php");

require_once("../../admin/gallery/classes/uploads.cls.php");

require_once("../../admin/system/classes/related.cls.php");

require_once("../../admin/attributes/classes/attrgroups.cls.php");

require_once("../../admin/attributes/classes/attrlabels.cls.php");

require_once("../../admin/attributes/classes/attrvalues.cls.php");

require_once("../../admin/ecommerce/classes/ecommprop.cls.php");

    $EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;

$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);

if (!$EleObj) die();

$ImgUrl = NULL;

$ImgUrl = $EleDao->getVariable($EleObj, 'imgurl' );

    $Str_ID = $EleDao->getVariable($EleObj, 'str_id' );

    $nopadding = $EleDao->getVariable($EleObj, 'nopadding', false);

    $nomargin = $EleDao->getVariable($EleObj, 'nomargin', false);

    $extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);

    $extramargin = $EleDao->getVariable($EleObj, 'extramargin', false);



    $TmpPrd = new PrdDAO();

    $TmpPrt = new PrtDAO();

    $TmpStr = new StrDAO();

    $TmpEco = new EcoDAO();



    $displayCurrency = '&pound;';

    $Str_ID = !empty($Str_ID)?$Str_ID:0;

    $TmpPrt = new PrtDAO();

    $TmpUpl = new UplDAO();



    $PerPag = 8;



?>
<div class="section products-section <?php echo $nopadding." ".$nomargin." ".$extrapadding." ".$extramargin;?>">

    <?php

        $homePage = $TmpStr->selectLevel($Str_ID, NULL, NULL, false);

    ?>

    <div class="container">

        <?php

        if(count($homePage)>$PerPag){

        ?>

        <div class="flexslider" data-type="desktop" data-sldsel="on">

            <ul class="slides">

                <li>



        <?php

        }

        ?>   <div class="row">



                        <?php



                            for ($i = 0; $i < count($homePage); $i++) {





                                 if($i%$PerPag==0 && $i>0){

                                    ?>

                                        </div>

                                       </li>

                <li>

                    <div class="row">

                                    <?php

                                    }





                                $uploads = $TmpUpl->select(NULL, 'STRUCTURE', $homePage[$i]['str_id'], NULL, false);

                                $className = 'noimg';

                                $fileName = '/pages/img/holding-253-370.jpg';

                                $fileName1 = '/pages/img/holding-700-500.jpg';

                                if (isset($uploads) && isset($uploads[0])) {

                                    $fileName = $patchworks->webRoot . 'uploads/images/products/253-370/' . $uploads[0]['filnam'];

                                    $fileName1 = $patchworks->webRoot . 'uploads/images/products/700-500/' . $uploads[0]['filnam'];

                                    $className = '';

                                }



                                $description = $patchworks->getJSONVariable($homePage[$i]['strobj'], 'strdsc', false);



                                ?>

                                <div class="col-sm-6 col-lg-3">

                                    <div class="product">

                                        <a href="<?php echo $_GET['seourl'] . '/category/' . $homePage[$i]['str_id'] . '/' . $homePage[$i]['seourl']; ?>" class="image">

                                            <div class="holder">

                                                <picture>

                                                    <source media="(max-width: 1199px)" srcset="<?php echo $fileName1;?>">

                                                    <img src="<?php echo $fileName; ?>" alt="">

                                                </picture>

                                            </div>

                                        </a>

                                        <div class="<?php echo $className; ?>">

                                            <div class="product-details <?php echo $className; ?>">

                                                <a href="<?php echo $_GET['seourl'] . '/category/' . $homePage[$i]['str_id'] . '/' . $homePage[$i]['seourl']; ?>">

                                                    <h3>

                                                        <?php echo $homePage[$i]['strnam']; ?>

                                                    </h3>

                                                    <span class="button">More Info</span>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <?php



                            }

                        ?>

                        <?php

                            if(count($homePage)>$PerPag){

                        ?>

                    </div>

                </li>

            </ul>



                                        <?php

                                            }

                                        ?>

        </div>

    </div>

</div>

