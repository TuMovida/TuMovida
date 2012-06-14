<?php

class comenta extends Conectar
{
	private $coments;
	function __construct(){
		$this->coments = $this->query("SELECT * FROM librodevisitas ORDER BY id DESC LIMIT 0, 40");
	}
	public function getJSON(){
		if (!$this->coments){
			echo "<script>alert('".mysql_error()."');</script>";
			//ERROR
		}else{
			while($comentario = mysql_fetch_assoc($this->coments)){
				$return[] = $comentario;
			}
			return json_encode($return);
		}
	}
}

?>