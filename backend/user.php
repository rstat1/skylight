<?php
class User
{
    public static $user;
	public static function newUser($username, $password, $email = "")
	{	
		$name = AuthUtils::encryptUser($username, true);
		$query = "SELECT * FROM users WHERE name LIKE '$name'";
		$isVaildName = Database::get($query, false);
      
		if ($isVaildName[0] == 0)
		{ 			
			$pass = AuthUtils::encryptPassword($password, "", true);
			$data = array(NULL, $name, $pass[0], $pass[1], "synergy", 1, NULL);
			$success = Database::put($data, "users");
			if ($success)  {return USER_REGISTER_SUCCESS;}
		}
		else
		{
			//return USER_NAME_EXISTS;
			echo '<p style="color:red;">' .$username. ' already exists.</p>';
		}
		return true;	
	}
    public static function createUserVar()
    {      
        $name = AuthUtils::encryptUser($_COOKIE['sk_U'], true);
		$query = "SELECT id,name,theme,enablejs FROM users WHERE name LIKE '$name'";
		$isVaildName = Database::get($query, false, "users");
        $_SESSION['currUser'] = $isVaildName[1][0];
        self::$user = $isVaildName[1][0];       
    }   
    public static function isAnAdmin()
    {
		global $config;
		if (self::isUserLoggedin())
		{
			$ugidQuery = Database::get("SELECT groupid FROM group_members WHERE userid LIKE '" . self::$user['id']. "'", false, "users");
			$admgid = Database::get("SELECT id FROM groups WHERE name LIKE 'Administrators'", true, "users");
			if ($ugidQuery[0] > 0)
			{
				if ($admgid[0] > 0)
				{
					if ($ugidQuery[1][0]['groupid'] == $admgid[1][0]['id']){return true;}
					else {return false;}
				}				
			}			
		}
    }    
	public static function isUserLoggedIn()
	{
		if (isset($_COOKIE['sk_U'])) {return true;}
        else {return false;}
	}
    public static function isUserAllowedHere($herebeingwhere)
    {
        return Auth::checkPermission($herebeingwhere);
    }
}
?>