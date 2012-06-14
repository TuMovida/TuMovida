<?php
interface IUser{
	//GETTER
	public function getID();
	public function getMail();
	public function getNombre();
	public function getApellido();
	public function getCI();
	public function getSexo();
	public function getNacimiento();
	public function getEdad();
	public function getTelefono();
	public function getUbicacion();
	public function getDireccion();
	public function getCompras();
	//SETTER
	public function setMail($Mail);
	public function setPass($Pass);
	public function setNombre($Nombre);
	public function setApellido($Apellido);
	public function setCI($CI);
	public function setSexo($Sexo);
	public function setNacimiento($Fecha);
	public function setEdad($Edad);
	public function setTelefono($Telefono);
	public function setUbicacion($Ubicacion);
	public function setDireccion($Direccion);
	public function setCompras($Compras);
}
class Usuario extends Conectar implements IUser
{
	protected $userData;
	public $userID;
	function __construct($userID){
		//Conectar DB:
		$this->Usuarios();
		$this->userID = $userID;
		$this->getData();
	}
	private function getData(){
		$query = $this->query("SELECT * FROM usuarios WHERE id=".$this->userID);
		$this->userData = mysql_fetch_assoc($query);
	}
	public function getID(){
		return $this->userData['id'];
	}
	public function getMail(){
		return $this->userData['Mail'];
	}
	public function getNombre(){
		return ucwords($this->userData['Nombre']);
	}
	public function getApellido(){
		return ucwords($this->userData['Apellido']);
	}
	public function getCI(){
		return $this->userData['CI'];
	}
	public function getNacimiento($literal = false){
		if ($literal == true){
			$monthSpanish = array("Enero", "Febrero", "Marzo", 
				"Abril", "Mayo", "Junio", "Julio", 
				"Agosto", "Setiembre", "Octubre", 
				"Noviembre", "Diciembre");
			$fecha = strtotime($this->userData['Nacimiento']);
			return date("j", $fecha)." de ".$monthSpanish[date("n", $fecha)-1];
		}
		return $this->userData['Nacimiento'];
	}
	public function getEdad(){
		if (!isset($this->userData['Nacimiento']))
			return false;
		list($year, $month, $day) = explode ("-", $this->userData['Nacimiento']);
		$yDiff = date("Y") - $year;
		$mDiff = date("m") - $month;
		$dDiff = date("d") - $day;
		if ($mDiff < 0) $yDiff--;
		elseif(($mDiff==0)&&($dDiff < 0)) $yDiff--;
		return $yDiff;
	}
	public function getTelefono(){
		return $this->userData['Telefono'];
	}
	public function getSexo(){
		return $this->userData['Sexo'];
	}
	public function getUbicacion(){
		return $this->userData['Ubicacion'];
	}
	public function getDireccion(){
		return $this->userData['Direccion'];
	}
	public function getCompras(){
		$query = $this->query("SELECT * FROM compras WHERE CI=".$this->getCI());
		while ($res = mysql_fetch_assoc($query)){
			$buff[] = $res;
		}
		return $buff;
	}
	public function setMail($Mail){
		$this->userData['Mail'] = $Mail;
	}
	public function setNombre($Nombre){
		$this->userData['Nombre'] = $Nombre;
	}
	public function setApellido($Apellido){
		$this->userData['Apellido'] = $Apellido;
	}
	public function setCI($CI){
		$replace = array(" ", ".", "-", "+", "(", ")");
		$this->userData['CI'] = str_replace($replace, "", $CI);
	}
	public function setNacimiento($Fecha){
		$this->userData['Nacimiento'] = $Fecha;
	}
	public function setEdad($Edad){
		$this->userData['Edad'] = $Edad;
	}
	public function setSexo($Sexo){
		$this->userData['Sexo'] = $Sexo;
	}
	public function setPass($Pass){
		$parse = $this->parsePass($Pass);
		$pass = $parse["hash"];
		$salt = $parse["salt"];
		$this->userData['Password'] = $pass;
		$this->userData['Salt'] = $salt;
	}
	public function setUbicacion($Ubicacion){
		$this->userData['Ubicacion'] = $Ubicacion;
	}
	public function setDireccion($Direccion){
		$this->userData['Direccion'] = $Direccion;
	}
	public function setTelefono($Telefono){
		$this->userData['Telefono'] = $Telefono;
	}
	public function setCompras($Compras){
		
	}
	private function parsePass($pass){
		$hash = hash('sha256', $pass);
		$string = md5(uniqid(rand(), true));
		$salt = substr($string, 0, 3);
		$hash = hash('sha256', $salt . $hash);
		return array("hash"=>$hash,"salt"=>$salt);
	}
}
class NuevoUsuario extends Usuario
{
	private $dataArray;
	public function __construct($dataArray){
		$this->dataArray = $dataArray;
		$this->dataArray['nacimiento'] = $dataArray['year']."-".$dataArray['month']."-".$dataArray['day'];
		$this->cleanForm();
	}
	private function cleanForm(){
		$dataArray = $this->dataArray;
		foreach($dataArray as $data){
			$data = mysql_real_escape_string($data);
		}
	}
	public function setValues(){
		foreach ($this->dataArray as $name=>$val)
		{
			switch ($name) {
				case 'mail': $this->setMail($val);break;
				case 'nombre': $this->setNombre($val);break;
				case 'apellido': $this->setApellido($val);break;
				case 'password': $this->setPass($val);break;
				case 'ci': $this->setCI($val);break;
				case 'nacimiento': $this->setNacimiento($val);break;
				case 'sexo': $this->setSexo($val);break;
				case 'ubicacion': $this->setUbicacion($val);break;
				case 'direccion': $this->setDireccion($val);break;
				case 'telefono': $this->setTelefono($val);break;
				default:
					;
				break;
			}
		}
		$columns = "";
		$values = "";
		foreach ($this->userData as $name=>$val)
		{
			if ($name != ""&& $val != ""){
				if ($columns == "")
					$columns = $name;
				else 
					$columns = $columns . ", " . $name;
				if ($values == "")
					$values = "'".$val."'";
				else 
					$values = $values . ", " . "'".$val."'";
			}
		}
		if ($columns != "" && $values != ""){
			$doQuery = "INSERT INTO usuarios (".$columns.") VALUES (".$values.")";
			$this->Conexion();
			$this->Usuarios();
			$query = $this->query($doQuery);
			if ($query){
				$this->insertPrivacidad(mysql_insert_id());
			}
		}
	}
	private function insertPrivacidad($idUsuario)
	{
		$privacidad = new NewUsuarioPrivacidad($idUsuario);
	}
}
class UpdateUsuario extends Usuario
{
	private $dataArray;
	public function __construct($dataArray){
		$this->dataArray = $dataArray;
		if (isset($dataArray['year']) && isset($dataArray['month']) && isset($dataArray['day']))
			$this->dataArray['nacimiento'] = $dataArray['year']."-".$dataArray['month']."-".$dataArray['day'];
		$this->cleanForm();
	}
	private function cleanForm(){
		$this->Conexion();
		$this->Usuarios();
		$dataArray = $this->dataArray;
		foreach($dataArray as $data){
			$data = mysql_real_escape_string($data);
		}
	}
	public function setValues(){
		foreach ($this->dataArray as $name=>$val)
		{
			switch ($name) {
				case 'mail': $this->setMail($val);break;
				case 'nombre': $this->setNombre($val);break;
				case 'apellido': $this->setApellido($val);break;
				case 'password': $this->setPass($val);break;
				case 'ci': $this->setCI($val);break;
				case 'nacimiento': $this->setNacimiento($val);break;
				case 'sexo': $this->setSexo($val);break;
				case 'ubicacion': $this->setUbicacion($val);break;
				case 'direccion': $this->setDireccion($val);break;
				case 'telefono': $this->setTelefono($val);break;
				default:
					;
				break;
			}
		}
		foreach ($this->userData as $name=>$val)
		{
			if (!isset($toUPDATE))
				$toUPDATE = $name . "='" . $val."'";
			else
				$toUPDATE = $toUPDATE . ", " . $name . "='" . $val."'";
		}
		if ($toUPDATE != NULL){
			$doQuery = "UPDATE usuarios SET ".$toUPDATE." WHERE id=".$_SESSION['idusuario'];
			$this->Conexion();
			$this->Usuarios();
			$query = $this->query($doQuery);
			if (!$query){
				throw new Exception("Error Processing Request: ".mysql_error(), 1);
			}
		}
	}
}
class UsuarioLog extends Usuario
{
	public $propitaryID;
	public function __construct($mail, $pass){
		$this->Conexion();
		$this->Usuarios();
		$query = $this->query("SELECT Password, Salt, id FROM usuarios WHERE Mail='".$mail."'");
		if (!$query){
			throw new Exception("Error en la conección con la base de datos");
			return false;
		}
		$userData = mysql_fetch_assoc($query);
		$hash = $userData['Password'];
		$salt = $userData['Salt'];
		$pass = hash('sha256', $userData['Salt'].hash('sha256', $pass));
		if ($pass == $hash){
			$idusuario = $userData['id'];
			$this->validateUser($idusuario);
		}else{
			throw new Exception("Error en el mail o la contraseña");
			return false;
		}
	}
	public function isLogged(){
		if(isset($_SESSION['valid']) && $_SESSION['valid']){
			if (isset($_SESSION['idusuario']))
				$this->propitaryID = $_SESSION['idusuario'];
			return true;	
		}
		return false;
	}
	public function logout(){
		$_SESSION = array();
    	session_destroy();
    	if ($this->isLogged())
    		return false;
    	return true;
	}
	private function validateUser($idusuario){
    	session_regenerate_id ();
    	$_SESSION['valid'] = 1;
    	$_SESSION['idusuario'] = $idusuario;
    	$this->propitaryID = $idusuario;
	}
}
class UsuarioActions extends UsuarioLog
{
	public function __construct(){
		if ($this->isLogged()){
			return true;
		}else{
			return false;	
		}
	}
	public function getAsistir($idEvento, $idUsuario)
	{
		$this->Conexion();
		$this->TM();
		$query = $this->query("SELECT id FROM asistencias WHERE idEvento=".$idEvento." AND idUsuario=".$idUsuario);	
		if (mysql_affected_rows() == 0)
			return false;
		$idAsistencia = mysql_fetch_row($query);
		return $idAsistencia[0];
	}
	public function setAsistir($idEvento, $idUsuario)
	{
		$this->Conexion();
		$this->TM();
		$exist = $this->getAsistir($idEvento, $idUsuario);
		if (!$exist)
			$query = $this->query("INSERT INTO asistencias (idEvento, idUsuario) VALUES ($idEvento, $idUsuario)");
		else
			$query = $this->toggleAsistencia($exist);
		return $query;
	}
	private function toggleAsistencia($idAsistencia)
	{
		$this->Conexion();
		$this->TM();
		$isBlocked = mysql_fetch_row($this->query("SELECT block FROM asistencias WHERE id=".$idAsistencia));
		if ($isBlocked[0] == 1){
			return $this->query("UPDATE asistencias SET block=0 WHERE id=".$idAsistencia);
		}else{
			return $this->query("UPDATE asistencias SET block=1 WHERE id=".$idAsistencia);
		}
	}
}
class NewUsuarioPrivacidad extends Conectar{
	const table = "opciones";
	public function __construct($idUsuario)
	{
		$this->Conectar();
		$this->TM();
		$this->query("INSERT INTO ".NewUsuarioPrivacidad::table." (idUsuario) VALUES (".$idUsuario.")");
	}
}
class UsuarioPrivacidad extends Conectar
{
	protected $userData;
	public $userID;
	function __construct($userID){
		//Conectar DB:
		$this->Usuarios();
		$this->userID = $userID;
		$this->getData();
	}
	private function getData(){
		$query = $this->query("SELECT * FROM opciones WHERE idUsuario=".$this->userID);
		$this->userData = mysql_fetch_assoc($query);
	}
	public function getID(){
		return $this->userData['id'];
	}
	public function getMail(){
		return $this->userData['Mail'];
	}
	public function getNombre(){
		return ucwords($this->userData['Nombre']);
	}
	public function getApellido(){
		return ucwords($this->userData['Apellido']);
	}
	public function getCI(){
		return $this->userData['CI'];
	}
	public function getNacimiento(){
		return $this->userData['Nacimiento'];
	}
	public function getEdad(){
		if (!isset($this->userData['Nacimiento']))
			return false;
		return $this->userData['Nacimiento'];
	}
	public function getTelefono(){
		return $this->userData['Telefono'];
	}
	public function getSexo(){
		return $this->userData['Sexo'];
	}
	public function getUbicacion(){
		return $this->userData['Ubicacion'];
	}
	public function getDireccion(){
		return $this->userData['Direccion'];
	}
	public function getCompras(){
		
	}
}
function isLogged(){
	if(isset($_SESSION['valid']) && $_SESSION['valid'])
		return true;
	return false;
}
function logout(){
	$_SESSION = array();
   	session_destroy();
   	if (isLogged())
   		return false;
   	return true;
}