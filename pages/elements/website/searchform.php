<?php
require_once("../../../config/config.php");
require_once("../../../admin/patchworks.php");
require_once("../../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl' );

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

<form method="get" action="<?php echo $FwdUrl; ?>" class="pwSearchForm">
	<div class="">
		<div class="input-group">
			<input type="text" class="form-control" name="keyword" placeholder="Enter Search Term">
			<span class="input-group-btn">
			<button class="btn btn-default" type="submit" value="GO"><i class="fa fa-search"></i></button>
			</span>
		</div>
	</div>
</form>

            </div>
        </div>
    </div>
</div>