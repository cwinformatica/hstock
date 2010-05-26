<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK::Módulo Administrador</title>
        <link href="estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
    <?php
	require_once('classes/functions.php');
	require_once('classes/conexao.class.php');
	session_start();
	if(isAuthenticated() == false)
	{
		echo "<p class='error_message'>Por favor, efetue o login.</p>";
		exit;
	}
	?>
   	<div id="header">
    	<h1>HSTOCK::Módulo Administrador</h1>
    </div>
    
    <div id="menu">
        <?php require_once('menu.php'); ?>
	</div>
    	
    <div id="content">
    	<?php
			@$action = $_GET['action'];
			switch($action)
			{
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
				
				
				case 'create':
					$_POST = array_trim($_POST);
					$username = $_POST['username'];
					$password = $_POST['password'];
					$nome = $_POST['nome'];
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "INSERT INTO usuarios(username, password, nome) VALUES('$username', md5('$password'), '$nome');";
					$c->query($q);
					header('Location: usuarios.php');
				break;
				
				
				case 'edit':
					$id = $_GET['id'];
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
                        	<th>Senha</th>
                            <td><input type='password' name='password' maxlength="255" value='<?php echo $usuario->password; ?>' /></td>
                        </tr>
                        <tr>
                        	<th>Nome</th>
                            <td><input type='text' name='nome' maxlength="255" value='<?php echo $usuario->nome; ?>' /></td>
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
				
				
				case 'update':
					$id = $_GET['id'];
					$_POST = array_trim($_POST);					
					$username = $_POST['username'];
					$password = $_POST['password'];
					$nome = $_POST['nome'];
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "UPDATE usuarios SET username = '$username', password = md5('$password'), nome = '$nome' WHERE id = '$id';";
					$c->query($q);
					header('Location: usuarios.php');
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