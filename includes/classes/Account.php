<?php

  class Account {

    private $con;
    private $errorArray;

    public function __construct($con) {
      $this->con = $con;
      $this->errorArray = array();
    }

    public function login($username, $password) {

      $encryptedPassword = md5($password);

      $query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$username' AND password='$encryptedPassword'");

      if(mysqli_num_rows($query) == 1) {
        return true;
      }
      else {
        array_push($this->errorArray, Constants::$loginFailed);
        return false;
      }
    }

    public function register($username, $firstName, $lastName, $email, $email2, $password, $password2) {
      $this->validateUsername($username);
      $this->validateFirstName($firstName);
      $this->validateLasttName($lastName);
      $this->validateEmails($email, $email2);
      $this->validatePasswords($password, $password2);

      if(empty($this->errorArray)) {
        /*Insert input values into the database*/
        return $this->insertUserDetails($username, $firstName, $lastName, $email, $password);
      }
      else {
        return false;
      }
    }

    private function insertUserDetails($username, $firstName, $lastName, $email, $password) {
      /* Insert data into users table */
      $encryptedPassword = md5($password);                                   // Encrypt password
      $profilePic = "assets/images/profile_pics/default_profile_pic.png";   // File path to profile pic
      $date = date("Y-m-d");

      $result = mysqli_query($this->con, "INSERT INTO users values ('', '$username', '$firstName', '$lastName', '$email', '$encryptedPassword', '$date', '$profilePic')");

      return $result;       // Return boonlean to check querry succeeded
    }

    public function getError($error) {

      if(!in_array($error, $this->errorArray)) {
        /* Check if error message in the error array
          if is not in the error array, output no error message */
        $error = "";
      }
      /* If error message exsists in the error array,
        output span html container error messsage*/
      return "<span class='errorMessage'>$error</span>";
    }

    private function validateUsername($username) {

      if(strlen($username) > 25 || strlen($username) < 5) {
        array_push($this->errorArray, Constants::$usernameCharacters);
        return;
      }

      $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$username'");
      if(mysqli_num_rows($checkUsernameQuery) != 0) {
        array_push($this->errorArray, Constants::$usernameTaken);
        return;
      }
    }

    private function validateFirstName($firstName) {

      if(strlen($firstName) > 25 || strlen($firstName) < 2) {
        array_push($this->errorArray, Constants::$firstNameCharacters);
        return;
      }

    }

    private function validateLasttName($lastName) {

      if(strlen($lastName) > 25 || strlen($lastName) < 2) {
        array_push($this->errorArray, Constants::$lastNameCharacters);
        return;
      }

    }

    private function validateEmails($email, $email2) {

      if($email != $email2) {
        array_push($this->errorArray, Constants::$emailsDoNotMatch);
        return;
      }

      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        /*Check if the input is a valid email form (eg. @abc.com)*/

        array_push($this->errorArray, Constants::$emailInvalid);
        return;
      }

      $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$email'");
      if(mysqli_num_rows($checkEmailQuery) != 0) {
        array_push($this->errorArray, Constants::$emailTaken);
        return;
      }



    }

    private function validatePasswords($password, $password2) {

      if($password != $password2) {
        array_push($this->errorArray, Constants::$passwordDoNotMatch);
        return;
      }

      if(preg_match('/[^A-Za-z0-9]/', $password)) {

        /*Check if it is not either alphabet or number*/
        array_push($this->errorArray, Constants::$passwordNotAlphaNumeric);
        return;
      }

      if(strlen($password) > 30 || strlen($password) < 5) {
        array_push($this->errorArray, Constants::$passwordCharacters);
        return;
      }

    }

  }



 ?>
