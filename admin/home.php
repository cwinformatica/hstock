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
            <h1>HSTOCK</h1>
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
            <p>Seja bem-vindo(a), <?php echo $_SESSION['nome']; ?>!</p>
        </div>
    </body>
</html>