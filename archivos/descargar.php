<?php
include('../inc.inc.php');
$thisdir = strrev(strstr(strrev($_SERVER['SCRIPT_FILENAME']),'/'));
$thisdir = scandir($thisdir);
arsort($thisdir);
  $f = str_input($_GET['f']);
  $ext = str_replace(".", "", strrchr($f, "."));
  if(strpos($f,'/')!==false){
    die("<h1>No es posible navegar por otros directorios.</h1>");
  }
  if(!in_array($f,$thisdir)){
    die("<h1>ERROR</h1><p><strong>No es posible descargar el archivo.</strong></p>");
  }
  if(($ext == "php") OR ($ext == "htaccess") OR ($ext == "htm") OR ($ext == "html") OR ($ext == "css") OR ($ext == "js") OR ($ext == "inc.php")  OR ($ext == "inc") OR ($ext == "fx") OR ($ext == "fx.php") OR ($ext == "log")){
    die("<h1>ERROR</h1><p><strong>No es posible descargar el archivo.</strong></p>");
  }
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=".$f."");
  $fp=fopen("$f", "r");
  fpassthru($fp);
//  fclose("$f");
?>