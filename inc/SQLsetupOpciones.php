<?php
function create(){
	include 'conectar.php';
	$conn = new Conectar;
	$conn->Usuarios();
	$q = "SELECT * FROM usuarios";
	$Q = $conn->query($q);
	$conn->query("CREATE TABLE IF NOT EXISTS opciones (id int(11))");
	if(mysql_error()) echo mysql_error();
	while ($r = mysql_fetch_assoc($Q)){
		foreach($r as $key=>$val){
			$conn->query("ALTER TABLE opciones ADD COLUMN ($key int(1))");
			if(mysql_error()) echo mysql_error();
		}
	}
}
	include 'conectar.php';
	$conn = new Conectar;
	$conn->Usuarios();
	$q = "SELECT * FROM usuarios";
	$Q = $conn->query($q);
	while ($r = mysql_fetch_assoc($Q)){
		$idUsuario = $r['id'];
		$conn->query("INSERT INTO opciones (idUsuario) VALUES (".$idUsuario.")");
		if(mysql_error()) echo mysql_error();
	}
?>