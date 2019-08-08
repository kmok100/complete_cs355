<?php
session_start();
session_destroy();
?>

<html>
  <body>
  <p>You log out</p>
  </body>
</html>

<!-- add this somewhere on info.php
<a href="logout.php">Logout</a> 
Find the line "login first" in info.php and redirect it to login.php
-->