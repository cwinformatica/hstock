<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK::Módulo Estoque</title>
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
    	<h1>HSTOCK::Módulo Estoque</h1>
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
                <h2>Cadastro de novo componente</h2>
                <form action='componentes.php?action=create' method="post">
				<table class="content_table">
                	<tr>
                    	<th>Código</th>
                        <td><input type='text' name='codigo' maxlength="10" /></td>
                    </tr>
                    <tr>
                    	<th>Descrição</th>
                        <td>
                        	<textarea name='descricao' cols='50'></textarea>
                        </td>
                    </tr>
                    <tr>
                    	<th>Unidade</th>
                        <td>
                            <input type='radio' name='unidade' value='PÇ' checked="checked" />Peça
                            <input type='radio' name='unidade' value='KG' />Quilo
                            <input type='radio' name='unidade' value='LT' />Litro
                            <input type='radio' name='unidade' value='MT' />Metro
                        </td>
                    </tr>
                    <tr>
                    	<td class="bottom_row" colspan="2">
                        	<input type='submit' value='Adicionar componente' />&nbsp;
                            <input type='reset' value='Limpar formulário' />
                        </td>
                    </tr>
                </table>
                </form>
				<?php
			break;
			
			
			case 'create':
				$_POST = array_trim($_POST);
				$codigo = $_POST['codigo'];
				$descricao = $_POST['descricao'];
				$unidade = $_POST['unidade'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "INSERT INTO componentes(codigo, descricao, unidade) VALUES('$codigo', '$descricao', '$unidade');";
				$c->query($q);
				header('Location: componentes.php');
			break;
			
			
			case 'edit':
				$codigo = $_GET['codigo'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "SELECT * FROM componentes WHERE codigo = '$codigo';";
				$r = $c->query($q);
				$componente = $r->fetch_object();
				?>
                <h2>Editando componente <?php echo $componente->codigo; ?></h2>
                <form action='componentes.php?action=update&codigo=<?php echo $componente->codigo; ?>' method="post">
				<table class="content_table">
                	<tr>
                    	<th>Código</th>
                        <td><input type='text' name='codigo' maxlength="10" value='<?php echo $componente->codigo; ?>' /></td>
                    </tr>
                    <tr>
                    	<th>Descrição</th>
                        <td>
                        	<textarea name='descricao' cols='50'><?php echo $componente->descricao; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                    	<th>Unidade</th>
                        <td>
                        	<?php
								switch($componente->unidade)
								{
									case 'PÇ':
										?>
										<input type='radio' name='unidade' value='PÇ' checked="checked" />Peça
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
										<?php
									break;
									
									
									case 'KG':
										?>
										<input type='radio' name='unidade' value='PÇ' />Peça
                                        <input type='radio' name='unidade' value='KG' checked="checked" />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
										<?php
									break;
									
									
									case 'LT':
										?>
										<input type='radio' name='unidade' value='PÇ' />Peça
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' checked="checked" />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
										<?php
									break;
									
									
									case 'MT';
										?>
										<input type='radio' name='unidade' value='PÇ' />Peça
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT' checked="checked"/>Metro
										<?php
									break;
								}
							?>
                        </td>
                    </tr>
                    <tr>
                    	<td class="bottom_row" colspan="2">
                        	<input type='submit' value='Atualizar componente' />&nbsp;
                            <input type='reset' value='Limpar formulário' />
                        </td>
                    </tr>
                </table>
                </form>
				<?php
			break;
			
			
			case 'update':
				$vcodigo = $_GET['codigo'];
				$_POST = array_trim($_POST);
				$codigo = $_POST['codigo'];
				$descricao = $_POST['descricao'];
				$unidade = $_POST['unidade'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "UPDATE componentes SET codigo = '$codigo', descricao = '$descricao', unidade = '$unidade' WHERE codigo = '$vcodigo';";
				$c->query($q);
				header('Location: componentes.php');
			break;
			
			
			case 'delete':
				$codigo = $_GET['codigo'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "DELETE FROM componentes WHERE codigo = '$codigo'";
				$c->query($q);
				header('Location: componentes.php');
			break;
			
			
			case 'view':
				$codigo = $_GET['codigo'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "SELECT * FROM componentes WHERE codigo = '$codigo';";
				$r = $c->query($q);
				$componente = $r->fetch_object();
				?>
                <h2>Visualizando dados do componente <?php echo $componente->codigo; ?></h2>
                <table class="content_table">
                	<tr>
                        <th>Código</th>
                        <td><?php echo $componente->codigo; ?></td>
                    </tr>
                    <tr>
                    	<th>Descrição</th>
                        <td><?php echo nl2br($componente->descricao); ?></td>
                    </tr>
                    <tr>
                    	<th>Unidade</th>
                        <td><?php echo $componente->unidade; ?></td>
                    </tr>
                    <tr>
                    	<th>Saldo</th>
                        <td><?php echo $componente->saldo; ?></td>
                    </tr>
                </table>
                <?php
			break;
			
			
			case 'quantidade':
				?>
                <h2>Alteração de quantidades em estoque</h2>
				<table class="content_table">
                	<tr>
                    	<th>Código</th>
                        <th>Descrição</th>
                        <th>Unidade</th>
                        <th>Saldo atual</th>
                        <th>Novo saldo</th>
                    </tr>
                    <?php
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "SELECT * FROM componentes;";
					$r = $c->query($q);
					while($componente = $r->fetch_object()): ?>
                    <tr>
                    	<td><?php echo $componente->codigo; ?></td>
                        <td><?php echo $componente->descricao; ?></td>
                        <td><?php echo $componente->unidade; ?></td>
                        <td><?php echo $componente->saldo; ?></td>
                        <td>
                        	<form action='componentes.php?action=alteraquantidade&codigo=<?php echo $componente->codigo; ?>' method="post">
                            	<input type='text' name='nsaldo' size="10" />
                                <input type='submit' value='Alterar' />
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
				<?php
			break;
			
			
			case 'alteraquantidade':
				$codigo = $_GET['codigo'];
				$_POST = array_trim($_POST);
				$nsaldo = $_POST['nsaldo'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "UPDATE componentes SET saldo = '$nsaldo' WHERE codigo = '$codigo';";
				$c->query($q);
				header('Location: componentes.php?action=quantidade');
			break;
			
			
			default:
				?>
                <h2>Lista de componentes</h2>
				<table class="content_table">
					<tr>
						<th>Código</th>
						<th>Descrição</th>
						<th>Unidade</th>
						<th>Saldo</th>
                        <th>Editar</th>
                        <th>Apagar</th>
					</tr>
					<?php
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "SELECT * FROM componentes;";
					$r = $c->query($q);
					while($componente = $r->fetch_object()): ?>
					<tr>
						<td><a href='componentes.php?action=view&codigo=<?php echo $componente->codigo; ?>'><?php echo $componente->codigo; ?></a></td>
						<td><?php echo $componente->descricao; ?></td>
						<td><?php echo $componente->unidade; ?></td>
						<td><?php echo $componente->saldo; ?></td>
                        <td><a href='componentes.php?action=edit&codigo=<?php echo $componente->codigo; ?>'>Editar</a></td>
                        <td><a href='componentes.php?action=delete&codigo=<?php echo $componente->codigo; ?>'>Apagar</a></td>
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