<?php
include("includes/includedFiles.php");

 ?>


 <div class="playlistContainer">

   <div class="gridViewContainer">
     <h2>PLAYLISTS</h2>

     <div class="buttonItems">

       <button class="button green" type="button" name="button" onclick="createPlaylist()">NEW PLAYLISTS</button>

     </div>

     <?php
      $username = $userLoggedIn->getUsername();
      $playlistQuery = mysqli_query($con, "SELECT * from playlists WHERE owner='$username'");

      if(mysqli_num_rows($playlistQuery) == 0) {
         //if no results retured
         echo "<span class='noResults'> You dont have any playlist yet</span>";
       };

      while($row = mysqli_fetch_array($playlistQuery)) {
         // Upload all the details in grid form

         $playlist = new Playlist($con, $row);   //iterate through the table as row and create class for Playlist
         echo "<div class='gridViewItem' role='link' tabindex='0' onclick='openPage(\"playlist.php?id=" . $playlist->getId() . "\")'>

                  <div class='playlistImage'>
                    <img src='assets/images/icons/playlist.png'>
                  </div>
                 <div class='gridViewInfo'>
                 " .
                   $playlist->getName()

                 . "
                 </div>
               </div>";
       }
      ?>



   </div>

 </div>
