<?php
// Base class for Pluggable authentication
class Auth
{
	public static function challenge($user, $pass)
	{
		global $config;
		if ($user == NULL){trigger_error("Auth::challenge requires a username", E_USER_ERROR);}
		if ($pass == NULL){trigger_error("Auth::challenge requires a password", E_USER_ERROR);}
        $authType = $config['auth-type'];
		$auth = new $authType;
		return $auth->doChallenge($user, $pass);
	}
}
?>