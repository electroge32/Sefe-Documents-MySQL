<?php session_start(); 
require_once("lib.php");
/* ************************* Cerrar sesión *********************************/
if(isset($_GET['req']))
{
$req=$_GET['req'];	
if($req=='logout'&&isset($_SESSION["Safe-Documents"]))
  {
	 session_unset(); 
     $result_dest = session_destroy();
     header( "Location: ./" );	
  }
}
/* ************************* Crear sesión *********************************/
 $message=-1;

if(isset($_POST['Login'])=='formLogin' &&!isset($_SESSION["Safe-Documents"]))
 {
 $message=loginMember($_POST['user'], $password=$_POST['password']);
 }

/* **************************** Validar sesión ******************************/
$session=false;
if(isset($_SESSION["Safe-Documents"]))
  {
	  
$antes = $_SESSION["SESION_TIME"];
// Si lleva más de 1 minuto (60 segundos)
if (time()-$antes > 700) {
// Libramos la sesion
session_unset();
session_destroy();

$session=false;
}
else 
{
 $session=true;
 $_SESSION["SESION_TIME"] = time();
}
  	
   } 
 ?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Safe Documents</title>
<link rel="stylesheet" type="text/css" href="./css/global.css" />
<link href="./favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>

<body>

<div id="contenedor">

<div id="cabecera">
<p><a href="./">Safe Documents</a></p>
<ul>
<?php if($session) { ?>
 
<li><a href="upload.html">Subir un documento</a></li>
<li><a href="search.html">Buscar documentos</a></li>
<li><a href="logout.html">Salir</a></li>

<?php }  ?>

</ul>
</div>
<div id="contenido"></div>

<?php
if($message!=-1)
 { ?>
<h2><?php echo $message; ?></h2>	
  <?php 
 }

if(!$session) 
{
formLogin();	
}

else {

if($_GET|| $_POST)
{
$request;	
if(isset($_GET['req']))
{
$request=$_GET['req'];

switch($request)
{
	case'upload';
	formLoadFile();
	break;
	
	case'search';
	$Search=null;
	formSearch($Search);
	break;
	default;
	?>
<h2>El elemento solicitado no esta disponible.</h2>	
<?php
	
	break;
}
}

// Eliminar documento
if(isset($_GET['del']))
{
$request=$_GET['del'];
if(del_file($request))
{
del_document($request);
echo '<h2> Elemento eliminado correctamente </h2>';
}
else { echo '<h2> Elemento no eliminado </h2>';}
}	
// Buscar
if(isset($_POST['formVSearch']))
{
if($_POST['formVSearch']=='formSearch')
{ 

$Search=htmlspecialchars($_POST['search']);

    formSearch($Search);
 if($Search)
  {	
	echo '<p><h2>Resultados de la busqueda</h2> </p>';
	
	$Search=mysql_escape_string($Search);
    busqueda($Search);
	
  } else { echo '<p><h2>Usted debe proporcionar una frase o palabra para buscar</h2> </p>'; }

}
}
// Subir documento
if(isset($_POST['upLoad'])) 
{

	if($_POST['upLoad']=='formUpLoad')
	{
	$titulo =mysql_escape_string(htmlspecialchars($_POST['titulo']));
	$descripcion=mysql_escape_string(htmlspecialchars($_POST['descripcion']));
	$etiqueta=mysql_escape_string(htmlspecialchars($_POST['etiqueta']));
	
	$vectorFile=sefeFile($_FILES,"documento");
	

    $fecha=dateTimeMySql();
	 //
 if(reg_document($titulo,$descripcion,$etiqueta,777, $vectorFile[0],$fecha,trim($vectorFile[1])))
 echo "<p><h2>Documento Registrado Correctamente</h2> </p>";
 else 
 echo "<p><h2>Fallo el registro del documento</h2> </p>";
 //
		
 }
}


}
else
{ 
$Search=null;
formSearch($Search);
newsDocumentos();

}
}


?>

<div id="pie"><p>Electroge32 todos los derechos reservados.</p></div>
</div>
</body>
</html>