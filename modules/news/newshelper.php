<?php
class NewsHelper
{
	private static $reqbase;
	private static $tags, $news = "";
	private static $tag_template = "";
	public static function Page($action = "", $value = "")
	{
		global $config;
		if ($action == "set")
		{
			$req = URL::scriptPath() . "/". $value;
			self::$reqbase = explode("/", $req);
			return;
		}
		if (self::$reqbase == null) {self::$reqbase = explode("/", $_SERVER['REQUEST_URI']);}
		if (count(self::$reqbase) > 3) {$reqarr = array("Page" => self::$reqbase[2], "Args" => self::$reqbase[3]);}
		else if (count(self::$reqbase) > 2) {$reqarr = array("Page" => self::$reqbase[2]);}
		return $reqarr;
	}
//	public function parse($temp_path, $temp_part, $tags, $replinfo, $toparse, $inc_output, $isfile)
	public static function getTags()
	{
		self::$tag_template = self::getTemplate("tag");
		$result = Database::get("SELECT * FROM categories");
		$numOfResults = Database::ResultCount($result);
		for($i = 0; $i < $numOfResults; $i++)
		{
//			self::$tags .= Theme::parse("","", array("{#TAG#}"), array("{#TAG#}" => $title[$i]['name']), self::$tag_template, false, false);
		}
		/*while($title = mysql_fetch_assoc($result))
		{
			self::$tags .= Theme::parse("","", array("{#TAG#}"), array("{#TAG#}" => $title['name']), self::$tag_template, false, false);
		}*/
		/*echo self::$tags;	*/
	}
	public static function getArticleById($id)
	{		
		$result = Database::get("SELECT * FROM posts WHERE `id` LIKE '$id'");
		self::$tag_template = self::getTemplate("content");
		$numOfResults = Database::ResultCount($result);
		for($i = 0; $i < $numOfResults; $i++)
		{				
			$title = $result[1][$i];
			$tags = array("{#TITLE#}", "{#CONTENT#}", "{#ID#}");
			$content = array($title['Title'], $title['content'], $title['id']);
			$data = array_combine($tags, $content);
			self::$news = Theme::parse("","", $tags, $data, self::$tag_template, false, false);
		}		
		return self::$news;
	}
	public static function getNews($tag = "")
	{		
		$idtags = array();
		$titletags = array();
		$authortags = array();
		$datetimetags = array();
		self::$tag_template = self::getTemplate("newsitem");	
		if ($tag == NULL) {$result = Database::sql_query("SELECT * FROM posts ORDER by `id` DESC LIMIT 5");}
		else{$result = Database::get("SELECT * FROM posts WHERE `category` LIKE '$tag' ORDER by `id` DESC LIMIT 5");}
		if (mysql_num_rows($result) > 0)
		{
			while($title = mysql_fetch_assoc($result))
			{
				
			}
		}
		return $news;
	}	
	public static function getLatest5Articles($tag = "", $getContent = false)
	{	
		$numOfResults = 0;
		$idtags = array();
		$titletags = array();
		$authortags = array();
		$datetimetags = array();
		$contenttags = array();
		self::$tag_template = self::getTemplate("fivenewsitems");
		if ($tag == NULL) {$title = Database::get("SELECT * FROM posts ORDER by `id` DESC LIMIT 5");}
		else{$result = Database::get("SELECT * FROM posts WHERE `category` LIKE '$tag' ORDER by `id` DESC LIMIT 5");}
		$numOfResults = Database::ResultCount($result);
		if ($numOfResults > 0)
		{
			if ($numOfResults < 5) {$x = 5;}
			else {$x = 1;}
			for($i = 0; $i < $numOfResults; $i++)
			{					
				$title = $result[1][$i];
				$id[] = $title['id'];
				$title_var[] = $title['Title'];
				$datetime[] = $title['postdate'];
				$author[] = $title['author'];
				if ($getContent == true){$content[] = $title['content'];}
				$idtags = array("{#ID5#}","{#ID4#}","{#ID3#}","{#ID2#}","{#ID1#}");
				$titletags = array("{#TITLE5#}","{#TITLE4#}", "{#TITLE3#}", "{#TITLE2#}", "{#TITLE1#}");							
				$authortags = array("{#AUTHOR5#}","{#AUTHOR4#}", "{#AUTHOR3#}", "{#AUTHOR2#}", "{#AUTHOR1#}");							
				$datetimetags = array("{#POSTDATE5#}","{#POSTDATE4#}", "{#POSTDATE3#}", "{#POSTDATE2#}", "{#POSTDATE1#}");					
				if ($getContent == true) {$contenttags = array("{#CONTENT1#}","{#CONTENT2#}","{#CONTENT3#}","{#CONTENT4#}","{#CONTENT5#}");}
				if ($numOfResults < 5) 
				{
					$fill = 5 - $numOfResults;
					for ($x = 0; $i < $fill; $x++)
					{
						$id[] = "";
						$title_var[] = "";
						$datetime[] = "";
						$author[] = "";
						if ($getContent == true) {$content[] = "";}
					}
					$x -= 1;
				}
				else {$x += 1;}
			}	
			$titles = array_combine($titletags, $title_var);
			$dates = array_combine($datetimetags, $datetime);
			$authors = array_combine($authortags, $author);			
			$ids = array_combine($idtags, $id);	
			if ($getContent == true) { $contents = array_combine($contenttags, $content);}
			if ($getContent == true) 
			{ 
				$tags = array_merge($titletags,$authortags, $datetimetags, $idtags, $contenttags);
				$data = array_merge($titles, $dates, $authors, $ids, $contents);
			}
			else
			{
				$tags = array_merge($titletags,$authortags, $datetimetags, $idtags);
				$data = array_merge($titles, $dates, $authors, $ids);
			}		
		}
		else 
		{
			/*$today = getdate();
			$id = array("1", "", "","","");
			$title_var = array("Tag is empty", "", "", "", "");
			$datetime = array($today['mday']. " ". $today['month'] . " " . $today['year'] , "", "", "","");
			$author = array("skylight news module", "", "","","");
		
			$idtags = array("{#ID5#}","{#ID4#}","{#ID3#}","{#ID2#}","{#ID1#}");
			$titletags = array("{#TITLE5#}","{#TITLE4#}", "{#TITLE3#}", "{#TITLE2#}", "{#TITLE1#}");							
			$authortags = array("{#AUTHOR5#}","{#AUTHOR4#}", "{#AUTHOR3#}", "{#AUTHOR2#}", "{#AUTHOR1#}");							
			$datetimetags = array("{#POSTDATE5#}","{#POSTDATE4#}", "{#POSTDATE3#}", "{#POSTDATE2#}", "{#POSTDATE1#}");		
		
			$titles = array_combine($titletags, $title_var);
			$dates = array_combine($datetimetags, $datetime);
			$authors = array_combine($authortags, $author);	
			$ids = array_combine($idtags, $id);	
			
			$tags = array_merge($titletags,$authortags, $datetimetags, $idtags);
			$data = array_merge($titles, $dates, $authors, $ids);*/
			return "No content here!";
		}
		self::$news = Theme::parse("","", $tags, $data, self::$tag_template, false, false);		
		return self::$news;				
	}
	//public static function compile_template($template, $path, $part, $cpath, $nocache = "")
	private static function getTemplate($file)
	{
		global $config;
		$path = "style/" . $config['style']. "/$file.htm";
		$hand = fopen($path,"r");
		$tag = fread($hand, filesize($path));
		fclose($hand);
		return $tag;
	}
}
?>
