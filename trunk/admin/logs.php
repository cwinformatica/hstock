<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK - Módulo Administrador</title>
        <link href="../estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
    <?php
	/*
		Importando classes e bibliotecas.
	*/
	require_once('../includes/functions.php');
	require_once('../includes/conexao.class.php');
	
	/*
		Retomando a sessão.
	*/
	session_start();
	
	/*
		Testando se o usuário está autenticado.
	*/
	if(isAuthenticated() == false)
	{
		echo "<p class='error_message'>Por favor, efetue o login.</p>";
		exit;
	}
	
	/*
		Testando se o usuário possui permissão para acessar o módulo atual.
	*/
	elseif(hasPermission($_SESSION['id'], 'Admin') == false)
	{
		echo "<p class='error_message'>Você não possui privilégios para acessar esta área.</p>";
		exit;
	}
	
	/*
		Verifica se a configuração de log está ligada ou desligada. Se estiver ligada, ele irá fazer uso da 
		função logAction.
	*/
	/*$c = new conexao;
	$c->set_charset('utf8');
	$q = "SELECT * FROM configuracoes WHERE opcao = 'log';";
	$r = $c->query($q);
	$log = $r->fetch_object();
	if($log->valor == 'ligado')
		logAction($_SESSION['id'], $_SERVER['REQUEST_URI'], var_export($_POST, true), var_export($_GET, true));*/
	?>
   	<div id="header">
    	<h1>HSTOCK - Módulo Administrador</h1>
    </div>
    
    <div id="menu">
        <?php require_once('menu.php'); ?>
	</div>
    	
    <div id="content">
    	<?php
			@$action = $_GET['action'];
			switch($action)
			{	
				case 'delete':
					$id = $_GET['id'];
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "DELETE FROM logs WHERE id = '$id'";
					$c->query($q);
					header('Location: logs.php');
				break;
				
				
				case 'clearall':
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "DELETE FROM logs;";
					$c->query($q);
					header('Location: logs.php');
				break;
				
				
				default:
					?>
					<h2>Logs</h2>
                    <table class="content_table">
                    	<tr>
                        	<th>ID</th>
                            <th>Usuário</th>
                            <th>URL</th>
                            <th>GET</th>
                            <th>POST</th>
                            <th>Horário</th>
                            <th>Apagar</th>
                        </tr>
                        <?php
						$c = new conexao;
						$c->set_charset('utf8');
						/*
							a: usuarios;
							b: logs.
						*/
						$q = "SELECT a.nome, b.* FROM usuarios AS a INNER JOIN logs AS b ON a.id = b.usuario_id ORDER BY b.id DESC;";
						$r = $c->query($q);
						while($log = $r->fetch_object()): ?>
                        	<tr>
                            	<td><?php echo $log->id; ?></td>
                                <td><?php echo $log->nome; ?></td>
                                <td><?php echo $log->url; ?></td>
                                <td><?php echo $log->get; ?></td>
                                <td><?php echo $log->post; ?></td>
                                <td><?php echo $log->horario; ?></td>
                                <td><a href='logs.php?action=delete&id=<?php echo $log->id; ?>'>Apagar</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
					<?php
				break;
			}
		?>
    </div>
    </body>
</html>