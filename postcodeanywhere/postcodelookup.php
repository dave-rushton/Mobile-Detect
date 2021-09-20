<?php



//error_reporting(0);



//Include Library

include('postcodeanywhere.php');



if (isset($_GET['pstcod'])) {



	//Set Licence and Account Code

	$oPostcode = new interactiveFindByPostcode();

	$oPostcode->setLicenceKey('tT6J-A924-cMcs-O7jC');

	$oPostcode->setAccountCode('INDIV75116');



	//Set Language (Not needed, english is the default)

	$oPostcode->setLanguage('English');



	//Set the company were looking for and address

	$oPostcode->setPostcode($_GET['pstcod']);



	if (!$oPostcode->run()) {

		//Ensure there isn't any errors

		var_dump($oPostcode->sErrorMessage);

	} else {

		//Output results

		//var_dump($oPostcode->aData);



		die(json_encode($oPostcode->aData));



	}



}



if (isset($_GET['postcodeid']) && is_numeric($_GET['postcodeid'])) {



	//Set Licence and Account Code

	$oPostcode = new interactiveRetrieveByID();

	$oPostcode->setLicenceKey('YB63-FP53-PZ11-KD49');

	$oPostcode->setAccountCode('INDIV75116');



	//Set Language (Not needed, english is the default)

	$oPostcode->setLanguage('English');



	//Set the company were looking for and address

	$oPostcode->setAddressID( (int)$_GET['postcodeid'] );





	if (!$oPostcode->run()) {

		//Ensure there isn't any errors

		var_dump($oPostcode->sErrorMessage);

	} else {

		//Output results

		//var_dump($oPostcode->aData);



		die(json_encode($oPostcode->aData));



	}



}



?>

