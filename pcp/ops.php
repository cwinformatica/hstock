<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK - Módulo PCP</title>
        <link href="estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
    <?php
	require_once('classes/conexao.class.php');
	require_once('classes/functions.php');
	session_start();
	if(isAuthenticated() == false)
	{
		echo "<p class='error_message'>Por favor, efetue o login.</p>";
		exit;
	}
	elseif(hasPermission($_SESSION['id'], 'PCP') == false)
	{
		echo "<p class='error_message'>Você não possui privilégios para acessar esta área.</p>";
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
    	<?php
        @$action = $_GET['action'];
		switch($action)
		{
			case 'add':
				?>
                <h2>Cadastro de nova OP</h2>
                <form action='ops.php?action=create' method="post">
				<table class="content_table">
                	<tr>
                    	<th>Produto final</th>
                        <td>
                        	<select name='produto_final_id'>
                            <?php
							$c = new conexao;
							$c->set_charset('utf8');
							$q = "SELECT * FROM produtos_finais;";
							$r = $c->query($q);
							while($produto_final = $r->fetch_object()):	?>
                            	<option value='<?php echo $produto_final->id; ?>'><?php echo $produto_final->nome; ?></option>
                            <?php endwhile; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<th>Quantidade</th>
                        <td><input type='text' name='quantidade' /></td>
                    </tr>
                    <tr>
                    	<td colspan="2" class="bottom_row">
                        	<input type='submit' value='Criar OP' />&nbsp;
                            <input type='reset' value='Limpar' />
                        </td>
                    </tr>
                </table>
                </form>
				<?php
			break;
			
			
			case 'create':
				$_POST = array_trim($_POST);
				$produto_final_id = $_POST['produto_final_id'];
				$quantidade = $_POST['quantidade'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "INSERT INTO ops(produto_final_id, quantidade, data) VALUES('$produto_final_id', '$quantidade', now());";
				$c->query($q);
				header('Location: ops.php');
			break;
			
			
			case 'edit':
				$id = $_GET['id'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "SELECT * FROM ops WHERE id = '$id';";
				$r = $c->query($q);
				$op = $r->fetch_object();
				?>
                <h2>Editando OP <?php echo $id; ?></h2>
                <form action='ops.php?action=update&id=<?php echo $id; ?>' method="post">
				<table class="content_table">
                	<tr>
                    	<th>Produto final</th>
                        <td>
                        	<select name='produto_final_id'>
                            <?php
							$q = "SELECT * FROM produtos_finais;";
							$r = $c->query($q);
							while($produto_final = $r->fetch_object()):	
								if($produto_final->id == $op->produto_final_id): ?>
	                            	<option value='<?php echo $produto_final->id; ?>' selected><?php echo $produto_final->nome; ?></option>
                                <?php else: ?>
	                                <option value='<?php echo $produto_final->id; ?>'><?php echo $produto_final->nome; ?></option>
                                <?php endif; 
                            endwhile; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<th>Quantidade</th>
                        <td><input type='text' name='quantidade' value='<?php echo $op->quantidade; ?>' /></td>
                    </tr>
                    <tr>
                    	<th>Status</th>
                        <td>
                        	<?php if($op->status == 'Ativa'): ?>
                                <input type='radio' name='status' value='Ativa' checked/>Ativa
                                <input type='radio' name='status' value='Aguardando compra' />Aguardando compra
                                <input type='radio' name='status' value='Em produção' />Em produção
                                <input type='radio' name='status' value='Finalizada' />Finalizada
                                <input type='radio' name='status' value='Cancelada' />Cancelada
                            <?php elseif($op->status == 'Aguardando compra'): ?>
                            	<input type='radio' name='status' value='Ativa' />Ativa
                                <input type='radio' name='status' value='Aguardando compra' checked />Aguardando compra
                                <input type='radio' name='status' value='Em produção' />Em produção
                                <input type='radio' name='status' value='Finalizada' />Finalizada
                                <input type='radio' name='status' value='Cancelada' />Cancelada
                            <?php elseif($op->status == 'Em produção'): ?>
                            	<input type='radio' name='status' value='Ativa' />Ativa
                                <input type='radio' name='status' value='Aguardando compra' />Aguardando compra
                                <input type='radio' name='status' value='Em produção' checked />Em produção
                                <input type='radio' name='status' value='Finalizada' />Finalizada
                                <input type='radio' name='status' value='Cancelada' />Cancelada
                            <?php elseif($op->status == 'Finalizada'): ?>
                            	<input type='radio' name='status' value='Ativa' />Ativa
                                <input type='radio' name='status' value='Aguardando compra' />Aguardando compra
                                <input type='radio' name='status' value='Em produção' />Em produção
                                <input type='radio' name='status' value='Finalizada' checked />Finalizada
                                <input type='radio' name='status' value='Cancelada' />Cancelada
                            <?php else: ?>
                            	<input type='radio' name='status' value='Ativa' />Ativa
                                <input type='radio' name='status' value='Aguardando compra' />Aguardando compra
                                <input type='radio' name='status' value='Em produção' />Em produção
                                <input type='radio' name='status' value='Finalizada' />Finalizada
                                <input type='radio' name='status' value='Cancelada' checked />Cancelada
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2" class="bottom_row">
                        	<input type='submit' value='Atualizar OP' />
                        </td>
                    </tr>
                </table>
                </form>
				<?php
			break;
			
			
			case 'update':
				$id = $_GET['id'];
				$_POST = array_trim($_POST);
				$produto_final_id = $_POST['produto_final_id'];
				$quantidade = $_POST['quantidade'];
				$status = $_POST['status'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "UPDATE ops SET produto_final_id = '$produto_final_id', quantidade = '$quantidade', status = '$status' WHERE id = '$id';";
				$c->query($q);
				header('Location: ops.php');
			break;
			
			
			case 'view':
				$id = $_GET['id'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "SELECT ops.id AS OpID, ops.status AS OpStatus, ops.quantidade AS OpQuantidade, ops.`data` AS OpData, produtos_finais.nome AS ProdutoFinalNome, produtos_finais.descricao AS ProdutoFinalDescricao FROM hton.produtos_finais INNER JOIN hton.ops ON ops.produto_final_id = produtos_finais.id WHERE ops.id = '$id';";
				$r = $c->query($q);
				$op = $r->fetch_object();
				?>
                <h2>Visualizando OP <?php echo $id; ?></h2>
				<table class="content_table">
                	<tr>
                    	<th>ID</th>
                        <td><?php echo $op->OpID; ?></td>
                    </tr>
                    <tr>
                    	<th>Produto Final</th>
                        <td><?php echo $op->ProdutoFinalNome; ?></td>
                    </tr>
                    <tr>
                    	<th>Quantidade</th>
                        <td><?php echo $op->OpQuantidade; ?></td>
                    </tr>
                    <tr>
                    	<th>Status</th>
                        <td><?php echo $op->OpStatus; ?></td>
                    </tr>
                </table><br />
				<table class="content_table">
                	<tr>
                    	<th colspan="6" class="bottom_row">Componentes necessários</th>
                    </tr>
                    <tr>
                    	<th>Código</th>
                        <th>Descrição</th>
                        <th>Unidade</th>
                        <th>Saldo</th>
                        <th>Quantidade necessária</th>
                        <th>Quantidade faltante</th>
                    </tr>
					<?php
					$q = "SELECT componentes.codigo AS ComponenteCodigo, componentes.descricao AS ComponenteDescricao, componentes.unidade AS ComponenteUnidade, componentes.saldo AS ComponenteSaldo, ops.quantidade * kits.quantidade AS ComponentesNecessarios, componentes.saldo - ops.quantidade * kits.quantidade AS ComponentesFaltantes FROM hton.kits INNER JOIN hton.componentes ON kits.componente_codigo = componentes.codigo INNER JOIN hton.produtos_finais ON kits.produto_final_id = produtos_finais.id INNER JOIN hton.ops ON ops.produto_final_id = produtos_finais.id WHERE ops.id = '$id';";
					$r = $c->query($q);
					while($componente = $r->fetch_object()): ?>
                    	<tr>
                        	<td><?php echo $componente->ComponenteCodigo; ?></td>
                            <td><?php echo $componente->ComponenteDescricao; ?></td>
                            <td><?php echo $componente->ComponenteUnidade; ?></td>
                            <td><?php echo $componente->ComponenteSaldo; ?></td>
                            <td><?php echo $componente->ComponentesNecessarios; ?></td>
                            <td><?php echo ($componente->ComponentesFaltantes >= 0) ? (0) : ($componente->ComponentesFaltantes); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
               	<?php
			break;
			
			
			case 'delete':
				$id = $_GET['id'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "DELETE FROM ops WHERE id = '$id';";
				$c->query($q);
				header('Location: ops.php');
			break;
			
			
			default:
				?>
                <h2>Lista de OPS</h2>
				<table class="content_table">
                	<tr>
                    	<th>ID</th>
                        <th>Produto final</th>
                        <th>Quantidade</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Editar</th>
                        <th>Apagar</th>
                    </tr>
                    <?php
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "SELECT ops.id, ops.status, ops.quantidade, ops.`data`, produtos_finais.nome FROM hton.ops INNER JOIN hton.produtos_finais ON ops.produto_final_id = produtos_finais.id;";
					$r = $c->query($q);
					while($op = $r->fetch_object()): ?>
                    	<tr>
                        	<td><a href='ops.php?action=view&id=<?php echo $op->id; ?>'><?php echo $op->id; ?></a></td>
                            <td><?php echo $op->nome; ?></td>
                            <td><?php echo $op->quantidade; ?></td>
                            <td><?php echo $op->status; ?></td>
                            <td><?php echo $op->data; ?></td>
                            <td><a href='ops.php?action=edit&id=<?php echo $op->id; ?>'>Editar</a></td>
                            <td><a href='ops.php?action=delete&id=<?php echo $op->id; ?>'>Apagar</a></td>
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