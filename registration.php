<html>
<head>
<title></title>
<link rel="stylesheet" href="css/styleR.css">
</head>
<body class="bg">

<div class="main">
<div class="register">
<h2>Register Here</h2>
<form id="register" method="POST" action="connect.php">
<label>Name :</label><br> 
<input type="text" name="name" id="name" value=""><p>

<label>Email :</label><br>      
<input type="text" name="email" id="name" value=""><p>

<label>Password :</label><br>
<input type="password" name="pass" id="name"value=""><p>

<label>Gender :</label>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="gender" id="male" value="M">
        <span>Male</span>&nbsp;&nbsp;
		
        <input type="radio" name="gender" id="female" value="F">
        <span>Female</span>&nbsp;&nbsp;
        
		<input type="radio" name="gender" id="other" value="O">
        <span>Other</span><br><br>

<label>Language :</label>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="checkbox" name="lang" id="eng" value="E" >
          <span id="eng">English</span>&nbsp;&nbsp;
         
		 <input type="checkbox" name="lang" id="hindi" value="H">
          <span id="hindi">Hindi</span><br><br>
		  
		  
         
		 <a href="index.php"><input type="submit" name="submit"  value="submit" id="submit"></a>
</form>
</div>
</div>
</html>