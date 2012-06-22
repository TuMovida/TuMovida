<?php
date_default_timezone_set('America/Montevideo');
header("Content-type: text/html; charset='utf8'");
#include '../inc/conectar.php';
#include '../inc/usuario.class.php';
?>
<!DOCTYPE html>
<html leng="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
    <center>
    <div data-role="page" data-theme="a" id="/registro">
            <div data-theme="a" data-role="header">
                <h3>
                    TuMovida Movil
                </h3>
            </div>
            <div data-role="content">
            <b><p id="descripcion">- Atenci�n!</p></b>
                <p id="ubicacion">Apreta "Mostrar Ubicaci�n" para mostrar tu ubicaci�n:</p>
                <br>
                
	<button onclick="getLocation()">Mostrar Ubicaci�n</button>
                    </div>
	<div id="mapa"></div>
	<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
var x=document.getElementById("ubicacion");
function getLocation()
  {
  if (navigator.geolocation)
    {
    navigator.geolocation.getCurrentPosition(showPosition,showError);
    }
  else{x.innerHTML="Su navegador no soporta este servicio";}
  }

function showPosition(position)
  {
  lat=position.coords.latitude;
  lon=position.coords.longitude;
  latlon=new google.maps.LatLng(lat, lon)
  mapholder=document.getElementById('mapa')
  mapholder.style.height='125px';
  mapholder.style.width='250px';

  var myOptions={
  center:latlon,zoom:14,
  mapTypeId:google.maps.MapTypeId.ROADMAP,
  mapTypeControl:false,
  disableDefaultUI: true
  };
  
  var map=new google.maps.Map(document.getElementById("mapa"),myOptions);
  var marker=new google.maps.Marker({position:latlon,map:map,title:"Aca estas vos!"});
  }

function showError(error)
  {
  switch(error.code) 
    {
    case error.PERMISSION_DENIED:
      x.innerHTML="El usuario no permitio el uso del servicio."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML="La informacion de la ubicacion no esta disponible"
      break;
    case error.TIMEOUT:
      x.innerHTML="Paso el tiempo permitido para obtener informacion de ubicacion."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML="Error desconocido."
      break;
    }
  }
</script>
		<div data-role="footer">
			<br>
			<br>
		</div><!-- /footer -->

</center>

    </body>
</html>