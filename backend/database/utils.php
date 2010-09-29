<?php
class Utils
{
	static $csvColumns = "";
	static $columnNames = "";
	static $dataAsCSV = "";
	public static function returnColumnNames($data)
	{
		$keys = array_keys($data);
		self::$columnNames = "true";
		self::$csvColumns = self::returnArrayAsCSV($keys, "true");
		return self::$csvColumns;		
	}
	public static function returnArrayAsCSV($data, $columnNames = "")
	{	
		self::$csvColumns = "";
		self::$dataAsCSV = "";
		if (is_array($data))
		{
			$finalArray = array_map(array("Utils", "makeCSVString"), $data, array(self::$columnNames));
			$final = $finalArray[count($finalArray) - 1];
			$data = str_split($final, strrpos($final, ","));
			array_pop($data);
			return $data[0];
		}
		else {throw new InvaildArgument("returnArrayAsCSV kind of requires an array. DUH!!");}
	}
	public static function makeSets($columns, $data)
	{
		$finalSets = array();
		foreach($columns as $value)
		{
			if (!is_numeric($data[$value])) {$finalSets[$value] = "'". $data[$value] . "'";}
			else {$finalSets[$value] = $data[$value];}
		}
		echo self::returnArrayAsCSV($finalSets, "false");
	}
	public static function singleQuoteAString($string)
	{
		return "'". $string . "'";
	}
	private static function makeCSVString($n, $m)
	{		
    	if (is_numeric($n)){self::$dataAsCSV .= $n. ",";}
        else
        {	
			if (self::$columnNames == "true"){self::$dataAsCSV .= $n. ",";}
			else {self::$dataAsCSV .= "'" .$n. "',";}
        	
        }
		return self::$dataAsCSV;
	}	
}