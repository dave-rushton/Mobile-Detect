<?php

require_once("../../../config/config.php");
require_once("../../../admin/patchworks.php");
require_once("../../../admin/website/classes/pageelements.cls.php");
require_once("../../../admin/system/classes/categories.cls.php");
require_once("../../../admin/system/classes/subcategories.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$ClsNam = $EleDao->getVariable($EleObj, 'clsnam' );

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$TblNam = 'article-types';

$TmpCat = new CatDAO();
$categoryRec = $TmpCat->select(NULL,$TblNam,NULL,NULL,true);

$TmpSub = new SubDAO();
$subCategories = $TmpSub->selectByTableName($TblNam);

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <ul class="<?php echo $ClsNam; ?>">

                <?php

                $tableLength = count($subCategories);
                for ($i=0;$i<$tableLength;++$i) {
                    echo '<li><a href="', $SeoUrl,'/categories/'.$subCategories[$i]['seourl'].'">',$subCategories[$i]['subnam'],'</a></li>';
                }
                ?>
                </ul>

            </div>
        </div>
    </div>
</div>