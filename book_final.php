
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
<?php $date = $_GET['date']; ?>
<h1 class="text-center">Book for Date: <?php echo $date; ?></h1>
</div>

<div><br>
  <h3 align="center">Vaidehi Mishra</h3>
  <?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
if(isset($_GET['date'])){
  $date = $_GET['date'];
  $month = date("m", strtotime($date));
  $year = date("Y", strtotime($date));
   $counselor_id1 = 201;
  $stmt = $mysqli->prepare("select * from bookings where date=? AND counselor_id =?");
  $stmt->bind_param('ss', $date,$counselor_id1);
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
if (isset($_POST["submit1"])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $timeslot = $_POST['timeslot'];
  $date = $date;
  $counselor_id1 = $_POST['c_id'];
  $stmt = $mysqli->prepare("select * from bookings where date=? AND timeslot =? AND counselor_id =?");
  $stmt->bind_param('sss', $date, $timeslot, $counselor_id1);
  if($stmt->execute()){
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $msg = "<div class='alert alert-danger'>Already Booked</div>";
    }
    else{
      $stmt = $mysqli->prepare("INSERT INTO bookings (timeslot, email, password, date, counselor_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sssss', $timeslot, $email, $password, $date, $counselor_id1);
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
      <button class="btn btn-success book1" data-cid="<?php echo $counselor_id1; ?>" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
      <?php
	  }?>
    </div>
  </div>
  <?php
       }
      ?>
    </div>
  </div>

  <div id="myModal1" class="modal fade" role="dialog">
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
                  <button class="btn btn-primary" type="submit" name="submit1">Submit</button>
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
      $(".btn.btn-success.book1").on("click", function() {
        var timeslot = $(this).data("timeslot");
        $("#slot").text(timeslot);
        $("#timeslot").val(timeslot);
		//var counselor_id = $(this).data("c_id");
        //$("#slot").text(c_id);
        //$("#c_id").val(c_id);
        $("#myModal1").modal("show");
      });
    });
  </script>
  
  </div>
  
  <div><br>
  <h3 align="center">Nivedita Sharma</h3>
  <?php  
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
if(isset($_GET['date'])){
  $date = $_GET['date'];
  $month = date("m", strtotime($date));
  $year = date("Y", strtotime($date));
  $counselor_id2 = 202;
  $stmt = $mysqli->prepare("select * from bookings where date=? AND counselor_id =?");
  $stmt->bind_param('ss', $date, $counselor_id2);
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
if (isset($_POST["submit2"])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $timeslot = $_POST['timeslot'];
  $date = $date;
  $counselor_id2 = "202";
  $stmt = $mysqli->prepare("select * from bookings where date=? AND timeslot =? AND counselor_id = ?");
  $stmt->bind_param('sss', $date, $timeslot, $counselor_id2);
  if($stmt->execute()){
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $msg = "<div class='alert alert-danger'>Already Booked</div>";
    }
    else{
      $stmt = $mysqli->prepare("INSERT INTO bookings (timeslot, email, password, date, counselor_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sssss', $timeslot, $email, $password, $date, $counselor_id2);
      $stmt->execute();
      $msg = "<div class='alert alert-success'>Booking Successful</div>";
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

  function timeslots1($duration, $cleanup, $start, $end)
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
      $timeslots = timeslots1($duration, $cleanup, $start, $end);
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
      <button class="btn btn-success book2" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
      <?php
	  }?>
    </div>
  </div>
  <?php
       }
      ?>
    </div>
  </div>

  <div id="myModal2" class="modal fade" role="dialog">
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
                  <input type="text" name="c_id" id="c_id" value="202" readonly>
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
      $(".btn.btn-success.book2").on("click", function() {
        var timeslot = $(this).data("timeslot");
        $("#slot").text(timeslot);
        $("#timeslot").val(timeslot);
		var counselor_id = <?php echo $counselor_id2; ?>;
		$("#c_id").val(counselor_id);
        $("#myModal2").modal("show");
      });
    });
  </script>
  
  </div>
  
  <div><br>
  <h3 align="center">Shristi Awasthi</h3>
  <?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
if(isset($_GET['date'])){
  $date = $_GET['date'];
  $month = date("m", strtotime($date));
  $year = date("Y", strtotime($date));
   $counselor_id3 = 203;
  $stmt = $mysqli->prepare("select * from bookings where date=? AND counselor_id =?");
  $stmt->bind_param('ss', $date, $counselor_id3);
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
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $timeslot = $_POST['timeslot'];
  $date = $date;
  $counselor_id3 = "203";
  $stmt = $mysqli->prepare("select * from bookings where date=? AND timeslot =? AND counselor_id = ?");
  $stmt->bind_param('sss', $date, $timeslot, $counselor_id3);
  if($stmt->execute()){
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $msg = "<div class='alert alert-danger'>Already Booked</div>";
    }
    else{
      $stmt = $mysqli->prepare("INSERT INTO bookings (timeslot, email, password, date, counselor_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sssss', $timeslot, $email, $password, $date, $counselor_id3);
      $stmt->execute();
      $msg = "<div class='alert alert-success'>Booking Successful</div>";
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

  function timeslots2($duration, $cleanup, $start, $end)
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
      $timeslots = timeslots2($duration, $cleanup, $start, $end);
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
        $("#myModal").modal("show");
      });
    });
  </script>
  
  </div>
  
