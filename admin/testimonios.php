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
$title = "Testimonios";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."testimonios";

// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

  $bin_array = array('si' => 'S&iacute;','no' => 'No');

disp_inicio_info($title,'libros');

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
   else {draw_additem("Testimonio");}

if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'test_nombre','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_select("Publicar: ",'test_pub',$bin_array,'','table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="test_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('test_text','',60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $test_nombre = str_input($_POST['test_nombre']);
      $test_text = str_input($_POST['test_text']);
      $test_pub = str_input($_POST['test_pub']);

      if (empty($test_nombre) OR empty($test_text) OR empty($test_pub)){
        if (empty($test_nombre) OR empty($test_text) OR empty($test_pub)){$validation .= "<div class=\"box_validation\">";}
        if (empty($test_nombre)) $validation .= "Debe escribir un titulo para el comentario.<br />";
        if (empty($test_pub)) $validation .= "Debe seleccionar si el comentario será publicado o no.<br />";
        if (empty($test_text)) $validation .= "Debe escribir el texto del comentario. <br />";
        if (empty($test_nombre) OR empty($test_text) OR empty($test_pub)){$validation .= "</div>";}
        echo $validation;

?>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'test_nombre','text',$test_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Publicar: ",'test_pub',$bin_array,$test_pub,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="test_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('test_text',$test_text,60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
// Insertar
        if ($_SERVER['REQUEST_METHOD']=='POST'){
              $sql_data = array('test_nombre' => $test_nombre,
                                 'test_text' => $test_text,
                                 'test_pub' => $test_pub);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'insert','');

        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
    }
      break;

//Modificación
    case 'm':
    $id = str_input($_GET['id']);
    $datos = mysql_query("SELECT * FROM ".$tabla." WHERE test_id=".$id."") or die(mysql_error());
      $test_nombre = str_output(mysql_result($datos,0,'test_nombre'));
      $test_text = str_output(mysql_result($datos,0,'test_text'));
      $test_pub = str_output(mysql_result($datos,0,'test_pub'));

?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'test_nombre','text',$test_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Publicar: ",'test_pub',$bin_array,$test_pub,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="test_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('test_text',$test_text,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
    </fieldset>
    </form>
<?php break;

    case 'vm':
//Validación de Modificación
    //código de validación de modificación de elemento
      $id = str_input($_POST['id']);
      $test_nombre = str_input($_POST['test_nombre']);
      $test_text = str_input($_POST['test_text']);
      $test_pub = str_input($_POST['test_pub']);

      if (empty($test_nombre) OR empty($test_text) OR empty($test_pub)){
        if (empty($test_nombre) OR empty($test_text) OR empty($test_pub)){$validation .= "<div class=\"box_validation\">";}
        if (empty($test_nombre)) $validation .= "Debe escribir un titulo para el comentario.<br />";
        if (empty($test_pub)) $validation .= "Debe seleccionar si el comentario será publicado o no.<br />";
        if (empty($test_text)) $validation .= "Debe escribir el texto del comentario. <br />";
        if (empty($test_nombre) OR empty($test_text) OR empty($test_pub)){$validation .= "</div>";}
        echo $validation;

?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'test_nombre','text',$test_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Publicar: ",'test_pub',$bin_array,$test_pub,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="test_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('test_text',$test_text,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden','$id','','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {

              $sql_data = array('test_nombre' => $test_nombre,
                                'test_text' => $test_text,
                                'test_pub' => $test_pub);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"test_idx".$id);
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
      break;

    case 'ce':
//Confirmar eliminación
    //código de confirmación de eliminación de elemento
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT test_nombre FROM ".$tabla." WHERE test_id=".$id."") or die(mysql_error());
          $test_nombre = str_output(mysql_result($datos,0,"test_nombre"));

?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este testimonio?</p>
        </div>
          <div align="center">
          <strong>Nombre:</strong> <?php echo $test_nombre; ?></p>
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
                <input type="submit" value="S&iacute; &raquo;" /></form>
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
          if(mysql_query("DELETE FROM ".$tabla." WHERE test_id='".$id."'") or die(mysql_error())){
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

    case 'p':
//Publicar
    //código de publicación de elemento
      $id = str_input($_GET['id']);
      $sql_data = array('test_id' => $id,
                        'test_pub' => 'si');
      sql_input($tabla,$sql_data,'update',"test_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'dp':
//Despublicar
    //código de despublicación de elemento
      $id = str_input($_GET['id']);
      $sql_data = array('test_id' => $id,
                        'test_pub' => 'no');
      sql_input($tabla,$sql_data,'update',"test_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vp':
//Ver publicados
    //código de visualización de elementos publicados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE test_pub = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT test_id FROM ".$tabla." WHERE test_pub = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE test_pub = 'si' ORDER BY test_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> Publicados</legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="20">N&ordm;</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"test_id");
          $test_nombre = str_output(mysql_result($datos,$j,"test_nombre"));
          $test_text = str_output(mysql_result($datos,$j,"test_text"));
          $test_pub = str_output(mysql_result($datos,$j,"test_pub"));
          $p = $j;
          $p++;
          foreach ($bin_array as $key => $value) {if ($key == $test_pub) {$test_pub_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $test_nombre; ?></a></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($test_pub_name); ?></p>
            </td>
<?php disp_icons_testimonios($celda,$phpself,$id,$test_pub); ?>
          </tr>
<?php     } ?>
        </table>
      </fieldset>
      <?php echo paginar($total,$pp,$st,$thisurl."?&amp;act=vp&amp;st="); ?>
<?php  }
      break;

    case 'vnp':
//Ver no publicados
    //código de visualización de elementos no publicados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE test_pub = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT test_id FROM ".$tabla." WHERE test_pub = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE test_pub = 'no' ORDER BY test_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> No Publicados</legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="20">N&ordm;</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"test_id");
          $test_nombre = str_output(mysql_result($datos,$j,"test_nombre"));
          $test_text = str_output(mysql_result($datos,$j,"test_text"));
          $test_pub = str_output(mysql_result($datos,$j,"test_pub"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $test_pub) {$test_pub_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $test_nombre; ?></a></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($test_pub_name); ?></p>
            </td>
<?php disp_icons_testimonios($celda,$phpself,$id,$test_pub); ?>
          </tr>
<?php     } ?>
        </table>
      </fieldset>
      <?php echo paginar($total,$pp,$st,$thisurl."?&amp;act=vnp&amp;st="); ?>
<?php  }
      break;

    case 'v':
//Visualizar
    //código de visualización de elementos
      $id = str_input($_GET['id']);
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE test_id=".$id."") or die(mysql_error());
?>
      <fieldset>
        <legend>Visualizar <?php echo $title; ?></legend>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"test_id");
          $test_nombre = str_output(mysql_result($datos,$j,"test_nombre"));
          $test_text = str_output(mysql_result($datos,$j,"test_text"));
          $test_pub = str_output(mysql_result($datos,$j,"test_pub"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $test_pub) {$test_pub_name = "$value";}}
?>
              <h1><?php echo $test_nombre; ?></h1>
              <p><strong>Publicado:</strong> <?php echo ucfirst($test_pub_name); ?></p>

              <hr />
              <?php echo $test_text; ?>
              <hr />
<?php       if (!empty($prod_text)){
               echo $text;
            }
          } ?>
      </fieldset>
<?php
      break;

    default:
      $_SESSION = array();
      header('Location: index.php');
      exit;
    }
  }
else {
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla."") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT test_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY test_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"test_id");
          $test_nombre = str_output(mysql_result($datos,$j,"test_nombre"));
          $test_text = str_output(mysql_result($datos,$j,"test_text"));
          $test_pub = str_output(mysql_result($datos,$j,"test_pub"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $test_pub) {$test_pub_name = "$value";}}

?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre:</strong>
              <a href="<?php echo $phpself; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $test_nombre; ?></a></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($test_pub_name); ?></p>
            </td>
<?php disp_icons_testimonios($celda,$phpself,$id,$test_pub); ?>
          </tr>
<?php } ?>
        </table>
      </fieldset>
      <?php echo paginar($total,$pp,$st,$thisurl."?&amp;st="); ?>
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
   else {draw_additem("Testimonio");}


  disp_fin_info();
  disp_footer_admin($pre);
?>