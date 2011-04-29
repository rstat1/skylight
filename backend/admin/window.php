<?php
class Window
{
    private static $idCount;
	private static $top;
	private static $toolbarItems = array();
	public function __construct($title, $width, $height, $content, $toolbarArr = NULL)
	{
		$this->create($title, $width, $height, $content, $toolbarArr);		
	}
	private function create($title, $width, $height, $content, $toolbarArr = NULL)
    { 
		self::$toolbarItems = $toolbarArr;
        $filepath = URL::scriptPath() . "/style/admin";
       	$window = file_get_contents($filepath. "/window.htm", FILE_USE_INCLUDE_PATH);
		//if ($_SESSION['zOrder'] == NULL) {$_SESSION['zOrder'] = 0;}
		//$_SESSION['zOrder'] += 1;
		$_SESSION['topVal'] = $_SESSION['topVal'] + 15;
		self::$top = $_SESSION['topVal'];
        $tags = array("{#ID#}", "{#WINDOWTITLE#}", "{#HEIGHT#}", "{#WIDTH#}", "{#WINDOWCONTENT#}", "{#LEFT#}", "{#RIGHT#}", "{#TOP#}", "{#BOTTOM#}", "{#WINDOWTOOLBAR#}");//, "{#STACKPOS#}");
       
	    $values = array(NULL, $title, $height ."px", $width."px", $content, $postion[0][0]."px", $position[1][0]."px", self::$top. "px", "0px", $this->makeToolbarFromItems());//,$_SESSION['zOrder']);
        $windowContent = array_combine($tags, $values);
        $window = Theme::parse("", "", $tags, $windowContent, $window, false, false);
        	
        echo json_encode(array("html" => $window, "windowid" => $_SESSION['idCount']),JSON_HEX_AMP);
    }
	private function makeToolbarFromItems()
	{
		$final = "";
		$leftMargin = 3;
		
		if (isset(self::$toolbarItems) && count(self::$toolbarItems) > 0)
		{
			for($i = 0; $i < count(self::$toolbarItems); $i++)
			{	
				$img = '<img id="" style="margin-left:'.$leftMargin. 'px;margin-top:4px;" src="' .self::$toolbarItems[$i]['image']. '" onclick="'.self::$toolbarItems[$i]['jsFunc']. '"/>';
				$final .= $img;
			}
			return $final;
		}
	}
	function __destruct()
	{
		
		$_SESSION['topVal'] = 0;
	}
}
?>