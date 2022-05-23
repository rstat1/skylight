<?php
abstract class Action
{
	public $name = "";
	public $action = '';
	public $args = NULL;
	//abstract function act();
	public function act($action)
	{
		//$this->action = $action;
		$method = "act_" . $action;
		if (method_exists($this, $method)){$this->$method();}
		else { trigger_error("specified action is undefined: " . $action); }
	}
}
?>