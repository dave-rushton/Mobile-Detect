<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/system/classes/people.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$SecCls = 'green';
$SecCls = $EleDao->getVariable($EleObj, 'seccls', false);
$Sec_ID = $EleDao->getVariable($EleObj, 'sec_id', false);
$EmpTyp = $EleDao->getVariable($EleObj, 'emptyp', false);


$TmpPpl = new PplDAO();
$employee = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false);

?>

<div class="section">
    <div class="container">
        <div class="row">

            <?php
            $tableLength = count($employee);
            for ($i=0;$i<$tableLength;++$i) {

                if (!empty($EmpTyp) && $EmpTyp != $employee[$i]['pplref']) continue;

            ?>
            <div class="col-xs-12 col-sm-3">
                <div class="teammember">

                    <?php

                    $imageFile = 'pages/img/no_user_profile-pic.jpg';
                    if (
                        !empty($employee[$i]['pplimg']) &&
                        file_exists($patchworks->docRoot . 'uploads/images/employees/' . $employee[$i]['pplimg']) &&
                        !is_dir    ($patchworks->docRoot . 'uploads/images/employees/' . $employee[$i]['pplimg'])
                    ) {
                        $imageFile = 'uploads/images/employees/' . $employee[$i]['pplimg'];
                    }
                    ?>

                    <div class="imagewrapper">
                        <div class="imageholder" style="background-image: url(<?php echo $imageFile; ?>)">

                        </div>
                    </div>

                    <div class="position"><?php echo $employee[$i]['pplref'] ?></div>
                    <div class="name"><?php echo $employee[$i]['pplnam'] ?></div>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>
</div>
