<?php

$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");
$resultArray = array();

while($row = mysqli_fetch_array($songQuery)){
  // push id of 10 random songs to a array
  array_push($resultArray, $row['id']);
}

// convert php array to json (so it can used by javascript)
$jsonArray = json_encode($resultArray);

?>

<script type="text/javascript">

$(document).ready(function() {
  //function starts with the page is loaded
  var newPlaylist = <?php echo $jsonArray; ?>;
  audioElement = new Audio();
  setTrack(newPlaylist[0], newPlaylist, false);
  updateVolumeProgressBar(audioElement.audio);    //set volumeBar progress to 1 at default

  $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) {
    //prevent default behavior (exmample highlight when mouse drag) on the norplaying bar
    e.preventDefault();
  });

  $(".playbackBar .progressBar").mousedown(function() {
    //when the selector is click and mouse is down, it will set the var mouseDown (in script.js) to true
    mouseDown = true;
  });

  $(".playbackBar .progressBar").mousemove(function(e) {
    //mouse is moved inside the selector, it will set the var mouseDown (inscript.js) to true
    if(mouseDown) {
      //set time of song depending on position of mouse
      timeFromOffset(e, this);
    }
  });

  $(".playbackBar .progressBar").mouseup(function(e) {
    timeFromOffset(e, this);
  });

  $(document).mouseup(function() {
    //use document to make sure mouse up is recognized where ever the mouse is at on the page
    mouseDown = false;
  });

  $(".volumeBar .progressBar").mousedown(function() {
    mouseDown = true;
  });

  $(".volumeBar .progressBar").mousemove(function(e) {
    if(mouseDown) {
      var percentage = e.offsetX / $(this).width();

      if(percentage >= 0 && percentage <= 1) {
        //only set volume if the offset is value is between 0 and 1 meaning the mouse is within the volume bar
        audioElement.audio.volume = percentage;
      }
    }
  });

  $(".volumeBar .progressBar").mouseup(function(e) {
    var percentage = e.offsetX / $(this).width();

    if(percentage >= 0 && percentage <= 1) {
      //only set volume if the offset is value is between 0 and 1 meaning the mouse is within the volume bar
      audioElement.audio.volume = percentage;
    }
  });

  $(document).mouseup(function() {
    //use document to make sure mouse up is recognized where ever the mouse is at on the page
    mouseDown = false;
  });

});

function timeFromOffset(mouse, progressBar) {
  //get the time of the song where it is clicked on the progress bar from the position clicked
  var percentage = mouse.offsetX / $(progressBar).width() * 100;    //get offset value on the x axis and divided by the width of the progress bar
  var seconds = audioElement.audio.duration * (percentage / 100);
  audioElement.setTime(seconds);
}

function prevSong() {
  if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
    //when press previous button it will go to the start of the song if the song is more than 3 second in or it is the first song in the playlist
    audioElement.setTime(0);
  }
  else {
    currentIndex--;
    setTrack(currentPlaylist[currentIndex], currentPlaylist, false);

  }

}

function nextSong() {
  if(repeat == true) {
    //set the song duration back to zero and play the song from the start if repeat is true
    audioElement.setTime(0);
    playSong();
    return;
  }
  if(currentIndex == currentPlaylist.length - 1) {
    //if the current index is the last song set index back to zero as it have reached the last song in the playlist
    currentIndex = 0;
  }
  else {
    currentIndex++;   //go to get song id
  }

  var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];    //get the track with the new trackId
  setTrack(trackToPlay, currentPlaylist, false);
}

function setRepeat() {
  repeat = !repeat;   //change the boonlean to opposite value of the current value
  var imageName = repeat ? "repeat-active.png" : "repeat.png";    //change icon according to boonlean value
  $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);    //change the repeat icon
}

function setMute() {
  audioElement.audio.muted = !audioElement.audio.muted    //change the boonlean to opposite value of the current value
  var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";    //change icon according to boonlean value
  $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);    //change the repeat icon
}

function setShuffle() {
  shuffle = !shuffle;   //change the boonlean to opposite value of the current value
  var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";    //change icon according to boonlean value
  $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);    //change the repeat icon

  if(shuffle) {
    //randomize playlist
    shuffleArray(shufflePlaylist);
    currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id)    //set current index to the playig song after shuffle to prevent same song playing
  }
  else {
    //return to normal playlist
    currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id)    //set current index to the playig song after shuffle to prevent same song playing

  }

}

