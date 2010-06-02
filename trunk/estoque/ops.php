<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HSTOCK - Módulo Estoque</title>
        <link href="../estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
    <?php
	require_once('../includes/conexao.class.php');
	require_once('../includes/functions.php');
	session_start();
	if(isAuthenticated() == false)
	{
		echo "<p class='error_message'>Por favor, efetue o login.</p>";
		exit;
	}
	elseif(hasPermission($_SESSION['id'], 'Estoque') == false)
	{
		echo "<p class='error_message'>Você não possui privilégios para acessar esta área.</p>";
		exit;
	}
	logAction($_SESSION['id'], $_SERVER['REQUEST_URI'], var_export($_POST, true), var_export($_GET, true));
	?>
   	<div id="header">
    	<h1>HSTOCK - Módulo Estoque</h1>
    </div>
    
    <div id="menu">
        <?php require_once('menu.php'); ?>
	</div>
    	
    <div id="content">
    	<?php
        @$action = $_GET['action'];
		switch($action)
		{	
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
			
			
			case 'baixa':
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
                        <th>Dar baixa em componentes</th>
                    </tr>
                    <?php
					$c = new conexao;
					$c->set_charset('utf8');
					$q = "SELECT ops.id, ops.status, ops.quantidade, ops.`data`, produtos_finais.nome FROM hton.ops INNER JOIN hton.produtos_finais ON ops.produto_final_id = produtos_finais.id WHERE ops.status = 'Ativa' OR ops.status = 'Aguardando compra';";
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
                            <td><a href='ops.php?action=baixa&id=<?php echo $op->id; ?>'>Dar baixa</a></td>
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