<?php
require_once "inc/conectar.php";
require_once "inc/paginas.class.php";	
function hayEventos($dia){
	$fecha = (string) $dia;
	$conn = new Conectar;
	$conn->Conexion();
	$conn->TM();
	$q = $conn->query("SELECT * FROM eventos WHERE Fecha='".$fecha."'");
	if (!$q) return false;
	if (mysql_num_rows($q) < 1)
		return false;
	return true;
}
/* draws a calendar */
function draw_calendar($month,$year){

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('D','L','M','M','J','V','S');
	$calendar.= '<thead><tr class="calendar-row"><td class="calendar-day-head">'
	.implode('</td><td class="calendar-day-head">',$headings).'</td></tr></thead>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tbody><tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"></td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar.= '<td class="calendar-day">';
			/* add in the day number */
			$clases = (hayEventos($year.'-'.$month.'-'.$list_day)) ? 'day-number' : 'day-number no-events';
			if ($year.'-'.$month.'-'.$list_day == date("Y-m-d")) $clases.= ' day-number-hoy';
			$calendar.= '<div class="'.$clases.'" rel="'.$year.'-'.$month.'-'.$list_day.'">'.$list_day.'</div>';

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			//$calendar.= str_repeat('<p>&nbsp;</p>',2);
			
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr></tbody>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}
?>