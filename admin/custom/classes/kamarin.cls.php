<?php

define('HOST', "localhost");
define('USER', "root");
define('PASS', "");
define('DATABASE1', "kamarin");

class kamarinClass extends db {


    private $orderRec = NULL;
    private $orderLines = NULL;
    private $productRec = NULL;

    private $DATABASE1 = NULL;

    function __construct() {

        $this->DATABASE1  = mysqli_connect(HOST, USER, PASS, DATABASE1);

    }

    function createOrderFromID($Ord_ID) {

        /***
         *
         * Select Order From Database and create kamarin order details
         *
         */

        $this->selectOrder($Ord_ID);
        $this->selectOrderLines($Ord_ID);


        $sql = '';

        $qryArray = array();

        if ($this->orderRec->ord_id != 0) {


            /***
             *
             * Create New Customer Record If Required
             *
             */

//            if ($loggedIn) {
//
//                $CusCls = new stdClass();
//                $qryArray["customer_id"] = $CusCls->customer_id;
//                $qryArray["account_code"] = $CusCls->account_code;
//                $qryArray["name"] = $CusCls->name;
//                $qryArray["address1"] = $CusCls->address1;
//                $qryArray["address2"] = $CusCls->address2;
//                $qryArray["address3"] = $CusCls->address3;
//                $qryArray["address4"] = $CusCls->address4;
//                $qryArray["address5"] = $CusCls->address5;
//                $qryArray["postcode"] = $CusCls->postcode;
//                $qryArray["telephone_number"] = $CusCls->telephone_number;
//                $qryArray["fax_number"] = $CusCls->fax_number;
//                $qryArray["mobile_number"] = $CusCls->mobile_number;
//                $qryArray["email_address"] = $CusCls->email_address;
//                $qryArray["cost_centre"] = $CusCls->cost_centre;
//                $qryArray["department"] = $CusCls->department;
//                $qryArray["password"] = $CusCls->password;
//                $qryArray["currency_code"] = $CusCls->currency_code;
//                $qryArray["vat_reg_number"] = $CusCls->vat_reg_number;
//                $qryArray["contact_name"] = $CusCls->contact_name;
//                $qryArray["record_updated"] = $CusCls->record_updated;
//
//            }


            /***
             * Create Order Record
             */

            $qryArray["order_number"]               = $this->orderRec->ord_id;
            $qryArray["order_date"]                 = $this->orderRec->invdat;
            $qryArray["invoice_name"]               = $this->orderRec->cusnam;
            $qryArray["invoice_address_1"]          = $this->orderRec->payadr1;
            $qryArray["invoice_address_2"]          = $this->orderRec->payadr2;
            $qryArray["delivery_address_3"]         = $this->orderRec->payadr3;
            $qryArray["invoice_address_4"]          = $this->orderRec->payadr4;
            $qryArray["invoice_address_5"]          = $this->orderRec->coucod;
            $qryArray["invoice_postcode"]           = $this->orderRec->paypstcod;
            $qryArray["delivery_name"]              = $this->orderRec->cusnam;
            $qryArray["delivery_address_1"]         = $this->orderRec->adr1;
            $qryArray["delivery_address_2"]         = $this->orderRec->adr2;
            $qryArray["delivery_address_3"]         = $this->orderRec->adr3;
            $qryArray["delivery_address_4"]         = $this->orderRec->adr4;
            $qryArray["delivery_address_5"]         = $this->orderRec->coucod;
            $qryArray["delivery_postcode"]          = $this->orderRec->pstcod;
            $qryArray["delivery_telephone_number"]  = $this->orderRec->delivery_telephone_number;
            $qryArray["delivery_fax_number"]        = $this->orderRec->delivery_fax_number;
            $qryArray["email_address"]              = $this->orderRec->emaadr;
            $qryArray["sales_ledger_account_code"]  = ''; //$this->orderRec->sales_ledger_account_code;
            $qryArray["comments"]                   = ''; //$this->orderRec->comments;
            $qryArray["customer_reference_number"]  = ''; //$this->orderRec->customer_reference_number;
            $qryArray["shipping_method"]            = ''; //$this->orderRec->shipping_method;   DELIVERY DETAIL
            $qryArray["payment_with_order"]         = $this->orderRec->payment_with_order;
            $qryArray["payment_method"]             = $this->orderRec->altnam;
            $qryArray["location_code"]              = ''; //$this->orderRec->location_code;
            $qryArray["currency_code"]              = 'GBP'; //$this->orderRec->currency_code;
            $qryArray["order_gross_total"]          = 0.00; //$this->orderRec->order_gross_total;
            $qryArray["user_field_1"]               = '1'; //$this->orderRec->user_field_1;
            $qryArray["user_field_2"]               = '2'; //$this->orderRec->user_field_2;
            $qryArray["user_field_3"]               = '3'; //$this->orderRec->user_field_3;
            $qryArray["user_field_4"]               = '4'; //$this->orderRec->user_field_4;
            $qryArray["user_field_5"]               = '5'; //$this->orderRec->user_field_5;
            $qryArray["user_field_6"]               = '6'; //$this->orderRec->user_field_6;
            $qryArray["user_field_7"]               = '7'; //$this->orderRec->user_field_7;
            $qryArray["user_field_8"]               = '8'; //$this->orderRec->user_field_8;
            $qryArray["user_field_9"]               = ''; //$this->orderRec->user_field_9;
            $qryArray["user_field_10"]              = ''; //$this->orderRec->user_field_10;
            $qryArray["exchange_rate"]              = ''; //$this->orderRec->exchange_rate;
            $qryArray["payment_code_1"]             = ''; //$this->orderRec->payment_code_1;
            $qryArray["payment_value_1"]            = ''; //$this->orderRec->payment_value_1;
            $qryArray["payment_code_2"]             = ''; //$this->orderRec->payment_code_2;
            $qryArray["payment_value_2"]            = ''; //$this->orderRec->payment_value_2;
            $qryArray["payment_code_3"]             = ''; //$this->orderRec->payment_code_3;
            $qryArray["payment_value_3"]            = ''; //$this->orderRec->payment_value_3;
            $qryArray["payment_code_4"]             = ''; //$this->orderRec->payment_code_4;
            $qryArray["payment_value_4"]            = ''; //$this->orderRec->payment_value_4;
            $qryArray["payment_code_5"]             = ''; //$this->orderRec->payment_code_5;
            $qryArray["payment_value_5"]            = ''; //$this->orderRec->payment_value_5;
            $qryArray["discount_percentage"]        = ''; //$this->orderRec->discount_percentage;   DISCOUNT DETAIL
            $qryArray["tag_number"]                 = ''; //$this->orderRec->tag_number;
            $qryArray["record_downloaded"]          = ''; //$this->orderRec->record_downloaded;

            $sql = "INSERT INTO order_headers
					(
					
					order_number,
					order_date,
					invoice_name,
					invoice_address_1,
					invoice_address_2,
					delivery_address_3,
					invoice_address_4,
					invoice_address_5,
					invoice_postcode,
					delivery_name,
					delivery_address_1,
					delivery_address_2,
					delivery_address_4,
					delivery_address_5,
					delivery_postcode,
                    delivery_telephone_number,
                    delivery_fax_number,
                    email_address,
                    sales_ledger_account_code,
                    comments,
                    customer_reference_number,
                    shipping_method,
                    payment_with_order,
                    payment_method,
                    location_code,
                    currency_code,
                    order_gross_total,
                    user_field_1,
                    user_field_2,
                    user_field_3,
                    user_field_4,
                    user_field_5,
                    user_field_6,
                    user_field_7,
                    user_field_8,
                    user_field_9,
                    user_field_10,
                    exchange_rate,
                    payment_code_1,
                    payment_value_1,
                    payment_code_2,
                    payment_value_2,
                    payment_code_3,
                    payment_value_3,
                    payment_code_4,
                    payment_value_4,
                    payment_code_5,
                    payment_value_5,
                    discount_percentage,
                    tag_number,
                    record_downloaded
					)
					VALUES
					(
					:order_number,
					:order_date,
					:invoice_name,
					:invoice_address_1,
					:invoice_address_2,
					:delivery_address_3,
					:invoice_address_4,
					:invoice_address_5,
					:invoice_postcode,
					:delivery_name,
					:delivery_address_1,
					:delivery_address_2,
					:delivery_address_4,
					:delivery_address_5,
					:delivery_postcode,
                    :delivery_telephone_number,
                    :delivery_fax_number,
                    :email_address,
                    :sales_ledger_account_code,
                    :comments,
                    :customer_reference_number,
                    :shipping_method,
                    :payment_with_order,
                    :payment_method,
                    :location_code,
                    :currency_code,
                    :order_gross_total,
                    :user_field_1,
                    :user_field_2,
                    :user_field_3,
                    :user_field_4,
                    :user_field_5,
                    :user_field_6,
                    :user_field_7,
                    :user_field_8,
                    :user_field_9,
                    :user_field_10,
                    :exchange_rate,
                    :payment_code_1,
                    :payment_value_1,
                    :payment_code_2,
                    :payment_value_2,
                    :payment_code_3,
                    :payment_value_3,
                    :payment_code_4,
                    :payment_value_4,
                    :payment_code_5,
                    :payment_value_5,
                    :discount_percentage,
                    :tag_number,
                    :record_downloaded
					);";


            $runSql = $this->buildQuery($sql, $qryArray);

            echo $runSql;

            $RESULT_QUERY = mysqli_query($this->DATABASE1, $runSql);
            $Ord_ID = mysqli_insert_id($this->DATABASE1);




//            $recordSet = $this->dbConn->prepare($sql);
//            $recordSet->execute($qryArray);
//
//            $Ord_ID = ($this->orderRec->order_header_id == 0) ? $this->dbConn->lastInsertId('order_header_id') : $this->orderRec->order_header_id;
//
//            /***
//             *
//             * Order Line Creation
//             *
//            ***/
//
//            for ($i = 0; $i <count($this->orderLines); $i++) {
//
//                /***
//                 * FIND PRODUCT
//                 */
//
//                $OlnCls = new stdClass();
//                $qryArray["order_details_id"]           = NULL; //$OlnCls->order_details_id;
//                $qryArray["order_header_id"]            = $Ord_ID;
//                $qryArray["stock_code"]                 = $this->orderLines[$i]['prd_id']; //$OlnCls->stock_code;
//                $qryArray["description"]                = $this->orderLines[$i]['olndsc']; //$OlnCls->description;
//                $qryArray["unit_nett_price"]            = $this->orderLines[$i]['unipri']; //$OlnCls->unit_nett_price;
//                $qryArray["quantity_sold"]              = $this->orderLines[$i]['qty']; //$OlnCls->quantity_sold;
//                $qryArray["line_nett_value"]            = $this->orderLines[$i]['']; //$OlnCls->line_nett_value;
//                $qryArray["line_vat_value"]             = $this->orderLines[$i]['']; //$OlnCls->line_vat_value;
//                $qryArray["vat_code"]                   = $this->orderLines[$i]['vat_id']; //$OlnCls->vat_code;
//                $qryArray["vat_rate"]                   = $this->orderLines[$i]['vatrat']; //$OlnCls->vat_rate;
//                $qryArray["original_web_order_line_id"] = $this->orderLines[$i]['oln_id']; //$OlnCls->original_web_order_line_id;
//                $qryArray["location_code"]              = $this->orderLines[$i]['pla_id']; //$OlnCls->location_code;
//
//                $recordSet = $this->dbConn->prepare($sql);
//                $recordSet->execute($qryArray);
//
//            }



        } elseif ( 1 == 2) {

            /***
             * Update should not be run
             */



            $qryArray["order_number"]               = $this->orderRec->ord_id;
            $qryArray["order_date"]                 = $this->orderRec->invdat;
            $qryArray["invoice_name"]               = $this->orderRec->cusnam;
            $qryArray["invoice_address_1"]          = $this->orderRec->payadr1;
            $qryArray["invoice_address_2"]          = $this->orderRec->payadr2;
            $qryArray["delivery_address_3"]         = $this->orderRec->payadr3;
            $qryArray["invoice_address_4"]          = $this->orderRec->payadr4;
            $qryArray["invoice_address_5"]          = $this->orderRec->coucod;
            $qryArray["invoice_postcode"]           = $this->orderRec->paypstcod;
            $qryArray["delivery_name"]              = $this->orderRec->cusnam;
            $qryArray["delivery_address_1"]         = $this->orderRec->adr1;
            $qryArray["delivery_address_2"]         = $this->orderRec->adr2;
            $qryArray["delivery_address_3"]         = $this->orderRec->adr3;
            $qryArray["delivery_address_4"]         = $this->orderRec->adr4;
            $qryArray["delivery_address_5"]         = $this->orderRec->coucod;
            $qryArray["delivery_postcode"]          = $this->orderRec->pstcod;
            $qryArray["delivery_telephone_number"]  = $this->orderRec->delivery_telephone_number;
            $qryArray["delivery_fax_number"]        = $this->orderRec->delivery_fax_number;
            $qryArray["email_address"]              = $this->orderRec->emaadr;
            $qryArray["sales_ledger_account_code"]  = ''; //$this->orderRec->sales_ledger_account_code;
            $qryArray["comments"]                   = ''; //$this->orderRec->comments;
            $qryArray["customer_reference_number"]  = ''; //$this->orderRec->customer_reference_number;
            $qryArray["shipping_method"]            = ''; //$this->orderRec->shipping_method;   DELIVERY DETAIL
            $qryArray["payment_with_order"]         = $this->orderRec->payment_with_order;
            $qryArray["payment_method"]             = $this->orderRec->altnam;
            $qryArray["location_code"]              = ''; //$this->orderRec->location_code;
            $qryArray["currency_code"]              = 'GBP'; //$this->orderRec->currency_code;
            $qryArray["order_gross_total"]          = 0.00; //$this->orderRec->order_gross_total;
            $qryArray["user_field_1"]               = ''; //$this->orderRec->user_field_1;
            $qryArray["user_field_2"]               = ''; //$this->orderRec->user_field_2;
            $qryArray["user_field_3"]               = ''; //$this->orderRec->user_field_3;
            $qryArray["user_field_4"]               = ''; //$this->orderRec->user_field_4;
            $qryArray["user_field_5"]               = ''; //$this->orderRec->user_field_5;
            $qryArray["user_field_6"]               = ''; //$this->orderRec->user_field_6;
            $qryArray["user_field_7"]               = ''; //$this->orderRec->user_field_7;
            $qryArray["user_field_8"]               = ''; //$this->orderRec->user_field_8;
            $qryArray["user_field_9"]               = ''; //$this->orderRec->user_field_9;
            $qryArray["user_field_10"]              = ''; //$this->orderRec->user_field_10;
            $qryArray["exchange_rate"]              = $this->orderRec->exchange_rate;
            $qryArray["payment_code_1"]             = ''; //$this->orderRec->payment_code_1;
            $qryArray["payment_value_1"]            = ''; //$this->orderRec->payment_value_1;
            $qryArray["payment_code_2"]             = ''; //$this->orderRec->payment_code_2;
            $qryArray["payment_value_2"]            = ''; //$this->orderRec->payment_value_2;
            $qryArray["payment_code_3"]             = ''; //$this->orderRec->payment_code_3;
            $qryArray["payment_value_3"]            = ''; //$this->orderRec->payment_value_3;
            $qryArray["payment_code_4"]             = ''; //$this->orderRec->payment_code_4;
            $qryArray["payment_value_4"]            = ''; //$this->orderRec->payment_value_4;
            $qryArray["payment_code_5"]             = ''; //$this->orderRec->payment_code_5;
            $qryArray["payment_value_5"]            = ''; //$this->orderRec->payment_value_5;
            $qryArray["discount_percentage"]        = ''; //$this->orderRec->discount_percentage;   DISCOUNT DETAIL
            $qryArray["tag_number"]                 = ''; //$this->orderRec->tag_number;
            $qryArray["record_downloaded"]          = ''; //$this->orderRec->record_downloaded;

            $sql = "UPDATE order_headers
					SET
					order_number = :order_number,
					order_date = :order_date,
					invoice_name = :invoice_name,
					invoice_address_1 = :invoice_address_1,
					invoice_address_2 = :invoice_address_2,
					delivery_address_3 = :delivery_address_3,
					invoice_address_4 = :invoice_address_4,
					invoice_address_5 = :invoice_address_5,
					invoice_postcode = :invoice_postcode,
					delivery_name = :delivery_name,
					delivery_address_1 = :delivery_address_1,
					delivery_address_2 = :delivery_address_2,
					delivery_address_3 = :delivery_address_3,
					delivery_address_4 = :delivery_address_4,
					delivery_address_5 = :delivery_address_5,
					delivery_postcode = :delivery_postcode,
                    delivery_telephone_number = :delivery_telephone_number,
                    delivery_fax_number = :delivery_fax_number,
                    email_address = :email_address,
                    sales_ledger_account_code = :sales_ledger_account_code,
                    comments = :comments,
                    customer_reference_number = :customer_reference_number,
                    shipping_method = :shipping_method,
                    payment_with_order = :payment_with_order,
                    payment_method = :payment_method,
                    location_code = :location_code,
                    currency_code = :currency_code,
                    order_gross_total = :order_gross_total,
                    user_field_1 = :user_field_1,
                    user_field_2 = :user_field_2,
                    user_field_3 = :user_field_3,
                    user_field_4 = :user_field_4,
                    user_field_5 = :user_field_5,
                    user_field_6 = :user_field_6,
                    user_field_7 = :user_field_7,
                    user_field_8 = :user_field_8,
                    user_field_9 = :user_field_9,
                    user_field_10 = :user_field_10,
                    exchange_rate = :exchange_rate,
                    payment_code_1 = :payment_code_1,
                    payment_value_1 = :payment_value_1,
                    payment_code_2 = :payment_code_2,
                    payment_value_2 = :payment_value_2,
                    payment_code_3 = :payment_code_3,
                    payment_value_3 = :payment_value_3,
                    payment_code_4 = :payment_code_4,
                    payment_value_4 = :payment_value_4,
                    payment_code_5 = :payment_code_5,
                    payment_value_5 = :payment_value_5,
                    discount_percentage = :discount_percentage,
                    tag_number = :tag_number,
                    record_downloaded = :record_downloaded";

            $sql .= " WHERE order_header_id = :order_header_id";
            $qryArray["order_header_id"] = $this->orderRec->order_header_id;


            $runSql = $this->buildQuery($sql, $qryArray);
            $RESULT_QUERY = mysqli_query($this->DATABASE1, $runSql);
            mysqli_insert_id($this->DATABASE1);

//            $recordSet = $this->dbConn->prepare($sql);
//            $recordSet->execute($qryArray);
//
//            return ($this->orderRec->atr_id == 0) ? $this->dbConn->lastInsertId('atr_id') : $this->orderRec->atr_id;


        }
    }



