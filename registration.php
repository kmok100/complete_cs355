<?php
//Below is copy and paste to show errors on white page
// ----------------------------------------------------------------------------------------------------
// - Display Errors
// ----------------------------------------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_errors', 'On');
ini_set('html_errors', 0);

// ----------------------------------------------------------------------------------------------------
// - Error Reporting
// ----------------------------------------------------------------------------------------------------
error_reporting(-1);

// ----------------------------------------------------------------------------------------------------
// - Shutdown Handler
// ----------------------------------------------------------------------------------------------------
function ShutdownHandler()
{
    if(@is_array($error = @error_get_last()))
    {
        return(@call_user_func_array('ErrorHandler', $error));
    };

    return(TRUE);
};

register_shutdown_function('ShutdownHandler');

// ----------------------------------------------------------------------------------------------------
// - Error Handler
// ----------------------------------------------------------------------------------------------------
function ErrorHandler($type, $message, $file, $line)
{
    $_ERRORS = Array(
        0x0001 => 'E_ERROR',
        0x0002 => 'E_WARNING',
        0x0004 => 'E_PARSE',
        0x0008 => 'E_NOTICE',
        0x0010 => 'E_CORE_ERROR',
        0x0020 => 'E_CORE_WARNING',
        0x0040 => 'E_COMPILE_ERROR',
        0x0080 => 'E_COMPILE_WARNING',
        0x0100 => 'E_USER_ERROR',
        0x0200 => 'E_USER_WARNING',
        0x0400 => 'E_USER_NOTICE',
        0x0800 => 'E_STRICT',
        0x1000 => 'E_RECOVERABLE_ERROR',
        0x2000 => 'E_DEPRECATED',
        0x4000 => 'E_USER_DEPRECATED'
    );

    if(!@is_string($name = @array_search($type, @array_flip($_ERRORS))))
    {
        $name = 'E_UNKNOWN';
    };

    return(print(@sprintf("%s Error in file \xBB%s\xAB at line %d: %s\n", $name, @basename($file), $line, $message)));
};

$old_error_handler = set_error_handler("ErrorHandler");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// other php code
include("config.php");
session_start();

// connect to the database

$message="";
$username = "";
$email = ""; 

if (isset($_POST['register']))
{
  // receive all input values from the form
  $username = $_POST['username'];
  $password_1 = $_POST['password1'];
  $password_2 = $_POST['password2'];
  $email = $_POST['email'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $gender = $_POST['gender'];  //M,F,O
  $weight = $_POST['weight'];
  $height = $_POST['height'];
  $bmi = $weight * 703 / ($height * $height);
  $joined = date("Y-m-d");
  $today = date("Y-m-d H:i:s"); 
  // first check the database to make sure 
  // a user does not already exist with the same username 
  //Change below to match our db
  $user_check_query = "SELECT * FROM userTable WHERE userName='$username' ";
  $result = mysqli_query($conn, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  

  if ($password_1 != $password_2) 
  {
	  echo ("The two passwords do not match");
  }
  else if ($user) // if user exists
  { 
    if ($user['userName'] == $username)
    {
      echo("Username already exists. Try a new one");
    }

  }
  else
  {
    //remove echo below to hide username and password
    //echo $username . " " . $password_1;

  	$query = "INSERT INTO userTable (userName, password, fName, lName, gender, registerDate, registerWeight, registerHeight, registerBmi, email) 
  			  VALUES('$username', '$password_1', '$fname', '$lname', '$gender', '$joined', '$weight', '$height', '$bmi', '$email')";
         
  	mysqli_query($conn, $query);

	$user_check_query = "SELECT * FROM userTable WHERE userName='$username' ";
 	 $result = mysqli_query($conn, $user_check_query);
  	$user1 = mysqli_fetch_assoc($result);

	$_SESSION["userID"] = $user1['userId'];
  $userID = $_SESSION["userID"];
//Need to get this inserted into the weightTable. Uncommenting this gives white page 
	$query1 = "INSERT INTO weightTable (userId, userName, weight, weightDate, bmi, weightTime, previousWeight) 
    VALUES('$userID', '$username', '$weight', '$joined', '$bmi', '$today', '$weight' )";

	mysqli_query($conn, $query1);

  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";

  // the message
  $msg = "Hello $fname,\n\nWelcome to weigh-in, your account has been created.";

  // use wordwrap() if lines are longer than 70 characters
  $msg = wordwrap($msg,70);

  // send email
  //mail("$email","Welcome to Weigh-in!",$msg);
    //Echo below redirects to info.html. Uncomment out later
    echo '<script>window.location.href = "info.php";</script>';
    
  }
}
?>

<html>
<head>
<title>Register</title>
<style>
#frmRegistration { 
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
<div style="display:block;margin:0px auto;">
<form action="" method="post" id="frmRegistration">
  <div class="field-group">
		<div><label for="login">Username</label></div>
		<div><input name="username" type="text" class="input-field" maxlength="45" required></div>
	</div>
	<div class="field-group">
		<div><label for="login">Password</label></div>
		<div><input name="password1" type="password" class="input-field" maxlength="45" required> </div>
	</div>
  <div class="field-group">
		<div><label for="login">Re-enter Password</label></div> 
		<div><input name="password2" type="password" class="input-field" maxlength="45" required> </div>
  </div> 
	<div class="field-group">
		<div><label for="login">Email</label></div>
		<div><input name="email" type="text" class="input-field" placeholder="username@email.com" oninvalid="setCustomValidity('Please enter a valid email address.')" oninput="setCustomValidity('')" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,3}$" required maxlength="45">
  </div>
	<div class="field-group">
		<div><label for="login">First Name</label></div>
		<div><input name="fname" type="text" class="input-field" maxlength="45" required> </div>
	</div>
  <div class="field-group">
		<div><label for="login">Last Name</label></div>
		<div><input name="lname" type="text" class="input-field" maxlength="45" required> </div>
	</div>
  <div id="Gender">
        <select name='gender' required>
          <option disabled selected value>Gender</option>
          <option value='M'>Male</option>
          <option value='F'>Female</option>
          <option value='O'>Other</option>
        </select>
  </div>
  <div class="field-group">
		<div><label for="login">Weight (in lb)</label></div> 
		<div><input name="weight" type="number" class="input-field" min="0" max="400" pattern="^\d*(\.\d{0,2})?$" step="0.01" maxlength="5" required> </div>
	</div>
	<div class="field-group">
		<div><label for="login">Height (in inches)</label></div>
		<div><input name="height" type="number" class="input-field" min="0" max="100" pattern="^\d*(\.\d{0,2})?$" max="3" required> </div>
	</div>
  <div><input type="submit" name="register" value="Register" class="form-submit-button" ></span></div>
</div>       
</form>
</div>
</div>
</body></html>
