<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once("../../../admin/website/classes/pageelements.cls.php");
require_once("../../../admin/system/classes/people.cls.php");

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

                <div class="col-xs-12 col-sm-6 col-md-4">

                    <div class="employeebox">

                        <div class="imagewrapper">
                            <div class="image" style="background-image: url('<?php echo $imageFile; ?>')">
                            </div>
                        </div>

                        <h2><?php echo $employee[$i]['pplnam'] ?></h2>
                        <h3><?php echo $employee[$i]['pplref'] ?></h3>
                        <div class="empbio"><?php echo $empBio; ?></div>


                    </div>

                </div>

                <?php
            }
            ?>

        </div>
    </div>
</div>


<div class="section circlebg">
    <div class="container">

        <?php
        $tableLength = count($employee);
        for ($i=0;$i<$tableLength;++$i) {

            $GrdLst = $patchworks->getJSONVariable($employee[$i]['ppltxt'], 'grdlst', true);

            if (empty($GrdLst) || $GrdLst == 'G') continue;


                ?>

                <div class="row">
                    <div class="col-sm-3">

                        <?php

                        if (
                            !empty($employee[$i]['pplimg']) &&
                            file_exists($patchworks->docRoot . 'uploads/images/employees/' . $employee[$i]['pplimg']) &&
                            !is_dir    ($patchworks->docRoot . 'uploads/images/employees/' . $employee[$i]['pplimg'])
                        ) {
                            $imageFile = 'uploads/images/employees/' . $employee[$i]['pplimg'];
                        } else {
                            $imageFile = 'pages/img/no_user_profile-pic.jpg';
                        }

                        $contentImg = $patchworks->getJSONVariable($employee[$i]['ppltxt'], 'oldimg', true);

                        if (
                            !empty($contentImg) &&
                            file_exists($patchworks->docRoot . 'uploads/images/employees/' . $contentImg) &&
                            !is_dir    ($patchworks->docRoot . 'uploads/images/employees/' . $contentImg)
                        ) {
                            $imageFile2 = 'uploads/images/employees/' . $contentImg;
                        } else {
                            $imageFile2 = 'pages/img/no_user_profile-pic.jpg';
                        }

                        ?>


                        <div class="employeeimage">
                            <img src="<?php echo $imageFile; ?>" alt="<?php echo $employee[$i]['pplnam'] ?>">

                            <div class="imagecover" style="background-image: url('<?php echo $imageFile2; ?>')"></div>

                        </div>

                    </div>
                    <div class="col-sm-9">

                        <div class="employeedetails">

                            <h3>
                                <?php echo $employee[$i]['pplnam'] ?>
                            </h3>
                            <h5><?php echo $employee[$i]['pplref'] ?></h5>

                            <p>
                                <?php
                                $cusFld = $patchworks->getJSONVariable($employee[$i]['ppltxt'], 'empbio', false);
                                if (!empty($cusFld)) echo $cusFld;
                                ?>
                            </p>

                        </div>

                    </div>
                </div>


            <hr class="coloured">

        <?php } ?>

    </div>
</div>
