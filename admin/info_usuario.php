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
$title = "Información del Usuario";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."usuarios";

  $generos = array("femenino" => 'Femenino',
                       "masculino" => 'Masculino');

disp_inicio_info($title,'info_usuario');

if (isset($_GET['act'])){
  switch ($_GET['act']){
//Modificación
    case 'm':
    //código de modificación de elemento
    $id = str_input($_GET['id']);
    $datos = mysql_query("SELECT * FROM ".$tabla." WHERE usuarios_id=".$id."") or die(mysql_error());
      $usuarios_nombre_completo = str_output(mysql_result($datos,0,'usuarios_nombre_completo'));
      $usuarios_email = str_output(mysql_result($datos,0,'usuarios_email'));
      $usuarios_genero = str_output(mysql_result($datos,0,'usuarios_genero'));
?>

      <fieldset>
        <legend>Modificar <?php echo $title; ?></legend>
        <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" >
        <table width="100%"  border="0"  align="center">
          <?php echo draw_input("Nombre Completo: ",'usuarios_nombre_completo','text',$usuarios_nombre_completo,'','table_horiz',50,'opc'); ?>
          <?php echo draw_input("E-mail: ",'usuarios_email','text',$usuarios_email,'','table_horiz',50,'opc'); ?>
          <?php echo draw_select("Género: ",'usuarios_genero',$generos,$usuarios_genero,'table_horiz','','opc'); ?>
        </table>
        <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
        <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
        </form>
      </fieldset>
<?php
      break;

//Validación de Modificación
    case 'vm':
    // Actualizar
      if ($_SERVER['REQUEST_METHOD']=='POST'){
        $id = str_input($_POST['id']);
        $usuarios_nombre_completo = str_input($_POST['usuarios_nombre_completo']);
        $usuarios_email = str_input($_POST['usuarios_email']);
        $usuarios_genero = str_input($_POST['usuarios_genero']);
        $sql_data = array('usuarios_nombre_completo' => $usuarios_nombre_completo,
                          'usuarios_email' => $usuarios_email,
                          'usuarios_genero' => $usuarios_genero);
        sql_input($tabla,$sql_data,'update',"usuarios_idx".$id);
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
  $datos = mysql_query("SELECT usuarios_id FROM ".$tabla." WHERE usuarios_nombre='".$_SESSION['user']."'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE usuarios_nombre='".$_SESSION['user']."' ORDER BY usuarios_id DESC") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?></legend>

      <div class="box_info">
        <p>Usted ha ingresado como [<?php echo $_SESSION['user']; ?>]
        y tiene privilegios de [<?php if($_SESSION['type'] =='admin') {echo 'administrador';} else {echo $_SESSION['type'];} ?>].</p>
      </div>

        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      for($j=0; $j<mysql_num_rows($datos); ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"usuarios_id");
          $usuarios_nombre_completo = str_output(mysql_result($datos,$j,'usuarios_nombre_completo'));
          $usuarios_email = str_output(mysql_result($datos,$j,'usuarios_email'));
          $usuarios_genero = str_output(mysql_result($datos,$j,'usuarios_genero'));
          foreach ($generos as $key => $value) {if ($key == $usuarios_genero) {$usuarios_genero = $value;}}

?>
          <tr>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre Completo: </strong><?php echo $usuarios_nombre_completo; ?></p>
              <p><strong>E-mail: </strong><?php echo $usuarios_email; ?></p>
              <p><strong>Género: </strong><?php echo $usuarios_genero; ?></p>
            </td>
<?php disp_icons_info_users($celda,$php_self,$id); ?>
          </tr>
<?php
          }
?>
      </table>
      </fieldset>
<?php
  }
}
  disp_fin_info();
  disp_footer_admin($pre);

?>