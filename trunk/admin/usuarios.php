<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK - Módulo Administrador</title>
        <link href="estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
		<?php
			require_once('../classes/functions.php');
			session_start();
			if(isAuthenticated() == false)
			{
				echo "<p class='error_message'>Por favor, efetue o login.</p>";
				exit;
			}
			else if(isAdmin() == false)
			{
				echo "<p class='error_message'>Você não é administrador.</p>";
				exit;
			}
        ?>
        <div id="header">
            <h1>HSTOCK - Módulo Administrador</h1>
        </div>
        
        <div id="menu">
            <ul>
                <li>Usuários
                  <ul>
                    <li><a href="usuarios.php">Cadastro de Usuários</a></li>
                    <li><a href="usuarios.php?action=add">Adicionar Novo Usuário</a></li>
                  </ul>
              </li>
                <li>Logs
                  <ul>
                    <li><a href="logs.php">Visualizar Logs</a></li>
                    <li><a href="logs.php?action=pesquisar">Pesquisar</a></li>
                  </ul>
              </li>
                <li>Outras Ações
                    <ul>
                        <li><a href="logout.php">Efetuar Logout </a></li>
                    </ul>
                </li>
            </ul>
	    </div>
            
        <div id="content">
            <?php
            require_once('../classes/conexao.class.php');
            @$action = $_GET['action'];
            switch($action)
            {
                case 'add':
                    ?>
                        <table class="content_table">
                        <form action='usuarios.php?action=create' method='post'>
                            <tr>
                                <th>Username</th>
                                <td><input type='text' name='username' maxlength="255"/>
                            </tr>
                            <tr>
                                <th>Senha</th>
                                <td><input type='password' name='password' maxlength="255"/>
                            </tr>
                            <tr>
                                <th>Nome</th>
                                <td><input type='text' name='nome' maxlength="255"/>
                            </tr>
                            <tr>
                                <th>Administrador?</th>
                                <td>
                                    <label>
                                      <input type="radio" name="admin" value="Sim" />
                                      Sim</label>
                                    <label>
                                      <input type="radio" name="admin" value="Não" checked />
                                      Não</label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="bottom_row">
                                    <input type='submit' value='Cadastrar' />&nbsp;
                                    <input type='reset' value='Limpar' />
                                </td>
                            </tr>
                        </form>                 
                        </table>
                    <?php
                break;
                
                
                
                case 'create':
                    $_POST = array_trim($_POST);
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $nome = $_POST['nome'];
                    $admin = $_POST['admin'];
                    $c = new Conexao;
                    $c->set_charset('utf8');
                    $q = "INSERT INTO usuarios(username, password, nome, admin) VALUES('$username', md5('$password'), '$nome', '$admin');";
                    $c->query($q);
					/*
						Log
					*/
					$q = "INSERT INTO logs(usuario_id, acao, data) VALUES('" . $_SESSION['id'] . "', 'Adicionou usuário $nome', now());";
					$c->query($q);
                    header('Location: usuarios.php');
                break;
    
                
                
                case 'delete':
                    $id = $_GET['id'];
                    $c = new Conexao;
                    $c->set_charset('utf8');
                    $q = "DELETE FROM usuarios WHERE id = '$id';";
                    $c->query($q);
					/*
						Log
					*/
					$q = "INSERT INTO logs(usuario_id, acao, data) VALUES('" . $_SESSION['id'] . "', 'Removeu usuário $id', now());";
					$c->query($q);
                    header('Location: usuarios.php');
                break;
                
                
                
                case 'edit':
                    $id = $_GET['id'];
                    $c = new Conexao;
                    $c->set_charset('utf8');
                    $q = "SELECT * FROM usuarios WHERE id = '$id';";
                    $r = $c->query($q);
                    $usuario = $r->fetch_object();
                    ?>
                        <table class="content_table">
                        <form action='usuarios.php?action=update&id=<?php echo $id; ?>' method='post'>
                            <tr>
                                <th>Username</th>
                                <td><input type='text' name='username' maxlength="255" value='<?php echo $usuario->username; ?>'/>
                            </tr>
                            <tr>
                                <th>Senha</th>
                                <td><input type='password' name='password' maxlength="255"/>
                            </tr>
                            <tr>
                                <th>Nome</th>
                                <td><input type='text' name='nome' maxlength="255" value="<?php echo $usuario->nome; ?>"/>
                            </tr>
                            <tr>
                                <th>Administrador?</th>
                                <td>
                                    <?php if($usuario->admin == 'Sim'): ?>
                                    <label>
                                      <input type="radio" name="admin" value="Sim" checked/>
                                      Sim</label>
                                    <label>
                                      <input type="radio" name="admin" value="Não"/>
                                      Não</label>
                                     <?php else: ?>
                                     <label>
                                      <input type="radio" name="admin" value="Sim" />
                                      Sim</label>
                                    <label>
                                      <input type="radio" name="admin" value="Não" checked/>
                                      Não</label>
                                     <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="bottom_row">
                                    <input type='submit' value='Cadastrar' />&nbsp;
                                    <input type='reset' value='Limpar' />
                                </td>
                            </tr>
                        </form>                 
                        </table>
                    <?php
                break;
                
                
                
                case 'update':
                    $_POST = array_trim($_POST);
                    $id = $_GET['id'];
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $nome = $_POST['nome'];
                    $admin = $_POST['admin'];
                    $c = new Conexao;
                    $c->set_charset('utf8');
                    $q = "UPDATE usuarios SET username = '$username', password = md5('$password'), nome = '$nome', admin = '$admin' WHERE id = '$id';";
                    $c->query($q);
					/*
						Log
					*/
					$q = "INSERT INTO logs(usuario_id, acao, data) VALUES('" . $_SESSION['id'] . "', 'Atualizou usuário $id', now());";
					$c->query($q);
                    header('Location: usuarios.php');
                break;
                
                
                default:
                    ?>
                        <table class="content_table">
                            <tr>
                                <th>Código</th>
                                <th>Username</th>
                                <th>Nome</th>
                                <th>Administrador?</th>
                                <th>Editar</th>
                                <th>Apagar</th>
                            </tr>
                            <?php
                            $conexao = new Conexao;
                            $conexao->set_charset('utf8');
                            $q = "SELECT * FROM usuarios;";
                            $r = $conexao->query($q);
                            while($usuario = $r->fetch_object()): ?>
                            <tr>
                                <td><?php echo $usuario->id; ?></td>
                                <td><?php echo $usuario->username; ?></td>
                                <td><?php echo $usuario->nome; ?></td>
                                <td><?php echo $usuario->admin; ?></td>
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