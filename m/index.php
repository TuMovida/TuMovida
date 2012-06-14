<?php
include 'lib/jqmPhp.php';

$j = new jqmPhp();
$j->head()->title("¡TuMovida!");

function login()
{
	$p = new jqmPage("login");
	$p->theme("a");
	$p->header()->addButton("Volver", "#", "d", "home");
	$p->title("¡TuMovida!");	
	$p->add(new jqmButton("loginBt", "b", "Login"));
	return $p;
}
function registrar()
{
	$p = new jqmPage("registro");
	$p->theme("a");
	$p->title("¡TuMovida!");	
	return $p;
}

$j->addPage(login());
echo $j;