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
				case 'pesquisar':
					if($_SERVER['REQUEST_METHOD'] == 'GET'): ?>
                    <form action="logs.php?action=pesquisar" method="post">
					<table class="content_table">
                    	<tr>
                        	<th>Digite a data inicial (dd/mm/aaaa)</th>
                            <td>
                            	<input type='text' name='data_inicio[dia]' maxlength="2" size="2" />/
                                <input type='text' name='data_inicio[mes]' maxlength="2" size="2" />/
                                <input type='text' name='data_inicio[ano]' maxlength="4" size="4" />
                            </td>
						</tr>
                        <tr>
                        	<th>Digite a data final (dd/mm/aaaa)</th>
                            <td>
                            	<input type='text' name='data_final[dia]' maxlength="2" size="2" />/
                                <input type='text' name='data_final[mes]' maxlength="2" size="2" />/
                                <input type='text' name='data_final[ano]' maxlength="4" size="4" />
                            </td>
						</tr>
                        <tr>
                        	<td class="bottom_row" colspan="2">
                            	<input type='submit' value='Pesquisar' />
                            </td>
                        </tr>
                    </table>
                    </form>
					<?php elseif($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                    <table class="content_table">
                    	<tr>
                        	<th>Código</th>
                            <th>Usuário</th>
                            <th>Ação</th>
                            <th>Data</th>
                        </tr>
                        <?php 
						$data_inicio = $_POST['data_inicio']['ano'] . $_POST['data_inicio']['mes'] . $_POST['data_inicio']['dia'];
						$data_final = $_POST['data_final']['ano'] . $_POST['data_final']['mes'] . $_POST['data_final']['dia'];
						$c = new Conexao;
						$c->set_charset('utf8');
						$q = "SELECT a.nome, b.id, b.acao, b.data FROM usuarios AS a INNER JOIN logs AS b ON a.id = b.usuario_id WHERE b.data BETWEEN '$data_inicio' AND '$data_final' ORDER BY b.data DESC;";
						$r = $c->query($q);
						while($log = $r->fetch_object()): ?>
                        <tr>
                        	<td><?php echo $log->id; ?></td>
                            <td><?php echo $log->nome; ?></td>
                            <td><?php echo $log->acao; ?></td>
                            <td><?php echo $log->data; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
					<?php endif; 
				break;
                
                
                default:
                    ?>
                        <table class="content_table">
                            <tr>
                                <th>Código</th>
                                <th>Usuário</th>
                                <th>Ação</th>
                                <th>Data</th>
                            </tr>
                            <?php
                            $conexao = new Conexao;
                            $conexao->set_charset('utf8');
							/*
								a: usuarios;
								b: logs;
							*/
                            $q = "SELECT a.nome, b.id, b.acao, b.data FROM usuarios AS a INNER JOIN logs AS b ON a.id = b.usuario_id ORDER BY b.data DESC;";
                            $r = $conexao->query($q);
                            while($log = $r->fetch_object()): ?>
                            <tr>
                                <td><?php echo $log->id; ?></td>
                                <td><?php echo $log->nome; ?></td>
                                <td><?php echo $log->acao; ?></td>
                                <td><?php echo $log->data; ?></td>
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