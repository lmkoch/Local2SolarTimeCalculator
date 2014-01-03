
<?php 

	//error_reporting(E_ALL);
	$strAutoFill  = 'Autofill information';
	$strClearForm = 'Clear form';
	$strLongitude = 'Longitude (degrees)';
	$strDate      = 'Date (dd.MM.yyyy)';
	$strTimeZone  = 'Time zone (e.g. Zurich: +1.0)';
	$strDST       = 'Daylight saving time active?';
	$strLocalTime = 'Local clock time (hh:mm)';
	$strSolarTime = 'Local solar time (hh:mm)';
	$strCalcRes   = 'Calculate result';

?>

<script type="text/javascript">

	// ERROR CODES
	strValDate		= "Invalid date!"
	strValLoc2Sol	= "Either local clock time or local solar time must be empty!"
	strValVarLN 	= "Valid longitude range is 0 to +180 deg!";
	strValVarLNEmp 	= "Please enter a value for the longitude!";
	strValVarZ 		= "Valid number of hours from GMT is -12 to +12";
	strValHours 	= "Valid range for Time is 0 to 23 hours!";
	strValMinutes 	= "Valid range for minutes is 0 to 59!";

	// COMMENTS (don't replace HTML tags!)
	strValSolarDone  = "<b>Calculated local solar time.</b>";
	strValLocalDone  = "<b>Calculated local clock time.</b>";
	strValGeoLocFail = "Geolocation failed";
	strValDayBack    = "<font color='gray'>Warning: The estimated time is one day before the input time!</font>";
	strValDayForward = "<font color='gray'>Warning: The estimated time is one day after the input time!</font>";

</script>

<div id="main" align="left">
<form name="angecalcForm">

<table cellpadding="0" cellspacing="5" class="formtab pluginwidth" style="width:500">

	<colgroup>
		<col width="200">
		<col width="300">
	</colgroup>

	<tr>
		<td colspan="2" class="h" id="myerror"></td>
	</tr>

	<tr>
		<td colspan="2">
			<?php echo "<input type='button' name='btnAutoFill' value='$strAutoFill' onclick='autofillForm();'>"; ?>
			<?php echo "<input type='button' name='btnClearForm' value='$strClearForm' onClick='clearForm(this.form);'>"; ?>
		</td>
	</tr>

	<tr>
		<td><?php echo $strLongitude; ?></td>
		<!-- <td style="width: 60px"> -->
		<td>
			<input class="tx" style="width:40" type="text" name="varLN" value="" />
			<input type="radio" id="radE" name="varEW" value="E" checked />
					<label for="radE">E</label>
			<input type="radio" id="radW" name="varEW" value="W"  />
					<label for="radW">W</label>
			<span id="commentLocation" style="color:gray"></span>
		</td>
	</tr>
	<tr>
		<td><?php echo $strDate; ?></td>
		<td><input class="tx" style="width:25" type="text" name="dateDD" value="" 
					onClick="this.select();" 
					onKeyUp="goFromFieldToField(event, this,document.angecalcForm.dateMM);" /> . 
			<input class="tx" style="width:25" type="text" name="dateMM" value="" 
					onClick="this.select();"
					onKeyUp="goFromFieldToField(event, this,document.angecalcForm.dateYYYY);" /> . 
			<input class="tx" style="width:50"  type="text" name="dateYYYY" value="" 
					onClick="this.select();" /></td>
	</tr>
	<tr>
		<td><?php echo $strTimeZone; ?></td>
		<td><input class="tx" style="width:50" type="text" name="varZ" value="" onClick="this.select();" /></td>
	</tr>
	<tr>
		<td><?php echo $strDST; ?></td>
		<td>
		<input type="checkbox" name="varDST" id="check" />
		</td>
	</tr>
	<tr><td colspan="2"><hr></hr></td></tr>
	<tr>
		<td><?php echo $strLocalTime; ?></td>
		<td>
			<input class="tx" style="width:25" type="text" name="l_hours" value="" 
					onKeyUp="onChangeLocalTime(event);goFromFieldToField(event, this,document.angecalcForm.l_minutes);" 
					onClick="this.select();" /> : 
			
			<input class="tx" style="width:25"  type="text" name="l_minutes" value="" 
					onKeyUp="onChangeLocalTime(event);" 
					onClick="this.select();"  />
			<span id="commentLocal" style="color:green"></span>
			</td>
	</tr>	
	<tr>
		<td><?php echo $strSolarTime; ?></td>
		<td >
			<input class="tx" style="width:25" type="text" name="s_hours" value="" 
					onChange="onChangeSolarTime(event);" 
					onKeyUp="onChangeSolarTime(event);goFromFieldToField(event, this,document.angecalcForm.s_minutes);" 
					onClick="this.select();"  /> : 
			<input class="tx" style="width:25"  type="text" name="s_minutes" value="" 
					onChange="onChangeSolarTime(event);" 
					onKeyUp="onChangeSolarTime(event);" 
					onClick="this.select();"  />
			<span id="commentSolar" style="color:green"></span>
			</td>
	</tr>
	<tr><td colspan="2"><hr></hr></td></tr>

