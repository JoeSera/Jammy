<?php
  include("includes/config/config.php");
  include("includes/classes/Account.php");
  include("includes/classes/Constants.php");

  $account = new Account($con);

  include("includes/handlers/register_handler.php");
  include("includes/handlers/login_handler.php");

  function getInputValue($name) {

    /* echo value if the input vield has been set
    this avoids error when there is not value set*/
    if(isset($_POST[$name])){
      echo $_POST[$name];
    }
  }
 ?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Welcome to Jammy!</title>

    <link rel="stylesheet" href="assets/css/register.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/register.js"></script>
  </head>
  <body>

    <?php
      if(isset($_POST['registerButton'])) {
        /* show earch form at a time*/
        echo '<script type="text/javascript">
          $(document).ready(function() {
            $("#loginForm").hide();
            $("#registerForm").show();
          });
        </script>';
      }
      else {
        echo '<script type="text/javascript">
          $(document).ready(function() {
            $("#loginForm").show();
            $("#registerForm").hide();
          });
        </script>';
      }

     ?>


    <div class="" id="background">

      <div class="" id="loginContainer">

        <!--Log in Section-->
        <div class="" id="inputContainer">
          <form class="" id="loginForm" action="register.php" method="post">
            <h2>Login to your account</h2>
            <p>
              <?php echo $account->getError(Constants::$loginFailed); ?>
              <label for="loginUsername">Username</label>
              <input id="loginUsername" type="text" name="loginUsername" placeholder="eg. Bart Simpson" value="<?php getInputValue('loginUsername')?>" required>
            </p>

            <p>
              <label for="loginPassword">Password</label>
              <input id="loginPassword" type="password" name="loginPassword" placeholder="Your Password" value="<?php getInputValue('loginUsername')?>" required>
            </p>

            <button type="submit" name="loginButton"> LOG IN </button>

            <div class="hasAccountText">
              <span id="hideLogin"> Dont't have a account yet? Sign up here! </span>
            </div>
          </form>

          <!--Register section-->
          <form class="" id="registerForm" action="register.php" method="post">
            <h2>Create your free account</h2>
            <p>
              <?php echo $account->getError(Constants::$usernameCharacters); ?>
              <?php echo $account->getError(Constants::$usernameTaken); ?>
              <label for="username">Username</label>
              <input id="username" type="text" name="username" placeholder="eg. bartsimpson" value="<?php getInputValue('username')?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$firstNameCharacters); ?>
              <label for="firstName">First Name</label>
              <input id="firstName" type="text" name="firstName" placeholder="eg. Bart" value="<?php getInputValue('firstName')?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$lastNameCharacters); ?>
              <label for="lastName">Last Name</label>
              <input id="lastName" type="text" name="lastName" placeholder="eg. Simpson" value="<?php getInputValue('lastName')?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
              <?php echo $account->getError(Constants::$emailInvalid); ?>
              <?php echo $account->getError(Constants::$emailTaken); ?>
              <label for="email">Email</label>
              <input id="email" type="email" name="email" placeholder="eg. bart@gmail.com" value="<?php getInputValue('email')?>" required>
            </p>

            <p>
              <label for="email2">Confirm Email</label>
              <input id="email2" type="email" name="email2" placeholder="eg. Bart Simpson" value="<?php getInputValue('email2')?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$passwordDoNotMatch); ?>
              <?php echo $account->getError(Constants::$passwordNotAlphaNumeric); ?>
              <?php echo $account->getError(Constants::$passwordCharacters); ?>
              <label for="password">Password</label>
              <input id="password" type="password" name="password" placeholder="Your Password" value="" required>
            </p>

            <p>
              <label for="password2">Confirm Password</label>
              <input id="password2" type="password" name="password2" placeholder="Confirm Password" value="" required>
            </p>

            <button type="submit" name="registerButton"> Register </button>

            <div class="hasAccountText">
              <span id="hideRegister"> Already have a account? Register here! </span>
            </div>
          </form>

        </div>

        <div class="" id="loginText">
          <h1>Get great music, right now</h1>
          <h2>Listen to loads of muic for free</h2>
          <ul>
            <li>Discover music</li>
            <li>Create your own playlist</li>
            <li>Follow artists to keep up to date</li>
          </ul>
        </div>


      </div>

    </div>

  </body>
</html>
