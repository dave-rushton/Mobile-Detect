// JavaScript Document

var minutes = 1000 * 60;
var hours = minutes * 60;
var days = hours * 24;
var years = days * 365;

var defaultDayStart = 7 * hours;
var dayStart = 9 * hours;
var dayFinish = (17 * hours);

function twoDigits(d) {
	if(0 <= d && d < 10) return "0" + d.toString();
	if(-10 < d && d < 0) return "-0" + (-1*d).toString();
	return d.toString();
}

function getMysqlDate (iActDat) {

	var returnDate = iActDat.split(" ");
		returnDate = returnDate[0];
		
	return returnDate;
	
}

function getMysqlTime (iActDat) {

	//alert(iActDat);

	var returnTime = iActDat.split(" ");
		returnTime = returnTime[1];
		returnTime = returnTime.split(":");
		returnTime = returnTime[0]+':'+returnTime[1];
		
	return returnTime;
	
}

function mysql2js(iActDat) {
	
	var regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
	var parts=iActDat.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');
	var returnDate = new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
	
	returnDate.setTime( returnDate.getTime() - (returnDate.getTimezoneOffset() * minutes) );
	
	return returnDate;
}

function js2mysql(iActDat) {
	
	if (iActDat == null) {iActDat = new Date();}

	return iActDat.getUTCFullYear() + "-" + twoDigits(1 + iActDat.getUTCMonth()) + "-" + twoDigits(iActDat.getUTCDate()) + " " + twoDigits(iActDat.getUTCHours()) + ":" + twoDigits(iActDat.getUTCMinutes()) + ":" + twoDigits(iActDat.getUTCSeconds());
	
}

function js2mysqlDate(iActDat) {
	
	if (iActDat == null) {iActDat = new Date();}

	return iActDat.getUTCFullYear() + "-" + twoDigits(1 + iActDat.getUTCMonth()) + "-" + twoDigits(iActDat.getUTCDate());
	
}

function js2mysqlTime(iActDat) {
	
	if (iActDat == null) {iActDat = new Date();}

	return twoDigits(iActDat.getUTCHours()) + ":" + twoDigits(iActDat.getUTCMinutes());
	
}

function getWeekDateList (iActDat) {
	
	var DatLst = [];
	
	if (iActDat == null) {iActDat = new Date();}
	
	var RunDat = getMonday(iActDat);
		RunDat.setHours(9);
		RunDat.setMinutes(0);
		RunDat.setSeconds(0);
	
	for (var d = 0; d < 7; d++) {
		
		var CurDat = new Date();
			CurDat.setTime( RunDat.getTime() );
		
		DatLst[DatLst.length] = CurDat;
		RunDat.setTime( RunDat.getTime() + (days) );
	}
	
	return DatLst;
	
}

function getMonday(iActDat) {

	if (iActDat == null) {iActDat =  new Date();}

	var nd = new Date();
		nd.setTime(iActDat.getTime());
	
	var one_day=1000*60*60*24;
				
	if (iActDat.getDay() == 1) {
		// Monday
	}
	else if (iActDat.getDay() == 0) {
		// Sunday
		nd.setTime(iActDat.getTime()+days);
	}
	else {
		// Other Day
		
		var addDays;
		addDays = nd.getDay() - 1;
		nd.setTime(nd.getTime() - (addDays * days));
		
	}
	
	nd.setHours(9);
	nd.setMinutes(0);
	nd.setSeconds(0);
	
	return nd;
	
}

function niceDate (iActDat, iSuffix) {

	if (iActDat == null) {iActDat = new Date();}

	iActDat.setUTCHours(9);

	var month_names = new Array ( );
	month_names[month_names.length] = "Jan";
	month_names[month_names.length] = "Feb";
	month_names[month_names.length] = "Mar";
	month_names[month_names.length] = "Apr";
	month_names[month_names.length] = "May";
	month_names[month_names.length] = "June";
	month_names[month_names.length] = "July";
	month_names[month_names.length] = "Aug";
	month_names[month_names.length] = "Sept";
	month_names[month_names.length] = "Oct";
	month_names[month_names.length] = "Nov";
	month_names[month_names.length] = "Dec";
	
	var day_names = new Array ( );
	day_names[day_names.length] = "Sun";
	day_names[day_names.length] = "Mon";
	day_names[day_names.length] = "Tue";
	day_names[day_names.length] = "Wed";
	day_names[day_names.length] = "Thu";
	day_names[day_names.length] = "Fri";
	day_names[day_names.length] = "Sat";
	
	var returnDate = '';
	
	returnDate += day_names[iActDat.getUTCDay()] ;
	returnDate += ", " ;
	returnDate += month_names[iActDat.getUTCMonth()] ;
	returnDate += " " + iActDat.getUTCDate() + addSuffix( iActDat.getUTCDate() ) ;
	
	return returnDate;

}

function addSuffix ( iDate ) {
	if (iDate == 1 || iDate == 21 || iDate ==31) { iDate = "st"; }
	else if (iDate == 2 || iDate == 22) { iDate = "nd"; }
	else if (iDate == 3 || iDate == 23) { iDate = "rd"; }
	else { iDate = "th"; }
	
	return iDate;
}

function switchDate (iActDat) {

	if (!iActDat) { return false; }
	
	var DatArr = iActDat.split("-");
	
	var returnDate = DatArr[2]+'-'+DatArr[1]+'-'+DatArr[0];
	
	return returnDate;
	
}