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
$title = "Registro de Intentos de Ingreso al Sistema";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
//$tabla = $pre."img";
disp_inicio_info($title,'login_log');

if (isset($_GET['act'])){
  switch ($_GET['act']){

//Confirmar eliminación
    case 'ce':
    //código de confirmación de eliminación de elemento
?>
      <fieldset>
        <legend>Resetear Registro de Intentos de Ingreso al Sistema</legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea resetear el Registro de Intentos de Ingreso al Sistema?</p>
        </div>
          <div align="center">
          <table width="100" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td align="left">
                <form action="<?php echo $phpself; ?>" method="POST"><input type="submit" value="&laquo; No" /></form>
              </td>
              <td>&nbsp;</td>
              <td align="right">
                <form action="<?php echo $phpself; ?>?&amp;act=e" method="POST">
                <?php echo draw_input('','reset','hidden','1','','','',''); ?>
                <input type="submit" value="Sí &raquo;" /></form>
              </td>
            </tr>
          </table>
          </div>
      </fieldset>
<?php
      break;

//Eliminar imagen y registro
    case 'e':
    //código de eliminación de elemento
      if($_SERVER['REQUEST_METHOD']=='POST'){
        $login_reset = str_input($_POST['reset']);
          if(unlink('login.log')){
?>
          <div class="box_success">El registro se reseteó exitosamente.</div>
          <form action="<?php echo $phpself; ?>" method="post">
          <div align="center"><br /><input type="submit" value="Continuar &raquo;" /></div></form>
<?php     }
          else { ?>
          <div class="box_error">Ocurrió un error. El registro no se pudo resetear.</div>
          <form action="<?php echo $phpself; ?>" method="post">
          <div align="center"><br /><input type="submit" value="Continuar &raquo;" /></div></form>
<?php     }
      }
      break;

    default:
      $_SESSION = array();
      header('Location: index.php');
      exit;
    }
  }
else {
?>
      <fieldset>
        <legend class="loging_log">Registro de Intentos de Ingreso al Sistema</legend>
        <div class="pre">
<?php
  $login_log = "login.log";
//  echo $lineas;
  if(file_exists($login_log)){
    $archivo = file($login_log);
    $lineas = count($archivo);
    for($i=0; $i < $lineas; $i++){
      echo nl2br($archivo[$i]);
    }
  }
  else{
    echo "No hay registro de intentos de ingreso erróneos.";
  }

?>
        </div>
<?php
  if(file_exists($login_log)){
?>
        <form action="<?php echo $phpself; ?>?&amp;act=ce" method="post" >
        <div align="center"><br /><?php echo draw_input('','submit','submit','Resetear Registro de Intentos de Ingreso &raquo;'); ?></div>
        </form>
<?php } ?>
      </fieldset>
<?php
}
  disp_fin_info();
  disp_footer_admin($pre);
?>