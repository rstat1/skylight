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
//http://stackoverflow.com/questions/1799123/how-to-automatic-resize-tinymce
function toScreenHeight(id, minus) {
    var height;

    if (typeof(window.innerHeight) == "number"){height = window.innerHeight;}
	else if (document.documentElement && document.documentElement.clientHeight){height = document.documentElement.clientHeight;}
    document.getElementById(id).style.height = (height - minus) + "px";
}
function checkForPHSupport() 
{
  var i = document.createElement('input');
  return 'placeholder' in i;
}