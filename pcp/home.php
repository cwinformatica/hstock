<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK - Módulo Estoque</title>
        <link href="estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
    <?php
	require_once('classes/functions.php');
	session_start();
	if(isAuthenticated() == false)
	{
		echo "<p class='error_message'>Por favor, efetue o login.</p>";
		exit;
	}
	?>
   	<div id="header">
    	<h1>HSTOCK::Módulo PCP</h1>
    </div>
    
    <div id="menu">
        <?php require_once('menu.php'); ?>
	</div>
    	
    <div id="content">
    	<p>Seja bem-vindo(a), <?php echo $_SESSION['nome']; ?>!</p>
    </div>
    </body>
</html>