<?php
if(isset($_GET['date'])) {
   $date =$_GET['date'];
}
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $timeslot = $_POST['timeslot'];
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    $stmt = $mysqli->prepare("INSERT INTO bookings (name, timeslot, email, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $timeslot, $email, $date);
    $stmt->execute();
    $msg = "<div class='alert alert-success'>Booking Successful</div>"; // Added quotes around the HTML string
    $stmt->close();
    $mysqli->close();
}
else{
	$msg ='';
}

$duration = 60;
$cleanup = 0;
$start = "09:00";
$end = "15:00";

function timeslots($duration, $cleanup, $start, $end){
	$start=new DateTime($start);
	$end=new DateTime($end);
	$interval=new DateInterval("PT".$duration."M");
	$cleanupInterval= new DateInterval("PT".$cleanup."M");
	$slots = array();
	 
	for($intStart = $start; $intStart<$end; $intStart->add($interval)->add($cleanupInterval)){
		$endPeriod = clone$intStart;
		$endPeriod->add($interval);
		if($endPeriod>$end){
			break;
		}
		
		$slots[]=$intStart->format("H:iA")."-".$endPeriod->format("H:iA");
		
	}
	
	return $slots;
}



?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie+edge">
  <meta name="viewport"content="width=device-width,initial-scale=1.0">
  
  <title></title>
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384- BVYiiSLFeK1dGmJRkycuHAHRg32OmUcww7on3RYdg4a+PmSTsz/K68vbEjh4u" crossorigin="anonymous">
   <link rel="stylesheet" href="/css/main.css">
   </head>
   
   <body>
    <div class="container">
      <h1 class="text-center">Book for Date:<?php echo$date;?></h1><hr>    
	  <div class="row">
	  <div class="col-md-12">
	  <?php
	  $msg="";
	       echo isset($msg)?$msg:"";?>
	  </div>
  <?php 
    $timeslots = timeslots($duration, $cleanup, $start, $end);
    foreach($timeslots as $ts) {
  ?>
  <div class="col-md-2">
     <div class="form-group">
       <button class="btn btn-success book" id="openFormBtn" data-timeslot="<?php echo $ts;?>"><?php echo $ts;?></button>
     </div>
  </div>
  <?php 
    }
  ?>
    </div>
 </div>
 <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Booking:<span id="slot"></span></h4>
      </div>
      <div class="modal-body">
        <div class="row">
         <div class="col-md-12">
		   <form action="" method="post">
		     <div class="form-group">
		      <label for="">Timeslot</label>
		      <input type="text" name="timeslot" id="timeslot" readonly>
		 </div>
		 <div class="form-group">
		    <label for="">Name</label>
	        <input type="text" name="name" class="form-control">
		</div>
		<div class="form-group">
		    <label for="">Email</label>
	        <input type="email" name="email" class="form-control">
		</div>	
		<div class="form-group pull-right">
		<button class="btn btn-primary" type="submit" name="submit">Submit</button>
		</div>
     </form>
   </div>
 </div>
</div>
   <div class="modal-footer">
     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
         </div>
    	  </div>
            </div>

	
	 
	 <script src="C:\xampp\htdocs\demos\jquery-3.6.4.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA712mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	 
	 <script>
	 
	 document.getElementById("openFormBtn").addEventListener("click", function() {
      var timeslot=$(this).attr('data-timeslot');
			$("#slot").html(timeslot);
			$("#timeslot").val(timeslot);
		$("#myModal").modal("show");
    });
	
			
		
	 </script>
   </body> 
   
 </html>
	 