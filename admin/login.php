<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<title>HSTOCK</title>
	    <link href="estilos_hton.css" rel="stylesheet" type="text/css" />
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
			require_once('../classes/conexao.class.php');
			$username = trim($_POST['username']);
			$password = trim($_POST['password']);
			if(empty($username) or empty($password))
			{
				echo "<p class='error_message'>Por favor, digite o nome de usuário e a senha.</p>";	
				exit;
			}
			$conexao = new Conexao;
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
				header('Location: home.php');
			}
			?>
        </div>
    </body>
</html>