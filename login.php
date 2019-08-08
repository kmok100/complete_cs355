<?php
   include("config.php");
session_start();
	
$message="";
if(!empty($_POST["login"])) {
  
  //Table named login with UserID, Username, Password as columns. Change to fit offical columns as neccessary
  //Tenzin's table "SELECT * from userTable WHERE username='" . $_POST["user_name"] . "' and password = '". $_POST["password"]."'");
	$result = mysqli_query($conn,"SELECT * from userTable WHERE username='" . $_POST["user_name"] . "' and password = '". $_POST["password"]."'");

	$row  = mysqli_fetch_array($result);
	if(is_array($row)) {
	$_SESSION["username"] = $_POST["user_name"];
	$_SESSION["userID"] = $row['userId'];
  //Echo below redirects to info.html
  echo '<script>window.location.href = "info.php";</script>';

	} else {
	echo "Invalid Username or Password!";
	}
}
if(!empty($_POST["logout"])) {
	$_SESSION["user_id"] = "";
	session_destroy();
}
?>
<html>
<head>
<title>User Login</title>
<style>
#frmLogin { 
	padding: 20px 60px;
	background: #B6E0FF;
	color: #555;
	display: inline-block;
	border-radius: 4px; 
}
.field-group { 
	margin:15px 0px; 
}
.input-field {
	padding: 8px;width: 200px;
	border: #A3C3E7 1px solid;
	border-radius: 4px; 
}
.form-submit-button {
	background: #65C370;
	border: 0;
	padding: 8px 20px;
	border-radius: 4px;
	color: #FFF;
	text-transform: uppercase; 
}
.member-dashboard {
	padding: 40px;
	background: #D2EDD5;
	color: #555;
	border-radius: 4px;
	display: inline-block;
	text-align:center; 
}
.logout-button {
	color: #09F;
	text-decoration: none;
	background: none;
	border: none;
	padding: 0px;
	cursor: pointer;
}
.error-message {
	text-align:center;
	color:#FF0000;
}
.demo-content label{
	width:auto;
}
</style>
</head>
<body>
<div>
<div style="display:block; margin:0px auto;">
<form action="" method="post" id="frmLogin">	
	<div class="field-group">
		<div><label for="login">Username</label></div>
		<div><input name="user_name" type="text" class="input-field"></div>
	</div>
	<div class="field-group">
		<div><label for="password">Password</label></div>
		<div><input name="password" type="password" class="input-field"> </div>
	</div>
	<div class="field-group">
		<div><input type="submit" name="login" value="Login" class="form-submit-button"></span></div>
	</div>  
    <a href="registration.php">Don't have an account? Register here</a>
</form>
</div>
</div>
</body></html>