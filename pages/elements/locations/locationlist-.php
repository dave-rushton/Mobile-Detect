<?php



require_once('../../../config/config.php');

require_once('../../../admin/patchworks.php');

require_once("../../../admin/website/classes/pageelements.cls.php");

require_once("../../../admin/system/classes/places.cls.php");



$EleDao = new PelDAO();



$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;

$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);

if (!$EleObj) die();



$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;



$TmpPla = new PlaDAO();

$locations = $TmpPla->select(NULL, 'LOCATION', NULL, NULL, NULL, false);

?>





<div class="section">

    <div class="container">

        <div class="row">

            <div class="col-sm-12">



                <?php

                $tableLength = count($locations);

                for ($i = 0; $i < $tableLength; ++$i) {



                    ?>



                    <div class="row" style="margin-bottom: 30px;">

                        <div class="col-md-7">



                            <h2><?php echo $locations[$i]['planam']; ?></h2>



                            <p>

                                <?php echo ($locations[$i]['adr1'] != '') ? $locations[$i]['adr1'] . '<br>' : ''; ?>

                                <?php echo ($locations[$i]['adr2'] != '') ? $locations[$i]['adr2'] . '<br>' : ''; ?>

                                <?php echo ($locations[$i]['adr3'] != '') ? $locations[$i]['adr3'] . '<br>' : ''; ?>

                                <?php echo ($locations[$i]['adr4'] != '') ? $locations[$i]['adr4'] . '<br>' : ''; ?>

                                <?php echo ($locations[$i]['pstcod'] != '') ? $locations[$i]['pstcod'] : ''; ?>

                            </p>



                            <p>

                                <?php echo ($locations[$i]['platel'] != '') ? '<i class="fa fa-phone" style="width: 20px;"></i> ' . $locations[$i]['platel'] . '<br>' : ''; ?>

                                <?php echo ($locations[$i]['plamob'] != '') ? '<i class="fa fa-fax" style="width: 20px;"></i> ' . $locations[$i]['plamob'] . '<br>' : ''; ?>

                                <?php echo ($locations[$i]['plaema'] != '') ? '<i class="fa fa-envelope" style="width: 20px;"></i> <a href="mailto:' . $locations[$i]['plaema'] . '">' . $locations[$i]['plaema'] . '</a>' : ''; ?>

                            </p>



                        </div>



                        <div class="col-md-5">



                            <div id="map_canvas_<?php echo $i; ?>" class="mapCanvas" style="width: 100%; height: 300px;"

                                 data-goolat="<?php echo $locations[$i]['goolat']; ?>"

                                 data-goolng="<?php echo $locations[$i]['goolng']; ?>"></div>



                        </div>

                    </div>



                <?php } ?>



            </div>

        </div>

    </div>

</div>

