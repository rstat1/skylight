<?php
class window
{
    private static $idCount = NULL;
    static function create()
    {
        $title = $_POST['title'];
        $width = $_POST['width'];
        $height = $_POST['height'];
        $position = $_POST['position'];
        $content = $_POST['content'];
        $filepath = URL::scriptPath() . "/style/admin";
       	$window = file_get_contents($filepath. "/window.htm", FILE_USE_INCLUDE_PATH);
        if ($idCount == NULL) {$idCount = $_SESSION['idCount'];}
        $tags = array("{#ID#}", "{#WINDOWTITLE#}", "{#HEIGHT#}", "{#WIDTH#}", "{#WINDOWCONTENT#}", "{#LEFT#}", "{#RIGHT#}", "{#TOP#}", "{#BOTTOM#}");
       // if (!is_array($position)) {trigger_error("position must be an array, with at least 4 values");}
       // if (count($position) != 4) {trigger_error("position must contain at least 4 values");}
        $values = array($idCount, $title, $height, $width, $content, $postion[0][0]."px", $position[1][0]."px", $position[2][0]."px", $position[3][0]."px");
        $windowContent = array_combine($tags, $values);
        $window = Theme::parse("", "", $tags, $windowContent, $window, false, false);
       // $_SESSION['idCount'] = $idCount;
        echo $window;
    }
    static function makeResizableAndDraggable()
    {
        echo '$(function() {
                 $(".window'. $_SESSION['idCount'] .'").resizable({containment: "#window-container"}).draggable({handles: "n, e, s, w", containment: "#window-container", scroll: false});                 
           });';
    }
}
?>