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
$title = "Categorías";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."cat";

// Categorías
      $categories = mysql_query("SELECT * FROM ".$tabla." ") or die(mysql_error());
      $num_fields = mysql_num_fields($categories);
      for($j=1; $j<$num_fields; ++$j) {
        $categories_name_key = mysql_field_name($categories, $j);
        $categories_name[$categories_name_key] = ucfirst(trim(str_replace('_', ' ',(strstr(mysql_field_name($categories, $j),'_')))));
      }

disp_inicio_info($title,'categorias');

  if (isset($_GET['act'])
      AND $_GET['act'] == 'v'){draw_additem("Categoría");}

if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre de Categoría: ",'cat_nombre','text',$cat_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Categoría: ",'cat_field',$categories_name,$cat_field,'table_horiz','','req'); ?>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $cat_nombre = str_input($_POST['cat_nombre']);
      $cat_field = str_input($_POST['cat_field']);

      if (empty($cat_nombre) OR empty($cat_field)){
        if (empty($cat_nombre) OR empty($cat_field)){$validation .= "<div class=\"box_validation\">";}
        if (empty($cat_nombre)) $validation .= "Debe escribir un nombre para la categoría.<br />";
        if (empty($cat_field)) $validation .= "Debe seleccionar una sección para la categoría. <br />";
        if (empty($cat_nombre) OR empty($cat_field)){$validation .= "</div>";}
        echo $validation;
?>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre de Categoría: ",'cat_nombre','text',$cat_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Categoría: ",'cat_field',$categories_name,$cat_field,'table_horiz','','req'); ?>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
// Insertar
        if ($_SERVER['REQUEST_METHOD']=='POST'){
              $insert = "INSERT INTO ".$tabla." SET ".$cat_field." = '".$cat_nombre."'";
              if(mysql_query("$insert") /*or die(mysql_error())*/){
                echo "<div class=\"box_success\">La información ha sido ingresada exitosamente.</div>";
              }
              else {echo "<div class=\"box_error\">Ha ocurrido un error y no se han ingresado los datos. Avise al administrador.</div>";}
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
    }
      break;

//Modificación
    case 'm':
        $cat = str_input($_GET['cat']);
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT cat_id,".$cat." FROM ".$tabla." WHERE cat_id=".$id."") or die(mysql_error());
        $cat_nombre = str_output(mysql_result($datos,0,$cat));
?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre de Categoría: ",'cat_nombre','text',$cat_nombre,'','table_horiz',50,'req'); ?>
      </table>
      <?php echo draw_input('','cat','hidden',$cat,'','','',''); ?>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
    </fieldset>
    </form>
<?php break;

    case 'vm':
//Validación de Modificación
    //código de validación de modificación de elemento
      $cat_nombre = str_input($_POST['cat_nombre']);
      $cat = str_input($_POST['cat']);
      $id = str_input($_POST['id']);

        if (empty($cat_nombre)){
        echo "<div class=\"box_validation\">Debe escribir un nombre para la categoría.<br /></div>";

?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre de Categoría: ",'cat_nombre','text',$cat_nombre,'','table_horiz',50,'req'); ?>
      </table>
      <?php echo draw_input('','cat','hidden',$cat,'','','',''); ?>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('',submit,submit,Agregar); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
// Insertar
        if ($_SERVER['REQUEST_METHOD']=='POST'){
              $update = "UPDATE ".$tabla." SET ".$cat."='".$cat_nombre."' WHERE cat_id='".$id."'";
              if(mysql_query("$update") or die(mysql_error())){
                echo "<div class=\"box_success\">La información ha sido ingresada exitosamente.</div>";
              }
              else {echo "<div class=\"box_error\">Ha ocurrido un error y no se han ingresado los datos. Avise al administrador.</div>";}
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
    }
      break;

    case 'ce':
//Confirmar eliminación
    //código de confirmación de eliminación de elemento
        $cat = str_input($_GET['cat']);
        $id = str_input($_GET['id']);
?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar esta categoría?</p>
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
                <?php echo draw_input('','cat','hidden',$cat,'','','',''); ?>
                <?php echo draw_input('','id','hidden',$id,'','','',''); ?>

                <input type="submit" value="Sí &raquo;" /></form>
              </td>
            </tr>
          </table>
          </div>
      </fieldset>
<?php
      break;

    case 'e':
//Eliminar imagen y registro
    //código de eliminación de elemento
      if($_SERVER['REQUEST_METHOD']=='POST'){
        $id = str_input($_POST['id']);
        $cat = str_input($_POST['cat']);
          if(mysql_query("UPDATE ".$tabla." SET ".$cat."=NULL WHERE cat_id='".$id."'") or die(mysql_error())){
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

    case 'v':
//Visualizar
    //código de visualización de elementos
      $id = str_input($_GET['id']);
      $datos = mysql_query("SELECT cat_id,".$id." FROM ".$tabla." WHERE ".$id." IS NOT NULL ORDER BY cat_id ") or die(mysql_error());
      if (mysql_num_rows($datos) == 0) {
        draw_noitems();
      }
      else {
?>
      <fieldset>
        <legend>Visualizar <?php echo $title; ?></legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="20">N&ordm;</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
        for($j=0; $j<mysql_num_rows($datos); ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $cat_id = mysql_result($datos,$j,"cat_id");
          $categoria = str_output(mysql_result($datos,$j,$id));
          $p = $j;
          $p++;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><?php echo $categoria; ?></p>
            </td>
        <?php disp_icons_cat($celda,$php_self,$id,$cat_id,"m"); ?>
          </tr>
<?php
        }
?>
        </table>
      </fieldset>

<?php
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
            <th width="20">N&ordm;</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      $num_fields = mysql_num_fields($datos);
      for($j=1; $j<$num_fields; ++$j) {
        $celda = (($j % 2) == 0) ? "celda1" : "celda2";
        $id = mysql_field_name($datos, $j);

?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $j; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo ucfirst(trim(str_replace('_', ' ',(strstr(mysql_field_name($datos,$j),'_'))))); ?>
              </a></p>
            </td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" /></a><br />
            </td>
          </tr>
<?php
      }
?>
        </table>
      </fieldset>
<?php
  //}
}

  if (isset($_GET['act'])
      AND $_GET['act'] == 'v'){draw_additem("Categoría");}

  disp_fin_info();
  disp_footer_admin($pre);
?>