<?php
class AjaxHandler extends Action
{

	public function act_ajax()
	{
		$arguments = $this->args;	
		//echo "\tUsing $this->action in handler named $this->name to handle the request with arguments ". print_r($this->args);		
		//die();
		$moduleArgs = array("function" => $this->args[2], "data" => $this->args[3]);
		Modules::action($moduleArgs['function'], $moduleArgs);
	}	
	/*public function act()
	{		
		$arguments = explode("/", $this->args);			
		
		Modules::action("$this->action", $arguments);		
	}*/
}
?>