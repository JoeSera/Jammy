<?php
include("includes/includedFiles.php");

if(isset($_GET['term'])) {
  $term = urldecode($_GET['term']);
}
else {
  $term = "";
}

 ?>


 <div class="searchContainer">

   <h4>Search for an Artist, Album or Song</h4>
   <input class="searchInput" type="text" name="" value="<?php echo $term; ?>" placeholder="Start Typing..." onfocus="var val=this.value; this.value=''; this.value= val;">

 </div>

 <script>

 $(".searchInput").focus();   //stay at the input field after refresh

 $(function() {
   //refreshes the page 2 secons after the user stopped typed

   $(".searchInput").keyup(function() {
     //somthing is typed, it cancel the timer
     clearTimeout(timer);

     //reset timer
     timer = setTimeout(function() {
       //after two seconds, will excute within the block
         var val = $(".searchInput").val();     //contains thwe value of the input field
         openPage("search.php?term=" + val)     //open page of the new term
     }, 2000)
   })
 })

 </script>

 <?php
 if($term == "") {
   //prevent loading all data when the search term is empty
   exit();
 }
 ?>

 <div class="trackListContainer borderBottom">

   <ul class="trackList">

     <h2>SONGS</h2>

     <?php

     $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '%$term%' LIMIT 10");    //any songs with title container $term

     if(mysqli_num_rows($songsQuery) == 0) {
       //if no results retured
       echo "<span class='noResults'> No songs found matching " . $term . "</span>";
     };


     $songIdArray = array();
     $i = 1;

     while($row = mysqli_fetch_array($songsQuery)) {

       if($i > 15) {
         break;
       }

       array_push($songIdArray, $row['id']);

       $albumSong = new Song($con, $row['id']);
       $albumArtist = $albumSong->getArtist();

       echo "<li class='trackListRow'>

               <div class='trackCount'>
                 <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)'>
                 <span class='trackCount'>" . $i . "</span>
               </div>

               <div class='trackInfo'>
                 <span class='trackName'>" . $albumSong->getTitle() . "</span>
                 <span class='artistName'>" . $albumArtist->getName() . "</span>
               </div>

               <div class='trackOption'>
                 <input type='hidden' class='songId' value='" . $albumSong->getid() . "'>
                 <img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
               </div>

               <div class='trackDuration'>
                 <span class='duration'>" . $albumSong->getDuration() . "</span>
               </div>
             </li>";

       $i++;

     }

     ?>

     <script>

       var tempSongIds = '<?php echo json_encode($songIdArray); ?>';   //convert php to json format

       tempPlaylist = JSON.parse(tempSongIds);   //convert json to object

     </script>


   </ul>

 </div>

 <div class="artistContainer borderBottom">

   <h2>ARTISTS</h2>

   <?php
   $artistsQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

   //search for artist and load div

   if(mysqli_num_rows($artistsQuery) == 0) {
     //if no results retured
     echo "<span class='noResults'> No artists found matching " . $term . "</span>";
   };

   while($row = mysqli_fetch_array($artistsQuery)) {
     $artistFound = new Artist($con, $row['id']);

     echo "<div class='searchResultRow'>

            <div class='artistName'>

              <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $artistFound->getId() . ")'>

                " . $artistFound->getName() . "

              </span>

            </div>

          </div>";
   }


    ?>

 </div>

 <div class="gridViewContainer">

   <h2>ALBUMS</h2>

   <?php

     $albumQuery = mysqli_query($con, "SELECT * from albums WHERE title LIKE '$term%' LIMIT 10");

     if(mysqli_num_rows($albumQuery) == 0) {
       //if no results retured
       echo "<span class='noResults'> No album found matching " . $term . "</span>";
     };

     while($row = mysqli_fetch_array($albumQuery)) {
       // Upload all the details in grid form
       echo "<div class='gridViewItem'>
               <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
                 <img src='" . $row['artworkPath'] . "'>

                 <div class='gridViewInfo'>
                 " .
                   $row['title']

                 . "
                 </div>
               </span>
             </div>";
     }


    ?>
 </div>

 <nav class="optionsMenu">
   <input type="hidden" class="songId" name="" value="">
   <?php echo Playlist::getPlaylistsdropdown($con, $userLoggedIn->getUsername()); ?>
 </nav>
