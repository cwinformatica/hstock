<?php
	function isAuthenticated()
	{
		if(empty($_SESSION['id']) or empty($_SESSION['username']) or empty($_SESSION['password']) or empty($_SESSION['nome']) or empty($_SESSION['admin']))
			return false;
		else
			return true;
	}
	function isAdmin()
	{
		if($_SESSION['admin'] != 'Sim')
			return false;
		else
			return true;
	}
	
	function array_trim($var) 
	{
		if (is_array($var))
			return array_map("array_trim", $var);
		if (is_string($var))
			return trim($var);
		return $var;
	}
?>