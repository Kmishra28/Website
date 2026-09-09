<?php
function build_calendar($month,$year)
{
	 	
	//First of all we'll create an array containing names of all days in a week.
	$daysOfWeek=array('Sunday','Monday','Tuesday','Wednessday','Thursday','Friday','Saturday');
	
	//Then we'll get the first day of the month that is in the argument of this function.
	$firstDayOfMonth=mktime(0,0,0,$month,1,$year);
	
	//Now getting the number of days this month contains.
	$numberDays=date('t',$firstDayOfMonth);
	
	//Getting some information about the first day of this month.
	$dateComponents=getdate($firstDayOfMonth);
		
	
	//Gettingthe name of this month.
    $monthName=$dateComponents['month'];
	
	
	//Getting the index value 0-6 of the first day of this month.
	$dayOfWeek=$dateComponents['wday'];
	$dayOfWeek;
	 
	
	//Getting the current date.
	$dateToday=date('Y-m-d');
	$dateToday;
	
	
	//Now createing the HTML table
	$calendar="<table class='table table-bordered'>";
	$calendar.="<center><h2>$monthName $year</h2></center>";
	
	$calendar.="<tr>";
	
	//Createing the calendar header
	foreach($daysOfWeek as $day){
	  $calendar.="<th class='header'>$day</th>";
    }
    $calendar.="</tr><tr>";	
	
	//The variable $dayOfWeek will make sure that there must be only 7 columns on our table
	if($dayOfWeek>0){
		for($k=0;$k<$dayOfWeek;$k++){
			$calendar.="<td></td>";
		}
	}
	//Initiating the day counter
    $currentDay=1;

    //Getting the month number
    $month=str_pad($month,2,"0",STR_PAD_LEFT);
	
	
	while($currentDay <= $numberDays){
		
		//if seventh column (saturday) reached, start a new row
		if($dayOfWeek==7)
		{
			$dayOfWeek=0;
			$calendar.="</tr><tr>";
			$calendar.="<td><h4>$currentDay</h4></td>";
		
	//Incrementing the counters
			$currentDay++;
			$dayOfWeek++;
	
		}
	
	//Completing the row of the last week in month,if necessary
		if($dayOfWeek!=7)
		{
			$remainingDays=7-$dayOfWeek;
			for($i=0;$i<$remainingDays;$i++)
			{
				$currentDayRel= str_pad($currentDay,2,"0",STR_PAD_LEFT);
				$date="$year-$month-$currentDayRel";
            
			
				if($dateToday==$date)
				{
					$calendar.="<td class='today'><h4>$currentDay</h4>";
				}
				else
				{
					$calendar.="<td><h4>$currentDay</h4>";
				}
				$calendar.="</td>";
				
				$currentDay++;
				
			}
			$dayOfWeek++;
			
			
		}
	$calendar.="</tr>";
	
		
	}
    $calendar.="</table>";	
 	echo $calendar;
	
}
?>
<html>
<head>
   <meta name="viewport"content="width=device-width,initial-scale=1.0">
   <link rel="styleseet" herf="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
   <style>
     .table{
		 table-layout:fixed;
	 }
	 .table-bordered{
		 border:1px solid black;
	 }
	 .td{
		 width:33%;
	 }
	 .today{
		 background:yellow;
	 }
   </style>
</head>
<body>
   <div class="container">
      <div class="row">
	    <div class="col-md-12">
		  <?php
		  $dateComponents=getdate();
		  $month=$dateComponents['mon'];
		  $year=$dateComponents['year'];
		  echo build_calendar($month,$year);
		  ?>
		</div>
	</div>
  </div>
</body>
</html>