<?php

//
// Product Types class
//

class PrtDAO extends db
{
    function getProductVariantImage($Prd_ID = null)
    {

        if (is_numeric($Prd_ID)) {
            $qryArray = array();
            $sql = 'SELECT
				u.*,
				p.prdnam
				FROM products p
				LEFT OUTER JOIN uploads u ON u.tblnam = "PRODUCT" AND u.tbl_id = p.prd_id
				WHERE p.prd_id = :prd_id GROUP BY p.prd_id ORDER BY p.srtord';
            $qryArray["prd_id"] = $Prd_ID;

            return $this->run($sql, $qryArray, false);
        }
        return null;
    }
    function getHomepageProducts($Prd_ID = null)
    {


            $qryArray = array();
            $sql = 'SELECT 
				*
				FROM producttypes WHERE hompag = 1 and sta_id = 0 ';



            return $this->run($sql, $qryArray, false);

        return null;
    }

    function getvarientlist($Prd_ID = null)
    {

        if (is_numeric($Prd_ID)) {
            $qryArray = array();
            $sql = 'SELECT
				p.prd_id,
				p.prdnam,
				p.unipri,
				p.seourl
				
				FROM products p
				WHERE p.prt_id = :prt_id ORDER BY p.srtord';
            $qryArray["prt_id"] = $Prd_ID;

            return $this->run($sql, $qryArray, false);
        }
        return null;
    }

    function getProductImage($Prt_ID = null)
    {

        if (is_numeric($Prt_ID)) {

            $qryArray = array();
            $sql = 'SELECT
				u.*,
				p.prdnam
				FROM products p
				LEFT OUTER JOIN uploads u ON u.tblnam = "PRDTYPE" AND u.tbl_id = p.prd_id
				WHERE p.prt_id = :prt_id GROUP BY p.prd_id ORDER BY p.srtord';
            $qryArray["prt_id"] = $Prt_ID;

            return $this->run($sql, $qryArray, false);

        }

        return null;

    }

    function getProductTypeImage($Prt_ID = null)
    {

        if (is_numeric($Prt_ID)) {

            $qryArray = array();
            $sql = 'SELECT
				*
				FROM uploads
				WHERE tblnam = "PRDTYPE" AND tbl_id = :prt_id ORDER BY srtord LIMIT 1';
            $qryArray["prt_id"] = $Prt_ID;

            return $this->run($sql, $qryArray, false);

        }

        return null;

    }

    function selectFromPrds($Prd_ID = null)
    {

//        SELECT p.id, p.name, GROUP_CONCAT(s.name) AS site_list
//        FROM sites s
//        INNER JOIN publications p ON(s.id = p.site_id)
//        GROUP BY p.id;

    }

    function select(
        $Prt_ID = null,
        $SeoUrl = null,
        $TblNam = null,
        $Tbl_ID = null,
        $PrtNam = null,
        $Atr_ID = null,
        $PerPag = null,
        $Pag_No = null,
        $ReqObj = false,
        $filters = null,
        $machine_type = null,
        $structure = null,
        $manufacturer = null,
        $sta_id = NULL
    )
    {
        if ($Pag_No == 0) {
            $Pag_No = 1;
        }

        $OffSet = null;
        if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) {
            $OffSet = ($Pag_No - 1) * $PerPag;
        }

        $qryArray = [];


        if (!empty($structure)) {
            $sql = 'SELECT 
				p.* ,
				r.*
				FROM producttypes p ';
            $sql .= "INNER JOIN related r ON p.prt_id = r.tbl_id ";
            $sql .= 'WHERE TRUE ';

            $sql .= ' AND r.ref_id = :structure ';
            $qryArray["structure"] = $structure;

        } else {
            $sql = 'SELECT 
				p.* 
			
				FROM producttypes p ';
//            $sql .= "INNER JOIN related r ON p.prt_id = r.tbl_id ";
            $sql .= 'WHERE TRUE ';


        }

        if (isset($sta_id)) {
            $sql .= ' AND p.sta_id = :sta_id ';
            $qryArray["sta_id"] = $sta_id;
        }


