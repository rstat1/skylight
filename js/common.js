$(document).ready(function(){       	
	$("#loginForm").submit(function() {
		return authChallengeInit();
	});
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