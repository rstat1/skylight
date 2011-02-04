<?php
class UserHandler extends Action
{
	public function act_authenticate()
	{
        $wasSuccessful = Auth::challenge($_POST['skylightUser'], $_POST['skylightPW']);
       	if (is_array($wasSuccessful))
        {
            setcookie("skylightUser", $_POST['skylightUser'], time()+3600, "/");
            $_SESSION['currUser'] = $wasSuccessful;
            echo "Login Successful!";            
        }        
        else if ($wasSuccessful == USER_NAME_DOESNT_EXIST) {echo "Username does not exist!";}
        else if ($wasSuccessful == USER_INVAILD_PW) {echo "Incorrect password";}
	}
}