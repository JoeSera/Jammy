<?php
include("includes/includedFiles.php");

 ?>


 <div class="entityInfo">

   <div class="centerSection">

     <div class="userInfo">
       <h1> <?php echo $userLoggedIn->getFirstAndLastName(); ?></h1>
     </div>

     <div class="buttonItems">
       <button type="button" name="button" class="button" onclick="openPage('updateDetails.php')"> USER DETAILS </button>
       <button type="button" name="button" class="button" onclick="logout()"> LOG OUT </button>

     </div>

   </div>

 </div>