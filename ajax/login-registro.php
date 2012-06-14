<div class="formulario" style="display: inline-block; vertical-align: middle;">
	<span style='color: rgba(0,0,0,0.3); background-color: rgba(82,96,117,0.5); 
	-webkit-background-clip: text; font-family: inherit; 
	font-weight: bold; font-size: 20px; line-height: 36px; 
	text-rendering: optimizelegibility; padding: 35px;
	text-shadow: rgba(255,255,255,0.5) 0 5px 6px;'>Ya tengo un usuario</span>
	<form id="dobleFormulario-login" action="ajax/user.php?login" method="POST">
	<fieldset style="text-align: center;" >
		<img src="images/star_256.png" width="100px"/>
		<label>Mail: </label><input type="email" name="mail" required />
		<label>Contraseña: </label><input type="password" name="password" required  />
		<input type="submit" value="Entrar" id="login-enviar" class="button"/>
	</fieldset>
</form>
</div>
<div class="formulario" style="
    -webkit-box-shadow:  -1px 0px 10px 0px #DDD;
    -moz-box-shadow:  -1px 0px 10px 0px #DDD;
    box-shadow:  -1px 0px 10px 0px #DDD;
    border-left: 1px solid #E7E7E7; 
	background: #F8F8F8; display: inline-block; padding: 30px; vertical-align: middle;">
	<span style='color: rgba(0,0,0,0.3); background-color: rgba(82,96,117,0.5); 
	-webkit-background-clip: text; font-family: inherit; 
	font-weight: bold; font-size: 20px; line-height: 36px; 
	text-rendering: optimizelegibility; text-shadow: rgba(255,255,255,0.5) 0 5px 6px;'>Crear una cuenta nueva</span>
	<form id="dobleFormulario-registro">
	<fieldset>
	<label>Nombre</label>
	<input type="text" name="nombre" title="Ingrese su nombre real"/>
	<label>Apellido</label>
	<input type="text" name="apellido" title="Ingrese su apellido.<br/>Recuerde que TM proteje sus datos de forma segura."/>
	<label>Tu correo electrónico</label>
	<input id='registro_mail' type="text" name="mail" title="Con este email usted ingresará al sitio." />
	<label>Tu contraseña</label>
	<input type="password" title="Escribe tu contraseña" name="password"/>
	<label>Cédula</label>
	<input type="text" name="ci" title="Cédula de identidad" />
	<label>Sexo</label>
	<select name='sexo' title="Escoja su genero">
		<option>Mujer</option>
		<option>Hombre</option>
	</select>
	<label>Fecha de nacimiento</label>
		<select name='day' title="Día en el que nació">
			<?php
			for($i=1; $i<32; $i++)
				echo "<option value='$i'>" . $i . "</option>";
			?>
		</select>
		<select name='month' title="Mes en el que nació">
			<?php
			$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
			for($i=0; $i<12; $i++)
				echo "<option value='$i'>" . $meses[$i] . "</option>";
			?>
		</select>
		<select name='year' title="Año de nacimiento">
			<?php
			for($i=2012; $i>1929; $i--)
				echo "<option value='$i'>" . $i . "</option>";
			?>
		</select>
	<input type="submit" value="Registrarme" id="dobleFormulario-registro-enviar" class="button"/>	
	</fieldset>
	</form>
</div>
<script type="text/javascript">
$("#login-enviar").click(function(e){
	e.preventDefault();
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var errorTip = {
		show: {
				event: false,
				ready: true
			},
			hide: {
				delay: 1000
			},
			style: {
				classes: 'ui-tooltip-red'
			},
			position: {
				my: 'middle left',
				at: 'middle right'
			}
	}
	if ($("#dobleFormulario-login input[type='email']").val() == '' || 
		!emailReg.test($("#login input[type='email']").val().toString())){
		$("#dobleFormulario-login input[type='email']").qtip($.extend({}, errorTip, {
			content: 'Introduce un mail válido'
		}));
		return false;
	}
	if ($("#login input[type='password']").val() == ''){
		$("#login input[type='password']").qtip($.extend({}, errorTip, {
			content: 'Falta la contraseña'
		}));
		return false;
	}
	var data = $("#dobleFormulario-login").serialize();
	$.post("ajax/user.php?login", data, function(res){
		if (res === "Error en el mail o la contraseña"){
			alert("El mail y/o la contraseña son incorrectos");
			try{
				piwikTracker.setCustomVariable(2, "Errores", "Mail o contraseña incorrecta en login","visit");
				piwikTracker.trackPageView();
			}catch(err){}
		}else{
			$("#usuario").load("index.php #usuario");
			dialog.destroy();
			try{
				<?php (isset($_SESSION['idusuario'])) ? $idUsuario = $_SESSION['idusuario'] : $idUsuario = "Anónimo"; ?> 
				piwikTracker.setCustomVariable(1, "usuario", "<?=$idUsuario?>","visit");
				piwikTracker.trackPageView();
			}catch(err){}
		}
	});	
});
$("#dobleFormulario-registro-enviar").click(function(e){
	e.preventDefault();
	$.ajax({
		url: 'ajax/user.php?checkMail='+$("#registro_mail").val(),
		success: function(res){
			if (res === "false"){
				alert("El mail seleccionado ya esta registrado");
				return false;
			}
		}
	});
	var data = $("#dobleFormulario-registro").serialize();
	$("#dobleFormulario-registro input").each(function(index){
		if ($(this).val() == ""){
			alert("Hay formularios en blanco");
		}
	});
	$.post("ajax/user.php?new", data, function(res){
		dialog.destroy();
		$("#usuario").load("index.php #usuario");
		createGrowl("¡Te has registrado con exito!<br />Ya podes iniciar sesión", false);
	});
});
</script>