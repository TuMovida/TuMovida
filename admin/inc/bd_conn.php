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
	public function TM(){
		$this->Conexion();
		mysql_select_db('tumovida_tumovida');
	}	
	public function Usuarios(){
		$this->Conexion(); 
		mysql_select_db('tumovida_usuarios');
	}
	public function Conexion(){
		$conexion = mysql_connect($this->dbhost, $this->dbusuario, $this->dbpass);
		@mysql_query("SET NAMES 'utf8'");
		
		if (!$conexion){
		  die('No fue posible conectarse con la base de datos: ' . mysql_error());
		  return FALSE;
	  	}
	}
	public function query($query){
		return mysql_query($query);
	}
	public function Salir(){
		mysql_close();
	}
	public function insert($into, $dataArray){
		foreach($dataArray as $name=>$val)
		{
			// if ($name == "Imagen"){
			// 	$upload = $this->imageUpload($dataArray["Imagen"]);
			// 	$val = $upload->getFile();
			// }
			$val = str_replace("\"", "\\\"", $val);
			if (isset($names) && $names != ""){
				$names .= ", ".$name;
			}else{
				$names = $name;
			}
			if (isset($values) && $values != ""){
				$values .= ", \"".$val."\"";
			}else{
				$values = "\"".mysql_real_escape_string($val)."\"";
			}
		}
		$query = $this->query("INSERT INTO ".$into." (".$names.") VALUES (".$values.")");
		if (!$query)
			return false;
		else
			return array("state" => $query, "id" => mysql_insert_id());
	}
	public function update($into, $dataArray, $where){
		foreach($dataArray as $name=>$val){
			$val = str_replace("\"", "\\\"", $val);
			$val = str_replace("'", "\'", $val);
			unset($dataArray['id']);
			if(!isset($set)){
				$set = $name."=\"".mysql_real_escape_string($val)."\"";
			}else{
				$set.= ", ".$name."=\"".mysql_real_escape_string($val)."\"";
			}
		}
		$query = $this->query("UPDATE ".$into." SET ".$set." WHERE ".$where);
		if (!$query)
			return false;
		else
			return array("state" => $query, "id" => mysql_insert_id());
	}
	public function clearFields($dataArray)
	{
		for ($i=0; $i > count($dataArray); $i++)
		{
			if ($dataArray[$i] == ""){
				unset($dataArray[$i]);
			}
		}
		array_values($dataArray);
	}
	public function imageUpload($image)
	{
		$upload = new imageUpload($image);
	}
}
class imageUpload
{
	const ROOT = "../";
	public $filename, $src, $obj, $ubicacion;

	public function __construct($file, $tipo)
	{
		$this->src 		= $file;
		$this->obj 		= $this->upload($this->src);
		$this->ubicacion= $tipo;
		return $this->obj;
	}
	public function __tostring()
	{
		$this->getFile();
	}
	private function defaultSettings()
	{
		//Limpiar el nombre
		$this->clear_fileName($fileName);
	}
	private function clear_fileName($fileName)
	{
		$this->filename = preg_replace('/[^\w\._]+/', '_', $fileName);
	}
	private function upload($file)
	{
		$file = $_FILES["Imagen"];
		$imagen = $file['name'];
		$imagen1 = explode(".",$imagen);
		$imagen2 = rand(0,9).rand(100,9999).rand(100,9999).".".$imagen1[1];
		move_uploaded_file($file['tmp_name'], imageUpload::ROOT."images/".$this->ubicacion."/".$imagen2);
		$ruta = imageUpload::ROOT."images/".$this->ubicacion."/".$imagen2;
		chmod($ruta,0777);
		list ($width, $height) = getimagesize($ruta);
		return $imagen2;
	}
	public function getFile()
	{
		return $this->obj;
	}
}
?>