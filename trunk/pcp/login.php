<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<title>HSTOCK</title>
	    <link href="../estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
        <div id="header">
	        <h1>HSTOCK</h1>
        </div>
        <div id='login'>
            <form action="login.php" method="post">
            <table id='tabela_login'>
	            <tr>
		            <th scope="row">Nome de usuário</th>
		            <td><input name="username" type="text" id="username" maxlength="255" /></td>
                </tr>
                <tr>
                    <th scope="row">Senha</th>
                    <td><input name="password" type="password" id="password" maxlength="255" /></td>
                </tr>
                <tr>
                    <th colspan="2" scope="row"><input type="submit" name="button" id="button" value="Enviar" />&nbsp;<input type='reset' value="Limpar" /></th>
                </tr>
            </table>            
            </form>
            <?php
			/*
				Importando classes e bibliotecas.
			*/
			require_once('../includes/conexao.class.php');
			require_once('../includes/functions.php');
			
			/*
				Recebendo dados do formulário.
			*/
			$username = trim($_POST['username']);
			$password = trim($_POST['password']);
			
			/*
				Testa se o nome de usuário ou a senha foram enviados em branco.
			*/
			if(empty($username) or empty($password))
			{
				echo "<p class='error_message'>Por favor, digite o nome de usuário e a senha.</p>";	
				exit;
			}
			
			/*
				Efetua uma consulta no banco de dados a fim de ver se o nome de usuário e senha enviados pelo
				formulário são válidos. Caso o número de registros seja igual a zero, significa que o nome de
				usuário e senha informados são inválidos. Caso contrário, é iniciada uma nova sessão e 
				carregados os dados da base de dados na variável $_SESSION, e o usuário é redirecionado para
				o arquivo home.php.
			*/
			$conexao = new conexao;
			$query = "SELECT * FROM usuarios WHERE username = '$username' AND password = md5('$password');";
			$resultado = $conexao->query($query);
			if($resultado->num_rows == 0)
			{
				echo "<p class='error_message'>Nome de usuário ou senha incorretos.</p>";	
				exit;
			}
			else
			{
				session_start();
				$_SESSION = $resultado->fetch_array();
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
				header('Location: home.php');
			}
			?>
        </div>
    </body>
</html>