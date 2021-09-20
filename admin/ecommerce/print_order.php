<?php

//error_reporting(0);

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../ecommerce/classes/order.cls.php");

$Ord_ID = (isset($_GET['ord_id']) && is_numeric($_GET['ord_id'])) ? $_GET['ord_id'] : die();
$Output = (isset($_GET['output'])) ? $_GET['output'] : 'SCREEN';

$html =  file_get_contents( $patchworks->pwRoot.'ecommerce/order_rpt.php?ord_id='.$Ord_ID ); //http://localhost/patchworkscms/admin/pdf.php'); 


require_once("../dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();

$output = $dompdf->output();

if ($output == 'FILE') {
	file_put_contents('../pdfs/INVOICE'. str_pad($Ord_ID, 8, "0", STR_PAD_LEFT) . '.pdf', $output);
} else {
	$dompdf->stream('INVOICE'. str_pad($Ord_ID, 8, "0", STR_PAD_LEFT) . '.pdf', array( 'compress' => '0', 'Attachment' => '0' ));
}
?>