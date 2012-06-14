<?php
/*$('#map_canavas').gmap({'callback': function() {
	var self = this;
	self.addMarker({'position': coordenadas, 'bounds': true}).click(function() {
		self.openInfoWindow({'content': text}, this);
	});
}});
$('#mapa_canavas').gmap().bind('init', function(ev, map) {
				$('#mapa_canavas').gmap(addMarker', {
					'position': coordenadas, 'bounds': true}).click(function() { 
						$('#mapa_canavas').gmap('openInfoWindow', {'content': text}, this);
					});
			});*/
class mapa{
	//private $delimitador = ",";
	public $mostrarImg;
	public $mostrarJS;
	function img($enlace){
		/*$enlace = explode($this->delimitador, $enlace); 
		$latitud	= $enlace[0];
		$longitud	= $enlace[1];
		$contenido	= $enlace[2];
		$cc			= $latitud.",".$longitud;
		$zoom = '';*/
		$enlace 	= json_decode($enlace);
		$latitud	= $enlace[0];
		$longitud	= $enlace[1];
		$zoom 		= $enlace[2];
		$contenido	= $enlace[3];
		$cc			= $latitud.",".$longitud;
		$mostrarImg = "	<script>
							$('#mapaImg').ready(function(){ 
								$('#mapaImg').click(function(e){
									e.preventDefault();
								});
								$('#mapaImg [title]').tipsy({fade: true});
							});
						</script>";
		$mostrarImg.= "<a id='mapaImg' onclick='mapa_alerta();' href='#'><img src='
			http://maps.google.com/maps/api/staticmap?center=".$cc."&zoom=".$zoom."&size=600x200&maptype=roadmap
			&markers=color:red|label:A|".$cc."&sensor=false' width='100%' title='Ver mapa interactivo' /></a>";
		
		return $mostrarImg;
	}
	function js($enlace){
		/*$enlace = explode($this->delimitador, $enlace); 
		$latitud	= $enlace[0];
		$longitud	= $enlace[1];
		$contenido	= $enlace[2];
		$cc			= $latitud.",".$longitud;
		$zoom = '';*/
		$enlace 	= json_decode($enlace);
		$latitud	= $enlace[0];
		$longitud	= $enlace[1];
		$zoom 		= $enlace[2];
		$contenido	= $enlace[3];
		$cc			= $latitud.",".$longitud;
		
		
		$mostrarJS = "<script type='text/javascript'>function mapa_alerta(){ $('#dialog_mapa2').dialog({height: 600,width: 750,modal: true});}";
		$mostrarJS.= "
		$('#dialog_mapa2').bind('dialogopen', function(event, ui){
			//$('#mapa_canavas').gmap();
			//$('#mapa_canavas').gmap('refresh');
			var coordenadas = new google.maps.LatLng(".$cc.");var text='".$contenido."';
			$('#mapa_canavas').gmap({'zoom': ".$zoom."});
			var \$marker = $('#mapa_canavas').gmap('addMarker', {'position': coordenadas, 'bounds': true});
			\$marker.click(function() { 
				$('#mapa_canavas').gmap('openInfoWindow', {'content': text}, this);
			});
			$('#mapa_canavas').gmap().bind('init', function(ev, map) {
				$('#mapa_canavas').gmap('option', 'center', coordenadas);
				$('#mapa_canavas').gmap('option', 'zoom', ".$zoom.");
			});
			
			
			
		});	
		$('#dialog_mapa2').bind('dialogclose', function(event, ui){
			$('#mapa_canavas').gmap('clear', 'markers');
		});	
		";
		$mostrarJS.= "</script>";
		$mostrarJS.= "
		<div title='Mapa' style='' id='dialog_mapa2'>
			<div id='mapa_canavas' style='width: 100%; height: 100%';></div>
		</div>
		";
		
		return $mostrarJS;
	}
}
?>