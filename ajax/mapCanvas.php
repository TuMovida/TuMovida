<?php
$mapData = explode("_", $_GET['coords']);
?>
<div id="map_canvas"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#map_canvas").width($(document).width()-200);
	var latlng = new google.maps.LatLng(<?=$mapData[0].",".$mapData[1]?>);
	var opciones = {
		zoom: <?=$mapData[2]?>,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("map_canvas"), opciones);
	var Marker = new google.maps.Marker({
		map: map,
		position: latlng,
		title: "<?=$mapData[3]?>"
	});
});
</script>