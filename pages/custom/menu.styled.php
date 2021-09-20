<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/website/classes/page.handler.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$Par_ID = $EleDao->getVariable($EleObj, 'par_id', false);

$pageHold = new pageHandler();
$pageRec = $pageHold->findPageBySEO($Par_ID);

$pages = $pageHold->getChildren($pageRec->id);
$pageCount = count($pages);

?>
<div class="section">
    <div class="container">
        <div class="row">

            <?php

            $alt = '';
            for ($i=0;$i<$pageCount;$i++) {

                if ($i % 2 == 0) {
                    $alt = ($alt == ' alt') ? '' : ' alt';
                }

                $pageImage = (!empty($pages[$i]['pagimg'])) ? $pages[$i]['pagimg'] : 'pages/img/noimg.png';

                ?>

                <div class="col-xs-6 col-sm-4">

                    <div class="linkbtn">

                        <a href="<?php echo $pages[$i]['seourl']; ?>" class="image" style="background-image: url('<?php echo $pageImage; ?>');">

                            <?php
                            if ($i % 2 == 0) {
                                ?>
                                <div class="topfade"></div>
                                <?php
                            } else {
                                ?>
                                <div class="bottomfade"></div>
                            <?php
                            }
                            ?>

                        </a>

                        <div class="contentwrapper">
                        <div class="content">

                            <h3><?php echo $pages[$i]['title']; ?></h3>

                            <?php
                            $pageText = $patchworks->getJSONVariable($pages[$i]['pagobj'], 'pagtxt', true);
                            ?>

                            <p>
                                <?php echo $pageText; ?>
                            </p>

                        </div>
                        </div>

                    </div>

                </div>

                <?php
            }
            ?>

        </div>
    </div>
</div>