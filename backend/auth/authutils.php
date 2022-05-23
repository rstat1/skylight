<?php
class AuthUtils
{
	public static function encryptUser($user)
	{
		return self::encryptPassword($user, 0, false);
	}
	public static function encryptPassword($pass, $salt, $forStore = false)
	{
		return password_hash($pass, PASSWORD_BCRYPT);
	}
	public static function encryptPWForStorage($pass)
	{
		return self::encryptPassword($pass, "", true);
	}
}
