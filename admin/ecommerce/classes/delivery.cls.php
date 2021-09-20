<?php

//
// Delivery Info class
//

class DelDAO extends db {
	
	function select($Del_ID = NULL, $DelCod=NULL, $DelDis=NULL, $Sta_ID=NULL, $ReqObj=false, $DelTyp=NULL) {

		$qryArray = array();
		$sql = 'SELECT
                del_id,
                delnam,
                delpri,
                delcod,
                deltyp,
                deldis,
                maxdis,
                sta_id
				FROM delivery WHERE TRUE';
		
		if (!is_null($Del_ID)) {
            $sql .= ' AND del_id = :del_id ';
			$qryArray["del_id"] = $Del_ID;
		} else {

            if (!is_null($DelDis)) {
                $sql .= ' AND (deldis <= :deldis AND maxdis >= :deldis) ';
                $qryArray["deldis"] = $DelDis;
            }


			if (!is_null($DelCod)) {

//                $sql .= ' AND delcod = :delcod ';
//				$qryArray["delcod"] = $DelCod;

                $sql .= ' AND delcod RLIKE :delcod ';
                $qryArray["delcod"] = '[[:<:]]' . $DelCod. '[[:>:]]';

			}


			if (!is_null($Sta_ID) && is_numeric($Sta_ID)) {
				$sql .= ' AND sta_id = :sta_id ';
				$qryArray["sta_id"] = $Sta_ID;
			}

            if (!is_null($DelTyp)) {
                $sql .= ' AND deltyp = :deltyp ';
                $qryArray["deltyp"] = $DelTyp;
            }

//            if (!is_null($DelDis)) {
//                $sql .= ' ORDER BY deldis DESC LIMIT 1';
//            } else {
                $sql .= ' ORDER BY delpri ASC';
            }
//		}
		
//		echo '<p>'.$sql.'</p>';
//        var_dump($qryArray,true);

		return $this->run($sql, $qryArray, $ReqObj);

	}

    function getCountryRecords() {

        $qryArray = array();
        $sql = 'SELECT * FROM delivery WHERE TRUE';

        $deliveryRecs = $this->run($sql, $qryArray, false);

        $countrylist = array();
        $countrycheck = array();
        for ($i=0;$i<count($deliveryRecs);$i++) {

            $countries = explode(",",$deliveryRecs[$i]['delcod']);

            for ($c=0;$c<count($countries);$c++) {

                if ( !in_array($countries[$c],$countrycheck) ) {

                    $countryRec = new stdClass();
                    $countryRec->coucod = $countries[$c];
                    $countryRec->counam = $this->convertCodeToCountry($countries[$c]);

                    array_push($countrycheck, $countries[$c]);
                    array_push($countrylist, $countryRec);

                }

            }

        }

        return $countrylist;

    }