<tr>
<td><?php echo "<input type='button' name='btnCalculate' value='$strCalcRes' onclick='calculateAll(this.form)'>"; ?></td>
</tr>
</table>
</form>
</div>

<script type="text/javascript">

function onChangeLocalTime(event) {

	var unicode=event.keyCode? event.keyCode : event.charCode // unicode of the pressed key
	if (unicode != 9 && unicode != 16 && unicode != 8 && unicode != 46) { // not tab, shift, backspace or del key was realeased
		document.angecalcForm.s_hours.value = "";
		document.angecalcForm.s_minutes.value = "";
		document.angecalcForm.s_hours.className = 'tx';
		document.angecalcForm.s_minutes.className = 'tx';
		deleteComments();
	}
}

function onChangeSolarTime(event) {


	var unicode=event.keyCode? event.keyCode : event.charCode // unicode of the pressed key
	if (unicode != 9 && unicode != 16 && unicode != 8 && unicode != 46) { // not tab, shift, backspace or del key was realeased
		document.angecalcForm.l_hours.value = "";
		document.angecalcForm.l_minutes.value = "";
		document.angecalcForm.l_hours.className = 'tx';
		document.angecalcForm.l_minutes.className = 'tx';
	    deleteComments();
	}
}

function deleteComments() {
	var div=document.getElementById("commentSolar");
	div.innerHTML = "";

	div=document.getElementById("commentLocal");
	div.innerHTML = "";
}

function goFromFieldToField(event, from, to) {

	var unicode=event.keyCode? event.keyCode : event.charCode // unicode of the pressed key

	if (unicode != 9 && unicode != 16) { // not tab or shift key was realeased

		hh = from.value;
		delimiter_occured = ((hh.indexOf(':') != -1) || (hh.indexOf(',') != -1) || (hh.indexOf('.') != -1));
		if ((hh.length >= 2) || delimiter_occured)  {
			if (delimiter_occured) {
				hh=hh.replace(/[:,.]/g, '');
				from.value = hh;
			}

			setTimeout(function() { to.focus(); to.select(); }, 70); // wait xx seconds before executing focus and select
		}
	}
}

// GET GEO LOCATION
function getLocation() {
	var db_field=document.getElementById("debug");
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition,showError);
	} else { 
		//alert("Geolocation is not supported by this browser.");
		generateComment( "commentLocation", strValGeoLocFail );
	}
}

function showPosition(position) {
	// var db_field=document.getElementById("debug");
 //  	db_field.innerHTML="Latitude: " + position.coords.latitude + 
 //  	"<br>Longitude: " + position.coords.longitude;	

	var longitude = position.coords.longitude;
	document.angecalcForm.varLN.value = (Math.abs(longitude)).toFixed(2);

	if (longitude > 0) {
		document.angecalcForm.radE.checked = true;
	} else {
		document.angecalcForm.radW.checked = true;
	}
		
}

