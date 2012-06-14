<?php
@session_start();
include_once '../inc/conectar.php';
include_once '../inc/usuario.class.php';
if (!isset($_SESSION['idusuario'])):
	?>
	<h2>Acceso Denegado <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
	<?php
	exit;
elseif(isset($_SESSION['valid']) && $_SESSION['valid']):
	$usuario =  new Usuario($_SESSION['idusuario']);
?>
<h2>Editar Perfil <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
<div id='editarPerfil'>
	<h3>Mis datos</h3>
	<div class='misDatos'>
		<div class='fila'><strong>Nombre</strong><div class='dataValues'><?=$usuario->getNombre();?></div><a href='#' class='cambiarDato'>Cambiar</a> <a href='#' class='cambiarPrivacidad'>Privacidad</a></div>
		<div class='fila'><strong>Apellido</strong><div class='dataValues'><?=$usuario->getApellido();?></div><a href='#' class='cambiarDato'>Cambiar</a> <a href='#' class='cambiarPrivacidad'>Privacidad</a></div>
		<div class='fila'><strong>Correo electrónico</strong><div class='dataValues'><?=$usuario->getMail();?></div><a href='#' class='cambiarDato'>Cambiar</a> <a href='#' class='cambiarPrivacidad'>Privacidad</a></div>
		<div class='fila'><strong>Fecha de nacimiento</strong><div class='dataValues'><?=$usuario->getNacimiento();?></div><a href='#' class='cambiarDato'>Cambiar</a> <a href='#' class='cambiarPrivacidad'>Privacidad</a></div>
		<div class='fila'><strong>Sexo</strong><div class='dataValues'><?=$usuario->getSexo();?></div><a href='#' class='cambiarDato'>Cambiar</a> <a href='#' class='cambiarPrivacidad'>Privacidad</a></div>
		<div class='fila'><strong>Contraseña</strong><div class='dataValues'><i>Recomendamos cambiar su contraseña periodicamente</i></div><a href='#' class='cambiarDato' rel='password'>Cambiar</a></div>
	</div>
	<h3>Datos adicionales</h3>
	<div class='misDatos'> 
		<div class='fila'><strong>Cédula de Identidad</strong><div class='dataValues'><?=$usuario->getCI();?></div><a href='#' class='cambiarDato'>Cambiar</a> <a href='#' class='cambiarPrivacidad'>Privacidad</a></div>
		<div class='fila'><strong>Dirección</strong><div class='dataValues'><?=$usuario->getDireccion();?></div><a href='#' class='cambiarDato'>Cambiar</a> <a href='#' class='cambiarPrivacidad'>Privacidad</a></div>
		<div class='fila'><strong>Ubicación</strong><div class='dataValues'><?=$usuario->getUbicacion();?></div><a href='#' class='cambiarDato'>Cambiar</a> <a href='#' class='cambiarPrivacidad'>Privacidad</a></div>
		<div class='fila'><strong>Teléfono</strong><div class='dataValues'><?=$usuario->getTelefono();?></div><a href='#' class='cambiarDato'>Cambiar</a> <a href='#' class='cambiarPrivacidad'>Privacidad</a></div>
	</div>
	<h3>Mis opciones</h3>
	<div class='misDatos'>
		<div class='fila'><strong>Deseo recibir mails con ofertas de TM</strong><div class='dataValues'></div><div class='rightSide'><input type='checkbox' /></div></div>
		<div class='fila'><strong>Deseo ver albums de eventos a los que asistí al iniciar sesión en TM</strong><div class='dataValues'></div><div class='rightSide'><input type='checkbox' /></div></div>
	</div>
</div>
<script type="text/javascript">
var privacidadMenu = "<ul class='privacidadMenu'><li><a href='#' class='mostrarEnMiPerfilAction'>Mostrar en mi perfil</a></li><li><a href='#' class='nomostrarEnMiPerfilAction'>No mostrar en mi perfil</a></li></ul>";
$(".cambiarPrivacidad").dropMenu(privacidadMenu, function(res){
	//nomostrarEnMiPerfilAction
	//mostrarEnMiPerfilAction
	$(res).addClass("asddsa");
	alert($(res).text());
});
$(".cambiarDato").click(function(e){
	e.preventDefault();
	var $label = ($(this).prev().prev("strong")).text();
	var type = "text";
	if ($label === "Sexo"){
		var $input = "<select name='sexo' style='width: 100%;'><option value='Hombre'>Hombre</option><option value='Mujer'>Mujer</option></select>";
	}else if($label === "Fecha de nacimiento"){
		var birth = (($(this).prev("div")).text()).split('-');
		var $input = "<select name='day'>";
		for (var z=1; z<32; z++){
			if (z == birth[2])
				$input += "<option value="+z+" SELECTED>"+z+"</option>";
			else
				$input += "<option value='"+z+"'>"+z+"</option>";
		}
		$input += "</select><select name='month'>";
		var mes = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");
		for (var x=1; x<13; x++){
			if (x == birth[1])
				$input += "<option value="+x+" SELECTED>"+mes[x-1]+"</option>";
			else
				$input += "<option value='"+x+"'>"+mes[x-1]+"</option>";
		}
		$input += "</select><select name='year'>";
		for (var i=2012; i>1929; i--){
			if (i == birth[0])
				$input += "<option value="+i+" SELECTED>"+i+"</option>";
			else
				$input += "<option value='"+i+"'>"+i+"</option>";
		}
		$input += "</select>";
	}else{
		switch ($label){
			case 'Nombre': var name = 'nombre'; break;
			case 'Apellido': var name = 'apellido'; break;
			case 'Correo electrónico': var name = 'mail'; type='email'; break;
			case 'Sexo': var name = 'sexo'; break;
			case 'Contraseña': var name='password'; type='password'; break;
			case 'Cédula de Identidad': var name = 'ci'; break;
			case 'Teléfono': var name='telefono'; break;
			case 'Dirección': var name='direccion'; break;
			case 'Ubicación': var name='ubicacion'; break;
		}
		var $input = "<input name='"+name+"' type='"+type+"' value='"+($(this).prev("div")).text()+"' />";	
	}
	
	var $submit = "<input type='submit' value='Enviar'/>";

	dialog.show("<div class='formulario'><form id='updateProfile'><label>"+$label+"</label>"+$input+$submit+"</form></div>");
	$("#updateProfile").submit(function(e){
		e.preventDefault();
		var data = $(this).serialize();
		$.post("ajax/user.php?update", data, function(res){
			document.location.reload();
			dialog.destroy();
		});
	});
});
$(".cambiarPrivacidad").click(function(e){
	e.preventDefault();
});
</script>
<?php endif; ?>