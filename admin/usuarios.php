<?php
session_start();
ob_start();
include('inc/inc.inc.php');
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== 'Pearl Jam') {
  $_SESSION = array();
  header('Location: index.php');
  exit;
  }
if (!isset($_SESSION['type']) || $_SESSION['type'] !== 'admin') {
  $_SESSION = array();
  header('Location: index.php');
  exit;
  }
$title = "Usuarios";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."usuarios";

// Categorías de usuarios
      $cat_usuarios = mysql_query("SELECT cat_id,cat_usuarios FROM ".$pre."cat WHERE cat_usuarios IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_usuarios); ++$j) {
          $cat_id = mysql_result($cat_usuarios,$j,"cat_id");
          $cat_users[$cat_id] = str_output(mysql_result($cat_usuarios,$j,"cat_usuarios"));
        }
      asort($cat_users);
      //echo disp_array_asoc($cat_artic);


disp_inicio_info($title,'usuarios');

//  js_delete();
  if (isset($_GET['act'])
      AND ($_GET['act'] == 'n'
      OR $_GET['act'] == 'vn'
      OR $_GET['act'] == 'm'
      OR $_GET['act'] == 'vm'
      OR $_GET['act'] == 'ce'
      OR $_GET['act'] == 'e'
      OR $_GET['act'] == 'p'
      OR $_GET['act'] == 'dp'
      OR $_GET['act'] == 'vp'
      OR $_GET['act'] == 'vnp'
      OR $_GET['act'] == 'd'
      OR $_GET['act'] == 'nd'
      OR $_GET['act'] == 'vd'
      OR $_GET['act'] == 'vnd'
      OR $_GET['act'] == 'v')){}
   else {draw_additem("Usuario");}



if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n':
    //código de ingreso de elemento nuevo
?>
      <fieldset>
        <legend>Agregar <?php echo $title; ?></legend>
        <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" >
        <table width="100%"  border="0"  align="center">
          <?php echo draw_input("Nombre: ",'user','text','','','table_horiz',50,'req'); ?>
          <?php echo draw_input("Password: ",'pass','password','','','table_horiz',50,'req'); ?>
          <?php echo draw_select("Privilegios: ",'type',$cat_users,'','table_horiz','','req'); ?>
        </table>
        <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
        </form>
      </fieldset>

<?php
      break;

//Validación de Elemento Nuevo
    case 'vn':
    //código de validación de elemento nuevo
    $user = str_input($_POST['user']);
    $pass = str_input($_POST['pass']);
    $type = str_input($_POST['type']);

    if (empty($user) OR empty($pass) OR empty($type) /*OR empty($seccion_univ) OR empty($seccion_txt)*/){
      echo "<div class=\"box_validation\">";
      if (!empty($user)) {
        $datos = mysql_query("SELECT usuarios_id FROM ".$tabla." WHERE usuarios_nombre='".$user."'") or die(mysql_error());
        if (mysql_num_fields($datos) > 0) {
           echo "Este usuario ya existe! Debe escribir un nombre de usuario distinto. <br />";
        }
      }
      if (empty($user)) echo "Debe escribir un nombre de usuario. <br />";
      if (empty($pass)) echo "Debe escribir un password. <br />";
      if (empty($type)) echo "Debe escoger los privilegios del usuario. <br />";
      echo "</div>";
?>
      <fieldset>
        <legend>Agregar <?php echo $title; ?></legend>
        <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" >
        <table width="100%"  border="0"  align="center">
          <?php echo draw_input("Nombre: ",'user','text',$user,'','table_horiz',50,'req'); ?>
          <?php echo draw_input("Password: ",'pass','password','','','table_horiz',50,'req'); ?>
          <?php echo draw_select("Privilegios: ",'type',$cat_users,$type,'table_horiz','','req'); ?>
        </table>
        <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
        </form>
      </fieldset>

<?php
    }
    else {
      if (!empty($user)) {
        $datos = mysql_query("SELECT usuarios_id FROM ".$tabla." WHERE usuarios_nombre='".$user."'") or die(mysql_error());
        //echo mysql_num_rows($datos);
        if (mysql_num_rows($datos) !== 0) {
           echo "<div class=\"box_validation\">";
           echo "<h2>Este usuario ya existe! Debe escribir un nombre de usuario distinto. </h2>";
           echo "</div>";
           echo "<form action=\"".$phpself."?&amp;act=n\" method=\"POST\">
           <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
        }
        else {
          // Insertar
          if ($_SERVER['REQUEST_METHOD']=='POST'){
            $user = str_input($_POST['user']);
            $pass = str_input(md5($_POST['pass']));
            $type = str_input($_POST['type']);
            foreach ($cat_users as $key => $value) {if ($key == $type) {$type = $value;}}
            $sql_data = array('usuarios_nombre' => $user,
                              'usuarios_password' => $pass,
                              'usuarios_tipo' => $type);
            sql_input($tabla,$sql_data,'insert','');
            echo "<form action=\"".$phpself."\" method=\"POST\">
            <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
          }
        }
      }
    }
      break;

//Modificación
    case 'm':
    //código de modificación de elemento
    $id = str_input($_GET['id']);
    $query = mysql_query("SELECT * FROM ".$tabla." WHERE usuarios_id=".$id."") or die(mysql_error());
      $user = str_output(mysql_result($query,0,'usuarios_nombre'));
      $pass = str_output(mysql_result($query,0,'usuarios_password'));
      $type = str_output(mysql_result($query,0,'usuarios_tipo'));
