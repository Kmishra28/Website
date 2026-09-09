<?php

function build_calendar($month, $year){
	
	  $mysqli=new mysqli('localhost','root','','bookingcalendar');
	  /*$stmt=$mysqli->prepare("select*from bookings where MONTH(date)= ? AND YEAR(date)= ?");
	  $stmt->bind_param('ss',$month,$year);
	  $bookings=array();
	 if($stmt->execute()){
		 $result=$stmt->get_result();
		 if($result->num_rows>0){
			 while($row=$result->fetch_assoc()){
				 $bookings[] =$row['date'];
			 }
			 $stmt->close();
		 }
	 }*/
	 
	 
	//Create array containing abbreviations of days of week.
	$daysOfWeek= array('Sunday','Monday','Tuesday','Wednessday','Thursday','Friday','Saturday');
	
	//what is the first day of the month in question?
	$firstDayOfMonth = mktime(0,0,0,$month,1,$year);
	
	//How many days does this month contain?
	$numberDays = date('t',$firstDayOfMonth);
	
	//Retrieve  some information about the first day of the
	//month in question
	$dateComponents = getdate($firstDayOfMonth);
	
	//what is the first day of the month in question?
	$monthName=$dateComponents['month'];
	
	//what is the index value (0-6) of the first day of the
	//month in quastion.
	$dayOfWeek=$dateComponents['wday'];

    //Create the table tag opener and day headers
	$dateToday=date('Y-m-d');
	
	$prev_month=date('m',mktime(0,0,0,$month-1, 1,$year));
	$prev_year=date('Y',mktime(0,0,0,$month-1, 1,$year));
	$next_month=date('m',mktime(0,0,0,$month+1, 1,$year));
	$next_year=date('Y',mktime(0,0,0,$month+1, 1,$year));
	$calendar="<center><h2>$monthName $year</h2>";
	$calendar.="<a class='btn btn-primary btn-xs' href='?month=".$prev_month."&year=".$prev_year."'>Prev Month</a>";
	$calendar.="<a class='btn btn-primary btn-xs' href='?month=".date('m')."&year=".date('Y')."'>Current Month</a>";
	$calendar.="<a class='btn btn-primary btn-xs' href='?month=".$next_month."&year=$next_year'>Next Month</a>";
    $calendar.="<br>";
	
	$calendar.="<br><table class='table table-bordered'>";
	$calendar.="<tr>";
	foreach($daysOfWeek as $day){
		$calendar.="<th class='header'>$day</th>";
	}	
	$calendar.="</tr><tr>";
	$currentDay=1;
	if($dayOfWeek > 0){
		for($k=0; $k<$dayOfWeek; $k++){
			$calendar.="<td class='empty'></td>";
		}
	}
	$month=str_pad($month,2,"0",STR_PAD_LEFT);
	while($currentDay <= $numberDays)
{
		if($dayOfWeek == 7){
			$dayOfWeek = 0;
			$calendar.="</tr><tr>";
		}
		$currentDayRel = str_pad($currentDay, 2,"0",STR_PAD_LEFT);
		$date = "$year-$month-$currentDayRel";
		$dayname = strtolower(date('l',strtotime($date)));
		$eventNum = 0;
		
//$today =$date==$dateToday ? 'today': "";
//$calendar.="<td class='$today'><h3>$currentDayRel</h3><a class='btn btn- success btn-xs'>Book</a></td>";
/*if ($date == date('Y-m-d'))
{
 $calendar.="<td style='background:yellow'><h3>$currentDay</h3><a href='book.php?date='$date'' class='btn btn-success btn-xs'>Book</button></td>";
}
else 
{
$calendar.="<td><h3>$currentDayRel</h3><a href='book.php?date='$date''
class='btn btn-success btn-xs'>Book</a></td>";
}*/
		
		$today =$date==date('Y-m-d')?"today":"";
		if($date<date('Y-m-d'))
		{
			$calendar.="<td><h3>$currentDay</h3><a href='book.php?date=".$date."' class='btn btn-danger btn-xs'>N/A</a></td>";
		}
		
		else
		{
			$calendar.="<td class='$today'><h3>$currentDay</h3><a href='book.php?date=".$date."' 
			class='btn btn-sucess btn-xs'>Book</a>";
		}
        		
		
		$currentDay++;
		$dayOfWeek++;
	}
	
	return $calendar;
}	

?>
<html>
<head>
   <meta name="viewport" content="width=device-width,initial-scale=1.0">
   <link rel="stylesheet" href="css/bootstrap.css">
   <style>
      @media only screen and (max-width: 760px),
	  (min-device-width:802px)and(max-device-width:1020px){
		  /*Force table to not be like table anymore*/
		  
table,thead,tbody,th,td,tr,{
		      display:block;
			  align-items:center;
		  }
		.empty{
			display:block;
		}
		.empty{
		  display:none;
		}
		/*hide table headers (but not display:none:,for accessibility)*/
		th{
			position:absolute;
			top: -9999px;
			left: -9999px;
		}
		tr{
			border:1px solid #ccc;
		}
		td{
			/*behave like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			position:relative;
			padding-left: 50%;
		}
		/*lable the data*/
		td:nth-of-type(1):before{
			content:"Sunday";
		}
		td:nth-of-type(2):before{
			content:"Monday";
		}
		td:nth-of-type(3):before{
			content:"Tuesday";
		}
		td:nth-of-type(4):before{
			content:"Wednesday";
		}
		td:nth-of-type(5):before{
			content:"Thursday";
		}
		td:nth-of-type(6):before{
			content:"Friday";
		}
		td:nth-of-type(7):before{
			content:"Saturday";
	  }
	}
    /*Smartphones (portrait and landscape)---------*/
	@media only screen and (min-device-width:320px)and (max-device-width:480px){
		body{
			padding:0;
			margin:0;
		}
	}
	/*ipads (portrait and landscape)--------*/
	@media only screen and (min-device-width:802px)and (max-device-width:1020px){
		body{
			width:495px;
		}
	}
	@media (min-width:641px){
		table{
		table-layout:fixed;
		}
		td{
			width:33%;
		}
	}
	.row{
		margin-top:20px;
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
       if(isset($_GET['month']) && isset($_GET['year'])){
        $month = $_GET['month'];
        $year = $_GET['year'];
	   }
	   else{
		$month = $dateComponents['mon'];
		$year = $dateComponents['year'];
	   }
	   echo build_calendar($month,$year);
	?>
 </body>         		  
 </html>