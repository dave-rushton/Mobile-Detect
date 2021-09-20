<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/product_types.cls.php");
require_once("classes/products.cls.php");
require_once("../system/classes/attrgroups.cls.php");

function seoUrl($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);

    $string = str_replace('+','plus',$string);

    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

if ($loggedIn == 0) {

    //header('location: ../login.php');

    $throwJSON['title'] = 'Authorisation';
    $throwJSON['description'] = 'You are not authorised for this action';
    $throwJSON['type'] = 'error';
}


$Prt_ID = (isset($_REQUEST['prt_id']) && is_numeric($_REQUEST['prt_id'])) ? $_REQUEST['prt_id'] : die('FAIL');

if (is_null($Prt_ID)) {
    $throwJSON['title'] = 'Invalid Product Type';
    $throwJSON['description'] = 'Product not found';
    $throwJSON['type'] = 'error';
}

$PrtDao = new PrtDAO();
$PrdDao = new PrdDAO();



if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

    $PrtObj = $PrtDao->select($Prt_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
//    die ("sssssss");
    if (!$PrtObj) {

        $PrtObj = new stdClass();

        $PrtObj->prt_id = 0;
        $PrtObj->tblnam = '';
        $PrtObj->tbl_id = 0;
        $PrtObj->prtnam = '';
        $PrtObj->prtdsc = '';
        $PrtObj->prtspc = '';
        $PrtObj->unipri = 0;
        $PrtObj->buypri = 0;
        $PrtObj->delpri = 0;
        $PrtObj->atr_id = 0;
        $PrtObj->sta_id = 0;

        $PrtObj->seourl = '';
        $PrtObj->seokey = '';
        $PrtObj->seodsc = '';

        $PrtObj->usestk = 0;
        $PrtObj->hompag = 0;
        $PrtObj->prttag = '';
        $PrtObj->prtobj = '';
        $PrtObj->vat_id = 0;
        $PrtObj->done = '';

        $PrtObj->operation= '';
        $PrtObj->feature_one= '';
        $PrtObj->feature_two= '';
        $PrtObj->machine_accessory = '';
        $PrtObj->machine_subcategory = '';
        $PrtObj->machine_title = '';
        $PrtObj->manufacturer = '';
        $PrtObj->machine_code = '';
        $PrtObj->machine_type = '';
        $PrtObj->operation_type = '';
        $PrtObj->material_type = '';
        $PrtObj->materials = '';
        $PrtObj->blade_size = '';
        $PrtObj->blade_speed = '';
        $PrtObj->dimensions_speed = '';
        $PrtObj->power_supply = '';
        $PrtObj->filters = '';
        $PrtObj->done = '';





        if (isset($_REQUEST['tblnam'])) $PrtObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PrtObj->tbl_id = $_REQUEST['tbl_id'];
        if (isset($_REQUEST['prt_id'])) $PrtObj->prt_id = $_REQUEST['prt_id'];
        if (isset($_REQUEST['prtnam'])) $PrtObj->prtnam = $_REQUEST['prtnam'];
        if (isset($_REQUEST['prtdsc'])) $PrtObj->prtdsc = $_REQUEST['prtdsc'];
        if (isset($_REQUEST['prtspc'])) $PrtObj->prtspc = $_REQUEST['prtspc'];
        if (isset($_REQUEST['unipri'])) $PrtObj->unipri = $_REQUEST['unipri'];
        if (isset($_REQUEST['buypri'])) $PrtObj->buypri = $_REQUEST['buypri'];
        if (isset($_REQUEST['delpri'])) $PrtObj->delpri = $_REQUEST['delpri'];
        if (isset($_REQUEST['atr_id'])) $PrtObj->atr_id = $_REQUEST['atr_id'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PrtObj->sta_id = $_REQUEST['sta_id'];

        if (isset($_REQUEST['seourl'])) $PrtObj->seourl = $_REQUEST['seourl'];
        if (isset($_REQUEST['seokey'])) $PrtObj->seokey = $_REQUEST['seokey'];
        if (isset($_REQUEST['seodsc'])) $PrtObj->seodsc = $_REQUEST['seodsc'];

        if (isset($_REQUEST['usestk']) && is_numeric($_REQUEST['usestk'])) $PrtObj->usestk = $_REQUEST['usestk'];
        if (isset($_REQUEST['hompag']) && is_numeric($_REQUEST['hompag'])) $PrtObj->hompag = $_REQUEST['hompag'];

        if (isset($_REQUEST['prttag'])) $PrtObj->prttag = $_REQUEST['prttag'];
        if (isset($_REQUEST['prtobj'])) $PrtObj->prtobj = $_REQUEST['prtobj'];


        if (isset($_REQUEST['vat_id']) && is_numeric($_REQUEST['vat_id'])) $PrtObj->vat_id = $_REQUEST['vat_id'];
        if (isset($_REQUEST['done']) && is_numeric($_REQUEST['done'])) $PrtObj->done = $_REQUEST['done'];

        if (isset($_REQUEST['capacity_round_90'])) $PrtObj->capacity_round_90 = $_REQUEST['capacity_round_90'];
        if (isset($_REQUEST['capacity_round_45_left'])) $PrtObj->capacity_round_45_left = $_REQUEST['capacity_round_45_left'];
        if (isset($_REQUEST['capacity_round_45_right'])) $PrtObj->capacity_round_45_right = $_REQUEST['capacity_round_45_right'];
        if (isset($_REQUEST['capacity_round_60_left'])) $PrtObj->capacity_round_60_left = $_REQUEST['capacity_round_60_left'];
        if (isset($_REQUEST['capacity_round_60_right'])) $PrtObj->capacity_round_60_right = $_REQUEST['capacity_round_60_right'];
        if (isset($_REQUEST['capacity_rec_horizontal_90'])) $PrtObj->capacity_rec_horizontal_90 = $_REQUEST['capacity_rec_horizontal_90'];
        if (isset($_REQUEST['capacity_rec_horizontal_45_left'])) $PrtObj->capacity_rec_horizontal_45_left = $_REQUEST['capacity_rec_horizontal_45_left'];
        if (isset($_REQUEST['capacity_rec_horizontal_45_right'])) $PrtObj->capacity_rec_horizontal_45_right = $_REQUEST['capacity_rec_horizontal_45_right'];
        if (isset($_REQUEST['capacity_rec_horizontal_60_left'])) $PrtObj->capacity_rec_horizontal_60_left = $_REQUEST['capacity_rec_horizontal_60_left'];
        if (isset($_REQUEST['capacity_rec_horizontal_60_right'])) $PrtObj->capacity_rec_horizontal_60_right = $_REQUEST['capacity_rec_horizontal_60_right'];
        if (isset($_REQUEST['capacity_rec_vertical_90'])) $PrtObj->capacity_rec_vertical_90 = $_REQUEST['capacity_rec_vertical_90'];
        if (isset($_REQUEST['capacity_rec_vertical_45_left'])) $PrtObj->capacity_rec_vertical_45_left = $_REQUEST['capacity_rec_vertical_45_left'];
        if (isset($_REQUEST['capacity_rec_vertical_45_right'])) $PrtObj->capacity_rec_vertical_45_right = $_REQUEST['capacity_rec_vertical_45_right'];
        if (isset($_REQUEST['capacity_rec_vertical_60_left'])) $PrtObj->capacity_rec_vertical_60_left = $_REQUEST['capacity_rec_vertical_60_left'];
        if (isset($_REQUEST['capacity_rec_vertical_60_right'])) $PrtObj->capacity_rec_vertical_60_right = $_REQUEST['capacity_rec_vertical_60_right'];
        if (isset($_REQUEST['capacity_rec_square_90'])) $PrtObj->capacity_rec_square_90 = $_REQUEST['capacity_rec_square_90'];
        if (isset($_REQUEST['capacity_rec_square_45_left'])) $PrtObj->capacity_rec_square_45_left = $_REQUEST['capacity_rec_square_45_left'];
        if (isset($_REQUEST['capacity_rec_square_45_right'])) $PrtObj->capacity_rec_square_45_right = $_REQUEST['capacity_rec_square_45_right'];
        if (isset($_REQUEST['capacity_rec_square_60_left'])) $PrtObj->capacity_rec_square_60_left = $_REQUEST['capacity_rec_square_60_left'];
        if (isset($_REQUEST['capacity_rec_square_60_right'])) $PrtObj->capacity_rec_square_60_right = $_REQUEST['capacity_rec_square_60_right'];
        if (isset($_REQUEST['capacity_solid_90_round'])) $PrtObj->capacity_solid_90_round = $_REQUEST['capacity_solid_90_round'];
        if (isset($_REQUEST['capacity_solid_90_rec'])) $PrtObj->capacity_solid_90_rec = $_REQUEST['capacity_solid_90_rec'];
        if (isset($_REQUEST['capacity_solid_90_square'])) $PrtObj->capacity_solid_90_square = $_REQUEST['capacity_solid_90_square'];

        if (isset($_REQUEST['heading_two'])) $PrtObj->heading_two = $_REQUEST['heading_two'];
        if (isset($_REQUEST['operation'])) $PrtObj->operation = $_REQUEST['operation'];
        if (isset($_REQUEST['feature_one'])) $PrtObj->feature_one = $_REQUEST['feature_one'];
        if (isset($_REQUEST['feature_two'])) $PrtObj->feature_two = $_REQUEST['feature_two'];
        if (isset($_REQUEST['technical_features'])) $PrtObj->technical_features = $_REQUEST['technical_features'];
        if (isset($_REQUEST['optional_features'])) $PrtObj->optional_features = $_REQUEST['optional_features'];
        if (isset($_REQUEST['overview'])) $PrtObj->overview = $_REQUEST['overview'];
        if (isset($_REQUEST['operation'])) $PrtObj->operation = $_REQUEST['operation'];
        if (isset($_REQUEST['machine_accessory'])) $PrtObj->machine_accessory = $_REQUEST['machine_accessory'];
        if (isset($_REQUEST['machine_subcategory'])) $PrtObj->machine_subcategory = $_REQUEST['machine_subcategory'];
        if (isset($_REQUEST['machine_title'])) $PrtObj->machine_title = $_REQUEST['machine_title'];
        if (isset($_REQUEST['machine_code'])) $PrtObj->machine_code = $_REQUEST['machine_code'];
        if (isset($_REQUEST['manufacturer'])) $PrtObj->manufacturer = $_REQUEST['manufacturer'];
        if (isset($_REQUEST['machine_type'])) $PrtObj->machine_type = $_REQUEST['machine_type'];
        if (isset($_REQUEST['operation_type'])) $PrtObj->operation_type = $_REQUEST['operation_type'];
        if (isset($_REQUEST['material_type'])) $PrtObj->material_type = $_REQUEST['material_type'];
        if (isset($_REQUEST['materials'])) $PrtObj->materials = $_REQUEST['materials'];
        if (isset($_REQUEST['blade_size'])) $PrtObj->blade_size = $_REQUEST['blade_size'];
        if (isset($_REQUEST['blade_speed'])) $PrtObj->blade_speed = $_REQUEST['blade_speed'];
        if (isset($_REQUEST['dimensions_speed'])) $PrtObj->dimensions_speed = $_REQUEST['dimensions_speed'];
        if (isset($_REQUEST['power_supply'])) $PrtObj->power_supply = $_REQUEST['power_supply'];
        if (isset($_REQUEST['filters'])) $PrtObj->filters = $_REQUEST['filters'];
        if (isset($_REQUEST['spec_pre_bending'])) $PrtObj->spec_pre_bending = $_REQUEST['spec_pre_bending'];
        if (isset($_REQUEST['spec_top_roll'])) $PrtObj->spec_top_roll = $_REQUEST['spec_top_roll'];
        if (isset($_REQUEST['spec_bottom_roll'])) $PrtObj->spec_bottom_roll = $_REQUEST['spec_bottom_roll'];
        if (isset($_REQUEST['spec_side_roll'])) $PrtObj->spec_side_roll = $_REQUEST['spec_side_roll'];
        if (isset($_REQUEST['spec_bending_speed'])) $PrtObj->spec_bending_speed = $_REQUEST['spec_bending_speed'];
        if (isset($_REQUEST['spec_rolls'])) $PrtObj->spec_rolls = $_REQUEST['spec_rolls'];
        if (isset($_REQUEST['spec_shaft'])) $PrtObj->spec_shaft = $_REQUEST['spec_shaft'];
        if (isset($_REQUEST['spec_max_section'])) $PrtObj->spec_max_section = $_REQUEST['spec_max_section'];
        if (isset($_REQUEST['table_height'])) $PrtObj->table_height = $_REQUEST['table_height'];
        if (isset($_REQUEST['table_size'])) $PrtObj->table_size = $_REQUEST['table_size'];
        if (isset($_REQUEST['motor'])) $PrtObj->motor = $_REQUEST['motor'];
        if (isset($_REQUEST['hydraulic_motor_type'])) $PrtObj->hydraulic_motor_type = $_REQUEST['hydraulic_motor_type'];
        if (isset($_REQUEST['hydraulic_tank'])) $PrtObj->hydraulic_tank = $_REQUEST['hydraulic_tank'];
        if (isset($_REQUEST['coolant_motor'])) $PrtObj->coolant_motor = $_REQUEST['coolant_motor'];
        if (isset($_REQUEST['coolant_tank'])) $PrtObj->coolant_tank = $_REQUEST['coolant_tank'];
        if (isset($_REQUEST['coolant_pump'])) $PrtObj->coolant_pump = $_REQUEST['coolant_pump'];
        if (isset($_REQUEST['feeding_stroke'])) $PrtObj->feeding_stroke = $_REQUEST['feeding_stroke'];
        if (isset($_REQUEST['weight'])) $PrtObj->weight = $_REQUEST['weight'];
        if (isset($_REQUEST['machine_dimensions'])) $PrtObj->machine_dimensions = $_REQUEST['machine_dimensions'];
        if (isset($_REQUEST['machine_dimensions_1'])) $PrtObj->machine_dimensions_1 = $_REQUEST['machine_dimensions_1'];
        if (isset($_REQUEST['machine_dimensions_2'])) $PrtObj->machine_dimensions_2 = $_REQUEST['machine_dimensions_2'];
        if (isset($_REQUEST['punching_1'])) $PrtObj->punching_1 = $_REQUEST['punching_1'];
        if (isset($_REQUEST['punching_2'])) $PrtObj->punching_2 = $_REQUEST['punching_2'];
        if (isset($_REQUEST['flatbar_shear_1'])) $PrtObj->flatbar_shear_1 = $_REQUEST['flatbar_shear_1'];
        if (isset($_REQUEST['flatbar_shear_2'])) $PrtObj->flatbar_shear_2 = $_REQUEST['flatbar_shear_2'];
        if (isset($_REQUEST['rectangular_notching'])) $PrtObj->rectangular_notching = $_REQUEST['rectangular_notching'];
        if (isset($_REQUEST['triangular_notching'])) $PrtObj->triangular_notching = $_REQUEST['triangular_notching'];
        if (isset($_REQUEST['angle_shear_90'])) $PrtObj->angle_shear_90 = $_REQUEST['angle_shear_90'];
        if (isset($_REQUEST['angle_shear_45'])) $PrtObj->angle_shear_45 = $_REQUEST['angle_shear_45'];
        if (isset($_REQUEST['bending'])) $PrtObj->bending = $_REQUEST['bending'];
        if (isset($_REQUEST['solid_bar'])) $PrtObj->solid_bar = $_REQUEST['solid_bar'];
        if (isset($_REQUEST['angle_shearing_power'])) $PrtObj->angle_shearing_power = $_REQUEST['angle_shearing_power'];
        if (isset($_REQUEST['punching_power'])) $PrtObj->punching_power = $_REQUEST['punching_power'];
        if (isset($_REQUEST['angle_shearing_tonnage'])) $PrtObj->angle_shearing_tonnage = $_REQUEST['angle_shearing_tonnage'];
        if (isset($_REQUEST['angle_optional_blade'])) $PrtObj->angle_optional_blade = $_REQUEST['angle_optional_blade'];
        if (isset($_REQUEST['throat_depth'])) $PrtObj->throat_depth = $_REQUEST['throat_depth'];
        if (isset($_REQUEST['tonnage'])) $PrtObj->tonnage = $_REQUEST['tonnage'];
        if (isset($_REQUEST['throat_capacity'])) $PrtObj->throat_capacity = $_REQUEST['throat_capacity'];
        if (isset($_REQUEST['materials_hand'])) $PrtObj->materials_hand = $_REQUEST['materials_hand'];
        if (isset($_REQUEST['mh_length'])) $PrtObj->mh_length = $_REQUEST['mh_length'];
        if (isset($_REQUEST['mh_width'])) $PrtObj->mh_width = $_REQUEST['mh_width'];





        $Prt_ID = $PrtDao->update($PrtObj);

        $PrdObj = new stdClass();

        $PrdObj->prd_id = 0;
        $PrdObj->tblnam = '';
        $PrdObj->tbl_id = 0;
        $PrdObj->prt_id = $Prt_ID;
        $PrdObj->prdnam = $PrtObj->prtnam;
        $PrdObj->prddsc = $PrtObj->prtdsc;
        $PrdObj->prdspc = $PrtObj->prtspc;
        $PrdObj->unipri = $PrtObj->unipri;
        $PrdObj->buypri = $PrtObj->buypri;
        $PrdObj->delpri = $PrtObj->delpri;
        $PrdObj->sup_id = 0;
        $PrdObj->atr_id = 0;
        $PrdObj->sta_id = 0;
        $PrdObj->seourl = seoUrl($PrtObj->prtnam);
        $PrdObj->seokey = '';
        $PrdObj->seodsc = '';
        $PrdObj->prdtag = '';
        $PrdObj->usestk = 0;
        $PrdObj->in_stk = 0;
        $PrdObj->on_ord = 0;
        $PrdObj->on_del = 0;
        $PrdObj->altref = '';
        $PrdObj->altnam = '';
        $PrdObj->weight = 0;
        $PrdObj->srtord = 1000;
        $PrdObj->vat_id = $PrtObj->vat_id;
        $PrdObj->done = $PrtObj->done;
        $PrdObj->prdobj = '';
        $PrdObj->filters = '';
        $PrdObj->technical_features = '';
        $PrdObj->optional_features = '';


        if (isset($_REQUEST['blade_type'])) $PrtObj->blade_type = $_REQUEST['blade_type'];
        if (isset($_REQUEST['spec_blade_size_1'])) $PrtObj->spec_blade_size_1 = $_REQUEST['spec_blade_size_1'];
        if (isset($_REQUEST['spec_blade_size_2'])) $PrtObj->spec_blade_size_2 = $_REQUEST['spec_blade_size_2'];
        if (isset($_REQUEST['spec_blade_size_3'])) $PrtObj->spec_blade_size_3 = $_REQUEST['spec_blade_size_3'];
        if (isset($_REQUEST['blade_speed'])) $PrtObj->blade_speed = $_REQUEST['blade_speed'];
        if (isset($_REQUEST['capacity_round_90'])) $PrtObj->capacity_round_90 = $_REQUEST['capacity_round_90'];
        if (isset($_REQUEST['capacity_round_45_left'])) $PrtObj->capacity_round_45_left = $_REQUEST['capacity_round_45_left'];
        if (isset($_REQUEST['capacity_round_45_right'])) $PrtObj->capacity_round_45_right = $_REQUEST['capacity_round_45_right'];
        if (isset($_REQUEST['capacity_round_60_left'])) $PrtObj->capacity_round_60_left = $_REQUEST['capacity_round_60_left'];
        if (isset($_REQUEST['capacity_round_60_right'])) $PrtObj->capacity_round_60_right = $_REQUEST['capacity_round_60_right'];
        if (isset($_REQUEST['capacity_rec_horizontal_90'])) $PrtObj->capacity_rec_horizontal_90 = $_REQUEST['capacity_rec_horizontal_90'];
        if (isset($_REQUEST['capacity_rec_horizontal_45_left'])) $PrtObj->capacity_rec_horizontal_45_left = $_REQUEST['capacity_rec_horizontal_45_left'];
        if (isset($_REQUEST['capacity_rec_horizontal_45_right'])) $PrtObj->capacity_rec_horizontal_45_right = $_REQUEST['capacity_rec_horizontal_45_right'];
        if (isset($_REQUEST['capacity_rec_horizontal_60_left'])) $PrtObj->capacity_rec_horizontal_60_left = $_REQUEST['capacity_rec_horizontal_60_left'];
        if (isset($_REQUEST['capacity_rec_horizontal_60_right'])) $PrtObj->capacity_rec_horizontal_60_right = $_REQUEST['capacity_rec_horizontal_60_right'];
        if (isset($_REQUEST['capacity_rec_vertical_90'])) $PrtObj->capacity_rec_vertical_90 = $_REQUEST['capacity_rec_vertical_90'];
        if (isset($_REQUEST['capacity_rec_vertical_45_left'])) $PrtObj->capacity_rec_vertical_45_left = $_REQUEST['capacity_rec_vertical_45_left'];
        if (isset($_REQUEST['capacity_rec_vertical_45_right'])) $PrtObj->capacity_rec_vertical_45_right = $_REQUEST['capacity_rec_vertical_45_right'];
        if (isset($_REQUEST['capacity_rec_vertical_60_left'])) $PrtObj->capacity_rec_vertical_60_left = $_REQUEST['capacity_rec_vertical_60_left'];
        if (isset($_REQUEST['capacity_rec_vertical_60_right'])) $PrtObj->capacity_rec_vertical_60_right = $_REQUEST['capacity_rec_vertical_60_right'];
        if (isset($_REQUEST['capacity_rec_square_90'])) $PrtObj->capacity_rec_square_90 = $_REQUEST['capacity_rec_square_90'];
        if (isset($_REQUEST['capacity_rec_square_45_left'])) $PrtObj->capacity_rec_square_45_left = $_REQUEST['capacity_rec_square_45_left'];
        if (isset($_REQUEST['capacity_rec_square_45_right'])) $PrtObj->capacity_rec_square_45_right = $_REQUEST['capacity_rec_square_45_right'];
        if (isset($_REQUEST['capacity_rec_square_60_left'])) $PrtObj->capacity_rec_square_60_left = $_REQUEST['capacity_rec_square_60_left'];
        if (isset($_REQUEST['capacity_rec_square_60_right'])) $PrtObj->capacity_rec_square_60_right = $_REQUEST['capacity_rec_square_60_right'];
        if (isset($_REQUEST['capacity_solid_90_round'])) $PrtObj->capacity_solid_90_round = $_REQUEST['capacity_solid_90_round'];
        if (isset($_REQUEST['capacity_solid_90_rec'])) $PrtObj->capacity_solid_90_rec = $_REQUEST['capacity_solid_90_rec'];
        if (isset($_REQUEST['capacity_solid_90_square'])) $PrtObj->capacity_solid_90_square = $_REQUEST['capacity_solid_90_square'];



        if (isset($_REQUEST['heading_two'])) $PrtObj->heading_two = $_REQUEST['heading_two'];
        if (isset($_REQUEST['feature_one'])) $PrtObj->feature_one = $_REQUEST['feature_one'];
        if (isset($_REQUEST['feature_two'])) $PrtObj->feature_two = $_REQUEST['feature_two'];
        if (isset($_REQUEST['operation'])) $PrtObj->operation = $_REQUEST['operation'];
        if (isset($_REQUEST['overview'])) $PrtObj->overview = $_REQUEST['overview'];
        if (isset($_REQUEST['technical_features'])) $PrtObj->technical_features = $_REQUEST['technical_features'];
        if (isset($_REQUEST['optional_features'])) $PrtObj->optional_features = $_REQUEST['optional_features'];
        if (isset($_REQUEST['machine_accessory'])) $PrtObj->machine_accessory = $_REQUEST['machine_accessory'];
        if (isset($_REQUEST['machine_subcategory'])) $PrtObj->machine_subcategory = $_REQUEST['machine_subcategory'];
        if (isset($_REQUEST['machine_title'])) $PrtObj->machine_title = $_REQUEST['machine_title'];
        if (isset($_REQUEST['machine_code'])) $PrtObj->machine_code = $_REQUEST['machine_code'];
        if (isset($_REQUEST['manufacturer'])) $PrtObj->manufacturer = $_REQUEST['manufacturer'];
        if (isset($_REQUEST['machine_type'])) $PrtObj->machine_type = $_REQUEST['machine_type'];
        if (isset($_REQUEST['operation_type'])) $PrtObj->operation_type = $_REQUEST['operation_type'];
        if (isset($_REQUEST['material_type'])) $PrtObj->material_type = $_REQUEST['material_type'];
        if (isset($_REQUEST['materials'])) $PrtObj->materials = $_REQUEST['materials'];
        if (isset($_REQUEST['blade_size'])) $PrtObj->blade_size = $_REQUEST['blade_size'];

        if (isset($_REQUEST['dimensions_speed'])) $PrtObj->dimensions_speed = $_REQUEST['dimensions_speed'];
        if (isset($_REQUEST['power_supply'])) $PrtObj->power_supply = $_REQUEST['power_supply'];
        if (isset($_REQUEST['filters'])) $PrtObj->filters = $_REQUEST['filters'];
        if (isset($_REQUEST['spec_pre_bending'])) $PrtObj->spec_pre_bending = $_REQUEST['spec_pre_bending'];
        if (isset($_REQUEST['spec_top_roll'])) $PrtObj->spec_top_roll = $_REQUEST['spec_top_roll'];
        if (isset($_REQUEST['spec_bottom_roll'])) $PrtObj->spec_bottom_roll = $_REQUEST['spec_bottom_roll'];
        if (isset($_REQUEST['spec_side_roll'])) $PrtObj->spec_side_roll = $_REQUEST['spec_side_roll'];
        if (isset($_REQUEST['spec_bending_speed'])) $PrtObj->spec_bending_speed = $_REQUEST['spec_bending_speed'];
        if (isset($_REQUEST['spec_rolls'])) $PrtObj->spec_rolls = $_REQUEST['spec_rolls'];
        if (isset($_REQUEST['spec_shaft'])) $PrtObj->spec_shaft = $_REQUEST['spec_shaft'];
        if (isset($_REQUEST['spec_max_section'])) $PrtObj->spec_max_section = $_REQUEST['spec_max_section'];
        if (isset($_REQUEST['table_height'])) $PrtObj->table_height = $_REQUEST['table_height'];
        if (isset($_REQUEST['table_size'])) $PrtObj->table_size = $_REQUEST['table_size'];
        if (isset($_REQUEST['motor'])) $PrtObj->motor = $_REQUEST['motor'];
        if (isset($_REQUEST['hydraulic_motor_type'])) $PrtObj->hydraulic_motor_type = $_REQUEST['hydraulic_motor_type'];
        if (isset($_REQUEST['hydraulic_tank'])) $PrtObj->hydraulic_tank = $_REQUEST['hydraulic_tank'];
        if (isset($_REQUEST['coolant_motor'])) $PrtObj->coolant_motor = $_REQUEST['coolant_motor'];
        if (isset($_REQUEST['coolant_tank'])) $PrtObj->coolant_tank = $_REQUEST['coolant_tank'];
        if (isset($_REQUEST['coolant_pump'])) $PrtObj->coolant_pump = $_REQUEST['coolant_pump'];
        if (isset($_REQUEST['feeding_stroke'])) $PrtObj->feeding_stroke = $_REQUEST['feeding_stroke'];
        if (isset($_REQUEST['weight'])) $PrtObj->weight = $_REQUEST['weight'];
        if (isset($_REQUEST['machine_dimensions'])) $PrtObj->machine_dimensions = $_REQUEST['machine_dimensions'];
        if (isset($_REQUEST['machine_dimensions_1'])) $PrtObj->machine_dimensions_1 = $_REQUEST['machine_dimensions_1'];
        if (isset($_REQUEST['machine_dimensions_2'])) $PrtObj->machine_dimensions_2 = $_REQUEST['machine_dimensions_2'];
        if (isset($_REQUEST['punching_1'])) $PrtObj->punching_1 = $_REQUEST['punching_1'];
        if (isset($_REQUEST['punching_2'])) $PrtObj->punching_2 = $_REQUEST['punching_2'];
        if (isset($_REQUEST['flatbar_shear_1'])) $PrtObj->flatbar_shear_1 = $_REQUEST['flatbar_shear_1'];
        if (isset($_REQUEST['flatbar_shear_2'])) $PrtObj->flatbar_shear_2 = $_REQUEST['flatbar_shear_2'];
        if (isset($_REQUEST['rectangular_notching'])) $PrtObj->rectangular_notching = $_REQUEST['rectangular_notching'];
        if (isset($_REQUEST['triangular_notching'])) $PrtObj->triangular_notching = $_REQUEST['triangular_notching'];
        if (isset($_REQUEST['angle_shear_90'])) $PrtObj->angle_shear_90 = $_REQUEST['angle_shear_90'];
        if (isset($_REQUEST['angle_shear_45'])) $PrtObj->angle_shear_45 = $_REQUEST['angle_shear_45'];
        if (isset($_REQUEST['bending'])) $PrtObj->bending = $_REQUEST['bending'];
        if (isset($_REQUEST['solid_bar'])) $PrtObj->solid_bar = $_REQUEST['solid_bar'];
        if (isset($_REQUEST['angle_shearing_power'])) $PrtObj->angle_shearing_power = $_REQUEST['angle_shearing_power'];
        if (isset($_REQUEST['punching_power'])) $PrtObj->punching_power = $_REQUEST['punching_power'];
        if (isset($_REQUEST['angle_shearing_tonnage'])) $PrtObj->angle_shearing_tonnage = $_REQUEST['angle_shearing_tonnage'];
        if (isset($_REQUEST['angle_optional_blade'])) $PrtObj->angle_optional_blade = $_REQUEST['angle_optional_blade'];
        if (isset($_REQUEST['throat_depth'])) $PrtObj->throat_depth = $_REQUEST['throat_depth'];
        if (isset($_REQUEST['tonnage'])) $PrtObj->tonnage = $_REQUEST['tonnage'];
        if (isset($_REQUEST['throat_capacity'])) $PrtObj->throat_capacity = $_REQUEST['throat_capacity'];
        if (isset($_REQUEST['materials_hand'])) $PrtObj->materials_hand = $_REQUEST['materials_hand'];
        if (isset($_REQUEST['mh_length'])) $PrtObj->mh_length = $_REQUEST['mh_length'];
        if (isset($_REQUEST['mh_width'])) $PrtObj->mh_width = $_REQUEST['mh_width'];


        $Prd_ID = $PrdDao->update($PrdObj);

        //
        // Attribute Group Creation to Match Product Type
        //

//        $TmpAtr = new AtrDAO();
//
//        $AtrObj = new stdClass();
//
//        $AtrObj->atr_id = 0;
//        $AtrObj->tblnam = 'PRODUCTTYPE';
//        $AtrObj->tbl_id = $Prt_ID;
//        $AtrObj->atrnam = $PrtObj->prtnam;
//        $AtrObj->atrdsc = $PrtObj->prtdsc;
//        $AtrObj->atrema = '';
//        $AtrObj->seourl = '';
//        $AtrObj->fwdurl = '';
//        $AtrObj->btntxt = 'SUBMIT';
//        $AtrObj->seokey = '';
//        $AtrObj->seodsc = '';
//        $AtrObj->sta_id = 0;
//        $AtrObj->atrtag = '';
//        $Atr_ID = $TmpAtr->update($AtrObj);


        $throwJSON['id'] = $Prt_ID;
        $throwJSON['title'] = 'Product Type Created';
        $throwJSON['description'] = 'Product Type '.$PrtObj->prtnam.' created';
        $throwJSON['type'] = 'success';


    } else {

        if (isset($_REQUEST['tblnam'])) $PrtObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PrtObj->tbl_id = $_REQUEST['tbl_id'];
        if (isset($_REQUEST['prtnam'])) $PrtObj->prtnam = $_REQUEST['prtnam'];
        if (isset($_REQUEST['prtdsc'])) $PrtObj->prtdsc = $_REQUEST['prtdsc'];
        if (isset($_REQUEST['prtspc'])) $PrtObj->prtspc = $_REQUEST['prtspc'];
        if (isset($_REQUEST['unipri'])) $PrtObj->unipri = $_REQUEST['unipri'];
        if (isset($_REQUEST['buypri'])) $PrtObj->buypri = $_REQUEST['buypri'];
        if (isset($_REQUEST['delpri'])) $PrtObj->delpri = $_REQUEST['delpri'];
        if (isset($_REQUEST['atr_id'])) $PrtObj->atr_id = $_REQUEST['atr_id'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PrtObj->sta_id = $_REQUEST['sta_id'];

        if (isset($_REQUEST['seourl'])) $PrtObj->seourl = $_REQUEST['seourl'];
        if (isset($_REQUEST['seokey'])) $PrtObj->seokey = $_REQUEST['seokey'];
        if (isset($_REQUEST['seodsc'])) $PrtObj->seodsc = $_REQUEST['seodsc'];

        if (isset($_REQUEST['usestk']) && is_numeric($_REQUEST['usestk'])) $PrtObj->usestk = $_REQUEST['usestk'];
        if (isset($_REQUEST['hompag']) && is_numeric($_REQUEST['hompag'])) {
            $PrtObj->hompag = $_REQUEST['hompag'];
        } else {
            $PrtObj->hompag = 0;
        }

        if (isset($_REQUEST['prttag'])) $PrtObj->prttag = $_REQUEST['prttag'];

        if (isset($_REQUEST['prtobj'])) $PrtObj->prtobj = $_REQUEST['prtobj'];

        if (isset($_REQUEST['vat_id'])) $PrtObj->vat_id = $_REQUEST['vat_id'];
        if (isset($_REQUEST['done'])) $PrtObj->done = $_REQUEST['done'];





        if (isset($_REQUEST['blade_type'])) $PrtObj->blade_type = $_REQUEST['blade_type'];
        if (isset($_REQUEST['spec_blade_size_1'])) $PrtObj->spec_blade_size_1 = $_REQUEST['spec_blade_size_1'];
        if (isset($_REQUEST['spec_blade_size_2'])) $PrtObj->spec_blade_size_2 = $_REQUEST['spec_blade_size_2'];
        if (isset($_REQUEST['spec_blade_size_3'])) $PrtObj->spec_blade_size_3 = $_REQUEST['spec_blade_size_3'];
        if (isset($_REQUEST['blade_speed'])) $PrtObj->blade_speed = $_REQUEST['blade_speed'];
        if (isset($_REQUEST['capacity_round_90'])) $PrtObj->capacity_round_90 = $_REQUEST['capacity_round_90'];
        if (isset($_REQUEST['capacity_round_45_left'])) $PrtObj->capacity_round_45_left = $_REQUEST['capacity_round_45_left'];
        if (isset($_REQUEST['capacity_round_45_right'])) $PrtObj->capacity_round_45_right = $_REQUEST['capacity_round_45_right'];
        if (isset($_REQUEST['capacity_round_60_left'])) $PrtObj->capacity_round_60_left = $_REQUEST['capacity_round_60_left'];
        if (isset($_REQUEST['capacity_round_60_right'])) $PrtObj->capacity_round_60_right = $_REQUEST['capacity_round_60_right'];
        if (isset($_REQUEST['capacity_rec_horizontal_90'])) $PrtObj->capacity_rec_horizontal_90 = $_REQUEST['capacity_rec_horizontal_90'];
        if (isset($_REQUEST['capacity_rec_horizontal_45_left'])) $PrtObj->capacity_rec_horizontal_45_left = $_REQUEST['capacity_rec_horizontal_45_left'];
        if (isset($_REQUEST['capacity_rec_horizontal_45_right'])) $PrtObj->capacity_rec_horizontal_45_right = $_REQUEST['capacity_rec_horizontal_45_right'];
        if (isset($_REQUEST['capacity_rec_horizontal_60_left'])) $PrtObj->capacity_rec_horizontal_60_left = $_REQUEST['capacity_rec_horizontal_60_left'];
        if (isset($_REQUEST['capacity_rec_horizontal_60_right'])) $PrtObj->capacity_rec_horizontal_60_right = $_REQUEST['capacity_rec_horizontal_60_right'];
        if (isset($_REQUEST['capacity_rec_vertical_90'])) $PrtObj->capacity_rec_vertical_90 = $_REQUEST['capacity_rec_vertical_90'];
        if (isset($_REQUEST['capacity_rec_vertical_45_left'])) $PrtObj->capacity_rec_vertical_45_left = $_REQUEST['capacity_rec_vertical_45_left'];
        if (isset($_REQUEST['capacity_rec_vertical_45_right'])) $PrtObj->capacity_rec_vertical_45_right = $_REQUEST['capacity_rec_vertical_45_right'];
        if (isset($_REQUEST['capacity_rec_vertical_60_left'])) $PrtObj->capacity_rec_vertical_60_left = $_REQUEST['capacity_rec_vertical_60_left'];
        if (isset($_REQUEST['capacity_rec_vertical_60_right'])) $PrtObj->capacity_rec_vertical_60_right = $_REQUEST['capacity_rec_vertical_60_right'];
        if (isset($_REQUEST['capacity_rec_square_90'])) $PrtObj->capacity_rec_square_90 = $_REQUEST['capacity_rec_square_90'];
        if (isset($_REQUEST['capacity_rec_square_45_left'])) $PrtObj->capacity_rec_square_45_left = $_REQUEST['capacity_rec_square_45_left'];
        if (isset($_REQUEST['capacity_rec_square_45_right'])) $PrtObj->capacity_rec_square_45_right = $_REQUEST['capacity_rec_square_45_right'];
        if (isset($_REQUEST['capacity_rec_square_60_left'])) $PrtObj->capacity_rec_square_60_left = $_REQUEST['capacity_rec_square_60_left'];
        if (isset($_REQUEST['capacity_rec_square_60_right'])) $PrtObj->capacity_rec_square_60_right = $_REQUEST['capacity_rec_square_60_right'];
        if (isset($_REQUEST['capacity_solid_90_round'])) $PrtObj->capacity_solid_90_round = $_REQUEST['capacity_solid_90_round'];
        if (isset($_REQUEST['capacity_solid_90_rec'])) $PrtObj->capacity_solid_90_rec = $_REQUEST['capacity_solid_90_rec'];
        if (isset($_REQUEST['capacity_solid_90_square'])) $PrtObj->capacity_solid_90_square = $_REQUEST['capacity_solid_90_square'];




        if (isset($_REQUEST['heading_one'])) $PrtObj->heading_one = $_REQUEST['heading_one'];
        if (isset($_REQUEST['heading_two'])) $PrtObj->heading_two = $_REQUEST['heading_two'];
        if (isset($_REQUEST['feature_one'])) $PrtObj->feature_one = $_REQUEST['feature_one'];
        if (isset($_REQUEST['feature_two'])) $PrtObj->feature_two = $_REQUEST['feature_two'];
        if (isset($_REQUEST['overview'])) $PrtObj->overview = $_REQUEST['overview'];
        if (isset($_REQUEST['operation'])) $PrtObj->operation = $_REQUEST['operation'];
        if (isset($_REQUEST['technical_features'])) $PrtObj->technical_features = $_REQUEST['technical_features'];
        if (isset($_REQUEST['optional_features'])) $PrtObj->optional_features = $_REQUEST['optional_features'];
        if (isset($_REQUEST['machine_accessory'])) $PrtObj->machine_accessory = $_REQUEST['machine_accessory'];
        if (isset($_REQUEST['machine_subcategory'])) $PrtObj->machine_subcategory = $_REQUEST['machine_subcategory'];
        if (isset($_REQUEST['machine_title'])) $PrtObj->machine_title = $_REQUEST['machine_title'];
        if (isset($_REQUEST['machine_code'])) $PrtObj->machine_code = $_REQUEST['machine_code'];
        if (isset($_REQUEST['manufacturer'])) $PrtObj->manufacturer = $_REQUEST['manufacturer'];
        if (isset($_REQUEST['machine_type'])) $PrtObj->machine_type = $_REQUEST['machine_type'];
        if (isset($_REQUEST['operation_type'])) $PrtObj->operation_type = $_REQUEST['operation_type'];
        if (isset($_REQUEST['material_type'])) $PrtObj->material_type = $_REQUEST['material_type'];
        if (isset($_REQUEST['materials'])) $PrtObj->materials = $_REQUEST['materials'];
        if (isset($_REQUEST['blade_size'])) $PrtObj->blade_size = $_REQUEST['blade_size'];
        if (isset($_REQUEST['blade_speed'])) $PrtObj->blade_speed = $_REQUEST['blade_speed'];
        if (isset($_REQUEST['dimensions_speed'])) $PrtObj->dimensions_speed = $_REQUEST['dimensions_speed'];
        if (isset($_REQUEST['power_supply'])) $PrtObj->power_supply = $_REQUEST['power_supply'];
        if (isset($_REQUEST['filters'])) $PrtObj->filters = $_REQUEST['filters'];
        if (isset($_REQUEST['spec_pre_bending'])) $PrtObj->spec_pre_bending = $_REQUEST['spec_pre_bending'];
        if (isset($_REQUEST['spec_top_roll'])) $PrtObj->spec_top_roll = $_REQUEST['spec_top_roll'];
        if (isset($_REQUEST['spec_bottom_roll'])) $PrtObj->spec_bottom_roll = $_REQUEST['spec_bottom_roll'];
        if (isset($_REQUEST['spec_side_roll'])) $PrtObj->spec_side_roll = $_REQUEST['spec_side_roll'];
        if (isset($_REQUEST['spec_bending_speed'])) $PrtObj->spec_bending_speed = $_REQUEST['spec_bending_speed'];
        if (isset($_REQUEST['spec_rolls'])) $PrtObj->spec_rolls = $_REQUEST['spec_rolls'];
        if (isset($_REQUEST['spec_shaft'])) $PrtObj->spec_shaft = $_REQUEST['spec_shaft'];
        if (isset($_REQUEST['spec_max_section'])) $PrtObj->spec_max_section = $_REQUEST['spec_max_section'];
        if (isset($_REQUEST['table_height'])) $PrtObj->table_height = $_REQUEST['table_height'];
        if (isset($_REQUEST['table_size'])) $PrtObj->table_size = $_REQUEST['table_size'];
        if (isset($_REQUEST['motor'])) $PrtObj->motor = $_REQUEST['motor'];
        if (isset($_REQUEST['hydraulic_motor_type'])) $PrtObj->hydraulic_motor_type = $_REQUEST['hydraulic_motor_type'];
        if (isset($_REQUEST['hydraulic_tank'])) $PrtObj->hydraulic_tank = $_REQUEST['hydraulic_tank'];
        if (isset($_REQUEST['coolant_motor'])) $PrtObj->coolant_motor = $_REQUEST['coolant_motor'];
        if (isset($_REQUEST['coolant_tank'])) $PrtObj->coolant_tank = $_REQUEST['coolant_tank'];
        if (isset($_REQUEST['coolant_pump'])) $PrtObj->coolant_pump = $_REQUEST['coolant_pump'];
        if (isset($_REQUEST['feeding_stroke'])) $PrtObj->feeding_stroke = $_REQUEST['feeding_stroke'];
        if (isset($_REQUEST['weight'])) $PrtObj->weight = $_REQUEST['weight'];
        if (isset($_REQUEST['machine_dimensions'])) $PrtObj->machine_dimensions = $_REQUEST['machine_dimensions'];
        if (isset($_REQUEST['machine_dimensions_1'])) $PrtObj->machine_dimensions_1 = $_REQUEST['machine_dimensions_1'];
        if (isset($_REQUEST['machine_dimensions_2'])) $PrtObj->machine_dimensions_2 = $_REQUEST['machine_dimensions_2'];
        if (isset($_REQUEST['punching_1'])) $PrtObj->punching_1 = $_REQUEST['punching_1'];
        if (isset($_REQUEST['punching_2'])) $PrtObj->punching_2 = $_REQUEST['punching_2'];
        if (isset($_REQUEST['flatbar_shear_1'])) $PrtObj->flatbar_shear_1 = $_REQUEST['flatbar_shear_1'];
        if (isset($_REQUEST['flatbar_shear_2'])) $PrtObj->flatbar_shear_2 = $_REQUEST['flatbar_shear_2'];
        if (isset($_REQUEST['rectangular_notching'])) $PrtObj->rectangular_notching = $_REQUEST['rectangular_notching'];
        if (isset($_REQUEST['triangular_notching'])) $PrtObj->triangular_notching = $_REQUEST['triangular_notching'];
        if (isset($_REQUEST['angle_shear_90'])) $PrtObj->angle_shear_90 = $_REQUEST['angle_shear_90'];
        if (isset($_REQUEST['angle_shear_45'])) $PrtObj->angle_shear_45 = $_REQUEST['angle_shear_45'];
        if (isset($_REQUEST['bending'])) $PrtObj->bending = $_REQUEST['bending'];
        if (isset($_REQUEST['solid_bar'])) $PrtObj->solid_bar = $_REQUEST['solid_bar'];
        if (isset($_REQUEST['angle_shearing_power'])) $PrtObj->angle_shearing_power = $_REQUEST['angle_shearing_power'];
        if (isset($_REQUEST['punching_power'])) $PrtObj->punching_power = $_REQUEST['punching_power'];
        if (isset($_REQUEST['angle_shearing_tonnage'])) $PrtObj->angle_shearing_tonnage = $_REQUEST['angle_shearing_tonnage'];
        if (isset($_REQUEST['angle_optional_blade'])) $PrtObj->angle_optional_blade = $_REQUEST['angle_optional_blade'];
        if (isset($_REQUEST['throat_depth'])) $PrtObj->throat_depth = $_REQUEST['throat_depth'];
        if (isset($_REQUEST['tonnage'])) $PrtObj->tonnage = $_REQUEST['tonnage'];
        if (isset($_REQUEST['throat_capacity'])) $PrtObj->throat_capacity = $_REQUEST['throat_capacity'];
        if (isset($_REQUEST['materials_hand'])) $PrtObj->materials_hand = $_REQUEST['materials_hand'];
        if (isset($_REQUEST['mh_length'])) $PrtObj->mh_length = $_REQUEST['mh_length'];
        if (isset($_REQUEST['mh_width'])) $PrtObj->mh_width = $_REQUEST['mh_width'];
//
//        echo "<pre>";
//        print_r($_REQUEST);
//        echo "</pre>";
//        echo "ddddddd";
//        echo "<pre>";
//        print_r($PrtObj);
//        echo "</pre>";

//        die ("sssssssssss");
        $Prt_ID = $PrtDao->update($PrtObj);

        // Update products VAT

        $throwJSON['id'] = $Prt_ID;
        $throwJSON['title'] = 'Product Type Updated';
        $throwJSON['description'] = 'Product Type '.$PrtObj->prtnam.' updated';
        $throwJSON['type'] = 'success';

    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {

    $PrtObj = $PrtDao->select($Prt_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
    if ($PrtObj) {
        $PrtDao->delete($PrtObj->prt_id);

        $throwJSON['id'] = $PrtObj->prt_id;
        $throwJSON['title'] = 'Product Type Deleted';
        $throwJSON['description'] = 'Product Type '.$PrtObj->prtnam.' deleted';
        $throwJSON['type'] = 'success';
    } else {

        $throwJSON['id'] = $Prt_ID;
        $throwJSON['title'] = 'Product Type No Found';
        $throwJSON['description'] = 'Product Type not found';
        $throwJSON['type'] = 'error';


    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {

    //mb_internal_encoding('UTF-8');
    //mb_http_output('UTF-8');

    if ($Prt_ID == 0) $Prt_ID = NULL;
    $products = $PrtDao->selectLight($Prt_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, false);
    die(json_encode($products));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'reprice') {

    $UniPri = (isset($_REQUEST['unipri']) && is_numeric($_REQUEST['unipri'])) ? $_REQUEST['unipri'] : NULL;
    $BuyPri = (isset($_REQUEST['buypri']) && is_numeric($_REQUEST['buypri'])) ? $_REQUEST['buypri'] : NULL;
    $DelPri = (isset($_REQUEST['delpri']) && is_numeric($_REQUEST['delpri'])) ? $_REQUEST['delpri'] : NULL;
    $InsPri = (isset($_REQUEST['inspri']) && is_numeric($_REQUEST['inspri'])) ? $_REQUEST['inspri'] : NULL;
    $products = $PrtDao->updatePrices($Prt_ID, $UniPri, $BuyPri, $DelPri, $InsPri);

    $throwJSON['id'] = $Prt_ID;
    $throwJSON['title'] = 'Prices Updated';
    $throwJSON['description'] = 'Product prices updated';
    $throwJSON['type'] = 'success';

}

die(json_encode($throwJSON));
?>