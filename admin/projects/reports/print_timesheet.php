<?php

require_once('../../../config/config.php');
require_once('../../patchworks.php'); 

$Cus_ID = (isset($_GET['cus_id']) && is_numeric($_GET['cus_id'])) ? $_GET['cus_id'] : NULL;
$BegDat = (isset($_GET['begdat'])) ? $_GET['begdat'] : NULL;
$EndDat = (isset($_GET['enddat'])) ? $_GET['enddat'] : NULL;
$Output = (isset($_GET['output'])) ? $_GET['output'] : 'SCREEN';

echo $patchworks->pwRoot.'projects/reports/timesheet.php?cus_id='.$Cus_ID.'&begdat='.$BegDat.'&enddat='.$EndDat.'<br>';

$html =  file_get_contents( $patchworks->pwRoot.'projects/reports/timesheet.php?cus_id='.$Cus_ID.'&begdat='.$BegDat.'&enddat='.$EndDat ); //http://localhost/patchworkscms/admin/pdf.php');

print_r($html);

//error_reporting(0);
require_once("../../dompdf/dompdf_config.inc.php");

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
echo '#';
$output = $dompdf->output();

if ($output == 'FILE') {
	file_put_contents('../../pdfs/INVOICE'. str_pad($Ord_ID, 8, "0", STR_PAD_LEFT) . '.pdf', $output);
} else {
	$dompdf->stream('TIMESHEET_'. str_pad($Ord_ID, 8, "0", STR_PAD_LEFT) . '.pdf', array( 'compress' => '0', 'Attachment' => '0' ));
}
?>