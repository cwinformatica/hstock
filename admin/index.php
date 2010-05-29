<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<title>HSTOCK - Módulo Administração</title>
	    <link href="estilos_hton.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
        <div id="header">
	        <h1>HSTOCK::Módulo Administração</h1>
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
        </div>
    </body>
</html>