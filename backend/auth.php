<?php
// Base class for Pluggable authentication
abstract class auth
{
	abstract static function challenge($user, $pass);
}
?>