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
	elseif(hasPermission($_SESSION['id'], 'Estoque') == false)
	{
		echo "<p class='error_message'>Você não possui privilégios para acessar esta área.</p>";
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
                            <input type='radio' name='unidade' value='CX' />Caixa
                            <input type='radio' name='unidade' value='KG' />Quilo
                            <input type='radio' name='unidade' value='LT' />Litro
                            <input type='radio' name='unidade' value='MT' />Metro
                            <input type='radio' name='unidade' value='PAR' />Par
                            <input type='radio' name='unidade' value='UN' />Unidade
                            <input type='radio' name='unidade' value='SV' />Serviço
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
									case 'CX':
										?>
                                        <input type='radio' name='unidade' value='PÇ' />Peça
                                        <input type='radio' name='unidade' value='CX' checked="checked" />Caixa
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
                                        <input type='radio' name='unidade' value='PAR' />Par
                                        <input type='radio' name='unidade' value='UN' />Unidade
                                        <input type='radio' name='unidade' value='SV' />Serviço
										<?php
									break;
									
									
									case 'KG':
										?>
										<input type='radio' name='unidade' value='PÇ'  />Peça
                                        <input type='radio' name='unidade' value='CX' />Caixa
                                        <input type='radio' name='unidade' value='KG' checked="checked" />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
                                        <input type='radio' name='unidade' value='PAR' />Par
                                        <input type='radio' name='unidade' value='UN' />Unidade
                                        <input type='radio' name='unidade' value='SV' />Serviço
										<?php
									break;
									
									
									case 'LT':
										?>
										<input type='radio' name='unidade' value='PÇ' />Peça
                                        <input type='radio' name='unidade' value='CX' />Caixa
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' checked="checked" />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
                                        <input type='radio' name='unidade' value='PAR' />Par
                                        <input type='radio' name='unidade' value='UN' />Unidade
                                        <input type='radio' name='unidade' value='SV' />Serviço
										<?php
									break;
									
									
									case 'MT';
										?>
										<input type='radio' name='unidade' value='PÇ' />Peça
                                        <input type='radio' name='unidade' value='CX' />Caixa
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT'  checked="checked" />Metro
                                        <input type='radio' name='unidade' value='PAR' />Par
                                        <input type='radio' name='unidade' value='UN' />Unidade
                                        <input type='radio' name='unidade' value='SV' />Serviço
										<?php
									break;
									
									
									case 'PAR':
										?>
                                        <input type='radio' name='unidade' value='PÇ' />Peça
                                        <input type='radio' name='unidade' value='CX' />Caixa
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
                                        <input type='radio' name='unidade' value='PAR' checked="checked" />Par
                                        <input type='radio' name='unidade' value='UN' />Unidade
                                        <input type='radio' name='unidade' value='SV' />Serviço
										<?php
									break;
									
									
									case 'PÇ':
										?>
                                        <input type='radio' name='unidade' value='PÇ' checked="checked" />Peça
                                        <input type='radio' name='unidade' value='CX' />Caixa
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
                                        <input type='radio' name='unidade' value='PAR' />Par
                                        <input type='radio' name='unidade' value='UN' />Unidade
                                        <input type='radio' name='unidade' value='SV' />Serviço
										<?php
									break;
									
									
									case 'UN':
										?>
                                        <input type='radio' name='unidade' value='PÇ' />Peça
                                        <input type='radio' name='unidade' value='CX' />Caixa
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
                                        <input type='radio' name='unidade' value='PAR' />Par
                                        <input type='radio' name='unidade' value='UN' checked="checked" />Unidade
                                        <input type='radio' name='unidade' value='SV' />Serviço
										<?php
									break;
									
									
									case 'SV':
										?>
                                        <input type='radio' name='unidade' value='PÇ' />Peça
                                        <input type='radio' name='unidade' value='CX' />Caixa
                                        <input type='radio' name='unidade' value='KG' />Quilo
                                        <input type='radio' name='unidade' value='LT' />Litro
                                        <input type='radio' name='unidade' value='MT' />Metro
                                        <input type='radio' name='unidade' value='PAR' />Par
                                        <input type='radio' name='unidade' value='UN' />Unidade
                                        <input type='radio' name='unidade' value='SV' checked="checked" />Serviço
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
				if($_SERVER['REQUEST_METHOD'] == "POST")
					@$p = trim($_POST['p']);
				else
					@$p = $_GET['p'];
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "SELECT count(codigo) AS QtdLinhas FROM componentes;";
				$r1 = $c->query($q);
				$qtdlinhas = $r1->fetch_object();
				$q = "SELECT * FROM componentes LIMIT $p,100;";
				$r = $c->query($q);
				?>
                <h2>Alteração de quantidades em estoque</h2>
                <div class='paginador'>
                <?php if($p < 100): ?>
                <form action='componentes.php?action=quantidade' method='post'>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                    <a href='componentes.php?action=quantidade&p=<?php echo $p + 100; ?>'>Próxima</a>
                </form>
                <?php elseif(($p + 100) >= $qtdlinhas->QtdLinhas): ?>
                <form action='componentes.php?action=quantidade' method='post'>
	                <a href='componentes.php?action=quantidade&p=<?php echo $p - 100; ?>'>Anterior</a>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                </form>
                <?php else: ?>
                <form action='componentes.php?action=quantidade' method='post'>
	                <a href='componentes.php?action=quantidade&p=<?php echo $p - 100; ?>'>Anterior</a>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                    <a href='componentes.php?action=quantidade&p=<?php echo $p + 100; ?>'>Próxima</a>
                </form>
                <?php endif; ?>
                </div>
                <br />
				<table class="content_table">
                	<tr>
                    	<th>Código</th>
                        <th>Descrição</th>
                        <th>Unidade</th>
                        <th>Saldo atual</th>
                        <th>Novo saldo</th>
                    </tr>
                    <?php
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
                <br />
                <div class='paginador'>
                <?php if($p < 100): ?>
                <form action='componentes.php?action=quantidade' method='post'>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                    <a href='componentes.php?action=quantidade&p=<?php echo $p + 100; ?>'>Próxima</a>
                </form>
                <?php elseif(($p + 100) >= $qtdlinhas->QtdLinhas): ?>
                <form action='componentes.php?action=quantidade' method='post'>
	                <a href='componentes.php?action=quantidade&p=<?php echo $p - 100; ?>'>Anterior</a>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                </form>
                <?php else: ?>
                <form action='componentes.php?action=quantidade' method='post'>
	                <a href='componentes.php?action=quantidade&p=<?php echo $p - 100; ?>'>Anterior</a>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                    <a href='componentes.php?action=quantidade&p=<?php echo $p + 100; ?>'>Próxima</a>
                </form>
                <?php endif; ?>
                </div>
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
				@$p = (empty($_GET['p'])) ? ($p = 0) : ($p = $_GET['p']);
				$c = new conexao;
				$c->set_charset('utf8');
				$q = "SELECT count(codigo) AS QtdLinhas FROM componentes;";
				$r1 = $c->query($q);
				$qtdlinhas = $r1->fetch_object();
				$q = "SELECT * FROM componentes LIMIT $p,100;";
				$r = $c->query($q);
				?>
                <h2>Lista de componentes</h2>
                <div class="paginador">
                <?php if($p < 100): ?>
                <form action='componentes.php' method='get'>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                    <a href='componentes.php?p=<?php echo $p + 100; ?>'>Próxima</a>
                </form>
                <?php elseif(($p + 100) >= $qtdlinhas->QtdLinhas): ?>
                <form action='componentes.php' method='get'>
	                <a href='componentes.php?p=<?php echo $p - 100; ?>'>Anterior</a>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                </form>
                <?php else: ?>
                <form action='componentes.php' method='get'>
	                <a href='componentes.php?p=<?php echo $p - 100; ?>'>Anterior</a>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                    <a href='componentes.php?p=<?php echo $p + 100; ?>'>Próxima</a>
                </form>
                <?php endif; ?>
                </div>
                <br />
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
                <br />
                <div class='paginador'>
                <?php if($p < 100): ?>
                <form action='componentes.php' method='get'>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                    <a href='componentes.php?p=<?php echo $p + 100; ?>'>Próxima</a>
                </form>
                <?php elseif(($p + 100) >= $qtdlinhas->QtdLinhas): ?>
                <form action='componentes.php' method='get'>
	                <a href='componentes.php?p=<?php echo $p - 100; ?>'>Anterior</a>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                </form>
                <?php else: ?>
                <form action='componentes.php' method='get'>
	                <a href='componentes.php?p=<?php echo $p - 100; ?>'>Anterior</a>
                    <input type='text' name='p' size='4' maxlength="4" value='<?php echo $p; ?>'/>
                    <a href='componentes.php?p=<?php echo $p + 100; ?>'>Próxima</a>
                </form>
                <?php endif; ?>
                </div>
                <?php
			break;
		}
		?>
    </div>
    </body>
</html>