?>

      <fieldset>
        <legend>Modificar <?php echo $title; ?></legend>
        <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" >
        <table width="100%"  border="0"  align="center">
          <?php echo draw_input("Nombre: ",'user','text',$user,'','table_horiz',50,'req'); ?>
          <?php echo draw_input("Password: ",'pass','password','','','table_horiz',50,'req'); ?>
          <?php echo draw_select("Privilegios: ",'type',$cat_users,$type,'table_horiz','','req'); ?>
        </table>
        <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
        <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
        </form>
      </fieldset>
<?php
      break;

//Validación de Modificación
    case 'vm':
    //código de validación de modificación de elemento
    $id = str_input($_POST['id']);
    $user = str_input($_POST['user']);
    $pass = str_input($_POST['pass']);
    $type = str_input($_POST['type']);

    if (empty($user) OR empty($pass) OR empty($type)){
      echo "<div class=\"box_validation\">";
      if (empty($user)) echo "Debe escribir un nombre de usuario. <br />";
      if (empty($pass)) echo "Debe escribir un password. <br />";
      if (empty($type)) echo "Debe escoger los privilegios del usuario. <br />";
      echo "</div>";
?>
      <fieldset>
        <legend>Modificar <?php echo $title; ?></legend>
        <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" >
        <table width="100%"  border="0"  align="center">
          <?php echo draw_input("Nombre: ",'user','text',$user,'','table_horiz',50,'req'); ?>
          <?php echo draw_input("Password: ",'pass','password','','','table_horiz',50,'req'); ?>
          <?php echo draw_select("Privilegios: ",'type',$cat_users,$type,'table_horiz','','req'); ?>
        </table>
        <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
        <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
        </form>
      </fieldset>

<?php
    }
    else {
    // Actualizar
      if ($_SERVER['REQUEST_METHOD']=='POST'){
        $id = str_input($_POST['id']);
        $user = str_input($_POST['user']);
        $pass = str_input(md5($_POST['pass']));
        $type = str_input($_POST['type']);
        foreach ($cat_users as $key => $value) {if ($key == $type) {$type = $value;}}
        $sql_data = array('usuarios_nombre' => $user,
                          'usuarios_password' => $pass,
                          'usuarios_tipo' => $type);
        sql_input($tabla,$sql_data,'update',"usuarios_idx".$id);
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
    }
      break;

//Confirmar eliminación
    case 'ce':
    //código de confirmación de eliminación de elemento
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT usuarios_nombre FROM ".$tabla." WHERE usuarios_id=".$id."") or die(mysql_error());
          $user = str_output(mysql_result($datos,0,"usuarios_nombre"));

?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este usuario?</p>
        </div>
          <div align="center">
          <strong>Usuario:</strong> <?php echo $user; ?></p>
          <br /><br />
          <table width="100" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td align="left">
                <form action="<?php echo $phpself; ?>" method="POST"><input type="submit" value="&laquo; No" /></form>
              </td>
              <td>&nbsp;</td>
              <td align="right">
                <form action="<?php echo $phpself; ?>?&amp;act=e" method="POST">
                <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
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
        $id = str_input($_POST['id']);
          if(mysql_query("DELETE FROM ".$tabla." WHERE usuarios_id='".$id."'") or die(mysql_error())){
?>
          <div class="box_success">El registro se eliminó exitosamente.</div>
          <form action="<?php echo $phpself; ?>" method="post">
          <div align="center"><br /><input type="submit" value="Continuar &raquo;" /></div></form>
<?php     }
          else { ?>
          <div class="box_error">Ocurrió un error. El registro no se pudo eliminar.</div>
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
  $datos = mysql_query("SELECT usuarios_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY usuarios_id DESC") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?></legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="20">N&ordm;</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      for($j=0; $j<mysql_num_rows($datos); ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"usuarios_id");
          $user = str_output(mysql_result($datos,$j,"usuarios_nombre"));
          $type = str_output(mysql_result($datos,$j,"usuarios_tipo"));
          $p = $j;
          ++$p;

?>
          <tr>
            <td valign="top" class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Usuario:</strong> <?php echo $user; ?></p>
              <p><strong>Privilegios:</strong> <?php echo $type; ?></p>
            </td>
<?php disp_icons_users($celda,$php_self,$id); ?>
          </tr>
<?php
          }
?>
      </table>
      </fieldset>
<?php
  }
}

  if (isset($_GET['act'])
      AND ($_GET['act'] == 'n'
      OR $_GET['act'] == 'vn'
      OR $_GET['act'] == 'm'
      OR $_GET['act'] == 'vm'
      OR $_GET['act'] == 'ce'
      OR $_GET['act'] == 'e'
      OR $_GET['act'] == 'p'
      OR $_GET['act'] == 'dp'
      OR $_GET['act'] == 'vp'
      OR $_GET['act'] == 'vnp'
      OR $_GET['act'] == 'd'
      OR $_GET['act'] == 'nd'
      OR $_GET['act'] == 'vd'
      OR $_GET['act'] == 'vnd'
      OR $_GET['act'] == 'v')){}
   else {draw_additem("Usuario");}

  disp_fin_info();
  disp_footer_admin($pre);

?>