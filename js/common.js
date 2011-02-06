$(document).ready(function(){    
   	$("#login").bind("click", getThemeLogin);
	$("#loginForm").submit(function() {
		return authChallengeInit();
	});
});
function authChallengeInit()
{
	$.post("auth/challenge/", $("#loginForm").serialize(), function(data) {        
        if (data != "Login Successful!")
        {
            $("#login-failed").html(data);
            $("#login-failed").css('visibility', 'visible');
        }
        else
        {
            $("#login-success").html(data);
            $("#login-success").css('visibility', 'visible');
        }
    });
	return false;
}