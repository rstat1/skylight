<?php
class DBAuth extends Auth
{
	public function action_challenge($user, $pass)
	{
		$pass = ;
		Database::query("SELECT * FROM 'users' WHERE `name` LIKE $user AND `passhash' LIKE $pass");
		
		return true;
	}
	private 
}
?>