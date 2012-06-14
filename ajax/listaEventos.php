<?php 
date_default_timezone_set('America/Montevideo');
require "../inc/conectar.php";
require "../inc/paginas.class.php";
if (isset($_GET['dia']) && class_exists("Conectar"))
{
	$fecha = (string) $_GET['dia'];
	$conn = new Conectar;
	$conn->Conexion();
	$conn->TM();
	$q = $conn->query("SELECT * FROM eventos WHERE Fecha='".$fecha."'");
	if (!$q) die(mysql_error());
	if (mysql_num_rows($q) < 1)
		echo "No hay eventos";
	while($eventoArray = mysql_fetch_assoc($q))
	{
		$e = new evento();
		$e->setPagina($eventoArray);
		echo "<a href='#!/evento/".$e->pagina['id']."' class='lqsvEvento'>";
		echo "<div class='lqsvEventoImg'>";
		echo "<img alt='' src='images/eventos/".$e->imagen()."' />";
		echo "</div>";
		echo "<div class='lqsvEventoTexto'>";
		echo $e->nombre();
		echo "</div>";
		echo "</a>";
	}
}
?>