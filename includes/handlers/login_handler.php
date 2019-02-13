<?php

// Log in button pressed
if(isset($_POST['loginButton'])) {
  $username = $_POST['loginUsername'];
  $password = $_POST['loginPassword'];

  $result = $account->login($username, $password);      //login function return boonlean

  if($result) {
    $_SESSION['userLoggedIn'] = $username;
    header("Location: index.php");
  }
}



 ?>
