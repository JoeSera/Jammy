<?php
  include("includes/config/config.php");
  include("includes/classes/Artist.php");
  include("includes/classes/Album.php");
  include("includes/classes/Song.php");

  //session_destroy();          // Log out manually as there is no log out button yet;

  if(isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
    echo "<script>userLoggedIn = '$userLoggedIn'; </script>";   //have userLoggedIn varible available for javascript
  }
  else {
    header("Location: register.php");
  }


 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="assets/css/style.css">

    <title>Welcome to Jammy!</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/js/script.js"></script>
  </head>
  <body>


    <div id="mainContainer">

      <div id="topContainer">
        <?php include("includes/navBarContainer.php"); ?>

        <div id="mainViewContainer">

          <div id="mainContent">
