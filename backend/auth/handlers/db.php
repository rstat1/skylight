<?php
//User authentication via a database
class db
{
	public function doChallenge($user, $pass)
	{
		$actualUser = AuthUtils::encryptUser($user);
		$results = Database::get("SELECT * FROM users WHERE name LIKE '$actualUser'", false);
		if ($results[0] == 0){return USER_NAME_DOESNT_EXIST;} //echo '<p style="color:red;">No such user "'. $user .'" </p>';}
		else
		{		
			$myPass = AuthUtils::encryptPassword($pass, $results[1][0]["salt"]);
			if ($results[1][0]["passhash"] != $myPass){ return USER_INVAILD_PW; }//echo '<p style="color:green;">Welcome, '. $user . '</p>';}		
		}                
        $userData = array("name" => $user, "theme" => $results[1][0]['theme'], "useJS" => $results[1][0]['enablejs'], "id" => $results[1][0]['id']);
		return $userData;
	}	
}
?>