<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
  //check whether the request is through ajax, all ajax request will have HTTP_X_REQUESTED_WITH
  //we do this to prevent the ajax content to load the navigation and now playing bar again causing the content to have a margin where these elements are there
  include("includes/config/config.php");
  include("includes/classes/User.php");
  include("includes/classes/Artist.php");
  include("includes/classes/Album.php");
  include("includes/classes/Song.php");
  include("includes/classes/Playlist.php");

  if(isset($_GET['userLoggedIn'])) {
    $userLoggedIn = new User($con, $_GET['userLoggedIn']);
  }
  else {
    echo "username varible was not passed into the page. Check the openPage JS function";   //if there is a error check the script.js
    exit();
  }

}
else {
  //came from manually or normall loading of the page
  include("includes/header.php");
  include("includes/footer.php");

  $url = $_SERVER['REQUEST_URI'];
  echo "<script>openPage('$url')</script>";
  exit();   //prevent content to be loaded twice

}





?>
