<?php
class Session
{
    static function init()
    {
       session_set_save_handler(
            array('Session', "create"),
            array('Session', "remove"),
            array('Session', "get"),
            array('Session', "put"),
            array('Session', "destroy"),
            array('Session', "gc"));
            
       register_shutdown_function("session_write_close");
       if (!isset($_SESSION)){session_start();}
       return true;
    }
    static function create($path, $name)
    {
        return true;
    }
    static function remove()
    {
        return true;
    }
    static function exists($id)
    {
		$query = "SELECT * FROM sessions WHERE sessid = '$id'";
        $session = Database::get($query, false);
        if ($session[0] == 0) {return false;}
        else {return true;}
    }
    static function get($id)
    {        
        $sessionrow = Database::get("SELECT * FROM sessions WHERE sessid = '$id'", false);
        if ($sessionrow[0] == 0) {return false;}
        else
        {
            $session = $sessionrow[1][0];
            $deleteme = false;
            if ($session['expiration'] == time()) {$deleteme = true;}
            if ($session['ip'] != $_SERVER["SERVER_ADDR"]) {$deleteme = true;}
            if ($deleteme == true)
            {
                Database::remove(array("sessid", "=", $id), "sessions");
            }
            return $session['data'];
        }
    }
    static function put($id, $data)
    {
        if (self::exists($id) == false){Database::put(array($id, $_SERVER['REMOTE_ADDR'], time()+ini_get('session.gc_maxlifetime'), $data, NULL), "sessions");}
        else 
        {
            Database::update(array("sessid" => $id, 
                                   'ip' => $_SERVER['REMOTE_ADDR'], 
                                   "data" => $data,
                                   "expiration" => time()+ini_get('session.gc_maxlifetime')), "sessions", array("sessid", "'". $id. "'"));
        }
    }
    static function attachUserId($userId)
    {
        Database::update(array("userid" => $userId), "sessions", array("sessid", "'". session_id(). "'"));
    }
    static function destroy($id)
    {
        Database::remove(array("sessid","=",$id), "sessions");
        return true;
    }
    static function gc($max_lifetime)
    {
        Database::remove(array("expiration",">", $max_lifetime), "sessions");
        return true;
    }
}
?>