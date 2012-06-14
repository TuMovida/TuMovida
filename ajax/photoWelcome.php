<?php
@session_start();
include '../inc/conectar.php';
include '../inc/paginas.class.php';
include '../inc/usuario.class.php';
include '../inc/fotos.class.php';

if (!isLogged()) exit;

$idUsuario = $_SESSION['idusuario'];

class WelcomeInterface
{
	public function __construct()
	{

	}
}

function getFullAlbum($idUsuario)
{
	$conn = new Conectar;
	$conn->TM();
	$q = $conn->query("SELECT * FROM asistencias WHERE idUsuario=".$idUsuario);

	if (mysql_num_rows($q) == 0) exit;

	while ($asistencia = mysql_fetch_assoc($q))
	{
		$idEvento = $asistencia['idEvento'];
		$query = $conn->query("SELECT id, Nombre, Fecha FROM eventos WHERE id=".$idEvento." AND Fecha < NOW()");
		$evento = mysql_fetch_assoc($query);
		if ($evento !== false){
			$fQuery = $conn->query("SELECT * FROM eventos_fotos WHERE idEvento=".$idEvento);
			$fotos = mysql_fetch_assoc($fQuery); 
		}
		if ($fotos !== false)
			$buff[] = $fotos;
	}
	if (isset($buff))
		return json_encode($buff);
}
function showGallery($fotoURL)
{
	return "<img src='$fotoURL' class='photoDisplay-photo'/>";
}

if (isset($_GET['indexInterface'])){
	// $interface = new WelcomeInterface;
	?>
	<div class='photoWelcomeInterface'>
	<?php
	echo "<h5>Les presentamos las fotos de -FIESTA-</h5>";
	?>
	</div>
	<?php
}

if (!isset($_GET['album'])){
	/* 	GET JSON Albums 	*/
	$return = getFullAlbum($idUsuario);
	$r = json_decode($return);	
	header("Location: ".$_SERVER['PHP_SELF']."?album=".$r[0]->id."&foto_id=0&indexInterface");
}
if (isset($_GET['album'])){
	/*	GET PHOTO By ID 	*/
	$conn = new Conectar;
	$conn->TM();
	$id = mysql_real_escape_string($_GET['album']);
	$fQuery = $conn->query("SELECT * FROM eventos_fotos WHERE id=".$id);
	$fResponse = mysql_fetch_assoc($fQuery);
	$imgPath = "images/fotos/eventos/";
	$fotos = json_decode($fResponse['Fotos']);
	if ($fotos !== false && $fotos != null){
		foreach ($fotos as $foto){
			$rest[] = $imgPath.$foto;
		}
	}
	if (isset($_GET['getRest']))
		echo json_encode($rest);
	elseif (isset($_GET['foto_id'])){
		$foto_id = mysql_real_escape_string($_GET['foto_id']);
		if (@$rest[$foto_id] == null){
			header("Location: ".$_SERVER['PHP_SELF']."?album=".$_GET['album']."&foto_id=0");
		}else{
			echo showGallery($rest[$foto_id]);
	 	}
	}
}