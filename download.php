<?php error_reporting(0); session_start();
require_once("./lib.php");

//Valida si el usuario esta logueado

 if(isset($_SESSION["Safe-Documents"]))
  {

// captura las varibles del enlace
if(isset($_GET["doc"]))
{

$cod_file=htmlspecialchars($_GET["doc"]);


//identifica la ruta 
$path=pathFiles();

// varibles nombre de archivo
$Vfile=null;

$Vfile=nameFile($cod_file);
	
if($Vfile[0])
{
    $vBarras = array("/", "\\");
    $sDocumento =  $path.str_replace($vBarras, "_", $Vfile[0]);
   
	    
    if (file_exists($sDocumento))

    {	
        header("Content-type: application/force-download");
        header("Content-Disposition: attachment; filename=".basename($Vfile[1]));
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($sDocumento));
        readfile($sDocumento);
    }
    else
    {
        echo "<br>El documento solicitado, no esta disponible, por favor póngase en contacto con el administrador del sistema. ";
    }
}
else
{
        echo "<br>El documento solicitado, no existe, por favor póngase en contacto con el administrador del sistema. ";
	}
  }
  else
  {
header ("Location: ./");
	  }
  
  }
  else
  {
header ("Location: ./");
	  }
?> 