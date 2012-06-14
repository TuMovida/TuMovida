<?php
@session_start();
include '../inc/conectar.php';
include '../inc/paginas.class.php';
include '../inc/usuario.class.php';
include '../inc/fotos.class.php';
?>
<?php
$imgPath = "images/fotos/eventos/";
$conn = new Conectar;
$conn->TM();
$id = $_GET['id'];
if (!is_numeric($id)){
	exit("URL INVALIDA");
}
$fQuery = $conn->query("SELECT * FROM eventos_fotos WHERE id=".$id);
if ($fQuery):
	$album  = mysql_fetch_assoc($fQuery);
	if (mysql_error()) exit;
	$fotos = json_decode($album['Fotos']);
?>
<h2><?=$album['Album']?> <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
<div id="album">
<div class='albumInfo'>
	<div class='albumInfoMenu'>
		<button class='compartir button button-red'>Compartir</button>
	</div>
	<div class='albumInfoContent'>
		<p style='display: none;'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	</div>
</div>
<?php
	$fotoID = 0;
	foreach($fotos as $foto){
		echo "<div class='album-Foto'><a href='#!/album/".$id."/foto/".$fotoID."'><img class='lazy' src='' data-original='images/thumb.php?src=http://img.tumovida.com.uy/fotos/eventos/".$foto."&w=260&h=150px&zc=1' /></a></div>";
		$fotoID++;
	}
?>
</div>
<script type="text/javascript">
$.address.title("¡TuMovida! - <?=$album['Album']?>");
$(".compartir").click(function(e){
	var sharer = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("http://www.tumovida.com.uy/facebook_request.php?t=eventos_fotos&id=<?=$id?>")+"&t="+encodeURIComponent(document.title);
	window.open(sharer, this.target, 'width=670,height=300');
});
$("img.lazy").lazyload({effect: "fadeIn"});
</script>
<?php
endif;
?>