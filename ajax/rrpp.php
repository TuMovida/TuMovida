<?php
@session_start();
require_once '../inc/conectar.php';
// require_once '../inc/paginas.class.php';
// require_once '../inc/usuario.class.php';
// require_once '../inc/compra.class.php';
	
$conn = new Conectar;
$conn->Usuarios();

if (isset($_GET['getList'])){
	(isset($_GET['term'])) ? $term = strtolower($_GET['term']) : $term = '';
	
	$query = $conn->query("SELECT id, idUsuario, Nombre, Apellido FROM vendedores WHERE LOWER(Nombre) LIKE '%" . $term . "%' ORDER BY Nombre");
	// $query=  $conn->query("SELECT * FROM vendedores");
	while ($r = mysql_fetch_assoc($query)){
		$buff[] = $r;
	}
	echo json_encode($buff);
	exit;
}

?>