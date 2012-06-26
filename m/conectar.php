<?php
class Conectar{
	protected $dbhost 		= 'localhost';
	protected $dbusuario 	= 'tumovida_bd';
	protected $dbpass 		= '1123581321';
	protected $conexion;
	
	public function __construct(){
		$conexion = mysql_connect($this->dbhost, $this->dbusuario, $this->dbpass);
		@mysql_query("SET NAMES 'utf8'");
		
		if (!$conexion){
		  die('No fue posible conectarse con la base de datos: ' . mysql_error());
		  return FALSE;
	  	}
	}
	public function Conexion(){
		$conexion = mysql_connect($this->dbhost, $this->dbusuario, $this->dbpass);
		@mysql_query("SET NAMES 'utf8'");
		
		if (!$conexion){
		  die('No fue posible conectarse con la base de datos: ' . mysql_error());
		  return FALSE;
	  	}
	}
	public function TM(){
		$this->Conexion();
		mysql_select_db('tumovida_tumovida');
	}	
	public function Usuarios(){
		$this->Conexion(); 
		mysql_select_db('tumovida_usuarios');
	}
	public function query($query){
		return mysql_query($query);
	}
	public function Salir(){
		mysql_close();
	}
}
class Eventos extends Conectar
{
	const table = "eventos";
	public function __construct()
	{
		$this->Conexion();
		$this->TM();
	}
	public function getList()
	{
		$query = $this->query("SELECT * FROM ".Evento::table);
		while ($res = mysql_fetch_assoc($query))
			$buff[] = $res;
		return $buff;
	}
}
?>