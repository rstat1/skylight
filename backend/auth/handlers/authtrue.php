<?php
/*Auth challenge class that always returns true on all challenge requests. Useful for permissions testing. I guess.*/
class authTrue
{
	public static function doChallenge($username, $password)
	{
		return array("name" => "authTrue", "theme" =>'theme', "useJS" => 1);
	}
}
?>