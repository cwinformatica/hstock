﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK - Módulo Estoque</title>
        <link href="../estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
    <?php
	require_once('../includes/functions.php');
	session_start();
	if(isAuthenticated() == false)
	{
		echo "<p class='error_message'>Por favor, efetue o login.</p>";
		exit;
	}
	elseif(hasPermission($_SESSION['id'], 'Estoque') == false)
	{
		echo "<p class='error_message'>Você não possui privilégios para acessar esta área.</p>";
		exit;
	}
	/*
		Verifica se a configuração de log está ligada ou desligada. Se estiver ligada, ele irá fazer uso da 
		função logAction.
	*/
	$c = new conexao;
	$c->set_charset('utf8');
	$q = "SELECT * FROM configuracoes WHERE opcao = 'log';";
	$r = $c->query($q);
	$log = $r->fetch_object();
	if($log->valor == 'ligado')
		logAction($_SESSION['id'], $_SERVER['REQUEST_URI'], var_export($_POST, true), var_export($_GET, true));
	?>
   	<div id="header">
    	<h1>HSTOCK - Módulo Estoque</h1>
    </div>
    
    <div id="menu">
        <?php require_once('menu.php'); ?>
	</div>
    	
    <div id="content">
    	<p>Seja bem-vindo(a), <?php echo $_SESSION['nome']; ?>!</p>
    </div>
    </body>
</html>