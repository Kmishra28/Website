<?php
  include("connection.php");
   $c_id = $_GET['c_id'];
     echo $c_id;
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
	  $url = "calendarnew.php?c_id=$c_id";
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