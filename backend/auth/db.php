<?php
class db
{
	public function doChallenge($user, $pass)
	{
		$results = Database::get("SELECT * FROM users WHERE name LIKE '$user'");
		if ($results[0] == 0){echo '<p style="color:red;">No such user "'. $user .'" </p>';}
		else
		{		
			$myPass = $this->makePasswordHash($pass);
			if ($results[1][0]["passhash"] == $myPass){echo '<p style="color:green;">Welcome, '. $user . '</p>';}		
			else {echo '<p style="color:red;">Invaild Password</p>';}
		}		
	}
	private function makePasswordHash($pass)
	{
		$passVar = md5(md5(md5(sha1(sha1(sha1($pass))))));
		$salt = sha1($pass + hash("sha512", hash("sha512", $passVar)));
		$hash = hash("sha512", crypt($passVar, $salt));
		$finalPass = hash("sha512", sha1(str_pad($passVar, 25, hash("sha512", $salt + $hash + $passVar))));
		return $finalPass;
	}	
}
?>