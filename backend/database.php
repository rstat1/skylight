<?php
class Database
{
	static $numquerys = 0;
	static $CacheHits = 0;
 	static $dbquerys = " ";
	static $connect_id;		
        static $dataAsCSV = "";
	public static function connect()
	{	
		global $config;
                die();
		self::$connect_id = mysql_connect($config['db-server'], $config['db-user'], $config['db-pass']);
		if(self::$connect_id)
		{
			$dbsel = mysql_select_db($config['db-name']);	
			if ($dbsel = true)
			{
				return self::$connect_id;
			}
			else
			{
				trigger_error(htmlentities(mysql_error()), E_USER_ERROR);
			}
		}   
		else
		{
			trigger_error(htmlentities(mysql_error()), E_USER_ERROR);
		} 
	}    
	public static function get($query, $cache = true)
	{
		$cacheHash = md5($query);
		if (Cache::inCache("sql_$cacheHash.php"))
		{
			self::$CacheHits += 1;
			return Cache::getDataFromCache("sql_$cacheHash.php");
		}
		if ($query != NULL)
		{
                        self::connect();
			$query_result = mysql_query($query, self::$connect_id);
                        if(!$query_result)
			{
				trigger_error(htmlentities(mysql_error($query_result)), E_USER_ERROR);
			}
			else
			{
				$data = array(mysql_num_rows($query_result), self::getResultAsArray($query_result));
				if ($cache) {Cache::putDataInCache(md5($query), $data);}
				self::$numquerys += 1;
				//Eventually return $data instead of $query_result
				return $data;
				//return $query_result;					
                        }
		}
		else
		{
                        return '<b>MySQL Error</b>: Empty Query!';
		}
	}
	public static function ResultCount($queryData)
	{
		if (!is_array($queryData)) {trigger_error("ResultCount needs an array", E_USER_ERROR);}
		return $queryData[0];
	}
	public static function put($data, $type, $table)
	{
                $finalQuery = "";
                $queryType = "";
                if ($data != NULL && $type != NULL)
		{                        
                        switch ($type)
                        {
                                case "update":
                                        /*if (is_array($data)) {$dataToInsert = self::returnArrayAsCSV($data);}
                                        $finalQuery = "UPDATE    " . $table. " VALUES(" . $dataToInsert . ")";*/ 
                                break;
                                case "insert":
                                        if (is_array($data)) {$dataToInsert = self::returnArrayAsCSV($data);}
                                        $finalQuery = "INSERT INTO " . $table. " VALUES(" . $dataToInsert . ")";                                         
                                break;
                                default:
                                        trigger_error(htmlentities($queryType ." is not a recognized query type. INSERT and  UPDATE are the only two choices here"));
                        } 
                        //self::connect();
			//$query_result = mysql_query($query, self::$connect_id);	
                }
                else {throw new InvaildArgumentException("This function requires data and a put type, either INSERT or UPDATE");}
	}
	public static function getResultAsArray($result)
	{
		$table_result=array();
		$r=0;
		while($row = mysql_fetch_assoc($result))
		{
			$arr_row=array();
			$c=0;
			while ($c < mysql_num_fields($result)) 
			{  
				$col = mysql_fetch_field($result, $c);   
				$arr_row[$col -> name] = $row[$col -> name];           
				$c++;
			}   
			$table_result[$r] = $arr_row;
			$r++;
		}   
		return $table_result;
	}
        //Array ( [0] = 4. [1] = "You sucks balls", [2] = 5)
        private static function returnArrayAsCSV($data)
        {
                if (is_array($data))
                {
                        $finalArray = array_map(array("Database", "makeCSVString"), $data);
                        $final = $finalArray[count($finalArray) - 1];
                        $data = str_split($final, strrpos($final, ","));
                        array_pop($data);
                        return $data[0];
                }
                else {throw new InvaildArgument("returnArrayAsCSV kind of requires an array. DUH!!");}
        }
        private static function makeCSVString($n)
        {
                if (is_numeric($n)) 
                {
                        self::$dataAsCSV .= $n. ",";
                }
                else {self::$dataAsCSV .= "'" .$n. "'," ;}
                return self::$dataAsCSV;
        }
}