	function update($DelCls = NULL) {
	
		if (is_null($DelCls) || !$DelCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($DelCls->del_id == 0) {

			$qryArray["delnam"] = $DelCls->delnam;
			$qryArray["delpri"] = $DelCls->delpri;
			$qryArray["delcod"] = $DelCls->delcod;
			$qryArray["deltyp"] = $DelCls->deltyp;
			$qryArray["deldis"] = $DelCls->deldis;
			$qryArray["maxdis"] = $DelCls->maxdis;
			$qryArray["sta_id"] = $DelCls->sta_id;
			
			$sql = "INSERT INTO delivery
					(

					delnam,
					delpri,
					delcod,
					deltyp,
					deldis,
					maxdis,
					sta_id
					
					)
					VALUES
					(
					
					:delnam,
					:delpri,
					:delcod,
					:deltyp,
					:deldis,
					:maxdis,
					:sta_id
					
					);";

					
						
		} else {

            $qryArray["delnam"] = $DelCls->delnam;
            $qryArray["delpri"] = $DelCls->delpri;
            $qryArray["delcod"] = $DelCls->delcod;
            $qryArray["deltyp"] = $DelCls->deltyp;
            $qryArray["deldis"] = $DelCls->deldis;
            $qryArray["maxdis"] = $DelCls->maxdis;
            $qryArray["sta_id"] = $DelCls->sta_id;
			
			$sql = "UPDATE delivery
					SET
					
					delnam = :delnam,
					delpri = :delpri,
					delcod = :delcod,
					deltyp = :deltyp,
					deldis = :deldis,
					maxdis = :maxdis,
					sta_id = :sta_id";
				
			$sql .= " WHERE del_id = :del_id";
			$qryArray["del_id"] = $DelCls->del_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($DelCls->del_id == 0) ? $this->dbConn->lastInsertId('del_id') : $DelCls->del_id;
	}
	
	function delete($Del_ID = NULL)
    {

        try {

            if (!is_null($Del_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM delivery WHERE del_id = :del_id ';
                $qryArray["del_id"] = $Del_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Del_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function convertCodeToCountry($iCouCod=NULL) {

        if ($iCouCod=="AF") return 'Afghanistan';
        if ($iCouCod=="AL") return 'Albania';
        if ($iCouCod=="DZ") return 'Algeria';
        if ($iCouCod=="AS") return 'American Samoa';
        if ($iCouCod=="AD") return 'Andorra';
        if ($iCouCod=="AO") return 'Angola';
        if ($iCouCod=="AI") return 'Anguilla';
        if ($iCouCod=="AQ") return 'Antarctica';
        if ($iCouCod=="AG") return 'Antigua and Barbuda';
        if ($iCouCod=="AR") return 'Argentina';
        if ($iCouCod=="AM") return 'Armenia';
        if ($iCouCod=="AW") return 'Aruba';
        if ($iCouCod=="AU") return 'Australia';
        if ($iCouCod=="AT") return 'Austria';
        if ($iCouCod=="AZ") return 'Azerbaijan';
        if ($iCouCod=="BS") return 'Bahamas';
        if ($iCouCod=="BH") return 'Bahrain';
        if ($iCouCod=="BD") return 'Bangladesh';
        if ($iCouCod=="BB") return 'Barbados';
        if ($iCouCod=="BY") return 'Belarus';
        if ($iCouCod=="BE") return 'Belgium';
        if ($iCouCod=="BZ") return 'Belize';
        if ($iCouCod=="BJ") return 'Benin';
        if ($iCouCod=="BM") return 'Bermuda';
        if ($iCouCod=="BT") return 'Bhutan';
        if ($iCouCod=="BO") return 'Bolivia';
        if ($iCouCod=="BA") return 'Bosnia and Herzegowina';
        if ($iCouCod=="BW") return 'Botswana';
        if ($iCouCod=="BV") return 'Bouvet Island';
        if ($iCouCod=="BR") return 'Brazil';
        if ($iCouCod=="IO") return 'British Indian Ocean Territory';
        if ($iCouCod=="BN") return 'Brunei Darussalam';
        if ($iCouCod=="BG") return 'Bulgaria';
        if ($iCouCod=="BF") return 'Burkina Faso';
        if ($iCouCod=="BI") return 'Burundi';
        if ($iCouCod=="KH") return 'Cambodia';
        if ($iCouCod=="CM") return 'Cameroon';
        if ($iCouCod=="CA") return 'Canada';
        if ($iCouCod=="CV") return 'Cape Verde';
        if ($iCouCod=="KY") return 'Cayman Islands';
        if ($iCouCod=="CF") return 'Central African Republic';
        if ($iCouCod=="TD") return 'Chad';
        if ($iCouCod=="CL") return 'Chile';
        if ($iCouCod=="CN") return 'China';
        if ($iCouCod=="CX") return 'Christmas Island';
        if ($iCouCod=="CC") return 'Cocos (Keeling) Islands';
        if ($iCouCod=="CO") return 'Colombia';
        if ($iCouCod=="KM") return 'Comoros';
        if ($iCouCod=="CG") return 'Congo';
        if ($iCouCod=="CD") return 'Congo, the Democratic Republic of the';
        if ($iCouCod=="CK") return 'Cook Islands';
        if ($iCouCod=="CR") return 'Costa Rica';
        if ($iCouCod=="CI") return 'Cote dIvoire';
        if ($iCouCod=="HR") return 'Croatia (Hrvatska)';
        if ($iCouCod=="CU") return 'Cuba';
        if ($iCouCod=="CY") return 'Cyprus';
        if ($iCouCod=="CZ") return 'Czech Republic';
        if ($iCouCod=="DK") return 'Denmark';
        if ($iCouCod=="DJ") return 'Djibouti';
        if ($iCouCod=="DM") return 'Dominica';
        if ($iCouCod=="DO") return 'Dominican Republic';
        if ($iCouCod=="TP") return 'East Timor';
        if ($iCouCod=="EC") return 'Ecuador';
        if ($iCouCod=="EG") return 'Egypt';
        if ($iCouCod=="SV") return 'El Salvador';
        if ($iCouCod=="GQ") return 'Equatorial Guinea';
        if ($iCouCod=="ER") return 'Eritrea';
        if ($iCouCod=="EE") return 'Estonia';
        if ($iCouCod=="ET") return 'Ethiopia';
        if ($iCouCod=="FK") return 'Falkland Islands (Malvinas)';
        if ($iCouCod=="FO") return 'Faroe Islands';
        if ($iCouCod=="FJ") return 'Fiji';
        if ($iCouCod=="FI") return 'Finland';
        if ($iCouCod=="FR") return 'France';
        if ($iCouCod=="FX") return 'France, Metropolitan';
        if ($iCouCod=="GF") return 'French Guiana';
        if ($iCouCod=="PF") return 'French Polynesia';
        if ($iCouCod=="TF") return 'French Southern Territories';
        if ($iCouCod=="GA") return 'Gabon';
        if ($iCouCod=="GM") return 'Gambia';
        if ($iCouCod=="GE") return 'Georgia';
        if ($iCouCod=="DE") return 'Germany';
        if ($iCouCod=="GH") return 'Ghana';
        if ($iCouCod=="GI") return 'Gibraltar';
        if ($iCouCod=="GR") return 'Greece';
        if ($iCouCod=="GL") return 'Greenland';
        if ($iCouCod=="GD") return 'Grenada';
        if ($iCouCod=="GP") return 'Guadeloupe';
        if ($iCouCod=="GU") return 'Guam';
        if ($iCouCod=="GT") return 'Guatemala';
        if ($iCouCod=="GN") return 'Guinea';
        if ($iCouCod=="GW") return 'Guinea-Bissau';
        if ($iCouCod=="GY") return 'Guyana';
        if ($iCouCod=="HT") return 'Haiti';
        if ($iCouCod=="HM") return 'Heard and Mc Donald Islands';
        if ($iCouCod=="VA") return 'Holy See (Vatican City State)';
        if ($iCouCod=="HN") return 'Honduras';
        if ($iCouCod=="HK") return 'Hong Kong';
        if ($iCouCod=="HU") return 'Hungary';
        if ($iCouCod=="IS") return 'Iceland';
        if ($iCouCod=="IN") return 'India';
        if ($iCouCod=="ID") return 'Indonesia';
        if ($iCouCod=="IR") return 'Iran (Islamic Republic of)';
        if ($iCouCod=="IQ") return 'Iraq';
        if ($iCouCod=="IE") return 'Ireland';
        if ($iCouCod=="IL") return 'Israel';
        if ($iCouCod=="IT") return 'Italy';
        if ($iCouCod=="JM") return 'Jamaica';
        if ($iCouCod=="JP") return 'Japan';
        if ($iCouCod=="JO") return 'Jordan';
        if ($iCouCod=="KZ") return 'Kazakhstan';
        if ($iCouCod=="KE") return 'Kenya';
        if ($iCouCod=="KI") return 'Kiribati';
        if ($iCouCod=="KP") return 'Korea, Democratic Peoples Republic of';
        if ($iCouCod=="KR") return 'Korea, Republic of';
        if ($iCouCod=="KW") return 'Kuwait';
        if ($iCouCod=="KG") return 'Kyrgyzstan';
        if ($iCouCod=="LA") return 'Lao Peoples Democratic Republic';
        if ($iCouCod=="LV") return 'Latvia';
        if ($iCouCod=="LB") return 'Lebanon';
        if ($iCouCod=="LS") return 'Lesotho';
        if ($iCouCod=="LR") return 'Liberia';
        if ($iCouCod=="LY") return 'Libyan Arab Jamahiriya';
        if ($iCouCod=="LI") return 'Liechtenstein';
        if ($iCouCod=="LT") return 'Lithuania';
        if ($iCouCod=="LU") return 'Luxembourg';
        if ($iCouCod=="MO") return 'Macau';
        if ($iCouCod=="MK") return 'Macedonia, The Former Yugoslav Republic of';
        if ($iCouCod=="MG") return 'Madagascar';
        if ($iCouCod=="MW") return 'Malawi';
        if ($iCouCod=="MY") return 'Malaysia';
        if ($iCouCod=="MV") return 'Maldives';
        if ($iCouCod=="ML") return 'Mali';
        if ($iCouCod=="MT") return 'Malta';
        if ($iCouCod=="MH") return 'Marshall Islands';
        if ($iCouCod=="MQ") return 'Martinique';
        if ($iCouCod=="MR") return 'Mauritania';
        if ($iCouCod=="MU") return 'Mauritius';
        if ($iCouCod=="YT") return 'Mayotte';
        if ($iCouCod=="MX") return 'Mexico';
        if ($iCouCod=="FM") return 'Micronesia, Federated States of';
        if ($iCouCod=="MD") return 'Moldova, Republic of';
        if ($iCouCod=="MC") return 'Monaco';
        if ($iCouCod=="MN") return 'Mongolia';
        if ($iCouCod=="MS") return 'Montserrat';
        if ($iCouCod=="MA") return 'Morocco';
        if ($iCouCod=="MZ") return 'Mozambique';
        if ($iCouCod=="MM") return 'Myanmar';
        if ($iCouCod=="NA") return 'Namibia';
        if ($iCouCod=="NR") return 'Nauru';
        if ($iCouCod=="NP") return 'Nepal';
        if ($iCouCod=="NL") return 'Netherlands';
        if ($iCouCod=="AN") return 'Netherlands Antilles';
        if ($iCouCod=="NC") return 'New Caledonia';
        if ($iCouCod=="NZ") return 'New Zealand';
        if ($iCouCod=="NI") return 'Nicaragua';
        if ($iCouCod=="NE") return 'Niger';
        if ($iCouCod=="NG") return 'Nigeria';
        if ($iCouCod=="NU") return 'Niue';
        if ($iCouCod=="NF") return 'Norfolk Island';
        if ($iCouCod=="MP") return 'Northern Mariana Islands';
        if ($iCouCod=="NO") return 'Norway';
        if ($iCouCod=="OM") return 'Oman';
        if ($iCouCod=="PK") return 'Pakistan';
        if ($iCouCod=="PW") return 'Palau';
        if ($iCouCod=="PA") return 'Panama';
        if ($iCouCod=="PG") return 'Papua New Guinea';
        if ($iCouCod=="PY") return 'Paraguay';
        if ($iCouCod=="PE") return 'Peru';
        if ($iCouCod=="PH") return 'Philippines';
        if ($iCouCod=="PN") return 'Pitcairn';
        if ($iCouCod=="PL") return 'Poland';
        if ($iCouCod=="PT") return 'Portugal';
        if ($iCouCod=="PR") return 'Puerto Rico';
        if ($iCouCod=="QA") return 'Qatar';
        if ($iCouCod=="RE") return 'Reunion';
        if ($iCouCod=="RO") return 'Romania';
        if ($iCouCod=="RU") return 'Russian Federation';
        if ($iCouCod=="RW") return 'Rwanda';
        if ($iCouCod=="KN") return 'Saint Kitts and Nevis';
        if ($iCouCod=="LC") return 'Saint LUCIA';
        if ($iCouCod=="VC") return 'Saint Vincent and the Grenadines';
        if ($iCouCod=="WS") return 'Samoa';
        if ($iCouCod=="SM") return 'San Marino';
        if ($iCouCod=="ST") return 'Sao Tome and Principe';
        if ($iCouCod=="SA") return 'Saudi Arabia';
        if ($iCouCod=="SN") return 'Senegal';
        if ($iCouCod=="SC") return 'Seychelles';
        if ($iCouCod=="SL") return 'Sierra Leone';
        if ($iCouCod=="SG") return 'Singapore';
        if ($iCouCod=="SK") return 'Slovakia (Slovak Republic)';
        if ($iCouCod=="SI") return 'Slovenia';
        if ($iCouCod=="SB") return 'Solomon Islands';
        if ($iCouCod=="SO") return 'Somalia';
        if ($iCouCod=="ZA") return 'South Africa';
        if ($iCouCod=="GS") return 'South Georgia and the South Sandwich Islands';
        if ($iCouCod=="ES") return 'Spain';
        if ($iCouCod=="LK") return 'Sri Lanka';
        if ($iCouCod=="SH") return 'St. Helena';
        if ($iCouCod=="PM") return 'St. Pierre and Miquelon';
        if ($iCouCod=="SD") return 'Sudan';
        if ($iCouCod=="SR") return 'Suriname';
        if ($iCouCod=="SJ") return 'Svalbard and Jan Mayen Islands';
        if ($iCouCod=="SZ") return 'Swaziland';
        if ($iCouCod=="SE") return 'Sweden';
        if ($iCouCod=="CH") return 'Switzerland';
        if ($iCouCod=="SY") return 'Syrian Arab Republic';
        if ($iCouCod=="TW") return 'Taiwan, Province of China';
        if ($iCouCod=="TJ") return 'Tajikistan';
        if ($iCouCod=="TZ") return 'Tanzania, United Republic of';
        if ($iCouCod=="TH") return 'Thailand';
        if ($iCouCod=="TG") return 'Togo';
        if ($iCouCod=="TK") return 'Tokelau';
        if ($iCouCod=="TO") return 'Tonga';
        if ($iCouCod=="TT") return 'Trinidad and Tobago';
        if ($iCouCod=="TN") return 'Tunisia';
        if ($iCouCod=="TR") return 'Turkey';
        if ($iCouCod=="TM") return 'Turkmenistan';
        if ($iCouCod=="TC") return 'Turks and Caicos Islands';
        if ($iCouCod=="TV") return 'Tuvalu';
        if ($iCouCod=="UG") return 'Uganda';
        if ($iCouCod=="UA") return 'Ukraine';
        if ($iCouCod=="AE") return 'United Arab Emirates';
        if ($iCouCod=="GB") return 'United Kingdom';
        if ($iCouCod=="US") return 'United States';
        if ($iCouCod=="UM") return 'United States Minor Outlying Islands';
        if ($iCouCod=="UY") return 'Uruguay';
        if ($iCouCod=="UZ") return 'Uzbekistan';
        if ($iCouCod=="VU") return 'Vanuatu';
        if ($iCouCod=="VE") return 'Venezuela';
        if ($iCouCod=="VN") return 'Viet Nam';
        if ($iCouCod=="VG") return 'Virgin Islands (British)';
        if ($iCouCod=="VI") return 'Virgin Islands (U.S.)';
        if ($iCouCod=="WF") return 'Wallis and Futuna Islands';
        if ($iCouCod=="EH") return 'Western Sahara';
        if ($iCouCod=="YE") return 'Yemen';
        if ($iCouCod=="YU") return 'Yugoslavia';
        if ($iCouCod=="ZM") return 'Zambia';
        if ($iCouCod=="ZW") return 'Zimbabwe';

    }


    function inEurope($iCouCod=NULL) {

        if ($iCouCod=="DE") return true; //'Germany';
        if ($iCouCod=="IT") return true; //'Italy';
        if ($iCouCod=="PL") return true; //'Poland';
        if ($iCouCod=="GB") return true; //'United Kingdom';
        if ($iCouCod=="FR") return true; //'France';
        if ($iCouCod=="FX") return true; //'France, Metropolitan';
        if ($iCouCod=="GF") return true; //'French Guiana';
        if ($iCouCod=="PF") return true; //'French Polynesia';
        if ($iCouCod=="TF") return true; //'French Southern Territories';
        if ($iCouCod=="RO") return true; //'Romania';
        if ($iCouCod=="SE") return true; //'Sweden';
        if ($iCouCod=="GR") return true; //'Greece';
        if ($iCouCod=="ES") return true; //'Spain';
        if ($iCouCod=="AT") return true; //'Austria';
        if ($iCouCod=="HU") return true; //'Hungary';
        if ($iCouCod=="BG") return true; //'Bulgaria';
        if ($iCouCod=="FI") return true; //'Finland';
        if ($iCouCod=="CZ") return true; //'Czech Republic';
        if ($iCouCod=="NL") return true; //'Netherlands';
        if ($iCouCod=="NO") return true; //'Norway';
        if ($iCouCod=="HR") return true; //'Croatia (Hrvatska)';
        if ($iCouCod=="LT") return true; //'Lithuania';
        if ($iCouCod=="IE") return true; //'Ireland';
        if ($iCouCod=="BE") return true; //'Belgium';
        if ($iCouCod=="CY") return true; //'Cyprus';
        if ($iCouCod=="SK") return true; //'Slovakia (Slovak Republic)';
        if ($iCouCod=="SI") return true; //'Slovenia';
        if ($iCouCod=="MT") return true; //'Malta';
        if ($iCouCod=="PT") return true; //'Portugal';
        if ($iCouCod=="EE") return true; //'Estonia';
        if ($iCouCod=="SI") return true; //'Slovenia';
        if ($iCouCod=="LV") return true; //'Latvia';
        if ($iCouCod=="DK") return true; //'Denmark';

    }
	
}

?>