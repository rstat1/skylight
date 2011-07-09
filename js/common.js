$(document).ready(function(){       	
	$("#loginForm").submit(function() {
		return authChallengeInit();
	});
	$("#registerForm").submit(function() {
		return validateRegistration();
	});
	if (checkForPHSupport() == false)
	{
		$('label').css('display', 'block');
	}
});
function authChallengeInit()
{
	$.post("auth/challenge/", $("#loginForm").serialize(), function(data) {
        if (data.indexOf("Login Successful!") == -1)
        {
            $("#login-failed").html(data);
            $("#login-failed").css('visibility', 'visible');            
        }
        else
        {        
            $("#login-success").html(data);
            $("#login-success").css('visibility', 'visible');
            window.location = path;
        }
    });
	return false;
}
function validateRegistration()
{
	$.post("auth/register/", $("#registerForm").serialize(), function(data) {       
        if (data.indexOf("Welcome to") == -1)
        {
            $("#login-failed").html(data);
            $("#login-failed").css('visibility', 'visible');            
        }
        else
        {        
            $("#login-success").html(data);
            $("#login-success").css('visibility', 'visible');
            window.location = path;
        }
    });
	return false;
}
function checkForPHSupport() 
{
  var i = document.createElement('input');
  return 'placeholder' in i;
}