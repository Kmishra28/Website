
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Book Form</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <!-- <link rel="stylesheet" href="/css/main.css"> -->
</head>

<body>
<div>
<?php $date = $_GET['date']; 
$counselor_id = "201";
?>
<h1 class="text-center">Book for Date: <?php echo $date; ?></h1>
</div>



<div><br>
<form method="post">
  <label for="options">Select a Counselor:</label>
  <select name="options" id="options">
    <option value="201" <?php if(isset($_POST['options']) && $_POST['options'] == '201') echo 'selected'; ?>>Vaidehi Mishra</option>
    <option value="202" <?php if(isset($_POST['options']) && $_POST['options'] == '202') echo 'selected'; ?>>Nivedita Sharma</option>
    <option value="203" <?php if(isset($_POST['options']) && $_POST['options'] == '203') echo 'selected'; ?>>Shrishti Awasthi</option>
	<option value="204" <?php if(isset($_POST['options']) && $_POST['options'] == '204') echo 'selected'; ?>>Mr.Manik Malhotra</option>
	<option value="205" <?php if(isset($_POST['options']) && $_POST['options'] == '205') echo 'selected'; ?>>Mrs. Vasishali Upadhyay</option>
	<option value="206" <?php if(isset($_POST['options']) && $_POST['options'] == '206') echo 'selected'; ?>>Ankush Tripathi</option>
  </select>
  <input type="submit" value="Submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $selectedOption = $_POST["options"];

  switch ($selectedOption) {
    case '201':
      $counselor_id = "201";
      break;
	case '202':
      $counselor_id = "202";
      break;
	case '203':
      $counselor_id = "203";
      break;
	case '204':
      $counselor_id = "204";
      break;
	case '205':
      $counselor_id = "205";
      break;
	case '206':
      $counselor_id = "206";
      break;
    default:
      echo "Invalid selection";
      break;
  }
}

?>

  <?php
   
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
if(isset($_GET['date'])){
  $date = $_GET['date'];
  $month = date("m", strtotime($date));
  $year = date("Y", strtotime($date));
  //echo $counselor_id;
  $stmt = $mysqli->prepare("select * from bookings where date=? AND counselor_id =?");
  $stmt->bind_param('ss', $date,$counselor_id);
  $bookings = array();
  if($stmt->execute()){
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $bookings[] = $row['timeslot'];
      }
    }
    $stmt->close();
  } // <-- Add a closing curly brace here
}  
if (isset($_POST["submit"])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $timeslot = $_POST['timeslot'];
  $date = $date;
  $counselor_id = $_POST['c_id'];
  $stmt = $mysqli->prepare("select * from bookings where date=? AND timeslot =? AND counselor_id =?");
  $stmt->bind_param('sss', $date, $timeslot, $counselor_id);
  if($stmt->execute()){
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $msg = "<div class='alert alert-danger'>Already Booked</div>";
    }
    else{
      $stmt = $mysqli->prepare("INSERT INTO bookings (timeslot, email, password, date, counselor_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sssss', $timeslot, $email, $password, $date, $counselor_id);
      $stmt->execute();
      //$msg = "<div class='alert alert-success'>Booking Successful</div>";
      $bookings[]=$timeslot;
      $stmt->close();
      $mysqli->close();
	  
	  echo '<script>
	   window.location.href = "our.php";
	   alert("Booking Successful")
	   </script>';
	   
	   exit();
    } 
  }
}	




  $duration = 60;
  $cleanup = 0;
  $start = "09:00";
  $end = "15:00";

  function timeslots($duration, $cleanup, $start, $end)
  {
    $start = new DateTime($start);
    $end = new DateTime($end);
    $interval = new DateInterval("PT".$duration."M");
    $cleanupInterval = new DateInterval("PT".$cleanup."M");
    $slots = array();

    for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($cleanupInterval)) 
	{
      $endPeriod = clone $intStart;
      $endPeriod->add($interval);
      if ($endPeriod > $end) 
	  {
        break;
      }

      $slots[] = $intStart->format("H:iA") . "-" . $endPeriod->format("H:iA");
    }

    return $slots;
  }
  ?>

  <div class="container">
    
    <hr>
    <div class="row">
      <div class="col-md-12">
        <?php echo isset($msg)?$msg:"";?>
      </div>
      <?php
      $timeslots = timeslots($duration, $cleanup, $start, $end);
      foreach ($timeslots as $ts)
	  {
?>

  <div class="col-md-2">
    <div class="form-group">
      <?php if(in_array($ts, $bookings))
	  { ?>
		<button class="btn btn-danger"><?php echo $ts; ?></button>
		<?php
	  }
	  else
	  {
        ?>
      <button class="btn btn-success book" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
      <?php
	  }?>
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
          <h4 class="modal-title">Booking: <span id="slot"></span></h4>
        </div>
        <div class="modal-body">
          <div class="row">
		  <div class="col-md-12">
		  <?php
		    echo isset($msg)?$msg:"";?>
		  </div>
            <div class="col-md-12">
              <form action="" method="post">
                <div class="form-group">
                  <label for="timeslot">Timeslot</label>
                  <input type="text" name="timeslot" id="timeslot" readonly>
                </div>
				<div class="form-group">
                  <label for="timeslot">Counselor ID</label>
                  <input type="text" name="c_id" id="c_id" readonly>
                </div>
               
              <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" name="email" id="email" class="form-control">
                </div>
				 <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" name="password" id="password" class="form-control">
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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function() {
      $(".btn.btn-success.book").on("click", function() {
        var timeslot = $(this).data("timeslot");
        $("#slot").text(timeslot);
        $("#timeslot").val(timeslot);
		var c_id = "<?php echo $counselor_id; ?>";
		$("#c_id").text(c_id);
        $("#c_id").val(c_id);
        $("#myModal").modal("show");
      });
    });
  </script>
  
  </div>
  
</body>

</html>