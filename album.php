<?php
include("includes/includedFiles.php");

if(isset($_GET['id'])) {
  $albumId = $_GET['id'];
} else {
  header("Location: index.php");
}

$album = new Album($con, $albumId);
$artist = $album->getArtist();
$artistId = $artist->getId();
?>

<div class="entityInfo">

  <div class="leftSection">
    <img src="<?php echo $album->getArtworkPath(); ?>" alt="">
  </div>

  <div class="rightSection">
    <h2><?php echo $album->getTitle(); ?></h2>
    <p>By <?php echo $artist->getName(); ?></p>
    <p><?php echo $album->getNumberOfSongs(); ?> songs</p>
  </div>

</div>

<div class="trackListContainer">

  <ul class="trackList">

    <?php
    $songIdArray = $album->getSongIds();
    $i = 1;

    foreach($songIdArray as $songId) {

      $albumSong = new Song($con, $songId);
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

<nav class="optionsMenu">

  <input type="hidden" class="songId" name="" value="">
  <?php echo Playlist::getPlaylistsdropdown($con, $userLoggedIn->getUsername()); ?>
  <option class="item" value="">Item1</option>
  <option class="item" value="">Item2</option>
</nav>
