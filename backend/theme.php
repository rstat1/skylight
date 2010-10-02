<?php
class theme
{
	public static $vars = array();
	public static $vars_data = array();
	public static $body_html = array();
	public static $header_html = array();
	public static $footer_html = array();
	private static $page = "";	
	
	public static function output()
	{
		global $config;
		$headerfile = file_get_contents("style/" .$config['style']. "/header.htm", FILE_USE_INCLUDE_PATH);
		$bodyfile = file_get_contents("style/" .$config['style']. "/body.htm", FILE_USE_INCLUDE_PATH);
		$footerfile = file_get_contents("style/" .$config['style']. "/footer.htm", FILE_USE_INCLUDE_PATH);
		
		self::$header_html[] = "\n\t". "<title>". $config['site-name']."</title>";
		
		self::$header_html[] = "\n\t". '<meta http-equiv="Content-type" content="text/html;charset=UTF-8" /> ' . "\n";
			
		self::addRequiredTags();
		
		if (count(ErrorHandler::$debugmsgs) > 0) 
		{
			$msgs = '<div style="margin:20px;">';
			foreach (ErrorHandler::$debugmsgs as $msg) { $msgs .= $msg; } 
		}
		self::$footer_html[] = $msgs;
		
		if (count(self::$header_html) > 0) { foreach(self::$header_html as $head) {$headerfile .= $head;} }
		if (count(self::$body_html) > 0){ foreach(self::$body_html as $body) {$bodyfile .= $body;} }
		if (count(self::$footer_html) > 0) { foreach(self::$footer_html as $foot) {$footerfile .= $foot;} }
						
		$headerfile .= "</head>\n";		
		$footerfile .= "\n</body>\n</html>";
		
		self::$page .= $headerfile . $bodyfile . $footerfile;					
		
		$templateData = array_combine(self::$vars, self::$vars_data);
		Cache::put_in_cache(self::parse("cache/","template.htm", self::$vars, $templateData, self::$page, false, true), "template", "htm");
			
		Compiler::compile_template("skylight", "cache/", Cache::getCacheFileName("template"), "cache/skylight-template.php", true, "" , true);
		include("cache/skylight-template.php");
	}	
	public static function outputLoginBox()
	{
		global $config;
		self::addRequiredTags();
		$loginbox = file_get_contents("style/" .$config['style']. "/login-style.htm", FILE_USE_INCLUDE_PATH);		
		$templateData = array_combine(self::$vars, self::$vars_data);
		$parsedTemp = self::parse("", "", self::$vars, $templateData, $loginbox, false, false);
		if ($parsedTemp == NULL) {return $loginbox;}
		else {return $parsedTemp;}
	}
	public static function outputWith404($url)
	{
		trigger_error("The page you are looking for cannot be located or might be invaild. Never can tell.");
	}
	//$this->parse_template("includes/cache/", "cp-header.php", $tags, $contents, $template, true, true)
	public static function parse($temp_path, $temp_part, $tags, $replinfo, $toparse, $inc_output, $isfile)
	{		
		$content = "";					
		$filename = $temp_path . $temp_part;
		$contents = $toparse;
		foreach($tags as $tag)
		{			
			if (stristr($contents, $tag))
			{	
				if ($content == "")	{$content = str_replace($tag, $replinfo[$tag], $contents);}
				else {$content = str_replace($tag, $replinfo[$tag], $content);}
			}
		}		
		return $content;
	}	
	private static function addRequiredTags()
	{
		global $config;
		$usage = memory_get_usage(true);
		
		self::$vars[] = "{#SITETITLE#}";
		self::$vars_data[] =  $config['site-name'];
		
		self::$vars[] = "{#BASEPATH#}";
		self::$vars_data[] =  URL::base();
			
		$use = round($usage / (1024), 2);
		self::$vars[] = "{#DEBUGMEMORY#}";
		self::$vars_data[] = "<p>Memory Use: ". $usage ." Bytes</p>";
	
		self::$vars[] = "{#USERNAME#}";
		self::$vars_data[] = "Guest";
		
		self::$vars[] = "{#LOGINLINK#}";
		/*if {$config['enable-ajax']) {*/self::$vars_data[] = "ajax/login/";//}
		/*else {self::$vars_data[] = "user/login/";}*/
		
		self::$vars[] = "{#LOGINOUTLINK#}";
		self::$vars_data[] = '<p id="login">Login</p>';
		
		self::$vars[] = "{#ACPLINK#}";
		self::$vars_data[] = 'Admin Controls';
		
		self::$vars[] = "{#UCPLINK#}";
		self::$vars_data[] = 'User Controls';
		
		/*self:$vars[] = "{#VERSION#}";
		self::$vars_data[] = $config['version'];*/
	}
}
// <!-- IF PAGE is tag/firefox --> <!-- ENDIF -->
// if (stristr($_SERVER["REQUEST_URI"], "tag/firefox") != FALSE) {}
// <!-- IF PAGE is article --> <!-- ENDIF --> 
// if (NewsHelper::page == "article") {}
// $tplcomp->compile_template("cp", "includes/style/main/index/", "index-header", "includes/cache/cp-index-header.php", true);
?>