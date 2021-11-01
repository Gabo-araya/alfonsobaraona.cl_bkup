<?php
session_start();
ob_start();
include('inc/inc.inc.php');
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== 'Pearl Jam') {
  $_SESSION = array();
  header('Location: index.php');
  exit;
  }
if (!isset($_SESSION['type'])) {
  $_SESSION = array();
  header('Location: index.php');
  exit;
  }
switch ($_SESSION['type']){
  case 'admin':
    break;
  default:
    $_SESSION = array();
    header('Location: index.php');
    exit;
  }
$title = "Registro de Descargas de libro.";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
//$tabla = $pre."img";
disp_inicio_info($title,'login_log');


?>
      <fieldset>
        <legend class="login_log">Registro de Descargas de libro</legend>
        <div class="pre">
<?php
  $login_log = "../mail_libro.log";
//  echo $lineas;
  if(file_exists($login_log)){
    $archivo = file($login_log);
    $lineas = count($archivo);
    for($i=0; $i < $lineas; $i++){
      echo nl2br(str_output($archivo[$i]));
      echo "<br />";
    }
  }
  else{
    echo "No hay descargas del libro aún.";
  }

?>
        </div>
      </fieldset>
<?php

  disp_fin_info();
  disp_footer_admin($pre);
?>