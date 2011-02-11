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
    public static function createUserVar()
    {        
        $name = AuthUtils::encryptUser($_COOKIE['sk_U'], true);
		$query = "SELECT * FROM users WHERE name LIKE '$name'";
		$isVaildName = Database::get($query, false);
        $_SESSION['currUser'] = $isVaildName[1][0];      
    }
    public static function startSession()
    {
        $user = $_SESSION['currUser'];        
        $sessid = md5(uniqid(microtime()));
        setcookie("sk_sid", $sessid, time()+3600, "/");
        echo "Hi!";
        Database::put(array($user['id'], $sessid, $_SERVER['REMOTE_ADDR']), "sessions");
    }
    public static function validateSession()
    {
        $user = $_SESSION['currUser'];
        $idQuery = Database::get("SELECT id FROM users WHERE name LIKE '" . $user['name']. "'", false);        
        if ($idQuery[0] != 0)
		{
            $id = $idQuery[1][0]['id'];
            $sessid = Database::get("SELECT sessid,ip FROM sessions WHERE userid LIKE '" . $id. "'", false);
            if ($sessid[1][0]['sessid'] == $_COOKIE['sk_sid'] && $sessid[1][0]['ip'] == $_SERVER['REMOTE_ADDR']) {return true;}
            else {return false;}            
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