function showError(error) {
	var db_field=document.getElementById("debug");
	switch(error.code) {
		case error.PERMISSION_DENIED:
			//alert("User denied the request for Geolocation.");
			generateComment( "commentLocation", strValGeoLocFail );
			break;
		case error.POSITION_UNAVAILABLE:
			//alert("Location information is unavailable.");
			generateComment( "commentLocation", strValGeoLocFail );
			break;
		case error.TIMEOUT:
			//alert("The request to get user location timed out.");
			generateComment( "commentLocation", strValGeoLocFail );
			break;
		case error.UNKNOWN_ERROR:
			//alert("An unknown error occurred.");
			generateComment( "commentLocation", strValGeoLocFail );
			break;
	}
}

// Get day number from date
function dateToDayNumber(dd, mm, yy) {
	var datum  = new Date(yy,mm-1,dd); // -1 because javascript takes this convention
	var start  = new Date(datum.getFullYear(), 0, 1); // first day of the year
	var diff   = datum - start;
	var oneDay = 1000 * 60 * 60 * 24; // Miliseconds in one day
	var day    = Math.ceil(diff / oneDay) + 1;

	return day;
}

// Get 60 day number from date.
// Not tested yet!
function dateTo60DayNumber(dd, mm, yy) {
	var datum  = new Date(yy,mm-1,dd); // -1 because javascript takes this convention
	var start  = new Date( 1904, 0, 1); // equals day number 31
	var diff   = datum - start;
	var oneDay = 1000 * 60 * 60 * 24; // Miliseconds in one day
	var day    = Math.ceil(diff / oneDay) + 31;

	return day%60;
}


function calculateAll(form){

	// Make sure all comments are gone
	deleteComments();

	var varLN=form.varLN.value;
	var varEW=form.varEW.value;

	var varDD=form.dateDD.value;
	var varMM=form.dateMM.value;
	var varYYYY=form.dateYYYY.value;


	var loc2sol;
	var varZ;
	var hours;
	var minutes;

	if ((document.angecalcForm.l_hours.value == "") && (document.angecalcForm.l_minutes.value == "")) {
		// CASE CALCULATE SOLAR TIME FROM LOCAL TIME
		loc2sol = 0;
		varZ=form.varZ.value;
		hours=form.s_hours.value;
		minutes=form.s_minutes.value;
	} else if ((document.angecalcForm.s_hours.value == "") && (document.angecalcForm.s_minutes.value == "")) {
		// CASE CALCULATE LOCAL TIME FROM SOLAR TIME
		loc2sol = 1;
		varZ=form.varZ.value;
		hours=form.l_hours.value;
		minutes=form.l_minutes.value;
    } else {
    	// CATCH ERROR
		var loc2sol = -1;
    }
	
	hours=Number(hours);
	minutes=Number(minutes);

	//alert("hours: " + hours)
	//alert(minutes)

	varLN=Number(varLN);
	varZ=Number(varZ);
	varDST=Number(varDST);
	
	bInputOk = validateInputs( varLN, varZ, hours, minutes, loc2sol, varDD, varMM, varYYYY);

	// day number from date
	var varD = dateToDayNumber( varDD, varMM, varYYYY ); 

	// checkbox
	var check=document.getElementById('check').checked;
	if(check) {
        var varDST = 1;
    } else {
        var varDST = 0;
    }

	// radiobutton
	if (document.getElementById('radW').checked) {
	 	varLN = -varLN;
	} 

	if( bInputOk )
	{
		varLT=hours+minutes/60;

		var varEOT = equationOfTime(varD);
		var varLC  = longitudeCorrection(varZ,varLN);

		if (loc2sol) {

			// Calculate Solar time
			var varTS  = solarTime(varLT,varDST,varEOT,varLC);
			varTS      = catchDayChange(varTS);
			
			// Update Solar Time Field
			document.angecalcForm.s_hours.value   = formatTimeNumber(getHfromDecimal(varTS));
			document.angecalcForm.s_minutes.value = formatTimeNumber(getMfromDecimal(varTS));

			// Create comment
			generateComment( "commentSolar", strValSolarDone );			

		} else {
			// Calculate local time
			var varTS  = localTime(varLT,varDST,varEOT,varLC);
			varTS      = catchDayChange(varTS);

			// Update Solar Time Field
			document.angecalcForm.l_hours.value   = formatTimeNumber(getHfromDecimal(varTS));
			document.angecalcForm.l_minutes.value = formatTimeNumber(getMfromDecimal(varTS));
			
			// Create comment
			generateComment( "commentLocal", strValLocalDone );
			
		}

	}
}

