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
                    <li data-theme="b">
                        <a href="#page6" data-rel="dialog" data-transition="pop">
                            Comparti en las redes sociales!
                        </a>
                    </li>
                </ul>
            </div>
        </div>                                                 
       <div data-role="dialog" data-theme="a" id="page6" >
            <div data-role="content">
            <h3>Comparti!</h3>
                    <a href="@" data-role="button" data-rel="dialog" data-transition="slidedown" data-theme="b">Subir a flickr</a>     
					<a href="#" data-role="button" data-rel="dialog" data-transition="slidedown" data-theme="b">Compartir en Facebook</a>       
					<a href="#" data-role="button" data-rel="dialog" data-transition="slidedown" data-theme="b">Descargar</a>      
					<a href="#" data-role="button" data-rel="dialog" data-transition="slidedown" data-theme="b">Tweetear foto</a>       
		 		  
		<a href="#page1" data-role="button" data-rel="back" data-theme="c">Volver</a>    
                </ul>
            </div>
        </div>                                          
        <script>
            //App custom javascript
        </script>
    </body>
</html>