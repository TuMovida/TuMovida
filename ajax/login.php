<div class="left-side">
	<img class='profile-pic' src="images/user_profiles/default.png" alt=''/>
</div>
<div class="right-side">
	<form id="login" action="ajax/user.php?login" method="POST" class="formulario">
		<fieldset>
			<label>Mail: </label><input type="email" name="mail" required="required" />
			<label>Contraseña: </label><input type="password" name="password" required="required"  />
			<input type="submit" value="Entrar" id="login-enviar" class="button"/>
		</fieldset>
	</form>
</div>

<script type="text/javascript">
$("form#login fieldset #login-enviar").click(function(e){
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
	/*if ($("form#login fieldset input[type='email']").val() == '' || 
		!emailReg.test($("form#login fieldset input[type='email']").val().toString())){
		$("form#login fieldset input[type='email']").qtip($.extend({}, errorTip, {
			content: 'Introduce un mail válido'
		}));
		return false;
	}
	if ($("form#login fieldset input[type='password']").val() == ''){
		$("form#login fieldset input[type='password']").qtip($.extend({}, errorTip, {
			content: 'Falta la contraseña'
		}));
		return false;
	}*/
	var data = $("form#login").serialize();
	$.post("ajax/user.php?login", data, function(res){
		if (res === "Error en el mail o la contraseña"){
			alert("El mail y/o la contraseña son incorrectos");
			try{
				piwikTracker.setCustomVariable(2, "Errores", "Mail o contraseña incorrecta en login","visit");
				piwikTracker.trackPageView();
			}catch(err){}
		}else{
			$("#usuario").load("index.php #usuario");
			dialog.destroy(function(){
				document.location.href = document.location.href;
				document.location.reload();
			});
			try{
				<?php (isset($_SESSION['idusuario'])) ? $idUsuario = $_SESSION['idusuario'] : $idUsuario = "Anónimo"; ?> 
				piwikTracker.setCustomVariable(1, "usuario", "<?=$idUsuario?>","visit");
				piwikTracker.trackPageView();
			}catch(err){}
		}
	});	
});
</script>