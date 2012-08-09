<?php
class comenta extends Conectar
{
	private $coments;
	function __construct(){
		$this->coments = $this->query("SELECT * FROM librodevisitas WHERE block=0 ORDER BY id DESC LIMIT 0, 40");
	}
	public function getJSON(){
		if (!$this->coments){
			echo "<script>alert('".mysql_error()."');</script>";
			//ERROR
		}else{
			while($comentario = mysql_fetch_assoc($this->coments)){
				//Sistema de etiquetas
				$patron = "/@[\\[](evento|promo|local|usuario)_([0-9]+)[]]/";
				if(preg_match($patron, $comentario["Mensaje"], $match)){
					$id 	= $match[2];
					try{
						$evento = new iEvento($id);
						$sus 	= "<a href='#!/$1/$2'>".$evento->getNombre()."</a>";
						$comentario["Mensaje"] = preg_replace($patron, $sus, $comentario["Mensaje"]);
					}catch(Exception $e){
						// echo $e->getMessage();
					}
				}
				$return[] = $comentario;
			}
			return json_encode($return);
		}
	}
}

?>