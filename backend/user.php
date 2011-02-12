<?php
class User
{
    public static $user;
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
    public static function createUserVar()
    {      
        $name = AuthUtils::encryptUser($_COOKIE['sk_U'], true);
		$query = "SELECT id,name,theme,enablejs FROM users WHERE name LIKE '$name'";
		$isVaildName = Database::get($query, false);
        $_SESSION['currUser'] = $isVaildName[1][0];
        self::$user = $isVaildName[1][0];       
    }   
    public static function isAnAdmin()
    {
        $ugidQuery = Database::get("SELECT groupid FROM group_members WHERE userid LIKE '" . self::$user['id']. "'", false);
        $admgid = Database::get("SELECT id FROM groups WHERE name LIKE 'Administrators'", true);
        
        if ($ugidQuery[0] > 0)
        {
            if ($admgid[0] > 0)
            {
                if ($ugidQuery[1][0]['groupid'] == $admgid[1][0]['id']){return true;}
                else {return false;}
            }
            else {if ($config['debug'] == true){trigger_error("admgid returned null.", E_USER_ERROR);}}
        }
        else {if ($config['debug'] == true){trigger_error("ugidQuery returned null.", E_USER_ERROR);}}
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