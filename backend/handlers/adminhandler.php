<?php
class AdminHandler extends Action
{
    public function act_admin()
    {
        global $config;
        User::$user = $_SESSION['currUser'];        
        if (User::isAnAdmin() == true)
        {
            $argsVar = $this->args;			
            if (count($argsVar) < 4) {header("Location: dashboard/");}
            switch ($argsVar[2])
            {
                case "dashboard":
                    AdminUI::init();
                break;
                case "newWindow":
                    if ($config['debug'] == true) {Window::create();}
                break;
				default:
					Modules::action("admin_" .$argsVar[2]);
            }
        }
        else {header("Location: ". $config['base-path'] . "user/login/");}
    }
}
?>