function catchDayChange(time) {
	if (time < 0) {
		time = 24 + time;
		document.getElementById('myerror').innerHTML = strValDayBack;
	} else if (time >= 24) {
		time = time - 24;
		document.getElementById('myerror').innerHTML = strValDayForward;
	}
	return time;
}

function clearForm(form) {

	form.reset();
	deleteComments();
	
	var div;
	div=document.getElementById("commentLocation");
	div.innerHTML = "";

	document.angecalcForm.dateDD.className = 'tx';
	document.angecalcForm.dateMM.className = 'tx';
	document.angecalcForm.dateYYYY.className = 'tx';
	document.angecalcForm.l_hours.className = 'tx';
	document.angecalcForm.l_minutes.className = 'tx';
	document.angecalcForm.s_hours.className = 'tx';
	document.angecalcForm.s_minutes.className = 'tx';
	document.angecalcForm.varLN.className = 'tx';
	document.angecalcForm.varZ.className = 'tx';
	
	var div=document.getElementById("myerror");
	while(div.hasChildNodes()) {
		div.removeChild(div.lastChild);
	}
}

function validateInputs( varLN, varZ, hours, minutes, loc2sol, varDD, varMM, varYYYY) {

	var alerts = new Array();

	var date = new Date(varYYYY,varMM-1,varDD);
	if (!(date.getFullYear() == varYYYY && date.getMonth() + 1 == varMM && date.getDate() == varDD)) {
		alerts.push( strValDate );
		document.angecalcForm.dateDD.className = 'txh';
		document.angecalcForm.dateMM.className = 'txh';
		document.angecalcForm.dateYYYY.className = 'txh';
	} else {
		document.angecalcForm.dateDD.className = 'tx';
		document.angecalcForm.dateMM.className = 'tx';
		document.angecalcForm.dateYYYY.className = 'tx';
	}
	
	if (loc2sol == -1) {
		alerts.push( strValLoc2Sol );
		document.angecalcForm.l_hours.className = 'txh';
		document.angecalcForm.s_hours.className = 'txh';
		document.angecalcForm.l_minutes.className = 'txh';
		document.angecalcForm.s_minutes.className = 'txh';
	}
	if(varLN<0||varLN>180) {
		alerts.push( strValVarLN );
		document.angecalcForm.varLN.className = 'txh';
	}
	else
	{
		document.angecalcForm.varLN.className = 'tx';
	}
	if (document.angecalcForm.varLN.value == "") { // if varLN empty
		alerts.push( strValVarLNEmp );
		document.angecalcForm.varLN.className = 'txh';
	}
	else
	{
		document.angecalcForm.varLN.className = 'tx';
	}
	if(varZ<-12||varZ>12) {
		alerts.push( strValVarZ );
		document.angecalcForm.varZ.className = 'txh';
	}
	else
	{
		document.angecalcForm.varZ.className = 'tx';
	}
	if(hours<0||hours>23) {
		alerts.push( strValHours );
		
		if (loc2sol == 1) {
			document.angecalcForm.l_hours.className = 'txh';
		}
		else if (loc2sol == 0) {
			document.angecalcForm.s_hours.className = 'txh';
		}
	}
	else
	{
		document.angecalcForm.l_hours.className = 'tx';
		document.angecalcForm.s_hours.className = 'tx';
	}
	if(minutes<0||minutes>59) {
		alerts.push( strValMinutes );
		
		if (loc2sol == 1) {
			document.angecalcForm.l_minutes.className = 'txh';
		}
		else if (loc2sol == 0) {
			document.angecalcForm.s_minutes.className = 'txh';
		}
	}
	else
	{
		document.angecalcForm.l_minutes.className = 'tx';
		document.angecalcForm.s_minutes.className = 'tx';
	}

	if (alerts.length) {
		document.getElementById('myerror').innerHTML = alerts.join("<br />") + '<br />&nbsp;';
		return false;
	}
	else
	{
		var div=document.getElementById("myerror");while(div.hasChildNodes()){div.removeChild(div.lastChild);}
		return true;
	}

}
function generateComment( strElementID, strComment ) {

		var line=document.createElement("span");
		line.innerHTML= strComment;
		var div=document.getElementById( strElementID );
		while(div.hasChildNodes()) { 
			div.removeChild(div.lastChild);
		}
		div.appendChild(line);
}

