<?php
include("../../config/config.php");

if(isset($_POST['playlistId'])) {
  $playlistId = $_POST['playlistId'];

  $playlistQuery = mysqli_query($con, "DELETE FROM playlists WHERE id='$playlistId'");
  $songQuery = mysqli_query($con, "DELETE FROM playlistSongs WHERE playlistId='$playlistId'");    // Also remove the songs refering to the playlist that is deleted
}
else {
  echo "PlaylistId was not passed into deletePlaylist.php";

}

 ?>
