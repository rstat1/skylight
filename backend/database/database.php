<?php
//TODO: Make database stuff easily extendable, and not depenedent on MySQL of any kind.
class Database
{
	static $numquerys = 0;
	static $CacheHits = 0;
 	static $dbquerys = " ";
	static $connect_id;		
	static $dataAsCSV = "";
	static $columns = "";
	public static function connect()
	{	
		global $config;
		if (!isset(self::$connect_id)) {self::$connect_id = mysql_connect($config['db-server'], $config['db-user'], $config['db-pass']);}
		if(self::$connect_id)
		{
			$dbsel = mysql_select_db($config['db-name']);	
			if ($dbsel = true){return self::$connect_id;}
			else{trigger_error(htmlentities(mysql_error()), E_USER_ERROR);}
		}   
		else{trigger_error(htmlentities(mysql_error()), E_USER_ERROR);} 
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
			if(!$query_result){trigger_error(htmlentities(mysql_error()), E_USER_ERROR);}
			else
			{
				$data = array(mysql_num_rows($query_result), self::getResultAsArray($query_result));
				if ($cache) {Cache::putDataInCache(md5($query), $data);}
				self::$numquerys += 1;
				return $data;
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
	public static function put($data, $table)
	{
		$finalQuery = "";
		$queryType = "";
		if ($data != NULL && $table != NULL)
		{			 
			if (is_array($data))
			{
				$dataToInsert = Utils::returnArrayAsCSV($data);
				$finalQuery = "INSERT INTO " . $table. " VALUES(" . $dataToInsert . ")";
                self::connect();
				$query_result = mysql_query($finalQuery, self::$connect_id);	
				if ($query_result){return true;}
				else {trigger_error(mysql_error());die();}
			}
			else {trigger_error("Function expects first argument to be an array.");}
		}
		else {trigger_error("This function requires data and table name.");}
	}
	public static function update($data, $table, $keyfield)
	{
        $finalQuery = "";
		$queryType = "";
		if ($data != NULL && $table != NULL)
		{			 
			if (is_array($data))
			{
		        self::$columns = Utils::returnColumnNames($data);
		        $sets = Utils::makeSets(explode(",",self::$columns), $data);
                $sets = trim($sets, ",");
                $finalQuery = "UPDATE $table SET $sets WHERE $keyfield[0] = ". $keyfield[1];
                self::connect();
				$query_result = mysql_query($finalQuery, self::$connect_id);	
				if ($query_result){return true;}
				else {trigger_error(mysql_error());die();}
            }
			else {trigger_error("Function expects first argument to be an array.");}
		}
		else {trigger_error("This function requires data, a table name and a key field.");}
	}
    public static function remove($where, $table)
    {
        if ($where != NULL && $table != NULL)
		{
            if (is_array($where))
			{
                $finalQuery = "DELETE FROM $table WHERE $where[0] $where[1]  '$where[2]'";
                self::connect();                
				$query_result = mysql_query($finalQuery, self::$connect_id);	
				if ($query_result){return true;}
				else {trigger_error(mysql_error(). " " . $finalQuery);die();}
            }
        	else {trigger_error("Function expects first argument to be an array.");}
		}
		else {trigger_error("This function requires some form of criteria so it knows what to delete and a table name so it knows where to delete from.");}
    }
	//borrowed from a page about a particular MySQL command on http://php.net
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
}