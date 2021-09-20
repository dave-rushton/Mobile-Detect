<?php


require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php" );
require_once('classes/vcard.cls.php');

    function isIphone($user_agent=NULL) {
        if(!isset($user_agent)) {
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }
        $result = (strpos($user_agent, 'iPhone') !== FALSE) || (strstr($_SERVER['HTTP_USER_AGENT'],'iPod')!== FALSE) || (strstr($_SERVER['HTTP_USER_AGENT'],'iPad')!== FALSE);
        
        return $result;
    }

    function isiOS7($user_agent=NULL) {
        if(!isset($user_agent)) {
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }
        $result = (strpos($user_agent, 'OS 7') !== FALSE);
        
        return $result;
    }

    function isiOS8($user_agent=NULL) {
        if(!isset($user_agent)) {
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }
        $result = (strpos($user_agent, 'OS 8') !== FALSE);
        
        return $result;
    }

    function isMobileSafari($user_agent=NULL) {
        if(!isset($user_agent)) {
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }

        # Please note: Chrome for iPhone reports 'CriOS' instead of 'Version' in it's user agent string and as of Feb 2013 Chrome for iPhone
        # does not support either vCard (.vcf) or vCalendar (.ics) file types - that's even worse than Mobile Safari - shame on you Google!!!
        $result1 = strstr($user_agent, "AppleWebKit/");
        $result2 = strstr($user_agent, "Mobile/");
        $result3 = strstr($user_agent, "Safari/");
        $result4 = strstr($user_agent, "Version/");
        $result = $result1 && $result2 && $result3 && $result4;
        
        return $result;
    }

    $Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
    
    # Output file contents - simple version
    if(!isIphone() || ((isiOS7() || isiOS8()) && isMobileSafari())) {
        # Send correct headers      
        //header("Content-type: text/x-vcard; charset=utf-8");
                    // Alternatively: application/octet-stream
                    // Depending on the desired browser behaviour
                    // Be sure to test thoroughly cross-browser

        //header("Content-Disposition: attachment; filename=\"iphonecontact.vcf\";");
        # Output file contents

        //echo file_get_contents($patchworks->webRoot."pages/events/vcard.php?pel_id=".$Pel_ID);

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

        //echo $vc->card;

        $vc->download();



        exit();
    }

    # Output file contents - simple version
//    if(!isMobileSafari()) {
//        echo file_get_contents("safari.php");
//        exit();
//    }



    # Send correct headers      
    header("Content-type: text/x-vcalendar; charset=utf-8"); 
                    // Alternatively: application/octet-stream
                    // Depending on the desired browser behaviour
                    // Be sure to test thoroughly cross-browser

    header("Content-Disposition: attachment; filename=\"iphonecontact.ics\";");
    
    # Generate file contents - advanced version
    # BEGIN:VCALENDAR
    # VERSION:2.0
    # BEGIN:VEVENT
    # DTSTART;TZID=Europe/London:20120617T090000
    # DTEND;TZID=Europe/London:20120617T100000
    # SUMMARY:iPhone Contact
    # DTSTAMP:20120617T080516Z
    # ATTACH;VALUE=BINARY;ENCODING=BASE64;FMTTYPE=text/directory;
    #  X-APPLE-FILENAME=iphonecontact.vcf:
    #  QkVHSU46VkNBUkQNClZFUlNJT046My4wDQpOOkNvbnRhY3Q7aVBob25lOzs7DQpGTjppUGhvbm
    #  UgQ29udGFjdA0KRU1BSUw7VFlQRT1JTlRFUk5FVDtUWVBFPVdPUks6aXBob25lQHRoZXNpbGlj
    #  b25nbG9iZS5jb20NClRFTDtUWVBFPUNFTEw7VFlQRT1WT0lDRTtUWVBFPXByZWY6KzQ0MTIzND
    #  U2Nzg5MA0KRU5EOlZDQVJE
    # END:VEVENT
    # END:VCALENDAR

    echo "BEGIN:VCALENDAR\n";
    echo "VERSION:2.0\n";
    echo "BEGIN:VEVENT\n";
    echo "SUMMARY:Click attached contact below to save to your contacts\n";
    $dtstart = date("Ymd")."T".date("Hi")."00";
    echo "DTSTART;TZID=Europe/London:".$dtstart."\n";
    $dtend = date("Ymd")."T".date("Hi")."01";
    echo "DTEND;TZID=Europe/London:".$dtend."\n";
    echo "DTSTAMP:".$dtstart."Z\n";
    echo "ATTACH;VALUE=BINARY;ENCODING=BASE64;FMTTYPE=text/directory;\n";
    echo " X-APPLE-FILENAME=iphonecontact.vcf:\n";
    $vcard = file_get_contents("iphonecontact.vcf");        # read the file into memory
    $b64vcard = base64_encode($vcard);                      # base64 encode it so that it can be used as an attachemnt to the "dummy" calendar appointment
    $b64mline = chunk_split($b64vcard,74,"\n");             # chunk the single long line of b64 text in accordance with RFC2045 (and the exact line length determined from the original .ics file exported from Apple calendar
    $b64final = preg_replace('/(.+)/', ' $1', $b64mline);   # need to indent all the lines by 1 space for the iphone (yes really?!!)
    echo $b64final;                                         # output the correctly formatted encoded text
    echo "END:VEVENT\n";
    echo "END:VCALENDAR\n";

    die('ERROR');