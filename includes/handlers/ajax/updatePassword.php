<?php
include("../../config/config.php");

if(!isset($_POST['username'])) {
  echo "ERROR: Could not set username";
  exit();
}

if(!isset($_POST['oldPassword']) || !isset($_POST['newPassword1']) || !isset($_POST['newPassword2'])) {
  echo "Not all passwords have been set";
  exit();
}

if($_POST['oldPassword'] == "" || $_POST['newPassword1'] == "" || $_POST['newPassword2'] == "") {
  echo "Please fill all fields";
  exit();
}

$username = $_POST['username'];
$oldPassword = $_POST['oldPassword'];
$newPassword1 = $_POST['newPassword1'];
$newPassword2 = $_POST['newPassword2'];

$oldMd5 = md5($oldPassword);

$passwordCheck = mysqli_query($con, "SELECT * from users WHERE username='$username' AND password='$oldMd5'");

if(mysqli_num_rows($passwordCheck) != 1) {
  echo "Password is incorrect";
}
if($newPassword1 != $newPassword2) {
  echo "You new passwor do not match";
  exit();
}

if(preg_match('/[^A-Za-z0-9]/', $newPassword1)) {
  echo "Your password must only include letters or numbers";
  exit();
}

if(strlen($newPassword1 > 30 ) || strlen($newPassword1) < 5) {
  echo "Your password must be between 5 and 30 characters";
  exit();
}

$newMd5 = md5($newPassword1);

$query = mysqli_query($con, "UPDATE users SET password='$newMd5' WHERE username='$username'");
echo "Update successful!";


 ?>
