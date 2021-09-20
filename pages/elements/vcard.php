<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php" );

require_once('classes/vcard.cls.php');

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();



$FstNam = $EleDao->getVariable($EleObj, 'fstnam', false);
$SurNam = $EleDao->getVariable($EleObj, 'surnam', false);
$CrdMob = $EleDao->getVariable($EleObj, 'crdmob', false);
$CrdTel = $EleDao->getVariable($EleObj, 'crdtel', false);
$CrdEma = $EleDao->getVariable($EleObj, 'crdema', false);
$CrdUrl = $EleDao->getVariable($EleObj, 'crdurl', false);
$ComNam = $EleDao->getVariable($EleObj, 'comnam', false);
$PosNam = $EleDao->getVariable($EleObj, 'posnam', false);

$WrkAdr1 = $EleDao->getVariable($EleObj, 'wrkadr1', false);
$WrkAdr2 = $EleDao->getVariable($EleObj, 'wrkadr2', false);
$WrkAdr3 = $EleDao->getVariable($EleObj, 'wrkadr3', false);
$WrkAdr4 = $EleDao->getVariable($EleObj, 'wrkadr4', false);
$WrkPstCod = $EleDao->getVariable($EleObj, 'wrkpstcod', false);

$HomAdr1 = $EleDao->getVariable($EleObj, 'homadr1', false);
$HomAdr2 = $EleDao->getVariable($EleObj, 'homadr2', false);
$HomAdr3 = $EleDao->getVariable($EleObj, 'homadr3', false);
$HomAdr4 = $EleDao->getVariable($EleObj, 'homadr4', false);
$HomPstCod = $EleDao->getVariable($EleObj, 'hompstcod', false);


$vc = new vcard();

#$vc->filename = "";
#$vc->revision_date = "";
#$vc->class = "PUBLIC";
#$vc->data['display_name'] = "";
$vc->data['first_name'] = $FstNam;
$vc->data['last_name'] = $SurNam;
#$vc->data['additional_name'] = ""; //Middle name
#$vc->data['name_prefix'] = "";  //Mr. Mrs. Dr.
#$vc->data['name_suffix'] = ""; //DDS, MD, III, other designations.
#$vc->data['nickname'] = "";

$vc->data['company'] = $ComNam;
#$vc->data['department'] = "";
$vc->data['title'] = $PosNam;
#$vc->data['role'] = "";

#$vc->data['work_po_box'] = "";
#$vc->data['work_extended_address'] = "";
$vc->data['work_address'] = $WrkAdr1;
$vc->data['work_city'] = $WrkAdr2;
$vc->data['work_state'] = $WrkAdr3;
$vc->data['work_postal_code'] = $WrkPstCod;
$vc->data['work_country'] = $WrkAdr4;

#$vc->data['home_po_box'] = "";
#$vc->data['home_extended_address'] = "";
$vc->data['home_address'] = $HomAdr1;
$vc->data['home_city'] = $HomAdr2;
$vc->data['home_state'] = $HomAdr3;
$vc->data['home_postal_code'] = $HomPstCod;
$vc->data['home_country'] = $HomAdr4;

$vc->data['office_tel'] = $CrdTel;
#$vc->data['home_tel'] = "";
$vc->data['cell_tel'] = $CrdMob;
$vc->data['fax_tel'] = "";
#$vc->data['pager_tel'] = "";

$vc->data['email1'] = $CrdEma;
#$vc->data['email2'] = "";

$vc->data['url'] = $CrdUrl;

#$vc->data['photo'] = "";  //Enter a URL.
#$vc->data['birthday'] = "1979-01-21";
#$vc->data['timezone'] = "00:00";

#$vc->data['sort_string'] = "";
#$vc->data['note'] = "Troy is an amazing guy!";

$vc->download();

?>