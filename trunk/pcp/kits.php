<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK::Módulo Estoque</title>
        <link href="../estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
    <?php
	require_once('../includes/functions.php');
	require_once('../includes/conexao.class.php');
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
				if($_SERVER['REQUEST_METHOD'] == 'GET'):
					?>
                    <form action='kits.php?action=add' method="post">
                    <table class="content_table">
	                    <tr>
                            <th>Selecione o produto final</th>
                            <td>
                            	<select name="produto_final_id">
									<?php 
									$c = new conexao;
									$c->set_charset('utf8');
									$q = "SELECT * FROM produtos_finais;";
									$r = $c->query($q);
									while($produto_final = $r->fetch_object()): ?>
										<option value='<?php echo $produto_final->id; ?>'><?php echo $produto_final->nome; ?></option>
									<?php endwhile; 
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Digite a quantidade de componentes que o kit irá possuir</th>
                            <td><input type='text' name='qtd_componentes' /></td>
                        </tr>
                        <tr>
                            <td class="bottom_row" colspan="2"><input type='submit' value='Próximo passo' /></td>
                        </tr>
                    </table>
                    </form>
				<?php else: ?>
                    <form action='kits.php?action=create&produto_final_id=<?php echo trim($_POST['produto_final_id']); ?>' method="post">
					<table class="content_table">
                    	<tr>
                        	<th>Item</th>
                            <th>Componente</th>
                            <th>Quantidade</th>
                        </tr>
                        <?php
						$_POST = array_trim($_POST);
						$produto_final_id = $_POST['produto_final_id'];
						$qtd_componentes = $_POST['qtd_componentes'];
						
						$c = new conexao;
						$c->set_charset('utf8');
						$q = "SELECT codigo FROM componentes;";
						$r = $c->query($q);
						for($i = 0; $i < $qtd_componentes; $i++): ?>
							<tr>
                            	<td><?php echo ($i + 1); ?></td>
                            	<td>
                                	<select name='<?php echo $i; ?>[componente_codigo]'>
                                    	<?php
										while($componente = $r->fetch_object()): ?>
                                        	<option value='<?php echo $componente->codigo; ?>'><?php echo $componente->codigo; ?></option>
										<?php endwhile; ?>
                                    </select>
                                </td>
                                <td><input type='text' name='<?php echo $i; ?>[quantidade]' /></td>
                            </tr>
						<?php 
						$r->data_seek(0);
						endfor;
						?>
                        <tr>
                        	<td colspan='2' class="bottom_row"><input type='submit' value='Criar kit' /></td>
                        </tr>
                    </table>
                    </form>
				<?php endif;
			break;
			
			
			case 'create':
				$produto_final_id = $_GET['produto_final_id'];
				$_POST = array_trim($_POST);
				$c = new conexao;
				$c->set_charset('utf8');
				foreach($_POST as $linha):
					$q = "INSERT INTO kits(componente_codigo, produto_final_id, quantidade) VALUES('" . $linha['componente_codigo'] . "', '$produto_final_id', '" . $linha['quantidade'] . "');";
					$c->query($q);
				endforeach;
				header('Location: kits.php');
			break;
			
			
			case 'edit':
			break;
			
			
			case 'update':
			break;
			
			
			case 'delete':
				$produto_final_id = $_GET['produto_final_id'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "DELETE FROM kits WHERE produto_final_id = '$produto_final_id';";
				$c->query($q);
				header('Location: kits.php');
			break;
			
			
			case 'view':
				$produto_final_id = $_GET['produto_final_id'];
				$c = new conexao;
				$c->set_charset('utf8');
				
				$q = "SELECT * FROM produtos_finais WHERE id = '$produto_final_id';";
				$r = $c->query($q);
				$produto_final = $r->fetch_object();
				?>
				<h2>Visualizando kit para o produto final <?php echo $produto_final->nome; ?></h2>
                <table class="content_table">
                	<tr>
                    	<th>Produto final</th>
                        <td><?php echo $produto_final->nome; ?></td>
                    </tr>
                    <tr>
                    	<th>Descrição</th>
                        <td><?php echo $produto_final->descricao; ?></td>
                    </tr>
                </table><br />
                <table class="content_table">
                	<tr>
                    	<th colspan="3" class="bottom_row">Componentes que fazem parte deste kit</th>
                    </tr>
                    <tr>
                    	<th>Componente</th>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                    </tr>
                    <?php
					/*
						a: componentes;
						b: kits;
						c: produtos_finais.
					*/
					$q = "SELECT a.codigo, a.descricao AS ComponenteDescricao, a.unidade, b.quantidade, c.nome AS ProdutoFinalNome, c.descricao  AS ProdutoFinalDescricao FROM componentes AS a INNER JOIN kits AS b ON a.codigo = b.componente_codigo INNER JOIN produtos_finais AS c ON b.produto_final_id = c.id WHERE b.produto_final_id = '$produto_final_id';";
					$r = $c->query($q);
					while($componente = $r->fetch_object()): ?>
					<tr>
						<td><?php echo $componente->codigo; ?></td>
                        <td><?php echo $componente->ComponenteDescricao; ?></td>
                        <td><?php echo $componente->quantidade; ?></td>
					</tr>
					<?php endwhile; ?>
                </table>
				<?php
			break;
			
			
			default:
				?>
				<h2>Lista de kits</h2>
                <table class="content_table">
                	<tr>
                    	<th>Produto final</th>
                        <th>Apagar</th>
                    </tr>
                    <?php
					$c = new conexao;
					$c->set_charset('utf8');
					/*
						a: produtos_finais;
						b: kits.
					*/
					$q = "SELECT a.* FROM produtos_finais AS a INNER JOIN kits AS b ON a.id = b.produto_final_id GROUP BY b.produto_final_id;";
					$r = $c->query($q);
					while($produto_final = $r->fetch_object()): ?>
                    <tr>
                    	<td><a href='kits.php?action=view&produto_final_id=<?php echo $produto_final->id; ?>'><?php echo $produto_final->nome; ?></a></td>
                        <td><a href='kits.php?action=delete&produto_final_id=<?php echo $produto_final->id; ?>'>Apagar</a></td>
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