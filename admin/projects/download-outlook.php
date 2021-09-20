<?php
require_once('config/db_config.php');
require_once( $_SERVER['DOCUMENT_ROOT'] .  "admin/events/classes/eventdates.cls.php" );
require_once( $_SERVER['DOCUMENT_ROOT'] .  "admin/events/classes/agenda.cls.php" );

$TmpEdt = new EdtDAO($host, $username, $password, $db_name);
$TmpAge = new AgeDAO($host, $username, $password, $db_name);

$edts = $TmpEdt->getEventDates(NULL, NULL, date("Y-m-d"), NULL, NULL, NULL, NULL, NULL);

$fileName = date("YmdHis").'_m_and_a_calendar.vcs';
header("Content-Type: text/x-vCalendar");
header("Content-Disposition: inline; filename=".$root_url.$fileName);

?>

BEGIN:VCALENDAR

<?php
while($evt = mysql_fetch_assoc($edts)) {
	$startTime = date("Ymd", strtotime($evt['actdat'])).'T'.str_replace(":","",$evt['actdat']).'00Z';
	$startTime = date("Ymd", strtotime($evt['actdat'])); //.'T090000Z';
	
	$agendas = $TmpAge->getAgenda(NULL, 'EVENT', $evt['evt_id'], 0, NULL, NULL, NULL);
	$StartTime = (mysql_num_rows($agendas) > 0) ? '23:59' : '1000';
	$EndTime = (mysql_num_rows($agendas) > 0) ? '00:00' : '1800';
	
	while($agendaRec = mysql_fetch_assoc($agendas)) {
		if ($agendaRec['begtim'] < $StartTime) $StartTime = $agendaRec['begtim'];
		if ($agendaRec['endtim'] > $EndTime) $EndTime = $agendaRec['endtim'];
	}
	
	$StartTime = str_replace(":","",$StartTime);
	$StartTime = intval($StartTime) - 100;
	if ($StartTime < 1000) $StartTime = '0'.$StartTime;
	
	$EndTime = str_replace(":","",$EndTime);
	$EndTime = intval($EndTime) - 100;
	if ($EndTime < 1000) $EndTime = '0'.$EndTime;
	
	$endTime = $startTime.'T'.$EndTime.'00Z';
	$startTime .= 'T'.$StartTime.'00Z';
	
?>

BEGIN:VEVENT
SUMMARY:<?php echo $evt['evtttl']."\n"; ?>
DESCRIPTION;ENCODING=QUOTED-PRINTABLE: <?php echo $evt['evtsum'] .' '. $StartTime .'-'.$EndTime . "\n"; ?>
DTSTART:<?php echo $startTime . "\n"; ?>
DTEND:<?php echo $endTime . "\n"; ?>
END:VEVENT

<?php
}
?>
END:VCALENDAR
