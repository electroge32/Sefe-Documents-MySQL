<?php require_once("configuration.php");
//error_reporting(0);
function formLoadFile()
{?>
<p> <form method="post" id="subirdocumento" name="subirdocumento" action="index.php" enctype="multipart/form-data">
  <fieldset>
<legend>Información del documento</legend>

<ul>
<li><label for="titulo">Titulo:</label><input id="titulo" name="titulo" type="text" value="Titulo"/></li>

<li class="textarea"><label for="descripcion">Descripción:</label><textarea id="descripcion" name="descripcion" cols="50" rows="5">Descripción</textarea></li>
<li><label for="etiqueta">Etiquetas:</label><input id="etiqueta" name="etiqueta" type="text" value="etiqueta1,etiqueta2,..."/></li>

<li><label for="documento">Documento:</label>
<input name="documento" type="file" id="documento">
</li>

</ul>
</fieldset>
<input type="submit" id="submit" name="submit" value="Subir Documento" />
<input name="upLoad" id="upLoad" type="hidden" value="formUpLoad" />
</form>
</p>
<?php
}

function formSearch($Search)
{
$buscar=htmlspecialchars(trim($Search));	
?>

<p> <form method="post" id="formSearch" name="formSearch" action="./" >
<fieldset>

<label for="search">Buscar Documentos</label><input id="search" name="search" type="text" value="<?php if($buscar) {print($buscar); } ?>" />

</fieldset>
<input type="submit" id="submit" name="submit" value="Buscar" />
<input name="formVSearch" id="formVSearch" type="hidden" value="formSearch" />
</form>
</p>

<?php
}

function formLogin()
{
?>
<form method="post" id="formLogin" name="formLogin" action="./" >
<fieldset>
<legend>Inicia sesión para acceder al sistema</legend>
<ul>
<li><label for="user">Usuario</label><input id="user" name="user" type="text" /></li>
<li><label for="password">Contraseña</label><input id="password" name="password" type="password" /></li>
</ul>
</fieldset>
<input type="submit" id="submit" name="submit" value="Inicir sesión" />
<input name="Login" id="Login" type="hidden" value="formLogin" />
</form>
<?php
}

function loginMember($user, $password)
{
$message;	
if($user && $password)
	{
	if($user=='demo'&&$password=='demo')
	{
	$member=array("member"=>array(
	"usuario"=>'demo',
	"nombre"=>'Demo',
	"id"=>'777'));
	$_SESSION["Safe-Documents"] = serialize($member);	
	$_SESSION["SESION_TIME"] = time();
	header( "Location: ./" );
	}
	else 
	{
	$message='El Usuario o la Contraseña no son validos';
	}	
	}
	else
   {
	$message='Se requiere un usuario y contraseña validos.'; 
   }	
	return $message;
}

// función para guardar documentos
function sefeFile ($arrayDoc,$documento)
{

// Sustituir especios por guion
$archivo_usuario = str_replace(' ','-',$arrayDoc[$documento]['name']); 

$tipo_archivo = $arrayDoc[$documento]['type']; 
$tamano_archivo = $arrayDoc[$documento]['size'];
$extencion = strrchr($arrayDoc[$documento]['name'],'.');

// Rutina que asegura que no se sobre-escriban documentos
$nuevo_archivo;
$flag= true;
while ($flag)
 {
$nuevo_archivo=randString();//.$extencion;
if (!file_exists(pathFiles().$nuevo_archivo))
{
$flag= false;
}
 }
//compruebo si las características del archivo son las que deseo 
try {

   if (move_uploaded_file($arrayDoc[$documento]['tmp_name'], pathFiles().$nuevo_archivo))
   { 
     //return $nuevo_archivo;
	return $vector = array ( $nuevo_archivo, $archivo_usuario );
   }
    else
     { 
     // return 'NO';
	 return $vector = array ( "NO", "NO" );
     } 


}
catch(Exception $e)
{
echo 'Error en la Función sefeFile --> lib.php ', $e->getMessage(), "\n";

exit;
}
}


// función que genera una cadena aleatoria
function randString ($length = 32)
{  
$string = "";
$possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXY";
$i = 0;
while ($i < $length)
 {    
$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
$string .= $char;    
$i++;  
}  
return $string;
}


// función que retorna la fecha y hora actual
function dateTimeMySql()
{
date_default_timezone_set('America/Bogota');
$ano = date('Y');
$mes = date('m');
$dia = date('j');
$hora = date('H');
$min = date('i');
$sec = date('s');
// mysql format 'YYYY-MM-DD HH:MM:SS'
$datemysql=$ano.'-'.$mes.'-'.$dia.' '.$hora.':'.$min.':'.$sec; 

//echo '<br> formato MySql '.$datemysql;
return $datemysql;
}


