<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK - Módulo Administrador</title>
        <link href="../estilos_hton.css" rel="stylesheet" type="text/css" />
        <script type='text/javascript' src="../includes/jquery.js"></script>
        <script type='text/javascript'>
        	$("document").ready(
				function()
				{
					$("tr:even").addClass("linhaPar");
					$("tr:odd").addClass("linhaImpar");
				}
			);
        </script>
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
	$c = new conexao;
	$c->set_charset('utf8');
	$q = "SELECT * FROM configuracoes WHERE opcao = 'log';";
	$r = $c->query($q);
	$log = $r->fetch_object();
	if($log->valor == 'ligado')
		logAction($_SESSION['id'], $_SERVER['REQUEST_URI'], var_export($_POST, true), var_export($_GET, true));
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
				case 'edit':
					$opcao = $_GET['opcao'];
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "SELECT * FROM configuracoes WHERE opcao = '$opcao';";
					$r = $c->query($q);
					$configuracao = $r->fetch_object();
					?>
					<h2>Editando configuração <?php echo $configuracao->opcao; ?></h2>
                    <form action='configuracoes.php?action=update&opcao=<?php echo $configuracao->opcao; ?>' method="post">
                    <table class="content_table">
                    	<tr>
                        	<th>Opção</th>
                            <td><input type='text' name='opcao' maxlength="255" value='<?php echo $configuracao->opcao; ?>' /></td>
                        </tr>
                        <tr>
                        	<th>Valor</th>
                            <td>
								<?php
                                switch($configuracao->tipo)
                                {
                                    case 'ONOFF':
										if($configuracao->valor == 'ligado'): ?>
											<input type='radio' name='valor' value='ligado' checked="checked" />Ligado
                                            <input type='radio' name='valor' value='desligado' />Desligado
										<?php else: ?>
											<input type='radio' name='valor' value='ligado' />Ligado
                                            <input type='radio' name='valor' value='desligado' checked="checked" />Desligado
										<?php endif; 
                                    break;
                                    
                                    
                                    case 'Valor':
										?>
											<input type='text' name='valor' value='<?php echo $configuracao->valor; ?>' />
										<?php
                                    break;
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="2" class="bottom_row">
                            	<input type='submit' value='Atualizar' />&nbsp;
                            </td>
                        </tr>
                    </table>
					</form>
					<?php
				break;
				
				
				case 'update':
					$opcao = $_GET['opcao'];
					$_POST = array_trim($_POST);
					$valor = $_POST['valor'];
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "UPDATE configuracoes SET valor = '$valor' WHERE opcao = '$opcao';";
					$c->query($q);
					header('Location: configuracoes.php');
				break;
				
				
				case 'view':
					$id = $_GET['id'];
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "SELECT * FROM usuarios WHERE id = '$id';";
					$r1 = $c->query($q);
					$usuario = $r1->fetch_object();
					?>
					<table class="content_table">
                    	<tr>
                        	<th>Username</th>
                            <td><?php echo $usuario->username; ?></td>
                        </tr>
                        <tr>
                        	<th>Nome</th>
                            <td><?php echo $usuario->nome; ?></td>
                        </tr>
                        <?php
						/*
							a: usuarios;
							b: permissoes;
							c: modulos.
						*/
						$q = "SELECT c.nome FROM usuarios AS a INNER JOIN permissoes AS b ON a.id = b.usuario_id INNER JOIN modulos AS c ON b.modulo_id = c.id WHERE a.id = $id;";
						$r2 = $c->query($q);
						?>
						<tr>
                        	<th rowspan="<?php echo $r2->num_rows; ?>">Módulos acessíveis pelo usuário</th>
							<?php
                            while($modulo = $r2->fetch_object()): ?>
                                <td><?php echo $modulo->nome; ?></td>
                        </tr>
						<?php endwhile; ?>
                    </table><br />
                    <form action='usuarios.php?action=permissoes&id=<?php echo $id; ?>' method="post">
					<table class="content_table">
                    	<tr>
                        	<th colspan="2">Permissões</th>
						</tr>
                        <?php
						$q = "SELECT * FROM modulos;";
						$r = $c->query($q);
						while($modulo = $r->fetch_object()): ?>
                        	<tr>
	                        	<td><?php echo $modulo->nome; ?></td>
                                <td>
                               	<?php 
									$q = "SELECT a.id, a.nome FROM modulos AS a INNER JOIN permissoes AS b ON a.id = b.modulo_id INNER JOIN usuarios AS c ON b.usuario_id = c.id WHERE c.id = '$id' AND a.nome = '" . $modulo->nome . "';";
									$r1 = $c->query($q);
									if($r1->num_rows == 0): ?>
										<input type='checkbox' name='permissoes[<?php echo $modulo->id; ?>]' />
									<?php else: ?>
										<input type='checkbox' name='permissoes[<?php echo $modulo->id; ?>]' checked />
									<?php endif; ?>
                                </td>
                            </tr>
						<?php endwhile; ?>
                        <tr>
                        	<td class="bottom_row" colspan="2"><input type='submit' value="Atualizar permissões" /></td>
                        </tr>
                    </table>
                    </form>
					<?php
				break;
				
				
				case 'permissoes':
					$id = $_GET['id'];
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "DELETE FROM permissoes WHERE usuario_id = '$id';";
					$c->query($q);
					foreach($_POST['permissoes'] as $chv => $vlr)
					{
						$q = "INSERT INTO permissoes(usuario_id, modulo_id) VALUES('$id', '$chv');";	
						$c->query($q);
					}
					header('Location: usuarios.php');
				break;
				
				
				case 'delete':
					$id = $_GET['id'];
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "DELETE FROM usuarios WHERE id = '$id'";
					$c->query($q);
					header('Location: usuarios.php');
				break;
				
				
				default:
					?>
					<h2>Configurações</h2>
                    <table class="content_table">
                    	<tr>
                        	<th>Opção</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Editar</th>
                        </tr>
                        <?php
						$c = new conexao;
						$c->set_charset('utf8');
						$q = "SELECT * FROM configuracoes ORDER BY opcao;";
						$r = $c->query($q);
						while($configuracao = $r->fetch_object()): ?>
                        	<tr>
                            	<td><?php echo $configuracao->opcao; ?></td>
                                <td><?php echo $configuracao->descricao; ?></td>
                                <td><?php echo $configuracao->valor; ?></td>
                                <td><a href='configuracoes.php?action=edit&opcao=<?php echo $configuracao->opcao; ?>'>Editar</a></td>
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