<?php
class User
{
	public static function newUser($username, $password)
	{	
		$name = AuthUtils::encryptUser($username, true);
		$query = "SELECT * FROM users WHERE name LIKE '$name'";
		$isVaildName = Database::get($query, false);
      
		if ($isVaildName[0] == 0)
		{ 			
			$pass = AuthUtils::encryptPassword($password, "", true);
			$data = array(NULL, $name, $pass[0], $pass[1], "synergy", 1);
			$success = Database::put($data, "users");
			if ($success)  {echo '<p style="color:green;"> Welcome to skylight, '.$username. '</p>';}
		}
		else
		{
			//return USER_NAME_EXISTS;
			echo '<p style="color:red;">' .$username. ' already exists.</p>';
		}
		return true;	
	}
	public static function isUserLoggedIn()
	{
		//For now we return true. I mean false.
		return false;
	}
}
?>