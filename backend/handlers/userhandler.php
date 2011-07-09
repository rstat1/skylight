<?php
class UserHandler extends Action
{
	public function act_user()
	{
		
	}
	public function act_challenge()
	{
        global $config;
		$wasSuccessful = Auth::challenge($_POST['skylightUser'], $_POST['skylightPW']);              
        if (is_array($wasSuccessful))
        {
            setcookie("sk_U", $_POST['skylightUser'], time()+3600, "/");
            $_SESSION['currUser'] = $wasSuccessful;
            echo "Login Successful!";            
        }        
        else if ($wasSuccessful == USER_NAME_DOESNT_EXIST) {echo "Username does not exist!";}
        else if ($wasSuccessful == USER_INVAILD_PW) {echo "Incorrect password";}
	}
	public function act_new()
	{		
		global $config;
		$bodyfile = file_get_contents("style/" .$config['style']. "/register-style.htm", FILE_USE_INCLUDE_PATH);
		echo Theme::outputPiece("header", $config['style']) . $bodyfile. Theme::outputPiece("footer", $config['style']);
	}
	public function act_register()
	{
		global $config;		
		$wasUserCreated = User::newUser($_POST['skylightUser'], $_POST['skylightPW']);
		if ($wasUserCreated == USER_REGISTER_SUCCESS) 
		{
			echo '<p style="color:green;"> Welcome to ' .$config['site-name']. ', '.$_POST['skylightUser']. '</p>';
		}
		
	}
}
?>