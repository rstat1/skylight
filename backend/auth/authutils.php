<?php
class AuthUtils 
{
	public static function makePasswordHash($pass)
	{
		$passVar = md5(md5(md5(sha1(sha1(sha1($pass))))));
		$salt = sha1($pass + hash("sha512", hash("sha512", $passVar)));
		$hash = hash("sha512", hash("sha512", $passVar + $salt));
		$finalPass = hash("sha512", sha1(str_pad($passVar, strlen($hash) + 128, hash("sha512", $salt + $hash + $passVar))));
		return hash("sha512", $finalPass);
	}
	public static function encryptUser($user)
	{
		return self::encryptPassword($user, 0, false);
	}
	public static function encryptPassword($pass, $salt, $forStore = false)
	{	
		$cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
		
		$toEncrypt = self::makePasswordHash($pass);
		$keys = str_split($toEncrypt, 32);
		if ($forStore == true)
		{
			$whichKey = rand(0, count($keys) - 1);
			$key = $keys[$whichKey];	
			$ivStart = hash("sha512", $key + $key);
			$ivMaker = str_split($ivStart, 32);
			$iv = $ivMaker[$whichKey];					
		}	
		else
		{
			$key = $keys[$salt];
			$ivStart = hash("sha512", $key + $key);
			$ivMaker = str_split($ivStart, 32);
			$iv = $ivMaker[$salt];		
		}
		if (mcrypt_generic_init($cipher, $key, $iv) != -1)
		{
			$cipherText = mcrypt_generic($cipher, $toEncrypt);
			mcrypt_generic_deinit($cipher);
			if ($forStore == true){ return array($cipherText, $whichKey);}
			else {return bin2hex($cipherText);}
		}
	}
	public static function encryptPWForStorage($pass)
	{
		return self::encryptPassword($pass, "", true);
	}	
}	
?>