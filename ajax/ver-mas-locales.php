<?php
include '../inc/conectar.php';
function cargarLocales($dia)
{
	$conn = new Conectar;
	$conn->TM();
	$res = $conn->query("SELECT id, Nombre, Imagen FROM locales WHERE ".$dia."=1");
	while ($r = mysql_fetch_assoc($res)){
		$buff[] = $r;
	}
	return $buff;
} 
function mostrarLocales($locales)
{
	foreach ($locales as $local) {
		(rand(0,4) == 0) ? $startDiv = "<div class='local-verMas longLocal'>" : $startDiv = "<div class='local-verMas'>";
		echo $startDiv;
		echo "<img src='images/locales/$local[Imagen]' />";
		echo "<span onclick=\"dialog.destroy(); $.address.value('local/$local[id]')\">".$local['Nombre']."</span>";
		echo "</div>";
	}
}
if (!isset($_GET['dia']))
	die("FALTA PARAMETRO DÍA");
$array = cargarLocales($_GET['dia']);
echo "<div class='verLocales'>";
mostrarLocales($array);
echo "</div>";
?>