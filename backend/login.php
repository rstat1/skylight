<?php
class Login
{
	public $action = '';
	public $args = NULL;

	/*public function act()
	{
		$this->addToTemplate('<p style="color:white;">' .$_POST['skylightPW']. "</p>", "body");
		header("Location: /skylight");
	}*/	
	
	public function action_displayLogin()
	{
		global $config;
		$bodyfile = file_get_contents("style/" .$config['style']. "/login-style.htm", FILE_USE_INCLUDE_PATH);
		echo $bodyfile;
	}
	public function action_doLogin($user, $pass)
	{
		$pwChallenge = Auth::challenge($user, $pass);
		if ($pwChallenge === USER_INVAILD_PW){echo '<p style="color:red;">Invaild Password</p>';}
		if ($pwChallenge === USER_INVAILD_NAME){echo '<p style="color:red;">Invaild Username</p>';}
		if (is_array($pwChallenge) && count($pwChallenge > 0)){print_r($pwChallenge);}
	}
}
?>