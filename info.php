<html>
<?php
// ----------------------------------------------------------------------------------------------------
// - Display Errors
// ----------------------------------------------------------------------------------------------------
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
if(isset($_SESSION['username']) && !empty($_SESSION['username']))
{ 
  // echo "User is ". $_SESSION["username"];
//Not set yet  
//echo "User id is" .  $_SESSION["userId"];
  $name =  $_SESSION["username"];
  $userId = $_SESSION["userID"];
}
  else
{ 
  echo "login first";
}

$result = mysqli_query($conn,"
SELECT registerHeight FROM userTable WHERE userName = '$name';");
$row  = mysqli_fetch_array($result);
$height = $row['registerHeight'];






$today = date("Y-m-d H:i:s"); 
$time = date('H:i');

if (isset($_POST['newWeight']))
{
  
$weight = $_POST['weight'];
  $bmi = $weight * 703 / ($height * $height); 
  
  $result = mysqli_query($conn,"
SELECT * FROM weightTable WHERE userName = '$name' ORDER BY weightDate DESC LIMIT 1;");
$row  = mysqli_fetch_array($result);
$prev = $row['weight'];
$weightLoss = $row['previousWeight'] - $weight;

  $query1 = "INSERT INTO weightTable (userId, userName, weight, weightDate, bmi, weightTime, previousWeight) 
    VALUES('$userId', '$name', '$weight', '$today', '$bmi', '$time', '$prev' )";

	mysqli_query($conn, $query1);

  
}

?>
<html>
  <style> 
    body
    {
      background-color: #1cdcf2;
    }
    table td
    {
      background-color: #fff;
      border: solid 0px black;
      text-align: center;
    }
    .center_element
    {
      margin-left: auto;
      margin-right: auto;
    }
    .rounded_border
    {
    }
  </style>
  <body>
<h1>Welcome back!</h1>
<a href="logout.php">Log out</a>
  <p>User info and stuff goes here</p>
  <table cellspacing="10" class="center_element">
    <tr>
      <tr>
        <td style="height:200px; width:200px;">
        img
        </td>
      </tr>
      <tr>
        <td>
<?php
        echo $name;
?>
        </td>
      </tr>
      <tr>
        <td>
           <table cellspacing="5" style="width:200px;">
          <tr>
            <td>weight</td>
            <td>bmi</td>
            <td>weight loss</td>
          </tr>
          <tr>
            <td>
<?php

//Should join with weightTable where userID is the same
$result = mysqli_query($conn,"
SELECT weight,bmi from userTable INNER JOIN weightTable on userTable.userName = weightTable.userName where userTable.userName = '$name'  
ORDER BY weightDate DESC 
LIMIT 1;");
$row  = mysqli_fetch_array($result);
  echo $row['weight'];



?>
          </td>
            
          <td> <?php echo $row['bmi']; ?>

</td>
<td>
  <?php
  $result = mysqli_query($conn,"
SELECT weight,previousWeight from userTable INNER JOIN weightTable on userTable.userName = weightTable.userName where userTable.userName = '$name'  
ORDER BY weightDate DESC 
LIMIT 1;");
$row  = mysqli_fetch_array($result);
  echo $row['weight'] - $row['previousWeight'];

?>
</td>
</tr>
<tr>
          </tr>
           <tr>
            <td></td>
           <td>
           <form action="" method="post" id="frmWeigh"> 
          <div class="field-group">
            <label for="weight">new weight</label>
            <input name="weight" type="number" class="input-field" min="0" max="400" pattern="^\d*(\.\d{0,2})?$" maxlength="5" required>   
            <input type="submit" name="newWeight" value="weigh-in" class="form-submit-button">
          </div>  
           </form>
           </td>
           <td></td>
           </tr>
        </table>
        </td>
      </tr>
    </tr>
  </table>
  </body>
</html>