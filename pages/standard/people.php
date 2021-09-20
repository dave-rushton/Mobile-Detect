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
        <div class="staff-overlay">
            <div class="inner">
                <div class="close">&times;</div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="staff-wrapper"></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="name-wrapper"></div>
                        <div class="role-wrapper"></div>
                        <div class="description-wrapper"></div>
                    </div>
                </div>
            </div>
        </div>
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
                $description = $patchworks->getJSONVariable($employee[$i]['ppltxt'],'description',true);
                ?>
                <li  data-bio="<?php echo $description;?>">
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
                                <?php
                                    if(!empty( $employee[$i]['pplnam'])){
                                        echo "<div class='name-wrapper'>";
                                            echo '<h2>' . $employee[$i]['pplnam'] . '</h2>';
                                        echo "</div>";
                                    }

                                    $role = $patchworks->getJSONVariable($employee[$i]['ppltxt'],'role',true);
                                    if(!empty($role)){
                                        echo "<div class='role-wrapper'>";
                                            echo '<p>' . $role . '</p>';
                                        echo "</div>";
                                    }

                                    $year = $patchworks->getJSONVariable($employee[$i]['ppltxt'],'year',true);
                                    if(!empty($year)){
                                        echo "<div class='year-wrapper'>";
                                            echo '<p>' . $year . '</p>';
                                        echo "</div>";
                                    }
//                                    $description = $patchworks->getJSONVariable($employee[$i]['ppltxt'],'description',true);
//                                    if(!empty($description)){
//                                        echo "<div class='description-wrapper'>";
//                                            echo '<p>' . $description . '</p>';
//                                        echo "</div>";
//                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>