function shuffleArray(a) {
  //randomzie array
  var j, x, i;
  for (i = a.length - 1; i > 0; i--) {
      j = Math.floor(Math.random() * (i + 1));
      x = a[i];
      a[i] = a[j];
      a[j] = x;
  }
}

function setTrack(trackId, newPlaylist, play) {

  if(newPlaylist != currentPlaylist) {
    //create standard playlist and a shuffled playlist
    currentPlaylist = newPlaylist;
    shufflePlaylist = currentPlaylist.slice();   //create a copy of the newPlaylist array so change will not be refelcted on both variables
    shuffleArray(shufflePlaylist);
  }

  if(shuffle) {
    currentIndex = shufflePlaylist.indexOf(trackId);    //index of the trackId or the current song select
  }
  else {
    currentIndex = currentPlaylist.indexOf(trackId);    //index of the trackId or the current song select
  }
  pauseSong();

  //retrieve songs from php database without having to refresh the page
  //specific the page, access songId key and pass trackId
  $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {


    var track = JSON.parse(data);   //convert from string to object

    $(".trackInfo .trackName span").text(track.title);   //Add song title to .trackName div by accessing track object

    $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
      //retrieve artist from php database only after the songId is set
      var artist = JSON.parse(data);

      $(".trackInfo .artistName span").text(artist.name);
      $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id + "')");
    });

    //retrieve album database from php database to set artwork src
    $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {

      var album = JSON.parse(data);

      $(".content .albumLink img").attr("src", album.artworkPath);   //add attribute of path to the src attribute in img
      $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
      $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id=" + album.id + "')");
    });

    audioElement.setTrack(track);   //pass in the track object

    if(play == true) {
      playSong();
    }

  });

}

function playSong() {

  if(audioElement.audio.currentTime == 0) {
    //update the number of plays if the song is played from the beginning using ajax
    $.post("includes/handlers/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
  }

  $(".controlButton.play").hide();
  $(".controlButton.pause").show();
  audioElement.play();
}

function pauseSong() {
  $(".controlButton.play").show();
  $(".controlButton.pause").hide();
  audioElement.pause();
}

</script>


<div id="nowPlayingBarContainer">
  <div id="nowPlayingBar">

    <div id="nowPlayingLeft">
      <div class="content">
        <span class="albumLink">
          <img role="link" tabindex="0" class="albumArtWork" src="" alt="">
        </span>

        <div class="trackInfo">

          <span class="trackName">
            <span role="link" tabindex="0"></span>
          </span>

          <span class="artistName">
            <span role="link" tabindex="0"></span>
          </span>

        </div>
      </div>
    </div>

    <div id="nowPlayingCenter">

      <div class="content playerControls">

        <div class="buttons">

          <button class="controlButton shuffle" title="Shuffle Button" onclick="setShuffle()">
            <img src="assets/images/icons/shuffle.png" alt="shuffle">
          </button>

          <button class="controlButton previous" title="Previous Button" onclick="prevSong()">
            <img src="assets/images/icons/previous.png" alt="previous">
          </button>

          <button class="controlButton play" title="Play Button" onClick="playSong()">
            <img src="assets/images/icons/play.png" alt="play">
          </button>

          <button class="controlButton pause" title="Pause Button" style="display:none;" onClick="pauseSong()">
            <img src="assets/images/icons/pause.png" alt="pause">
          </button>

          <button class="controlButton next" title="Next Button" onClick="nextSong()">
            <img src="assets/images/icons/next.png" alt="next">
          </button>

          <button class="controlButton repeat" title="Repeat Button" onClick="setRepeat()">
            <img src="assets/images/icons/repeat.png" alt="repeat">
          </button>

        </div>

        <div class="playbackBar">

          <span class="progressTime current">0.00</span>

          <div class="progressBar">
            <div class="progressBarBg">
              <div class="progress"></div>
            </div>
          </div>

          <span class="progressTime remaining">0.00</span>

        </div>

      </div>

    </div>

    <div id="nowPlayingRight">

      <div class="volumeBar">

        <button class="controlButton volume" title="Volume Button" onclick="setMute()">
          <img src="assets/images/icons/volume.png" alt="Volume">
        </button>

        <div class="progressBar">
          <div class="progressBarBg">
            <div class="progress"></div>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>
