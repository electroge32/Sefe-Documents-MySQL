<?php

function conexion()
{
try { 
 	$result =mysql_pconnect("localhost", "username", "password") or die(trigger_error(mysql_error(),E_USER_ERROR)); 
   if (!$result)
    return false;
   if (!mysql_select_db("sefedocuments"))
     return false;
     return $result;
    } 

catch(PDOException $e)
 {
die("Fallo en la conexión a la base de datos " . $e->getMessage()); 

 }

}

$path=null;

// Retorna la ruta donde se encuentran los archivos de los usuarios
function pathFiles()
{
// Definir directorio donde almacenar los archivos, debe terminar en "/" 
$directorio="KWE54O31MDORBOJRFRPLMM8C7H24LQQR/";

try { 
$path="./".$directorio;	

if (!file_exists($path)) {
mkdir($path, 0755);
}

writeHtaccess($path);

return $path;
  } 

catch (Exception $e) 
 {
	 echo $e;
  return false;
 }
}


function writeHtaccess($path)
{
// htaccess documentos
if(!file_exists($path.'.htaccess'))
{
$htaccess_content="Order allow,deny
Deny from all";
$file = fopen($path.'.htaccess' , "w+");
fwrite($file, $htaccess_content);
}
// htaccess Raiz
if(!file_exists('./.htaccess'))
{
$htaccess_content="Options -Indexes
Options +FollowSymlinks
RewriteEngine on
#RewriteBase /SefeDocuments/
RewriteRule ^([a-zA-Z]+).html$ index.php?req=$1";
$file = fopen('./.htaccess' , "w+");
fwrite($file, $htaccess_content);
}

}
?>