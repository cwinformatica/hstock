<?php
	/*
		Retomando a sessão.
	*/
	session_start();
	
	/*
		Destruindo o array $_SESSION.
	*/
	session_unset();
	
	/*
		Destruindo o arquivo de sessão no servidor.
	*/
	session_destroy();
	
	/*
		Redirecionando o usuário para o arquivo index.php para que o mesmo efetue novamente o login.
	*/
	header('Location: index.php');
?>