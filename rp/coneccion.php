<?php
class Conectar{
	protected $dbhost 		= 'localhost';
	protected $dbusuario 	= 'tumovida_bd';
	protected $dbpass 		= '1123581321';
	
	function TM(){
		$this->Conexion();
		mysql_select_db('tumovida_tumovida');
	}	
	function Usuarios(){
		$this->Conexion();
		mysql_select_db('tumovida_usuarios');
	}
	function Conexion(){
		$conexion = mysql_connect($this->dbhost, $this->dbusuario, $this->dbpass);
		if (!$conexion){
		  die('No fue posible conectarse con la base de datos: ' . mysql_error());
	  	}
	}
}
?>