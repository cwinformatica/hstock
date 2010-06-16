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
		Testando se o usuário está autenticado e se ele possui permissão para acessar o módulo atual.
	*/
	if(isAuthenticated() == false)
	{
		echo "<p class='error_message'>Por favor, efetue o login.</p>";
		exit;
	}
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
			/*
				Recebendo o valor da variável $action via método GET e testando.	
			*/
			@$action = $_GET['action'];
			switch($action)
			{
				/*
					Formulário para adicionar um novo usuário.
				*/
				case 'add':
					?>
					<h2>Cadastro de novo usuário</h2>
                    <form action='usuarios.php?action=create' method="post">
                    <table class="content_table">
                    	<tr>
                        	<th>Username</th>
                            <td><input type='text' name='username' maxlength="255" /></td>
                        </tr>
                        <tr>
                        	<th>Senha</th>
                            <td><input type='password' name='password' maxlength="255" /></td>
                        </tr>
                        <tr>
                        	<th>Confirmar senha</th>
                            <td><input type='password' name='cpassword' maxlength="255" /></td>
                        </tr>
                        <tr>
                        	<th>Nome</th>
                            <td><input type='text' name='nome' maxlength="255" /></td>
                        </tr>
                        <tr>
                        	<td colspan="2" class="bottom_row">
                            	<input type='submit' value='Adicionar usuário' />&nbsp;
                                <input type='reset' value='Limpar formulário' />
                            </td>
                        </tr>
                    </table>
                    </form>
					<?php
				break;
				
				
				/*
					Insere o novo usuário na base de dados.
				*/
				case 'create':
					/*
						Percorre o array $_POST, removendo os espaços a mais com a função array_trim.
					*/				
					$_POST = array_trim($_POST);
					
					/*
						Recebendo os dados do formulário.
					*/
					$username = $_POST['username'];
					$password = $_POST['password'];
					$cpassword = $_POST['cpassword'];
					$nome = $_POST['nome'];
					
					/*
						Testando se ambas as senhas informadas batem. Caso negativo, mostra uma mensagem para o 
						usuário informando o erro. Caso positivo, insere os valores recebidos do formulário na
						base de dados e redireciona o usuário para o arquivo usuarios.php.
					*/
					if($password != $cpassword): ?>
                    	<p class="error_message">As senhas não conferem. Por favor, digite novamente as senhas.</p>
	                    <?php
						exit;
					endif;
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "INSERT INTO usuarios(username, password, nome) VALUES('$username', md5('$password'), '$nome');";
					$c->query($q);
					header('Location: usuarios.php');
				break;
				
				
				/*
					Formulário para editar os dados de um usuário.
				*/
				case 'edit':
					/*
						Recebendo os dados via GET.
					*/
					$id = $_GET['id'];
					
					/*
						Conectando ao banco de dados e buscando os dados do usuário cujo id foi informado. Então, esses
						dados são utilizados para preencher os campos do formulário.
					*/
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "SELECT * FROM usuarios WHERE id = '$id';";
					$r = $c->query($q);
					$usuario = $r->fetch_object();
					?>
					<h2>Editando usuário <?php echo $usuario->nome; ?></h2>
                    <form action='usuarios.php?action=update&id=<?php echo $usuario->id; ?>' method="post">
                    <table class="content_table">
                    	<tr>
                        	<th>Username</th>
                            <td><input type='text' name='username' maxlength="255" value='<?php echo $usuario->username; ?>' /></td>
                        </tr>
                        <tr>
                        	<th>Senha antiga</th>
                            <td><input type='password' name='opassword' maxlength="255" /></td>
                        </tr>
                        <tr>
                        	<th>Nova senha</th>
                            <td><input type='password' name='password' maxlength="255" /></td>
                        </tr>
                        <tr>
                        	<th>Confirmação de nova senha</th>
                            <td><input type='password' name='cpassword' maxlength="255" /></td>
                        </tr>
                        <tr>
                        	<th>Nome</th>
                            <td><input type='text' name='nome' maxlength="255" value='<?php echo $usuario->nome; ?>' /></td>
                        </tr>
                        <tr>
                        	<td colspan="2" class="bottom_row">
                            	<input type='submit' value='Atualizar' />&nbsp;
                                <input type='reset' value='Limpar formulário' />
                            </td>
                        </tr>
                    </table>
					</form>
					<?php
				break;
				
				
				/*
					Atualiza um usuário na base de dados.
				*/
				case 'update':
					/*
						Recebendo os dados via GET.
					*/
					$id = $_GET['id'];
					
					/*
						Percorre o array $_POST, removendo os espaços a mais com a função array_trim.
					*/
					$_POST = array_trim($_POST);
					
					/*
						Recebendo os novos dados do usuário e atualizando a base de dados. Após isso, o usuário é redirecionado
						para o arquivo usuarios.php.
					*/
					$username = $_POST['username'];
					$opassword = $_POST['opassword'];
					$password = $_POST['password'];
					$cpassword = $_POST['cpassword'];
					$nome = $_POST['nome'];
					
					/*
						Testa se os valores de $password e $cpassword conferem.
					*/
					if($password != $cpassword):
						echo "<p class='error_message'>As senhas informadas não batem. Por favor, volte e tente novamente.</p>";
						exit;
					else:
						$c = new conexao;
						$c->set_charset('utf8');
						$q = "SELECT * FROM usuarios WHERE id = '$id';";
						$r = $c->query($q);
						$usuario = $r->fetch_object();
						if($usuario->password != md5($opassword)):
							echo "<p class='error_message'>A senha antiga não confere.</p>";
							exit;
						else:
							$q = "UPDATE usuarios SET username = '$username', password = md5('$password'), nome = '$nome' WHERE id = '$id';";
							$c->query($q);
							header('Location: usuarios.php');
						endif;
					endif;
				break;
				
				
				/*
					Visualiza dados de um usuário.
				*/
				case 'view':
					/*
						Recebendo o id do usuário via GET.
					*/
					$id = $_GET['id'];
					
					/*
						Conectando a base de dados e buscando os dados do usuário.
					*/
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
                    </table><br />
                    <form action='usuarios.php?action=permissoes&id=<?php echo $id; ?>' method="post">
					<table class="content_table">
                    	<tr>
                        	<th colspan="2">Permissões</th>
						</tr>
                        <?php
						/*
							Mostrando os módulos que o usuário pode acessar.
						*/
						$q = "SELECT * FROM modulos;";
						$r = $c->query($q);
						while($modulo = $r->fetch_object()): ?>
                        	<tr>
	                        	<td><?php echo $modulo->nome; ?></td>
                                <td>
                               	<?php 
									$q = "SELECT a.id, a.nome FROM modulos AS a INNER JOIN permissoes AS b ON a.id = b.modulo_id INNER JOIN usuarios AS c ON b.usuario_id = c.id WHERE c.id = '$id' AND a.nome = '" . $modulo->nome . "';";
									$r1 = $c->query($q);
									
									/*
										Se o usuário possui permissão para acessar o módulo, o checkbox irá aparecer 
										marcardo. Senão, ele irá aparecer desmarcado.
									*/
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
				
				
				/*
					Seta as permissões de um usuário no banco de dados.
				*/
				case 'permissoes':
					/*
						Recebendo o id do usuário via GET.
					*/
					$id = $_GET['id'];
					
					/*
						Conecta no banco de dados, remove todas as permissões existentes do usuário, cria 
						as novas permissões e redireciona o usuário para o arquivo usuarios.php
					*/
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
				
				
				/*
					Remove um usuário do banco de dados.
				*/
				case 'delete':
					/*
						Recebe o id do usuário via GET.
					*/
					$id = $_GET['id'];
					
					/*
						Após remover o usuário da base, redireciona o usuário para o arquivo usuario.php.
					*/
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "DELETE FROM usuarios WHERE id = '$id'";
					$c->query($q);
					header('Location: usuarios.php');
				break;
				
				
				/*
					Mostra a lista de usuários do sistema.
				*/
				default:
					?>
					<h2>Lista de usuários</h2>
                    <table class="content_table">
                    	<tr>
                        	<th>ID</th>
                            <th>Username</th>
                            <th>Nome</th>
                            <th>Editar</th>
                            <th>Apagar</th>
                        </tr>
                        <?php
						$c = new conexao;
						$c->set_charset('utf8');
						$q = "SELECT * FROM usuarios;";
						$r = $c->query($q);
						while($usuario = $r->fetch_object()): ?>
                        	<tr>
                            	<td><?php echo $usuario->id; ?></td>
                                <td><a href='usuarios.php?action=view&id=<?php echo $usuario->id; ?>'><?php echo $usuario->username; ?></a></td>
                                <td><?php echo $usuario->nome; ?></td>
                                <td><a href='usuarios.php?action=edit&id=<?php echo $usuario->id; ?>'>Editar</a></td>
                                <td><a href='usuarios.php?action=delete&id=<?php echo $usuario->id; ?>'>Apagar</a></td>
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