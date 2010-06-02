<?php
	require_once('conexao.class.php');
	
	function isAuthenticated()
	{
		if(empty($_SESSION['id']) or empty($_SESSION['username']) or empty($_SESSION['password']) or empty($_SESSION['nome']))
			return false;
		else
			return true;
	}
	
	function hasPermission($id, $modulo)
	{
		$c = new conexao;
		$c->set_charset('utf8');
		/*
			a: usuarios;
			b: permisssoes;
			c: modulos.
		*/
		$q = "SELECT a.id FROM usuarios AS a INNER JOIN permissoes AS b ON a.id = b.usuario_id INNER JOIN modulos AS c ON b.modulo_id = c.id WHERE a.id = '$id' AND c.nome = '$modulo';";
		$r = $c->query($q);
		if($r->num_rows > 0)
			return true;
		else
			return false;
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