<div><br>
  <h3 align="center">Mr. Manik Malhotra</h3>
  <?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
if(isset($_GET['date'])){
  $date = $_GET['date'];
  $month = date("m", strtotime($date));
  $year = date("Y", strtotime($date));
   $counselor_id4 = 204;
  $stmt = $mysqli->prepare("select * from bookings where date=? AND counselor_id =?");
  $stmt->bind_param('ss', $date, $counselor_id4);
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
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $timeslot = $_POST['timeslot'];
  $date = $date;
  $counselor_id4 = "204";
  $stmt = $mysqli->prepare("select * from bookings where date=? AND timeslot =? AND counselor_id =?");
  $stmt->bind_param('sss', $date, $timeslot, $counselor_id4);
  if($stmt->execute()){
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $msg = "<div class='alert alert-danger'>Already Booked</div>";
    }
    else{
      $stmt = $mysqli->prepare("INSERT INTO bookings (timeslot, email, password, date, counselor_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sssss', $timeslot, $email, $password, $date, $counselor_id4);
      $stmt->execute();
      $msg = "<div class='alert alert-success'>Booking Successful</div>";
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

  function timeslots3($duration, $cleanup, $start, $end)
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
      $timeslots = timeslots3($duration, $cleanup, $start, $end);
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
        $("#myModal").modal("show");
      });
    });
  </script> 
  </div>
  
 <div><br>
  <h3 align="center">Mrs. Vaishali Upadhyay</h3>
  <?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
if(isset($_GET['date'])){
  $date = $_GET['date'];
  $month = date("m", strtotime($date));
  $year = date("Y", strtotime($date));
   $counselor_id5 = 205;
  $stmt = $mysqli->prepare("select * from bookings where date=? AND counselor_id =?");
  $stmt->bind_param('ss', $date, $counselor_id5);
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
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $timeslot = $_POST['timeslot'];
  $date = $date;
  $counselor_id5 = "205";
  $stmt = $mysqli->prepare("select * from bookings where date=? AND timeslot =? AND counselor_id = ?");
  $stmt->bind_param('sss', $date, $timeslot, $counselor_id5);
  if($stmt->execute()){
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $msg = "<div class='alert alert-danger'>Already Booked</div>";
    }
    else{
      $stmt = $mysqli->prepare("INSERT INTO bookings (timeslot, email, password, date, counselor_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sssss', $timeslot, $email, $password, $date, $counselor_id5);
      $stmt->execute();
      $msg = "<div class='alert alert-success'>Booking Successful</div>";
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

  function timeslots4($duration, $cleanup, $start, $end)
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
      $timeslots = timeslots4($duration, $cleanup, $start, $end);
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
        $("#myModal").modal("show");
      });
    });
  </script>
  
  </div>
  
<div><br>
  <h3 align="center">Ankush Tripathi</h3>
  <?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
if(isset($_GET['date'])){
  $date = $_GET['date'];
  $month = date("m", strtotime($date));
  $year = date("Y", strtotime($date));
   $counselor_id6 = 206;
  $stmt = $mysqli->prepare("select * from bookings where date=? AND counselor_id =?");
  $stmt->bind_param('ss', $date,$counselor_id6);
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
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $timeslot = $_POST['timeslot'];
  $date = $date;
  $counselor_id6 = "206";
  $stmt = $mysqli->prepare("select * from bookings where date=? AND timeslot =? AND counselor_id =?");
  $stmt->bind_param('sss', $date, $timeslot, $counselor_id6);
  if($stmt->execute()){
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $msg = "<div class='alert alert-danger'>Already Booked</div>";
    }
    else{
      $stmt = $mysqli->prepare("INSERT INTO bookings (timeslot, email, password, date, counselor_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sssss', $timeslot, $email, $password, $date, $counselor_id6);
      $stmt->execute();
      $msg = "<div class='alert alert-success'>Booking Successful</div>";
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

  function timeslots5($duration, $cleanup, $start, $end)
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
      $timeslots = timeslots5($duration, $cleanup, $start, $end);
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
        $("#myModal").modal("show");
      });
    });
  </script>
  
  </div>
  

  
  
</body>

</html>