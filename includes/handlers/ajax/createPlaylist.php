<?php

include("../../config/config.php");

if(isset($_POST['name']) && isset($_POST['username'])) {
  //name and user a from createPlaylist function in script.js

  $name = $_POST['name'];
  $username = $_POST['username'];
  $date = date("Y-m-d");

  $query = mysqli_query($con, "INSERT INTO playlists VALUES('', '$name', '$username', '$date')");   //insert the values into the mysqli datebase

}
else {
  echo "Name or username parameters not passed into file";
}

 ?>