    function selectOrder($Ord_ID = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT
				o.*,
				p.planam,
				p.comnam,
				SUM(ol.unipri * ol.numuni) AS ordtot
				FROM orders o
				LEFT OUTER JOIN places p ON p.pla_id = o.tbl_id
				LEFT OUTER JOIN orderline ol ON o.ord_id = ol.ord_id
				WHERE o.ord_id = :ord_id GROUP BY o.ord_id ORDER BY o.invdat DESC';

        $qryArray["ord_id"] = $Ord_ID;

        //echo $sql;

        $this->orderRec = $this->run($sql, $qryArray, true);

    }

    function selectOrderLines($Ord_ID = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT
				o.oln_id,
				o.ord_id,
				o.prd_id,
				o.numuni,
				o.unipri,
				o.vatrat,
				o.olndsc,
				o.tblnam,
				o.tbl_id,
				o.sta_id,
				p.prdnam
				FROM orderline o
				LEFT OUTER JOIN products p ON o.prd_id = p.prd_id
				WHERE ord_id = :ord_id';

        $qryArray["ord_id"] = $Ord_ID;

        $this->orderLines = $this->run($sql, $qryArray, false);

    }


    function selectProduct($Prd_ID = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT
				p.prd_id,
				p.tblnam,
				p.tbl_id,
				p.prt_id,
				p.prdnam,
				p.prddsc,
				p.prdspc,
				p.unipri,
				p.buypri,
				p.delpri,
				p.inspri,
				p.sup_id,
				p.atr_id,
				p.sta_id,
				p.usestk,
				p.in_stk,
				p.on_ord,
				p.on_del,
				p.seourl,
				p.seokey,
				p.seodsc,
				p.dim1,
				p.dim2,
				p.dim3,
				p.altref,
				p.altnam,
				p.prdtag,
				p.weight,
				p.srtord,
				p.vat_id,
				p.prdobj,
				prt.prtnam,
				prt.seourl AS prtseo,
				prt.prtobj
				FROM products p
				LEFT OUTER JOIN producttypes prt ON prt.prt_id = p.prt_id
				WHERE p.prd_id = :prd_id';

        $qryArray["prd_id"] = $Prd_ID;

        $sql .= ' GROUP BY p.prd_id '; // ORDER BY altnam, unipri DESC ';

        $this->productRec = $this->run($sql, $qryArray, true);

    }

    function buildQuery ($sql = '', $qryArray = NULL) {


        /*
         * THIS SHOULD ONLY BE USED FOR BASIC QUERIES!!!
         */


        if (is_array($qryArray)) {

            while (list($key, $val) = each($qryArray)) {
                //echo "$key => $val\n";

//                if (is_numeric($val)) {
//                    $sql = str_replace(":".$key, $val, $sql);
//                } else {
//                    $sql = st r_replace(":".$key, "'".$val."'", $sql);
//                }

                if (is_numeric($val)) {
                    $sql = preg_replace(":".$key, $val, $sql);
                } else {
                    $sql = preg_replace(":".$key, "'".$val."'", $sql);
                }

            }

            return $sql;

        }

    }


}


?>


