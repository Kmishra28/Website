<?php
   include("connection.php");
   
   
  if(isset($_POST['submit'])){
  $name=$_POST["name"];
  $password=$_POST["pass"];
  echo $name;
  echo $password;
  
  $sql = "select * from details where name= '$name' and password = '$password'";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $count = mysqli_num_rows($result);
  if($count==1){
	  $url = "calendarnew.php";
	  header("Location:" . $url);
	  
  }
  else {
	  echo '<script>
	   window.location.href = "log.php";
	   alert("Login failed. Invalid name or password!!! Please try again")
	   </script>';
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" cintent="width,device-width,initial-scale=1.e">
  <title>Document</title>
  <link rel="stylesheet" type="text/css" href="css/style_new.css">
  
</head>
<body>
<div id="register">
        
		 <form name="signup" action="registration.php">
		 
		    <label id="lbl">New User</label>
			<input type="submit" id="btn" value="Registration" name="Sign Up">
		
		  </form>
	 </div>

   <div id="form">
         <h1>Login Form</h1>
		 
		 <form name="form"  method="POST">
		    <lable>Name:</lable>
			<input type="text" id="name" name="name"></br></br>
			<lable>Password</lable>
			<input type="password" id="password" name="pass"></br></br>
			<input type="submit" id="btn" value="login" name="submit"/>
		
		  </form>
	 </div>
	 <script>
  function signupFunction() {
    window.location.href="registration.php";
  }
 </script>
  </body>
  </html>
			