function nameFile($cod_file)
{
$vname_files=null;

$db_conn = conexion();
if ($db_conn)
{	
$resultado = mysql_query("SELECT doc.url,doc.file_name
                          FROM documentos doc
                          WHERE doc.iddocumentos='$cod_file'");
while($row = mysql_fetch_row($resultado))
     {
      $vname_files[0]=$row[0];
      $vname_files[1]=$row[1];
     }

return $vname_files;

}

}

// muestra los ultimos 10 documentos publicados
function newsDocumentos()
{

$db_conn = conexion();
if ($db_conn)
{
?> <p><h2>Ultimas publicaciones</h2> </p> <?php
$resultado = mysql_query("SELECT
 	Titulo,
	Descripcion,
  	iddocumentos,
  	Feha
  from documentos  ORDER BY iddocumentos DESC Limit 10");

 while ($row = mysql_fetch_row($resultado))
 {
 /****************************************************************************************/
 ?>
  <p><h3><?php echo $row[0]; ?></h3>
         <?php echo $row[1]; ?>
    <br /><a href="download.php?doc=<?php echo $row[2]; ?>" target="_blank">Descargar</a>|<a href="./?del=<?php echo $row[2]; ?>" >Eliminar</a>| Fecha de publicación:  <?php echo $row[3]; ?>
  </p>
 
  <?php
 /****************************************************************************************/
}

}

}


function busqueda($buscar)
{

$buscar=htmlspecialchars(trim($buscar));

  $trozos=explode(" ",$buscar);
   $numero=count($trozos);
  if ($numero==1) // Algoritmo de búsqueda con una palabra 
  {
   $consulta="SELECT
 	Titulo,
	Descripcion,
  	iddocumentos,
  	Feha
FROM documentos
WHERE Titulo LIKE '%$buscar%'
 OR Descripcion LIKE '%$buscar%'
 OR palabras_clave LIKE '%$buscar%'
 OR file_name LIKE '%$buscar%' ";
 
  } elseif ($numero>1) // Algoritmo de búsqueda con más de una palabra
  {
 
  $consulta="SELECT
 	   titulo,
	   descripcion,
  	 iddocumentos,
  	 Feha
FROM documentos
WHERE MATCH(titulo,descripcion,palabras_clave) AGAINST ('$buscar')";
/* ALTER TABLE documentos ADD FULLTEXT(titulo,descripcion,palabras_clave); */

}
           

$db_conn = conexion();
if ($db_conn)
{

$resultado = mysql_query($consulta) or die (mysql_error());

while ($row = mysql_fetch_row($resultado))
 {
 
 /****************************************************************************************/

 /****************************************************************************************/
 ?>
    <p><h3><?php echo $row[0]; ?></h3>
         <?php echo $row[1]; ?>
    <br /><a href="download.php?doc=<?php echo $row[2]; ?>" target="_blank">Descargar</a>|<a href="./?del=<?php echo $row[2]; ?>" >Eliminar</a>| Fecha de publicación:  <?php echo $row[3]; ?>
  </p>
 
 <?php
 /****************************************************************************************/
}
}

}



function reg_document($titulo,$descripcion,$palabras_clave,$idusuario, $file,$fecha,$file_name)
{
$db_conn = conexion();
if ($db_conn)
{


try {

$resultado = mysql_query("INSERT INTO documentos
	(
	Titulo,
	Descripcion,
	palabras_clave,
	idUsuarios,
	url,
	Feha,
	file_name
	)
VALUES 
	(
	'$titulo',
	'$descripcion',
	'$palabras_clave',
     '$idusuario',
	'$file',
	'$fecha',
	'$file_name'	
	);");
	
	return true;
	}
	catch(Exception $e)
	{
	return false;

	
	}
	}
	else { echo "Error de conexión"; exit; }
	}	
	
	function del_document($idDocument)
{
$db_conn = conexion();
if ($db_conn)
{
try {

$resultado = mysql_query("DELETE FROM documentos WHERE documentos.iddocumentos ='$idDocument'");
	
	return true;
	}
	catch(Exception $e)
	{
	 die("Se produjo un error al intentar eliminar el documento => " . $e->getMessage());

	}
	}
	else { echo "Error de conexión"; exit; }

}		
	
function del_file($cod_file)
{
if (exist_file($cod_file))
  {	
   try
      {
$vname_file=nameFile($cod_file);

 if (!unlink(pathFiles().$vname_file[0]))
	     {
          return false;
         }
		else { return true; }
      }
    catch (Exception $e) { return false; }
 }
else {return true;}
}

function exist_file($cod_file)
{
$vname_file=nameFile($cod_file);
if (file_exists(pathFiles().$vname_file[0]))
{return true; }
else {return false; }
}
	
?>