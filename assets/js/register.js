$(document).ready(function() {

  /*Hide login form when user
  without account click on the span*/

  $("#hideLogin").click(function(){
    $("#loginForm").hide();
    $("#registerForm").show();
  });

  /*Hide register form when user
  with account click on the span*/

  $("#hideRegister").click(function(){
    $("#loginForm").show();
    $("#registerForm").hide();
  });
});
