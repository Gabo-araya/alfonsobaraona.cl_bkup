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
  case 'editor':
    break;
  default:
    $_SESSION = array();
    header('Location: index.php');
    exit;
}
$title = "Secciones de Información";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."info";
disp_inicio_info($title,'secciones');

if (isset($_GET['act'])){
  switch ($_GET['act']){

//Modificación
    case 'm':
      $secc = str_input($_GET['secc']);
      $datos = mysql_query("SELECT ".$secc." FROM ".$tabla."") or die(mysql_error());
      $txt = str_output(mysql_result($datos,0,$secc));
?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo ucfirst(trim(str_replace('_', ' ',(strstr(mysql_field_name($datos,0),'_'))))); ?> </legend>
      <table width="100%"  border="0"  align="center">
      <tr>
        <td><label for="txt_new">Información: </label><?php echo draw_req(); ?><br /><br /></td>
      </tr>
      <tr>
        <td align="center"><?php echo draw_textarea('txt_new',$txt,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','secc','hidden',$secc); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
    </fieldset>
    </form>
<?php break;

    case 'vm':
//Validación de Modificación
    //código de validación de modificación de elemento

    $secc = str_output($_POST['secc']);
    $txt_new = str_output($_POST['txt_new']);

    if (empty($txt_new)){
      echo "<div class=\"box_validation\">";
      echo "No puede enviar el formulario en blanco. <br />";
      echo "</div>";

      $datos = mysql_query("SELECT ".$secc." FROM ".$tabla."") or die(mysql_error());
      $txt = str_output(mysql_result($datos,0,$secc));
?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo ucfirst(trim(str_replace('_', ' ',(strstr(mysql_field_name($datos,0),'_'))))); ?> </legend>
      <table width="100%"  border="0"  align="center">
      <tr>
        <td><label for="txt_new">Información: </label><?php echo draw_req(); ?><br /><br /></td>
      </tr>
      <tr>
        <td align="center"><?php echo draw_textarea('txt_new',$txt,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','secc','hidden',$secc); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
    </fieldset>
    </form>
<?php
    }
    else {
        $sql_data = array($secc => $txt_new);
        //echo disp_array_asoc($sql_data);
        sql_input($tabla,$sql_data,'update',"info_idx0");
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
    }
      break;

    default:
      $_SESSION = array();
      header('Location: index.php');
      exit;
    }
  }
else {

      $datos = mysql_query("SELECT * FROM ".$tabla."") or die(mysql_error());

?>
      <fieldset>
        <legend><?php echo $title; ?></legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      for($j=1; $j<mysql_num_fields($datos); ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $secc = mysql_field_name($datos,$j);
?>
          <tr>
            <td class="<?php echo $celda; ?>">
              <p><?php echo ucfirst(trim(str_replace('_', ' ',(strstr(mysql_field_name($datos,$j),'_'))))); ?>
              </p>
            </td>

<?php disp_icons_seccprinc($celda,$php_self,$secc,"m"); ?>
<?php     } ?>

        </table>
      </fieldset>
<?php
}

  disp_fin_info();
  disp_footer_admin($pre);
?>