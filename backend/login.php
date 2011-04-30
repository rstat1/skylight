<?php
class Login
{
	public $action = '';
	public $args = NULL;
	public function action_displayLogin(){}
	public function action_doLogin($user, $pass)
	{
		$pwChallenge = Auth::challenge($user, $pass);
		if ($pwChallenge === USER_INVAILD_PW){echo '<p style="color:red;">Invaild Password</p>';}
		if ($pwChallenge === USER_INVAILD_NAME){echo '<p style="color:red;">Invaild Username</p>';}
		if (is_array($pwChallenge) && count($pwChallenge > 0)){print_r($pwChallenge);}
	}
}
?>