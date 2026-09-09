<?php

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['pass'];
$gender = $_POST['gender'];
$language = $_POST['lang'];

// Database Connection
$conn = new mysqli('localhost', 'root', '', 'customer');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
} else {
    $stmt = $conn->prepare("INSERT INTO details (name, email, password, gender, language) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $name, $email, $password, $gender, $language);
    if ($stmt->execute()) {
        $success_message = "Registration successful!";
    } else {
        $error_message = "Registration failed. Please try again.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Result</title>
    <style>
        .message-container {
            text-align: center;
            margin-top: 200px;
        }
        .success-message {
            color: green;
            font-size: 24px;
        }
        .error-message {
            color: red;
            font-size: 24px;
        }
		.button{
			width:20%;
			height:30px;
			font-size:25px;
			background:black;
			color:white;
		}
    </style>
</head>
<body bgcolor="#FEE6E1">
    <div class="message-container">
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>
	<form action="index.php" method="post" align="center">
        <!-- Your form fields here -->
        <br><input type="submit" value="Go Back" class="button">
    </form>
</body>
</html>