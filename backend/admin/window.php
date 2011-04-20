<?php
class Window
{
    private static $idCount;
	private static $toolbarItems = array();
	public function __construct($title, $width, $height, $content, $toolbarArr)
	{
		
	}
	private function create($title, $width, $height, $content, $toolbarArr)
    {  
        $filepath = URL::scriptPath() . "/style/admin";
       	$window = file_get_contents($filepath. "/window.htm", FILE_USE_INCLUDE_PATH);
		
		$_SESSION['idCount'] = rand(0, 100);		
        if (self::$idCount == NULL) {self::$idCount = $_SESSION['idCount'];}
        $tags = array("{#ID#}", "{#WINDOWTITLE#}", "{#HEIGHT#}", "{#WIDTH#}", "{#WINDOWCONTENT#}", "{#LEFT#}", "{#RIGHT#}", "{#TOP#}", "{#BOTTOM#}", "{#WINDOWTOOLBAR#}");
       
	    $values = array(NULL, $title, $height, $width, $content, $postion[0][0]."px", $position[1][0]."px", $position[2][0]."px", $position[3][0]."px", self::makeToolbarFromItems());
        $windowContent = array_combine($tags, $values);
        $window = Theme::parse("", "", $tags, $windowContent, $window, false, false);
        	
        echo json_encode(array("html" => $window, "windowid" => $_SESSION['idCount']),JSON_HEX_AMP);
    }
	private function makeToolbarFromArray()
	{
		
	}
}
?>