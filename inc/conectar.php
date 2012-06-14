<?php
class Conectar{
	protected $dbhost 		= 'localhost';
	protected $dbusuario 	= 'tumovida_bd';
	protected $dbpass 		= '1123581321';
	protected $conexion;
	
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
		@mysql_query("SET NAMES 'utf8'");
		
		if (!$conexion){
		  die('No fue posible conectarse con la base de datos: ' . mysql_error());
		  return FALSE;
	  	}
	}
	function __construct(){
		$conexion = mysql_connect($this->dbhost, $this->dbusuario, $this->dbpass);
		@mysql_query("SET NAMES 'utf8'");
		
		if (!$conexion){
		  die('No fue posible conectarse con la base de datos: ' . mysql_error());
		  return FALSE;
	  	}
	}
	function query($query){
		return mysql_query($query);
	}
	function Salir(){
		mysql_close();
	}
}
?>