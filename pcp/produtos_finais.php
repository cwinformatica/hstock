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
                <h2>Cadastro de novo produto final</h2>
                <form action='produtos_finais.php?action=create' method="post">
				<table class="content_table">
                	<tr>
                    	<th>Nome</th>
                        <td><input type='text' name='nome' maxlength="255" /></td>
                    </tr>
                    <tr>
                    	<th>Descrição</th>
                        <td>
                        	<textarea name='descricao' cols='50'></textarea>
                        </td>
                    </tr>
                    <tr>
                    	<td class="bottom_row" colspan="2">
                        	<input type='submit' value='Adicionar produto final' />&nbsp;
                            <input type='reset' value='Limpar formulário' />
                        </td>
                    </tr>
                </table>
                </form>
				<?php
			break;
			
			
			case 'create':
				$_POST = array_trim($_POST);
				$nome = $_POST['nome'];
				$descricao = $_POST['descricao'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "INSERT INTO produtos_finais(nome, descricao) VALUES('$nome', '$descricao');";
				$c->query($q);
				header('Location: produtos_finais.php');
			break;
			
			
			case 'edit':
				$id = $_GET['id'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "SELECT * FROM produtos_finais WHERE id = '$id';";
				$r = $c->query($q);
				$produto_final = $r->fetch_object();
				?>
                <h2>Editando produto final <?php echo $produto_final->id; ?></h2>
                <form action='produtos_finais.php?action=update&id=<?php echo $produto_final->id; ?>' method="post">
				<table class="content_table">
                	<tr>
                    	<th>Nome</th>
                        <td><input type='text' name='nome' maxlength="255" value='<?php echo $produto_final->nome; ?>' /></td>
                    </tr>
                    <tr>
                    	<th>Descrição</th>
                        <td>
                        	<textarea name='descricao' cols='50'><?php echo $produto_final->descricao; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                    	<td class="bottom_row" colspan="2">
                        	<input type='submit' value='Atualizar produto final' />&nbsp;
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
				$nome = $_POST['nome'];
				$descricao = $_POST['descricao'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "UPDATE produtos_finais SET nome = '$nome', descricao = '$descricao' WHERE id = '$id';";
				$c->query($q);
				header('Location: produtos_finais.php');
			break;
			
			
			case 'delete':
				$id = $_GET['id'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "DELETE FROM produtos_finais WHERE id = '$id'";
				$c->query($q);
				header('Location: produtos_finais.php');
			break;
			
			
			case 'view':
				$id = $_GET['id'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "SELECT * FROM produtos_finais WHERE id = '$id';";
				$r = $c->query($q);
				$produto_final = $r->fetch_object();
				?>
                <h2>Visualizando dados do produto final <?php echo $produto_final->nome; ?></h2>
                <table class="content_table">
                	<tr>
                        <th>ID</th>
                        <td><?php echo $produto_final->id; ?></td>
                    </tr>
                    <tr>
                    	<th>Descrição</th>
                        <td><?php echo nl2br($produto_final->descricao); ?></td>
                    </tr>
                </table>
                <?php
			break;
			
			
			default:
				?>
                <h2>Lista de produtos finais</h2>
				<table class="content_table">
					<tr>
						<th>ID</th>
						<th>Nome</th>
						<th>Descrição</th>
                        <th>Editar</th>
                        <th>Apagar</th>
					</tr>
					<?php
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "SELECT * FROM produtos_finais;";
					$r = $c->query($q);
					while($produto_final = $r->fetch_object()): ?>
					<tr>
						<td><a href='produtos_finais.php?action=view&id=<?php echo $produto_final->id; ?>'><?php echo $produto_final->id; ?></a></td>
						<td><?php echo $produto_final->nome; ?></td>
						<td><?php echo $produto_final->descricao; ?></td>
                        <td><a href='produtos_finais.php?action=edit&id=<?php echo $produto_final->id; ?>'>Editar</a></td>
                        <td><a href='produtos_finais.php?action=delete&id=<?php echo $produto_final->id; ?>'>Apagar</a></td>
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