<?php
class Database
{
	static $numquerys = 0;
 	static $dbquerys = " ";
	static $connect_id;		
	public static function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

	  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

	  switch ($theType) {
	    case "text":
    	  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	    break;    
    	case "long":
	    case "int":
	      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
    	break;
	    case "double":
    	  $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
	    break;
    	case "date":
	   	  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	    break;
    	case "defined":
	      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
    	break;
	  }
	  return $theValue;
	}

    public static function connect()
	{	
		global $config;
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
              return self::error();
           }
		}   
        else
		{
            return self::error();
        } 
    }
    public static function error()
	{
        if(mysql_error() != '')
		{
			if (@function_exists("errorbox")) {errorbox("",mysql_error(), "", "");}
		    return mysql_error();
		    //echo '<b>MySQL Error</b>: '.mysql_error().'<br/>';				
        }
    }
		
       public static function sql_query($query)
		{
		    //global $numquerys, $dbquerys, $db;
			if ($query != NULL)
			{
				$query_result = mysql_query($query, self::$connect_id);
                if(!$query_result)
				{
                    return self::error();
                }
				else
				{
				    self::$numquerys += 1;
					if (@function_exists("debugmsg")) {@debugmsg($query);}
					return $query_result;					
             	}
            }
			else{
               return '<b>MySQL Error</b>: Empty Query!';
            }
        }
        public static function get_num_rows($query_id = "")
		{
            if($query_id == NULL)
			{
                $return = mysql_num_rows($query_result);
            }
			else
			{
                $return = mysql_num_rows($query_id);
            }
            if(!$return)
			{
                $error();
            }
			else
			{
                return $return;
            }
        }
		public static function fetch_array($query_id = "")
		{
		    if($query_id == NULL)
			{
                $return = @mysql_fetch_array($query_result);
            }
			else
			{
                $return = @mysql_fetch_array($query_id);
            }
            if(!$return)
			{
                self::error();
            }
			else
			{
                return $return;
            }

		}
        public static function fetch_row($query_id = "")
		{
            if($query_id == NULL)
			{
                $return = @mysql_fetch_row($query_result);
            }
			else
			{
                $return = @mysql_fetch_row($query_id);
            }
            if(!$return)
			{
                $error();
            }
			else
			{
                return $return;
            }
        }   
        public static function get_affected_rows($query_id = "")
		{
            if($query_id == NULL)
			{
                $return = mysql_affected_rows($query_result);
            }
			else
			{
                $return = mysql_affected_rows($query_id);
            }
            if(!$return)
			{
                $error();
            }
			else
			{
                return $return;
            }
        }
        public static function sql_close()
		{
            if($connect_id)
			{
                return mysql_close($connect_id);
            }
        }
    }
