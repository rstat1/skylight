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
                    $db = new Dashboard();
                    $db->init();
                break;
            }
        }
        else {header("Location: ". $config['base-path']);}
    }
}
?>