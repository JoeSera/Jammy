<?php
  ob_start();
  session_start();          //create session

  $timezone = date_default_timezone_set("Asia/Bangkok");

  $con = mysqli_connect("localhost", "root", "", "jammy");          // Connect with server, username, password, table

  if(mysqli_connect_errno()) {
    /*Check there is a error when connecting to sql server*/
    echo "Failed to connect: " . mysqli_connect_errno();
  }




 ?>
