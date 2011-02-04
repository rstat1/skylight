<?php
class news extends Module
{	
	public function info(){}
	public function set_priorities(){}
	public function action_shutdown(){}
	public function action_theme_init(){}
	public function action_tag($args)
	{		
		global $config;	
		NewsHelper::Page("set", "tag/" . $args['data']);
        echo NewsHelper::getLatest5Articles($args['data'], $config['package-content-withL5A']);	
	}
	public function action_article($args)
	{
		echo NewsHelper::getArticleById($args['data']);
	}
	public function action_init()
	{
		global $config;
		if ($config['enable-ajax'])
		{
			$this->addToTemplate("\n\t". '<script type="text/javascript" src="js/jquery.js"></script>', "header");
		}
		$this->addToTemplate("\n\t". '<script type="text/javascript" src="style/' . $config['style']. '/js/index.js"></script>', "header");		
		$this->addURLFilter(array("name" => "show-article", "matchto" => "%article/([0-9-]+)%i" , "handler" => "ThemeHandler", "action" => "displayPost"));
		/*$this->addURLFilter(array("name" => "ajax", "matchto" => "%ajax/([A-Za-z0-9-]+)%mx" , "handler" => "AjaxHandler", "action" => "display_tag"));
		$this->addURLFilter(array("name" => "show-article", "matchto" => "%article/([0-9-]+)%i" , "handler" => "ThemeHandler", "action" => "display_post"));*/
		$this->assignToVar("{#TAGS#}", NewsHelper::getTags());
		$this->assignToVar("{#LATESTARTICLES#}", NewsHelper::getLatest5Articles("home", $config['package-content-withL5A']));		
		$this->assignToVar("{#SITETITLE#}", $config['site-name']);
		$this->assignToVar("{#BASEPATH#}", URL::base());
		$request = NewsHelper::Page();		
		switch($request['Page'])
		{	
			case "ajax-load":
				//header("Content-Type: application/xml; charset=UTF-8");
				echo NewsHelper::getNews($request['Args']);						
			break;
			case "article":
				echo NewsHelper::getArticleById($request['Args']);
			break;
			default:								
		}	
		
	}	
}	
?>