        if (!is_null($Prt_ID)) {
            $sql .= ' AND p.prt_id = :prt_id ';
            $qryArray["prt_id"] = $Prt_ID;
        } else {
            if (!is_null($Atr_ID) && is_numeric($Atr_ID)) {
                $sql .= ' AND p.atr_id = :atr_id ';
                $qryArray["atr_id"] = $Atr_ID;
            }

            if (!is_null($SeoUrl)) {
                $sql .= ' AND p.seourl = :seourl ';
                $qryArray["seourl"] = $SeoUrl;
            }

            if (!is_null($TblNam)) {
                $sql .= ' AND p.tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }

            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND p.tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }

            if (!is_null($PrtNam)) {
                $PrtNam = '%' . $PrtNam . '%';
                $sql .= ' AND p.prtnam LIKE :prtnam ';
                $qryArray["prtnam"] = $PrtNam;
            }
            if (!empty($machine_type)) {
//				$PrtNam = '%' . $PrtNam . '%';
                $sql .= ' AND p.machine_type = :machine_type ';
                $qryArray["machine_type"] = $machine_type;
            }
            if (!empty($manufacturer)) {
//				$PrtNam = '%' . $PrtNam . '%';
                $sql .= ' AND p.manufacturer = :manufacturer ';
                $qryArray["manufacturer"] = $manufacturer;
            }


            if (!empty($filters)) {
                $filtering = explode(",", $filters);
                for ($f = 0; $f < count($filtering); $f++) {
                    $sql .= ' AND p.filters LIKE :filter' . $f;
                    $qryArray["filter" . $f] = "%," . $filtering[$f] . ",%";
                }
            }

            //REMOVERD CAUSE OF DUPLICATE
            if (empty($structure)) {
//                $sql .= 'ORDER BY r.srtord DESC';
            }
            if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
                $sql .= ' LIMIT ' . $OffSet . ' ,' . $PerPag;
            }
        }

