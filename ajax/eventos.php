<?php
date_default_timezone_set('America/Montevideo');
include_once "../inc/conectar.php";
include_once "../inc/paginas.class.php"; 

class Eventos extends Conectar
{
	public $dia, $day; 
	public function getEventos($day)
	{
		$this->setDay($day);
		echo $this->getDayTitle();
		echo "<div class='eventos-y-locales'>";
			if($this->getNumberList()){
				echo "<div class='eventosDia' title='$day'>";
				$this->getList();
				echo "</div>";
				echo "<div class='eventosDiaLocales'>";
			}else{
				echo "<div class='eventosDiaLocales' style='width: 100%;'>";	
			}
			$this->getLocalesList();
			echo "</div>";
		echo "</div>";
	}
	public function setDay($day)
	{
		$this->dia = $day;
		$dayToeng = array("Domingo" => "Sunday", "Lunes"=>"Monday", "Martes"=> "Tuesday", "Miércoles"=>"Wednesday", "Jueves"=>"Thursday", "Viernes"=>"Friday", "Sábado" => "Saturday");
		$this->day = $dayToeng[$day];
	}
	public function getDayTitle(){
		$dia = $this->dia;
		$fecha = date("j", strtotime($this->day. " this week"));
		$return = 
		"<div class='diaTitle'>" .
			"<div class='diaTitleFlecha'>-</div>".
			"<div class='diaTitleFecha'>".$fecha."</div>" .
			$dia.
		"</div>";
		return $return;
	}
	public function getNumberList()
	{
		$this->Conexion();
		$this->TM();
		$query = $this->query("SELECT * FROM eventos WHERE DATE_FORMAT(Fecha, '%U')=DATE_FORMAT(CURDATE(), '%U') AND DATE_FORMAT(Fecha, '%W') = '".$this->day."' ORDER BY id DESC");
		if (!$query)
			return false;
		if(mysql_num_rows($query) < 1)
			return false;
		return true;
	}
	public function getList()
	{
		$this->Conexion();
		$this->TM();
		$query = $this->query("SELECT * FROM eventos WHERE DATE_FORMAT(Fecha, '%U')=DATE_FORMAT(CURDATE(), '%U') AND DATE_FORMAT(Fecha, '%W') = '".$this->day."' ORDER BY id DESC");
		if (!$query)
			return false;
		if(mysql_num_rows($query) < 1)
			return false;
		while ($eventoArray = mysql_fetch_assoc($query)){
			$evento = new evento();
			$evento->setPagina($eventoArray);
			if ($eventoArray){
				?>
				<div class='evento' onclick="$.address.path('/evento/<?=$evento->pagina['id']?>')">
					<div class='eventoImagen'>
						<?php  if (file_exists("images/eventos/s_".$evento->imagen())): ?>
						<img src='images/eventos/s_<?=$evento->imagen()?>' />
						<?php else:?>
						<img src='images/eventos/<?=$evento->imagen()?>' />
						<?php endif; ?>
					</div>
					<div class='eventoInfo'>
						<?=$evento-> nombre(true);?>
					</div>
				</div>
				<?php
			}
		}
	}
	public function getLocalesList()
	{
		$dia= $this->dia;
		if ($dia == "Miércoles") $dia = "Miercoles";
		if ($dia == "Sábado") $dia = "Sabado";
		$this->Conexion();
		$this->TM();
		$resEmp = $this->query("SELECT * FROM locales WHERE ".$dia."=1 AND Listado=1 ORDER BY Nombre ASC");
		$totEmp = mysql_num_rows($resEmp);
		if ($totEmp> 0) {
			echo "<h4>Locales abiertos</h4><ul>";
			while ( $rowEmp = mysql_fetch_assoc($resEmp) ) {
				//Locales:
				echo "<li>";
				echo "<a href='#!/local/".$rowEmp['id']."'><img src='images/icons/drink_empty.png' />".$rowEmp['Nombre']."</a>";
				echo "</li>";
			}
			echo "</ul>";
			echo "<a href='#!/eventos/' class='ver-mas' rel='".$dia."'>Ver más</a>";
		}
	}
}
$eventos = new Eventos();
?>
<h2>Eventos <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
<div id="eventos">
	<?php 
	echo $eventos->getEventos("Domingo");
	echo $eventos->getEventos("Lunes");
	echo $eventos->getEventos("Martes");
	echo $eventos->getEventos("Miércoles");
	echo $eventos->getEventos("Jueves");
	echo $eventos->getEventos("Viernes");
	echo $eventos->getEventos("Sábado");
	?>
</div>
<script type="text/javascript">
var actualDay = (new Date()).getDay();
$(".eventos-y-locales").each(function(index){
	if (index < actualDay){
		$(this).hide();
		$(this).prev().children("div.diaTitleFlecha").text("+");
	}
});
$(".diaTitle").click(function(e){
	$(this).next(".eventos-y-locales").slideToggle();
	if ($(this).children("div.diaTitleFlecha").text() == "+"){
		$(this).children("div.diaTitleFlecha").text("-");
	}else{
		$(this).children("div.diaTitleFlecha").text("+");
	}
});
$(".ver-mas").click(function(e){
	e.preventDefault();
	dialog.init();
	$.ajax({
		url: "ajax/ver-mas-locales.php?dia="+$(this).attr("rel"),
		success: function(res){
			$(".overlayer").append(res);
			$(window).scrollTop(0);
			$("body, html").css("overflow", "hidden");	
			$(".verLocales").height($(window).height());
			$(".verLocales").jScrollPane({
				autoReinitialise: true, 
				showArrows: true,
				verticalGutter: 80
			});
		}
	});
});
</script>