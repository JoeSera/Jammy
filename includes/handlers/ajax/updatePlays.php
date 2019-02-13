<?php
include("../../config/config.php");

if(isset($_POST['songId'])) {
  //if the songId is set, add 1 count to the number of plays of the song with the songId
  $songId = $_POST['songId'];

  $query = mysqli_query($con, "UPDATE songs SET plays = plays + 1 WHERE id='$songId'");


}
 ?>