function autofillForm() {

	clearForm(document.angecalcForm);

	// GET LOCATION
	bLocAvailable = getLocation();

	if (bLocAvailable == -1) {	
		// Create comment
		generateComment( "commentLocation", "Geolocation failed" );
	}

	
	
	// DATE AND LOCAL TIME
	now  = new Date();
	hour = now.getHours();
	min  = now.getMinutes();

	dd = now.getDate();
	mm = now.getMonth()+1;
	yy = now.getFullYear();

	document.angecalcForm.l_hours.value   = formatTimeNumber(hour);
	document.angecalcForm.l_minutes.value = formatTimeNumber(min);

	document.angecalcForm.dateDD.value    = formatTimeNumber(dd);
	document.angecalcForm.dateMM.value    = formatTimeNumber(mm);
	document.angecalcForm.dateYYYY.value  = formatTimeNumber(yy);

	// DAYLIGHT SAVING TIME

	document.angecalcForm.check.checked = now.dst();

	// TIME ZONE

	document.angecalcForm.varZ.value = -(now.getTimezoneOffset()/60+now.dst());


}

Date.prototype.stdTimezoneOffset = function() {
	var jan = new Date(this.getFullYear(), 0, 1);
	var jul = new Date(this.getFullYear(), 6, 1);
	return Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
}

Date.prototype.dst = function() {
	return this.getTimezoneOffset() < this.stdTimezoneOffset();
}

// Conversions, string formatting, etc

var pi=Math.PI;

function radians(x) {
	return x*pi/180;
}

function degrees(x) {
	return x*180/pi;
}

function decimalToHMS(x) {
	var hrs=parseInt(x);
	var mins=parseInt(60*(x-hrs));
	var result=(hrs+((mins==0)?":00":((mins<10)?":0":":")+mins));
	return result;
}

function getHfromDecimal(x) {
	var hrs=parseInt(x);
	return hrs;
}

function getMfromDecimal(x) {
	var hrs=getHfromDecimal(x);
	var mins=parseInt(60*(x-hrs));
	return mins;
}

function formatTimeNumber(x) {
	if (x==0)
		out = "00";
	else if (x<10)
		out = "0" + x.toString();
	else
		out = x.toString();

	return out
}

function IsNumeric(strString) {
	var strValidChars="0123456789.-";
	var strChar;
	var blnResult=true;
	if (strString.length==0) {
		return false;
	}
	for(i=0;i<strString.length&&blnResult==true;i++) {
		strChar=strString.charAt(i);
		if(strValidChars.indexOf(strChar)==-1) {
			blnResult=false;
		}
	}
	return blnResult;
}

// Calculations needed for solar/local time conversions

function equationOfTime(varD) {
	var varX=radians((360*(varD-1))/365.242);
	return 0.258*Math.cos(varX)-7.416*Math.sin(varX)-3.648*Math.cos(2*varX)-9.228*Math.sin(2*varX);
}

function longitudeCorrection(varZ,varLN) {
	return(15*varZ-varLN)/15;
}

function solarTime(varLT,varDST,varEOT,varLC) { 
	return Number(varLT+(varEOT/60)-varLC-varDST);
}
function localTime(varLT,varDST,varEOT,varLC){
	return Number(varLT-(varEOT/60)+varLC+varDST); // NOTE varLT is really varTS
}


</script>