<?php

function sanitizeFormString($inputText) {

  $inputText = strip_tags($inputText);                    // Strip html tags
  $inputText = str_replace(" ", "", $inputText);          // Find space and replace with empty string
  return $inputText;

}

function sanitizeFormName($inputText) {

  $inputText = strip_tags($inputText);                    // Strip html tags
  $inputText = str_replace(" ", "", $inputText);          // Find space and replace with empty string
  $inputText = ucfirst(strtolower($inputText));           // convert string to lowercase and change first letter to upper case
  return $inputText;

}

function sanitizeFormPassword($inputText) {

  $inputText = strip_tags($inputText);                    // Strip html tags
  return $inputText;

}


if(isset($_POST['registerButton'])) {

  /* Regsiter button pressed */

  $username = sanitizeFormString($_POST['username']);
  $firstName = sanitizeFormName($_POST['firstName']);
  $lastName = sanitizeFormName($_POST['lastName']);
  $email = sanitizeFormString($_POST['email']);
  $email2 = sanitizeFormString($_POST['email2']);
  $password = sanitizeFormPassword($_POST['password']);
  $password2 = sanitizeFormPassword($_POST['password2']);

  $wasSuccessful = $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);

  if($wasSuccessful) {
    $_SESSION['userLoggedIn'] = $username;
    header("Location: index.php");
  }

}
 ?>
