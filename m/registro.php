<?php
#include '../inc/conectar.php';
#include '../inc/usuario.class.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>
        </title>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
        <style>
            /* App custom styles */
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js">
        </script>
        <script src="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js">
        </script>
        <link href="css/tmlogo.css" rel="stylesheet" type="text/css" />
    </head>
    <body> 
    <?php #if (isLogged()):?>
    <input type="submit" data-theme="b" data-icon="check" data-iconpos="right" value="¡Ingresar!" />
    
    
    <?php  #else: ?>
	<center>
	<div data-role="page" data-theme="a" id="/registro">
            <div data-theme="a" data-role="header">
                <h3>
                    TuMovida Movil
                </h3>
            </div>
            <div data-role="content">
                <form url="" action="POST">
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput3">
                            Nombre:
                            </label>
                            <input id="textinput3" placeholder="Nombre" value="" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput4">
                            Apellido:
                            </label>
                            <input id="textinput4" placeholder="Apellido" value="" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput5">
                            Email:
                            </label>
                            <input id="textinput5" placeholder="Correo electrónico" value="" type="email" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput6">
                            Contraseña:
                            </label>
                            <input id="textinput6" placeholder="Contraseña" value="" type="password" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup" data-type="horizontal">
                            <legend>
                                Genero
                            </legend>
                            <input name="radiobuttons1" id="radio1" value="Mujer" type="radio" />
                            <label for="radio1">
                                Mujer
                            </label>
                            <input name="radiobuttons1" id="radio2" value="Hombre" type="radio" />
                            <label for="radio2">
                                Hombre
                            </label>
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput7">
                                Fecha de nacimiento
                            </label>
                            <input id="textinput7" placeholder="" value="" type="date" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput6">
                            Cedula de Identidad:
                            </label>
                            <input id="textinput6" placeholder="CI (sin puntos ni guiones)" value="" type="password" />
                        </fieldset>
                    </div>
                    <input type="submit" data-theme="b" data-icon="plus" data-iconpos="right" value="¡Registrarse!" />
                </form>
            </div>
        </div>
        </center>
        <script>
            //App custom javascript
        </script>
    </body>
</html>