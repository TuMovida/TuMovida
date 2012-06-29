<?php
class destacados extends pagina
{
	private $q;
	function __construct(){
		$this->TM();
		$this->q = $this->query("SELECT * FROM destacados WHERE Activo=1");
	}
	public function getJSON(){
		if ($this->q == false)
			return false;
		$return = array();
		while ($destacado = mysql_fetch_assoc($this->q)){
			if($destacado['Tipo'] == "Evento" && is_numeric($destacado['Enlace'])
				&& $destacado['Enlace'] != 0){
				$evento = new evento($destacado['Enlace']);
				if($evento->getPromo()){
					$destacado['Entradas'] = $evento->getPromo();
				}else{
					$destacado['Entradas'] = false;
				}
			}else{
				$destacado['Entradas'] = false;
			}
			$return[] = $destacado;
		}
		return json_encode($return);
	}
}

if (isset($_GET['json'])){
	$destacados = new destacados();
	echo $destacados->getJSON();
}
?>