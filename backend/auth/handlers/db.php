<?php
//User authentication via a database
class db
{
	public function doChallenge($user, $pass)
	{
		$results = Database::get("SELECT * FROM users WHERE name LIKE '$user'", false, "users");
		if ($results[0] == 0){return USER_NAME_DOESNT_EXIST;} //echo '<p style="color:red;">No such user "'. $user .'" </p>';}
		else
		{
			$myPass = AuthUtils::encryptPassword($pass, $results[1][0]["salt"]);
			if (!password_verify($pass, $results[1][0]["passhash"])) { return USER_INVAILD_PW; }//echo '<p style="color:green;">Welcome, '. $user . '</p>';}		
		}                
        $userData = array("name" => $user, "theme" => $results[1][0]['theme'], "useJS" => $results[1][0]['enablejs'], "id" => $results[1][0]['id']);        
		return $userData;
	}	
}
