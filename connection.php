<?php
  $servername="localhost";
  $name="root";
  $password="";
  $db_name="customer";
  $conn=new mysqli($servername, $name, $password, $db_name,);
  if($conn->connect_error){
	  die("Connection failed".$conn->connect_error);
  }	  
?>






