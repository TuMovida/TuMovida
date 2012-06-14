<?php
@session_start();
include '../inc/conectar.php';
include '../inc/paginas.class.php';
include '../inc/usuario.class.php';
include '../inc/fotos.class.php';
?>
<h2>Fotos <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
<div id="fotos">
<?php
$imgPath = "fotos/eventos/";
$conn = new Conectar;
$conn->TM();
$fQuery = $conn->query("SELECT * FROM eventos_fotos WHERE Block=0 ORDER BY id DESC");
if ($fQuery){
	while($album = mysql_fetch_assoc($fQuery)){
		?>
		<div class='fotoAlbum' onclick="$.address.path('album/<?=$album['id']?>')">
		<?php
		$fotos = json_decode($album["Fotos"]);
		$isFirst = true;
		$i = 0;
		foreach($fotos as $foto){
			if ($i > 4)
				break;
			if ($isFirst):
			?>
			<div class='fotoAlbumImageGrande'>
				<div><img class='lazy' data-original='images/thumb.php?src=http://img.tumovida.com.uy/<?=$imgPath.$foto?>&amp;w=550&amp;zc=0' src='' /></div>
			</div>
			<div class='fotosAlbumRightSide'>
				<h3><?=$album['Album']?></h3>
				<? 
				$isFirst = false;
				else:
				?>	
				<div class='fotoAlbumImage'>
					<img class='lazy' data-original='images/thumb.php?src=http://img.tumovida.com.uy/<?=$imgPath.$foto?>&amp;w=150' src='' />
				</div>
				<?php
				endif;
			$i++;
			}
			?>
			</div>
			<div class='fotoAlbum-verMasArrow'></div>
		</div>
		<?php
	}
}
?>
</div>
<script type="text/javascript">$("img.lazy").lazyload({effect: "fadeIn"});</script>