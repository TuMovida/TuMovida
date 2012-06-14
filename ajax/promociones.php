<?php
include_once "../inc/conectar.php";
include_once "../inc/paginas.class.php"; 

$c = new Conectar();
$c->TM();
$promos = $c->query("SELECT * FROM promociones WHERE Activo=1 ORDER BY id DESC");
while ($promo = mysql_fetch_assoc($promos)){
	$return[] = $promo;
}
if (isset($_GET['json'])){
	echo json_encode($return);
	exit();
}

?>
<div id="promociones">
	<h2>Promociones <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
	<ul>
		<?php 
		if (isset($return)){
		foreach ($return as $promo)
		{
			?>
			<li>
				<div class='promoA promoImagen'>
					<img src='images/promos/<?=$promo['Imagen']?>' />
					<div class='promoImagenMask'></div>
				</div>
				<div class='promoB'>
					<h4><?=$promo['Nombre']?> / /</h4>
					<div class='promoDescripcion'><?=$promo['Descripcion']?></div>
				</div>
				<div class='promoC'>
					<div class='valor'><div>$<?=$promo['Valor']?></div></div>
					<div onclick="$.address.path('promo/<?=$promo['id']?>')" class='button comprar button-red' role='button'>Comprar</div>
					<div onclick="$.address.path('promo/<?=$promo['id']?>')" class='button ver-mas button-blue' role='button'>Ver más</div>
				</div>
			</li>
			<?php
		}
		}else{
			echo "Las promociones se encuentran temporalmente indisponibles.";
		}
		?>
	</ul>
</div>