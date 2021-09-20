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


if($nomargin == 1){
    $nomargin = "nomargin";
}
if($nopadding == 1){
    $nopadding = "nopadding";
}
if($extramargin == 1){
    $extramargin = "extramargin";
}
if($extrapadding == 1){
    $extrapadding = "extrapadding";
}

$classname = $nomargin." ".$nopadding." ".$extramargin." ".$extrapadding;
$TmpPpl = new PplDAO();
$employee = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false);

?>
<div class="section <?php echo $classname;?>">
    <div class="staff-area ">
        <ul>
            <?php

            $tableLength = count($employee);
            for ($i=0;$i<$tableLength;++$i) {

                $imageFile = 'pages/img/no_user_profile-pic.jpg';

                if (
                    !empty($employee[$i]['pplimg']) &&
                    file_exists($patchworks->docRoot . 'uploads/images/employees/' . $employee[$i]['pplimg']) &&
                    !is_dir    ($patchworks->docRoot . 'uploads/images/employees/' . $employee[$i]['pplimg'])
                ) {
                    $imageFile = 'uploads/images/employees/' . $employee[$i]['pplimg'];
                }

                $empBio = $patchworks->getJSONVariable($employee[$i]['ppltxt'], 'empbio', false);

                ?>
                <li>
                    <?php
                    $image = $patchworks->getJSONVariable($employee[$i]['ppltxt'],'imgurl',true);
                    $imagesize = $patchworks->getJSONVariable($employee[$i]['ppltxt'],'imgsiz',true);
                    $image = str_replace("images/","images/".$imagesize."/",$image);
                    ?>
                    <div class="staff-wrapper">
                        <img src=" <?php echo $image; ?>" alt="<?php echo $employee[$i]['pplnam'] ;?>">
                    </div>
                    <div class="text-wrapper">
                        <div class="table">
                            <div class="cell">
                                <h2><?php echo $employee[$i]['pplnam'] ;?></h2>
                                <h3><?php echo $patchworks->getJSONVariable($employee[$i]['ppltxt'],'year',true);?></h3>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
        echo $employee;
        ?>
    </div>
</div>