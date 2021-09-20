<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../emailtemplates/classes/emailtemplate.cls.php");
require_once("../emailtemplates/classes/emailsections.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die();

$TmpEmt = new EmtDAO();
$TmpEms = new EmsDAO();

$Emt_ID = (isset($_GET['emt_id']) && is_numeric($_GET['emt_id'])) ? $_GET['emt_id'] : NULL;
$emailTemplateRec = NULL;
$emailSectionRec = NULL;

if (!is_null($Emt_ID) && !empty($Emt_ID)) {

    $emailTemplateRec = $TmpEmt->select($Emt_ID, null, null, null, true);

    if (isset($emailTemplateRec->emt_id)) {
        $Emt_ID = $emailTemplateRec->emt_id;
        $emailSectionRec = $TmpEms->select($Emt_ID, null, false);
    }
}

$tableLength = count($emailSectionRec);
for ($i=0;$i<$tableLength;$i++) {?>
    <tr>
        <td>
            <a href="#" class="btn btn-mini emailSectionSort" rel="tooltip" title="Drag To Reorder"><i class="icon icon-reorder"></i></a>
        </td>
        <td>
            <a href="#" data-emt_id="<?php echo $emailSectionRec[$i]['emt_id']; ?>" data-ems_id="<?php echo $emailSectionRec[$i]['ems_id']; ?>" class="editEmailSection"><?php echo $emailSectionRec[$i]['emstyp']; ?></a>
        </td>
        <td>
            <a href="#" data-emt_id="<?php echo $emailSectionRec[$i]['emt_id']; ?>" data-ems_id="<?php echo $emailSectionRec[$i]['ems_id']; ?>" class="btn btn-mini btn-danger delEmailSection" rel="tooltip" title="Delete"><i class="icon icon-trash"></i></a>
        </td>
    </tr>
<?php } ?>