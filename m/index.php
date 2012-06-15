<?php
include '../inc/conectar.php';
include '../inc/usuario.class.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>¡TuMovida!</title>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js"></script>
</head>
<body>
	<div data-role="page" data-theme="a" id="page2">
        <div data-theme="a" data-role="header">
            <h3>TuMovida</h3>
        </div>
        <div data-role="content">
            <h2>Entrar al sitio</h2>
            <form url="http://www.tumovida.com.uy/ajax/user.php?login" action="POST">
                <div data-role="fieldcontain">
                    <fieldset data-role="controlgroup">
                        <label for="textinput1"></label>
                        <input id="textinput1" name="mail" placeholder="correo electrónico" 
                        	value="" type="email" />
                    </fieldset>
                </div>
                <div data-role="fieldcontain">
                    <fieldset data-role="controlgroup">
                        <label for="textinput2"></label>
                        <input id="textinput2" name="password" placeholder="contraseña" 
                        	value="" type="password" />
                    </fieldset>
                </div>
                <input type="submit" data-theme="b" 
                	data-icon="check" data-iconpos="right" value="¡Ingresar!" />
            </form>
            <a data-role="button" data-transition="fade" data-theme="a" 
            	href="#page3" data-icon="plus" data-iconpos="top">Registrarse</a>
        </div>
    </div>
</body>
</html>