<?php
	$id = (isset($_GET['id'])) ? $_GET['id'] : NULL;
?>
<div class='formulario dialogo-enviarInvitacion'>
<?php
if(!isset($_GET['step']) || $_GET['step']=1)
{
?>
	<div class=''>
		Invitación privada
	</div>
	<div class=''>
		Invitación anónima
	</div>
<?php
}
elseif($_GET['step']=2)
{
?>
	<div class=''>
	</div>
<?php
}elseif($_GET['step']=3)
{
?>
	<div class=''>
	</div>
<?php
}
?>
</div>