//		echo $this->displayQuery($sql, $qryArray, $ReqObj);
        return $this->run($sql, $qryArray, $ReqObj);
    }

    function selectLight($Prt_ID = null, $SeoUrl = null, $TblNam = null, $Tbl_ID = null, $PrtNam = null, $Atr_ID = null, $PerPag = null, $Pag_No = null, $ReqObj = false)
    {

        if ($Pag_No == 0) $Pag_No = 1;

        $OffSet = null;
        if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No - 1) * $PerPag;

        $qryArray = array();
        $sql = 'SELECT
                p.prt_id,
				p.prtnam
				FROM producttypes p
				WHERE TRUE';

        if (!is_null($Prt_ID)) {
            $sql .= ' AND p.prt_id = :prt_id ';
            $qryArray["prt_id"] = $Prt_ID;
        } else {
            if (!is_null($Atr_ID) && is_numeric($Atr_ID)) {
                $sql .= ' AND p.atr_id = :atr_id ';
                $qryArray["atr_id"] = $Atr_ID;
            }

            if (!is_null($SeoUrl)) {
                $sql .= ' AND p.seourl = :seourl ';
                $qryArray["seourl"] = $SeoUrl;
            }

            if (!is_null($TblNam)) {
                $sql .= ' AND p.tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }
            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND p.tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }
            if (!is_null($PrtNam)) {
                $PrtNam = '%' . $PrtNam . '%';
                $sql .= ' AND p.prtnam LIKE :prtnam ';
                $qryArray["prtnam"] = $PrtNam;
            }

            $sql .= ' GROUP BY p.prt_id ';

            if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
                $sql .= ' LIMIT ' . $OffSet . ' , ' . $PerPag;
            } else {

            }

        }

        //$this->displayQuery($sql, $qryArray, $ReqObj);
        return $this->run($sql, $qryArray, $ReqObj);

    }

    function selectHomePage($TblNam = null, $Tbl_ID = null, $ReqObj = false)
    {

        $OffSet = null;
        if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No - 1) * $PerPag;

        $qryArray = array();
        $sql = 'SELECT
				p.*,
				u.filnam AS prtimg
				FROM producttypes p
				LEFT OUTER JOIN uploads u ON u.tblnam = "PRDTYPE" AND u.tbl_id = p.prt_id
				WHERE hompag = 1 ';

        if (!is_null($TblNam)) {
            $sql .= ' AND p.tblnam = :tblnam ';
            $qryArray["tblnam"] = $TblNam;
        }
        if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
            $sql .= ' AND p.tbl_id = :tbl_id ';
            $qryArray["tbl_id"] = $Tbl_ID;
        }

        $sql .= ' GROUP BY p.prt_id ';

        if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
            $sql .= ' LIMIT ' . $OffSet . ' , ' . $PerPag;
        } else {

        }

        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($PrtCls = null)
    {

        if (is_null($PrtCls) || !$PrtCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($PrtCls->prt_id == 0) {

            $qryArray["tblnam"] = $PrtCls->tblnam;
            $qryArray["tbl_id"] = $PrtCls->tbl_id;
            $qryArray["prtnam"] = $PrtCls->prtnam;
            $qryArray["prtdsc"] = $PrtCls->prtdsc;
            $qryArray["prtspc"] = $PrtCls->prtspc;
            $qryArray["atr_id"] = $PrtCls->atr_id;
            $qryArray["sta_id"] = $PrtCls->sta_id;

            $qryArray["usestk"] = $PrtCls->usestk;
            $qryArray["unipri"] = $PrtCls->unipri;
            $qryArray["buypri"] = $PrtCls->buypri;
            $qryArray["delpri"] = $PrtCls->delpri;

            $qryArray["seourl"] = $PrtCls->seourl;
            $qryArray["seokey"] = $PrtCls->seokey;
            $qryArray["seodsc"] = $PrtCls->seodsc;
            $qryArray["hompag"] = $PrtCls->hompag;

            $qryArray["prttag"] = $PrtCls->prttag;
            $qryArray["prtobj"] = $PrtCls->prtobj;

            $qryArray["vat_id"] = $PrtCls->vat_id;
            $qryArray["done"] = $PrtCls->done;

            $qryArray["operation"] = $PrtCls->operation;
            $qryArray["blade_type"] = $PrtCls->blade_type;
            $qryArray["spec_blade_size_1"] = $PrtCls->spec_blade_size_1;
            $qryArray["spec_blade_size_2"] = $PrtCls->spec_blade_size_2;
            $qryArray["spec_blade_size_3"] = $PrtCls->spec_blade_size_3;
            $qryArray["blade_speed"] = $PrtCls->blade_speed;
            $qryArray["capacity_round_90"] = $PrtCls->capacity_round_90;
            $qryArray["capacity_round_45_left"] = $PrtCls->capacity_round_45_left;
            $qryArray["capacity_round_45_right"] = $PrtCls->capacity_round_45_right;
            $qryArray["capacity_round_60_left"] = $PrtCls->capacity_round_60_left;
            $qryArray["capacity_round_60_right"] = $PrtCls->capacity_round_60_right;
            $qryArray["capacity_rec_horizontal_90"] = $PrtCls->capacity_rec_horizontal_90;
            $qryArray["capacity_rec_horizontal_45_left"] = $PrtCls->capacity_rec_horizontal_45_left;
            $qryArray["capacity_rec_horizontal_45_right"] = $PrtCls->capacity_rec_horizontal_45_right;
            $qryArray["capacity_rec_horizontal_60_left"] = $PrtCls->capacity_rec_horizontal_60_left;
            $qryArray["capacity_rec_horizontal_60_right"] = $PrtCls->capacity_rec_horizontal_60_right;
            $qryArray["capacity_rec_vertical_90"] = $PrtCls->capacity_rec_vertical_90;
            $qryArray["capacity_rec_vertical_45_left"] = $PrtCls->capacity_rec_vertical_45_left;
            $qryArray["capacity_rec_vertical_45_right"] = $PrtCls->capacity_rec_vertical_45_right;
            $qryArray["capacity_rec_vertical_60_left"] = $PrtCls->capacity_rec_vertical_60_left;
            $qryArray["capacity_rec_vertical_60_right"] = $PrtCls->capacity_rec_vertical_60_right;
            $qryArray["capacity_rec_square_90"] = $PrtCls->capacity_rec_square_90;
            $qryArray["capacity_rec_square_45_left"] = $PrtCls->capacity_rec_square_45_left;
            $qryArray["capacity_rec_square_45_right"] = $PrtCls->capacity_rec_square_45_right;
            $qryArray["capacity_rec_square_60_left"] = $PrtCls->capacity_rec_square_60_left;
            $qryArray["capacity_rec_square_60_right"] = $PrtCls->capacity_rec_square_60_right;
            $qryArray["capacity_solid_90_round"] = $PrtCls->capacity_solid_90_round;
            $qryArray["capacity_solid_90_rec"] = $PrtCls->capacity_solid_90_rec;
            $qryArray["capacity_solid_90_square"] = $PrtCls->capacity_solid_90_square;
            $qryArray["heading_one"] = $PrtCls->heading_one;
            $qryArray["heading_two"] = $PrtCls->heading_two;
            $qryArray["feature_one"] = $PrtCls->feature_one;
            $qryArray["feature_two"] = $PrtCls->feature_two;
            $qryArray["overview"] = $PrtCls->overview;
            $qryArray["technical_features"] = $PrtCls->technical_features;
            $qryArray["optional_features"] = $PrtCls->optional_features;
            $qryArray["machine_accessory"] = $PrtCls->machine_accessory;
            $qryArray["machine_subcategory"] = $PrtCls->machine_subcategory;
            $qryArray["machine_title"] = $PrtCls->machine_title;
            $qryArray["manufacturer"] = $PrtCls->manufacturer;
            $qryArray["machine_code"] = $PrtCls->machine_code;
            $qryArray["machine_type"] = $PrtCls->machine_type;
            $qryArray["operation_type"] = $PrtCls->operation_type;
            $qryArray["material_type"] = $PrtCls->material_type;
            $qryArray["materials"] = $PrtCls->materials;
            $qryArray["blade_size"] = $PrtCls->blade_size;
            $qryArray["blade_speed"] = $PrtCls->blade_speed;
            $qryArray["dimensions_speed"] = $PrtCls->dimensions_speed;
            $qryArray["power_supply"] = $PrtCls->power_supply;
            $qryArray["filters"] = $PrtCls->filters;
            $qryArray["spec_pre_bending"] = $PrtCls->spec_pre_bending;
            $qryArray["spec_top_roll"] = $PrtCls->spec_top_roll;
            $qryArray["spec_bottom_roll"] = $PrtCls->spec_bottom_roll;
            $qryArray["spec_side_roll"] = $PrtCls->spec_side_roll;
            $qryArray["spec_bending_speed"] = $PrtCls->spec_bending_speed;
            $qryArray["spec_rolls"] = $PrtCls->spec_rolls;
            $qryArray["spec_shaft"] = $PrtCls->spec_shaft;
            $qryArray["spec_max_section"] = $PrtCls->spec_max_section;
            $qryArray["table_height"] = $PrtCls->table_height;
            $qryArray["table_size"] = $PrtCls->table_size;
            $qryArray["motor"] = $PrtCls->motor;
            $qryArray["hydraulic_motor_type"] = $PrtCls->hydraulic_motor_type;
            $qryArray["hydraulic_tank"] = $PrtCls->hydraulic_tank;
            $qryArray["coolant_motor"] = $PrtCls->coolant_motor;
            $qryArray["coolant_tank"] = $PrtCls->coolant_tank;
            $qryArray["coolant_pump"] = $PrtCls->coolant_pump;
            $qryArray["feeding_stroke"] = $PrtCls->feeding_stroke;
            $qryArray["weight"] = $PrtCls->weight;
            $qryArray["machine_dimensions"] = $PrtCls->machine_dimensions;
            $qryArray["machine_dimensions_1"] = $PrtCls->machine_dimensions_1;
            $qryArray["machine_dimensions_2"] = $PrtCls->machine_dimensions_2;
            $qryArray["punching_1"] = $PrtCls->punching_1;
            $qryArray["punching_2"] = $PrtCls->punching_2;
            $qryArray["flatbar_shear_1"] = $PrtCls->flatbar_shear_1;
            $qryArray["flatbar_shear_2"] = $PrtCls->flatbar_shear_2;
            $qryArray["rectangular_notching"] = $PrtCls->rectangular_notching;
            $qryArray["triangular_notching"] = $PrtCls->triangular_notching;
            $qryArray["angle_shear_90"] = $PrtCls->angle_shear_90;
            $qryArray["angle_shear_45"] = $PrtCls->angle_shear_45;
            $qryArray["bending"] = $PrtCls->bending;
            $qryArray["solid_bar"] = $PrtCls->solid_bar;
            $qryArray["angle_shearing_power"] = $PrtCls->angle_shearing_power;
            $qryArray["punching_power"] = $PrtCls->punching_power;
            $qryArray["angle_shearing_tonnage"] = $PrtCls->angle_shearing_tonnage;
            $qryArray["angle_optional_blade"] = $PrtCls->angle_optional_blade;
            $qryArray["throat_depth"] = $PrtCls->throat_depth;
            $qryArray["tonnage"] = $PrtCls->tonnage;
            $qryArray["throat_capacity"] = $PrtCls->throat_capacity;
            $qryArray["materials_hand"] = $PrtCls->materials_hand;
            $qryArray["mh_length"] = $PrtCls->mh_length;
            $qryArray["mh_width"] = $PrtCls->mh_width;


            $sql = "INSERT INTO producttypes
					(
					
					tblnam,
					tbl_id,
					prtnam,
					prtdsc,
					prtspc,
					atr_id,
					sta_id,
					usestk,
					unipri,
					buypri,
					delpri,
					seourl,
					seokey,
					seodsc,
					hompag,
					prttag,
					prtobj,
					vat_id,
					done,
					blade_type,
					spec_blade_size_1,
					spec_blade_size_2,
					spec_blade_size_3,
				
					capacity_round_90,
					capacity_round_45_left,
					capacity_round_45_right,
					capacity_round_60_left,
					capacity_round_60_right,
					capacity_rec_horizontal_90,
					capacity_rec_horizontal_45_left,
					capacity_rec_horizontal_45_right,
					capacity_rec_horizontal_60_left,
					capacity_rec_horizontal_60_right,
					capacity_rec_vertical_90,
					capacity_rec_vertical_45_left,
					capacity_rec_vertical_45_right,
					capacity_rec_vertical_60_left,
					capacity_rec_vertical_60_right,
					capacity_rec_square_90,
					capacity_rec_square_45_left,
					capacity_rec_square_45_right,
					capacity_rec_square_60_left,
					capacity_rec_square_60_right,
					capacity_solid_90_round,
					capacity_solid_90_rec,
					capacity_solid_90_square,
					heading_one,
					heading_two,
					feature_one,
					feature_two,
					overview,
					operation,
					technical_features,
					optional_features,
					machine_accessory,
					machine_subcategory,
					machine_title,
					machine_code,
					manufacturer,
                    machine_type,
                    operation_type,
                    material_type,
                    materials,
                    blade_size,
                    blade_speed,
                    dimensions_speed,
                    power_supply,
                    filters,
                    spec_pre_bending,
                    spec_top_roll,
                    spec_bottom_roll,
                    spec_side_roll,
                    spec_bending_speed,
                    spec_rolls,
                    spec_shaft,
                    spec_max_section,
                    table_height,
                    table_size,
                    motor,
                    hydraulic_motor_type,
                    hydraulic_tank,
                    coolant_motor,
                    coolant_tank,
                    coolant_pump,
                    feeding_stroke,
                    weight,
                    machine_dimensions,
                    machine_dimensions_1,
                    machine_dimensions_2,
                    punching_1,
                    punching_2,
                    flatbar_shear_1,
                    flatbar_shear_2,
                    rectangular_notching,
                    triangular_notching,
                    angle_shear_90,
                    angle_shear_45,
                    bending,
                    solid_bar,
                    angle_shearing_power,
                    punching_power,
                    angle_shearing_tonnage,
                    angle_optional_blade,
                    throat_depth,
                    tonnage,
                    throat_capacity,
                    materials_hand,
                    mh_length,
                    mh_width
                
					)
					VALUES
					(
					
					:tblnam,
					:tbl_id,
					:prtnam,
					:prtdsc,
					:prtspc,
					:atr_id,
					:sta_id,
					:usestk,
					:unipri,
					:buypri,
					:delpri,
					:seourl,
					:seokey,
					:seodsc,
					:hompag,
					:prttag,
					:prtobj,
					:vat_id,
					:done,
					:blade_type,
					:spec_blade_size_1,
					:spec_blade_size_2,
					:spec_blade_size_3,
				
					:capacity_round_90,
					:capacity_round_45_left,
					:capacity_round_45_right,
					:capacity_round_60_left,
					:capacity_round_60_right,
					:capacity_rec_horizontal_90,
					:capacity_rec_horizontal_45_left,
					:capacity_rec_horizontal_45_right,
					:capacity_rec_horizontal_60_left,
					:capacity_rec_horizontal_60_right,
					:capacity_rec_vertical_90,
					:capacity_rec_vertical_45_left,
					:capacity_rec_vertical_45_right,
					:capacity_rec_vertical_60_left,
					:capacity_rec_vertical_60_right,
					:capacity_rec_square_90,
					:capacity_rec_square_45_left,
					:capacity_rec_square_45_right,
					:capacity_rec_square_60_left,
					:capacity_rec_square_60_right,
					:capacity_solid_90_round,
					:capacity_solid_90_rec,
					:capacity_solid_90_square,
					:heading_one,
					:heading_two,
					:feature_one,
					:feature_two,
					:overview,
					:operation,
					:technical_features,
					:optional_features,
					:machine_accessory,
					:machine_subcategory,
					:machine_title,
					:machine_code,
					:manufacturer,
                    :machine_type,
                    :operation_type,
                    :material_type,
                    :materials,
                    :blade_size,
                    :blade_speed,
                    :dimensions_speed,
                    :power_supply,
                    :filters,
                    :spec_pre_bending,
                    :spec_top_roll,
                    :spec_bottom_roll,
                    :spec_side_roll,
                    :spec_bending_speed,
                    :spec_rolls,
                    :spec_shaft,
                    :spec_max_section,
                    :table_height,
                    :table_size,
                    :motor,
                    :hydraulic_motor_type,
                    :hydraulic_tank,
                    :coolant_motor,
                    :coolant_tank,
                    :coolant_pump,
                    :feeding_stroke,
                    :weight,
                    :machine_dimensions,
                    :machine_dimensions_1,
                    :machine_dimensions_2,
                    :punching_1,
                    :punching_2,
                    :flatbar_shear_1,
                    :flatbar_shear_2,
                    :rectangular_notching,
                    :triangular_notching,
                    :angle_shear_90,
                    :angle_shear_45,
                    :bending,
                    :solid_bar,
                    :angle_shearing_power,
                    :punching_power,
                    :angle_shearing_tonnage,
                    :angle_optional_blade,
                    :throat_depth,
                    :tonnage,
                    :throat_capacity,
                    :materials_hand,
                    :mh_width,
                    :mh_length
                   
					);";

        } else {

            $qryArray["tblnam"] = $PrtCls->tblnam;
            $qryArray["tbl_id"] = $PrtCls->tbl_id;
            $qryArray["prt_id"] = $PrtCls->prt_id;
            $qryArray["prtnam"] = $PrtCls->prtnam;
            $qryArray["prtdsc"] = $PrtCls->prtdsc;
            $qryArray["prtspc"] = $PrtCls->prtspc;
            $qryArray["atr_id"] = $PrtCls->atr_id;
            $qryArray["sta_id"] = $PrtCls->sta_id;
            $qryArray["unipri"] = $PrtCls->unipri;
            $qryArray["buypri"] = $PrtCls->buypri;
            $qryArray["delpri"] = $PrtCls->delpri;
            $qryArray["usestk"] = $PrtCls->usestk;

            $qryArray["seourl"] = $PrtCls->seourl;
            $qryArray["seokey"] = $PrtCls->seokey;
            $qryArray["seodsc"] = $PrtCls->seodsc;
            $qryArray["hompag"] = $PrtCls->hompag;
            $qryArray["prttag"] = $PrtCls->prttag;
            $qryArray["prtobj"] = $PrtCls->prtobj;
            $qryArray["vat_id"] = $PrtCls->vat_id;
            $qryArray["done"] = $PrtCls->done;

            $qryArray["blade_type"] = $PrtCls->blade_type;
            $qryArray["spec_blade_size_1"] = $PrtCls->spec_blade_size_1;
            $qryArray["spec_blade_size_2"] = $PrtCls->spec_blade_size_2;
            $qryArray["spec_blade_size_3"] = $PrtCls->spec_blade_size_3;
            $qryArray["blade_speed"] = $PrtCls->blade_speed;
            $qryArray["capacity_round_90"] = $PrtCls->capacity_round_90;
            $qryArray["capacity_round_45_left"] = $PrtCls->capacity_round_45_left;
            $qryArray["capacity_round_45_right"] = $PrtCls->capacity_round_45_right;
            $qryArray["capacity_round_60_left"] = $PrtCls->capacity_round_60_left;
            $qryArray["capacity_round_60_right"] = $PrtCls->capacity_round_60_right;
            $qryArray["capacity_rec_horizontal_90"] = $PrtCls->capacity_rec_horizontal_90;
            $qryArray["capacity_rec_horizontal_45_left"] = $PrtCls->capacity_rec_horizontal_45_left;
            $qryArray["capacity_rec_horizontal_45_right"] = $PrtCls->capacity_rec_horizontal_45_right;
            $qryArray["capacity_rec_horizontal_60_left"] = $PrtCls->capacity_rec_horizontal_60_left;
            $qryArray["capacity_rec_horizontal_60_right"] = $PrtCls->capacity_rec_horizontal_60_right;
            $qryArray["capacity_rec_vertical_90"] = $PrtCls->capacity_rec_vertical_90;
            $qryArray["capacity_rec_vertical_45_left"] = $PrtCls->capacity_rec_vertical_45_left;
            $qryArray["capacity_rec_vertical_45_right"] = $PrtCls->capacity_rec_vertical_45_right;
            $qryArray["capacity_rec_vertical_60_left"] = $PrtCls->capacity_rec_vertical_60_left;
            $qryArray["capacity_rec_vertical_60_right"] = $PrtCls->capacity_rec_vertical_60_right;
            $qryArray["capacity_rec_square_90"] = $PrtCls->capacity_rec_square_90;
            $qryArray["capacity_rec_square_45_left"] = $PrtCls->capacity_rec_square_45_left;
            $qryArray["capacity_rec_square_45_right"] = $PrtCls->capacity_rec_square_45_right;
            $qryArray["capacity_rec_square_60_left"] = $PrtCls->capacity_rec_square_60_left;
            $qryArray["capacity_rec_square_60_right"] = $PrtCls->capacity_rec_square_60_right;
            $qryArray["capacity_solid_90_round"] = $PrtCls->capacity_solid_90_round;
            $qryArray["capacity_solid_90_rec"] = $PrtCls->capacity_solid_90_rec;
            $qryArray["capacity_solid_90_square"] = $PrtCls->capacity_solid_90_square;


            $qryArray["heading_one"] = $PrtCls->heading_one;
            $qryArray["heading_two"] = $PrtCls->heading_two;
            $qryArray["feature_one"] = $PrtCls->feature_one;
            $qryArray["feature_two"] = $PrtCls->feature_two;
            $qryArray["overview"] = $PrtCls->overview;
            $qryArray["operation"] = $PrtCls->operation;
            $qryArray["technical_features"] = $PrtCls->technical_features;
            $qryArray["optional_features"] = $PrtCls->optional_features;
            $qryArray["machine_accessory"] = $PrtCls->machine_accessory;
            $qryArray["machine_subcategory"] = $PrtCls->machine_subcategory;
            $qryArray["machine_title"] = $PrtCls->machine_title;
            $qryArray["machine_code"] = $PrtCls->machine_code;
            $qryArray["manufacturer"] = $PrtCls->manufacturer;
            $qryArray["machine_type"] = $PrtCls->machine_type;
            $qryArray["operation_type"] = $PrtCls->operation_type;
            $qryArray["material_type"] = $PrtCls->material_type;
            $qryArray["materials"] = $PrtCls->materials;
            $qryArray["blade_size"] = $PrtCls->blade_size;
            $qryArray["blade_speed"] = $PrtCls->blade_speed;
            $qryArray["dimensions_speed"] = $PrtCls->dimensions_speed;
            $qryArray["power_supply"] = $PrtCls->power_supply;
            $qryArray["filters"] = $PrtCls->filters;
            $qryArray["spec_pre_bending"] = $PrtCls->spec_pre_bending;
            $qryArray["spec_top_roll"] = $PrtCls->spec_top_roll;
            $qryArray["spec_bottom_roll"] = $PrtCls->spec_bottom_roll;
            $qryArray["spec_side_roll"] = $PrtCls->spec_side_roll;
            $qryArray["spec_bending_speed"] = $PrtCls->spec_bending_speed;
            $qryArray["spec_rolls"] = $PrtCls->spec_rolls;
            $qryArray["spec_shaft"] = $PrtCls->spec_shaft;
            $qryArray["spec_max_section"] = $PrtCls->spec_max_section;
            $qryArray["table_height"] = $PrtCls->table_height;
            $qryArray["table_size"] = $PrtCls->table_size;
            $qryArray["motor"] = $PrtCls->motor;
            $qryArray["hydraulic_motor_type"] = $PrtCls->hydraulic_motor_type;
            $qryArray["hydraulic_tank"] = $PrtCls->hydraulic_tank;
            $qryArray["coolant_motor"] = $PrtCls->coolant_motor;
            $qryArray["coolant_tank"] = $PrtCls->coolant_tank;
            $qryArray["coolant_pump"] = $PrtCls->coolant_pump;
            $qryArray["feeding_stroke"] = $PrtCls->feeding_stroke;
            $qryArray["weight"] = $PrtCls->weight;
            $qryArray["machine_dimensions"] = $PrtCls->machine_dimensions;
            $qryArray["machine_dimensions_1"] = $PrtCls->machine_dimensions_1;
            $qryArray["machine_dimensions_2"] = $PrtCls->machine_dimensions_2;
            $qryArray["punching_1"] = $PrtCls->punching_1;
            $qryArray["punching_2"] = $PrtCls->punching_2;
            $qryArray["flatbar_shear_1"] = $PrtCls->flatbar_shear_1;
            $qryArray["flatbar_shear_2"] = $PrtCls->flatbar_shear_2;
            $qryArray["rectangular_notching"] = $PrtCls->rectangular_notching;
            $qryArray["triangular_notching"] = $PrtCls->triangular_notching;
            $qryArray["angle_shear_90"] = $PrtCls->angle_shear_90;
            $qryArray["angle_shear_45"] = $PrtCls->angle_shear_45;
            $qryArray["bending"] = $PrtCls->bending;
            $qryArray["solid_bar"] = $PrtCls->solid_bar;
            $qryArray["angle_shearing_power"] = $PrtCls->angle_shearing_power;
            $qryArray["punching_power"] = $PrtCls->punching_power;
            $qryArray["angle_shearing_tonnage"] = $PrtCls->angle_shearing_tonnage;
            $qryArray["angle_optional_blade"] = $PrtCls->angle_optional_blade;
            $qryArray["throat_depth"] = $PrtCls->throat_depth;
            $qryArray["tonnage"] = $PrtCls->tonnage;
            $qryArray["throat_capacity"] = $PrtCls->throat_capacity;
            $qryArray["materials_hand"] = $PrtCls->materials_hand;
            $qryArray["mh_length"] = $PrtCls->mh_length;
            $qryArray["mh_width"] = $PrtCls->mh_width;


            $sql = "UPDATE producttypes
					SET
					
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					prt_id = :prt_id,
					prtnam = :prtnam,
					prtdsc = :prtdsc,
					prtspc = :prtspc,
					atr_id = :atr_id,
					sta_id = :sta_id,
					unipri = :unipri,
					buypri = :buypri,
					delpri = :delpri,
					usestk = :usestk,
					seourl = :seourl,
					seokey = :seokey,
					seodsc = :seodsc,
					hompag = :hompag,
					prttag = :prttag,
					prtobj = :prtobj,
					vat_id = :vat_id,		
					done = :done,		
	
					blade_type = :blade_type,
					spec_blade_size_1 = :spec_blade_size_1,
					spec_blade_size_2 = :spec_blade_size_2,
					spec_blade_size_3 = :spec_blade_size_3,
					blade_speed = :blade_speed,
					capacity_round_90 = :capacity_round_90,
					capacity_round_45_left = :capacity_round_45_left,
					capacity_round_45_right = :capacity_round_45_right,
					capacity_round_60_left = :capacity_round_60_left,
					capacity_round_60_right = :capacity_round_60_right,
					capacity_rec_horizontal_90 = :capacity_rec_horizontal_90,
					capacity_rec_horizontal_45_left = :capacity_rec_horizontal_45_left,
					capacity_rec_horizontal_45_right = :capacity_rec_horizontal_45_right,
					capacity_rec_horizontal_60_left = :capacity_rec_horizontal_60_left,
					capacity_rec_horizontal_60_right = :capacity_rec_horizontal_60_right,
					capacity_rec_vertical_90 = :capacity_rec_vertical_90,
					capacity_rec_vertical_45_left = :capacity_rec_vertical_45_left,
					capacity_rec_vertical_45_right = :capacity_rec_vertical_45_right,
					capacity_rec_vertical_60_left = :capacity_rec_vertical_60_left,
					capacity_rec_vertical_60_right = :capacity_rec_vertical_60_right,
					capacity_rec_square_90 = :capacity_rec_square_90,
					capacity_rec_square_45_left = :capacity_rec_square_45_left,
					capacity_rec_square_45_right = :capacity_rec_square_45_right,
					capacity_rec_square_60_left = :capacity_rec_square_60_left,
					capacity_rec_square_60_right = :capacity_rec_square_60_right,
					capacity_solid_90_round = :capacity_solid_90_round,
					capacity_solid_90_rec = :capacity_solid_90_rec,
					capacity_solid_90_square = :capacity_solid_90_square,
			
					
					heading_one = :heading_one,
					heading_two = :heading_two,
					feature_one = :feature_one,
					feature_two = :feature_two,
					overview = :overview,
					operation = :operation,
					technical_features = :technical_features,
					optional_features = :optional_features,
					machine_accessory = :machine_accessory,
					machine_subcategory = :machine_subcategory,
					machine_title = :machine_title,
					machine_code = :machine_code,
					manufacturer = :manufacturer,
                    machine_type = :machine_type,
                    operation_type = :operation_type,
                    material_type = :material_type,
                    materials = :materials,
                    blade_size = :blade_size,
                    blade_speed = :blade_speed,
                    dimensions_speed = :dimensions_speed,
                    power_supply = :power_supply,
                    filters = :filters,
                    spec_pre_bending = :spec_pre_bending,
                    spec_top_roll = :spec_top_roll,
                    spec_bottom_roll = :spec_bottom_roll,
                    spec_side_roll = :spec_side_roll,
                    spec_bending_speed = :spec_bending_speed,
                    spec_rolls = :spec_rolls,
                    spec_shaft = :spec_shaft,
                    spec_max_section = :spec_max_section,
                    table_height = :table_height,
                    table_size = :table_size,
                    motor = :motor,
                    hydraulic_motor_type = :hydraulic_motor_type,
                    hydraulic_tank = :hydraulic_tank,
                    coolant_motor = :coolant_motor,
                    coolant_tank = :coolant_tank,
                    coolant_pump = :coolant_pump,
                    feeding_stroke = :feeding_stroke,
                    weight = :weight,
                    machine_dimensions = :machine_dimensions,
                    machine_dimensions_1 = :machine_dimensions_1,
                    machine_dimensions_2 = :machine_dimensions_2,
                    punching_1 = :punching_1,
                    punching_2 = :punching_2,
                    flatbar_shear_1 = :flatbar_shear_1,
                    flatbar_shear_2 = :flatbar_shear_2,
                    rectangular_notching = :rectangular_notching,
                    triangular_notching = :triangular_notching,
                    angle_shear_90 = :angle_shear_90,
                    angle_shear_45 = :angle_shear_45,
                    bending = :bending,
                    solid_bar = :solid_bar,
                    angle_shearing_power = :angle_shearing_power,
                    punching_power = :punching_power,
                    angle_shearing_tonnage = :angle_shearing_tonnage,
                    angle_optional_blade = :angle_optional_blade,
                    throat_depth = :throat_depth,
                    tonnage = :tonnage,
                    throat_capacity = :throat_capacity,
                    materials_hand = :materials_hand,
                    mh_length = :mh_length,
                    mh_width = :mh_width
					";

            $sql .= " WHERE prt_id = :prt_id";
            $qryArray["prt_id"] = $PrtCls->prt_id;

        }

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($PrtCls->prt_id == 0) ? $this->dbConn->lastInsertId('prt_id') : $PrtCls->prt_id;
    }

    function delete($Prt_ID = null)
    {

        try {

            if (!is_null($Prt_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM producttypes WHERE prt_id = :prt_id ';
                $qryArray["prt_id"] = $Prt_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);


                $qryArray = array();
                $sql = 'DELETE FROM products WHERE prt_id = :prt_id ';
                $qryArray["prt_id"] = $Prt_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                //
                // DELETE ATTRIBUTES
                //

                //
                // DELETE IMAGES
                //

                $qryArray = array();
                $sql = "DELETE FROM uploads WHERE tblnam = 'PRDTYPE' AND tbl_id = :prt_id";
                $qryArray["prt_id"] = $Prt_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Prt_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function updatePrices($Prt_ID = null, $UniPri = null, $BuyPri = null, $DelPri = null, $InsPri = null)
    {

        try {

            if (
                !is_null($Prt_ID) &&
                (is_numeric($UniPri) || is_numeric($BuyPri) || is_numeric($DelPri) || is_numeric($InsPri))
            ) {
                $qryArray = array();
                $sql = 'UPDATE products SET ';

                if (is_numeric($UniPri)) {
                    $sql .= ' unipri = :unipri, ';
                    $qryArray["unipri"] = $UniPri;
                }

                if (is_numeric($BuyPri)) {
                    $sql .= ' buypri = :buypri, ';
                    $qryArray["buypri"] = $BuyPri;
                }

                if (is_numeric($DelPri)) {
                    $sql .= ' delpri = :delpri, ';
                    $qryArray["delpri"] = $DelPri;
                }

                if (is_numeric($InsPri)) {
                    $sql .= ' inspri = :inspri, ';
                    $qryArray["inspri"] = $InsPri;
                }

                // blank update for SQL query
                $sql .= ' on_ord = 0 ';

                $sql .= ' WHERE prt_id = :prt_id AND unipri = :hlduni';
                $qryArray["prt_id"] = $Prt_ID;
                $qryArray["hlduni"] = $UniPri;

                //echo $sql;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}

?>
