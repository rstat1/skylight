<?php
abstract class Action
{
	public $action = '';
	public $args = NULL;
	abstract function act();
}
?>