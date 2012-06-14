<?php 
include '../inc/conectar.php';
include '../inc/usuario.class.php';
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
    </head>
    <body>
    	<?php if (isLogged()):?>
        <div data-role="page" data-theme="a" id="page1">
            <div data-theme="a" data-role="header">
                <h3>
                    TuMovida
                </h3>
                <a data-role="button" data-direction="reverse" data-transition="fade" data-theme="c" href="#page1" data-icon="home" data-iconpos="left">
                    Salir
                </a>
            </div>
            <div data-role="content">
                <h2>
                    ¡Bienvenido!
                </h2>
                <ul data-role="listview" data-divider-theme="a" data-inset="true">
                    <li data-role="list-divider" role="heading">
                        ¿Qué desea hacer?
                    </li>
                    <li data-theme="c">
                        <a href="#page4" data-transition="slide">
                            Mis compras
                        </a>
                    </li>
                    <li data-theme="c">
                        <a href="#page5" data-transition="slide">
                            Mis fotos
                        </a>
                    </li>
                    <li data-theme="b">
                        <a href="#page6" data-transition="slide">
                            ¡Explorar el sitio!
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php else: ?>
        <div data-role="page" data-theme="a" id="page2">
            <div data-theme="a" data-role="header">
                <h3>
                    TuMovida
                </h3>
            </div>
            <div data-role="content">
                <h2>
                    Entrar al sitio
                </h2>
                <form url="http://www.tumovida.com.uy/tm3/ajax/user.php?login" action="POST">
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput1">
                            </label>
                            <input id="textinput1" name="mail" placeholder="correo electrónico" value="" type="email" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput2">
                            </label>
                            <input id="textinput2" name="password" placeholder="contraseña" value="" type="password" />
                        </fieldset>
                    </div>
                    <input type="submit" data-theme="b" data-icon="check" data-iconpos="right" value="¡Ingresar!" />
                </form>
                <a data-role="button" data-transition="fade" data-theme="a" href="#page3" data-icon="plus" data-iconpos="top">
                    Registrarse
                </a>
            </div>
        </div>
        <?php endif;?>
        <div data-role="page" data-theme="a" id="page3">
            <div data-theme="a" data-role="header">
                <h3>
                    TuMovida
                </h3>
            </div>
            <div data-role="content">
                <form url="" action="POST">
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput3">
                            </label>
                            <input id="textinput3" placeholder="Nombre" value="" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput4">
                            </label>
                            <input id="textinput4" placeholder="Apellido" value="" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput5">
                            </label>
                            <input id="textinput5" placeholder="Correo electrónico" value="" type="email" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="textinput6">
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
                    <input type="submit" data-theme="b" data-icon="plus" data-iconpos="right" value="¡Registrarse!" />
                </form>
            </div>
        </div>
        <div data-role="page" data-theme="a" id="page4">
            <div data-theme="a" data-role="header">
                <a data-role="button" data-direction="reverse" data-rel="back" data-transition="fade" data-theme="c" href="#page1" data-icon="home" data-iconpos="left">
                    Volver
                </a>
                <h3>
                    TuMovida
                </h3>
            </div>
            <div data-role="content">
                <h2>
                    Pagadas
                </h2>
                <div>
                    <a href="#" data-transition="fade">
                        Promo 1
                    </a>
                </div>
                <h2>
                    No pagadas
                </h2>
                <div>
                    <a href="#" data-transition="fade">
                        Promo 2
                    </a>
                </div>
            </div>
        </div>
        <div data-role="page" data-theme="a" id="page5">
            <div data-theme="a" data-role="header">
                <h3>
                    TuMovida
                </h3>
                <a data-role="button" data-direction="reverse" data-rel="back" data-transition="slide" data-theme="c" href="#page1" data-icon="home" data-iconpos="left">
                    Volver
                </a>
            </div>
            <div data-role="content">
                <h2>
                    Aún no disponemos de esta sección.
                </h2>
                <h5>
                    El equipo de TuMovida esta trabajando para que puedas disfrutar de esta y otras novedades.
                </h5>
            </div>
        </div>
        <div data-role="page" data-theme="a" id="page6">
            <div data-theme="a" data-role="header">
                <a data-role="button" data-direction="reverse" data-rel="back" data-transition="slide" data-theme="c" href="#page1" data-icon="home" data-iconpos="left">
                    Volver
                </a>
                <h3>
                    TuMovida
                </h3>
            </div>
            <div data-role="content">
            </div>
        </div>
        <script>
            //App custom javascript
        </script>
    </body>
</html>