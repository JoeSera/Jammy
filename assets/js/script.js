var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;    //keep track whether ther mouse if pressed dow nor not
var currentIndex = 0;   //keep track of song in the playlist
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

$(document).click(function(click) {
  var target = $(click.target);   //set target as the element that user clicked on

  if(!target.hasClass("item") && !target.hasClass("optionsButton")) {
    // if the target clicked does not have class item or optionsMenu (clicking outside the optionmenu) hide the option menu
    hideOptionsMenu();
  }
});

$(window).scroll(function() {
  //when user scolls the window
  hideOptionsMenu();    // as soon as user start scrolling options menu will disappear
});

function openPage(url) {
  //open different page dynamically, ie only changing the content of main content

  if(timer != null) {
    //everytime we open a new page clear the timer so it will stop the search function
    clearTimeout(timer);
  }

  if(url.indexOf("?") == -1) {
    //find if the url container ?, if it does not find it it return -1
    url = url + "?";
  }

  var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);   //convert any charaters that cannot be used in url to a url friendsly charaters
  $("#mainContent").load(encodedUrl);
  $("body").scrollTop(0);   //When change page automatically scroll top
  history.pushState(null, null, url);   //loads the url when ajax is called into the browser history
}

function hideOptionsMenu() {
  var menu  = $(".optionsMenu");

  if(menu.css("display") != "none") {
    // if the display is not none ie is showing, hide the menu
    menu.css({"display" : "none"});
  }
}

function showOptionsMenu(button) {
  // get the x y position of the button clicked and show option menu beside it
  var menu = $(".optionsMenu");

  var menuWidth = menu.width();

  var scrollTop = $(window).scrollTop();    //get the distance from top of window to top of document
  var elementOffset = $(button).offset().top;    // change to jquery element and get distance from the top of the document

  var top = elementOffset - scrollTop;
  var left = $(button).position().left;   //how far from the left the button

  menu.css({"top" : top + "px",
            "left" : left - menuWidth + "px",
             "display" : "inline"});
}

function formatTime(seconds) {
  //convert seconds and change to minutes and seconds
  var time = Math.round(seconds);   //rounnd up the seconds
  var minutes = Math.floor(time / 60);   //round down
  var seconds = time - (minutes * 60)   //get remaining seconds

  var extraZero = (seconds < 10) ? "0" : "";

  return minutes + ":" + extraZero + seconds;
 }

 function createPlaylist() {
   var popup = prompt("Please enter the name of your playlist");    //open a alert pop up using built in function in browsers

   if(popup != null) {
     //when there is a value inputed into the pop up

     $.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn}).done(function(error) {
       //run function when ajax returns

       if(error != "") {
         //check for errors in ajax called
         alert(error);
         return;
       }

       openPage("yourMusic.php");   //page refresh
     });
   }
 }

 function deletePlaylist(playlistId) {
   var popup = confirm("Are you sure you want to delete this playlist?");

   if(popup) {
     $.post("includes/handlers/ajax/deletePlaylist.php", {playlistId: playlistId}).done(function(error) {
       //run function when ajax returns

       if(error != "") {
         //check for errors in ajax called
         alert(error);
         return;
       }

       openPage("yourMusic.php");   //page refresh
     });
   }
 }

 function updateTimeProgressBar(audio) {
   //update the time of the songs bother current and remaining and change progress bar css
   $(".progressTime.current").text(formatTime(audio.currentTime));
   $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));
   var progress = (audio.currentTime / audio.duration) * 100;   //get percentage of current time against total duration to update progress bar

   $(".playbackBar .progress").css("width", progress + "%");   // add width css to progress bar
 }

 function updateVolumeProgressBar(audio) {
   //update volume bar according to the song volume
   var volume = (audio.volume) * 100;   //get percentage of current time against total duration to update progress bar

   $(".volumeBar .progress").css("width", volume + "%");   // add width css to progress bar
 }

 function playFirstSong() {
   setTrack(tempPlaylist[0], tempPlaylist, true);
 }

function Audio() {

  //create variables
  this.currentlyPlaying;

  //set html for audio element
  this.audio = document.createElement('audio');

  //create event listener
  this.audio.addEventListener("canplay", function() {
    //when canplay occurs, run function below
    //'this.' refers to the object the event was called on
    var duration = formatTime(this.duration);
    $(".progressTime.remaining").text(duration);
  });

  this.audio.addEventListener("timeupdate", function() {
    if(this.duration) {
      updateTimeProgressBar(this);
    }
  });

  this.audio.addEventListener("volumechange", function() {
    //run function updateVolumeProgressBar when the volume of audio is changed
    updateVolumeProgressBar(this);
  });

  this.audio.addEventListener("ended", function() {
    //run nextsong function when the song has ended
    nextSong();
  });

  this.setTrack = function(track) {
    //get json oject and set track info
    this.currentlyPlaying = track;    //update the current track that is playing
    this.audio.src = track.path;    //get the path of the audio from track oject path key
  }

  this.play = function() {
    //play the audio
    this.audio.play();
  }

  this.pause = function() {
    //pause the audio
    this.audio.pause();
  }

  this.setTime = function(seconds) {
    //set the current time of the song to the second passed in
    this.audio.currentTime = seconds;
  }




}
