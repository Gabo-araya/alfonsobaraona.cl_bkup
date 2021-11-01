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
$title = "Libro de Visitas";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."librovisitas";

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
   else {draw_additem("Comentario");}

if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'guest_nombre','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_input("Email: ",'guest_email','text','','','table_horiz',50,'opc'); ?>
      <?php echo draw_input("Web: ",'guest_web','text','','','table_horiz',50,'opc'); ?>
      <?php echo draw_input("Ciudad: ",'guest_ciudad','text','','','table_horiz',50,'opc'); ?>
      <?php echo draw_input("Pa&iacute;s: ",'guest_pais','text','','','table_horiz',50,'opc'); ?>
      <?php echo draw_select("Publicar: ",'guest_pub',$bin_array,'','table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="guest_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('guest_text','',60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $guest_nombre = str_input($_POST['guest_nombre']);
      $guest_email = str_input($_POST['guest_email']);
      $guest_web = str_input($_POST['guest_web']);
      $guest_ciudad = str_input($_POST['guest_ciudad']);
      $guest_pais = str_input($_POST['guest_pais']);
      $guest_text = str_input($_POST['guest_text']);
      $guest_pub = str_input($_POST['guest_pub']);

      if (empty($guest_nombre) OR empty($guest_text) OR empty($guest_pub)){
        if (empty($guest_nombre) OR empty($guest_text) OR empty($guest_pub)){$validation .= "<div class=\"box_validation\">";}
        if (empty($guest_nombre)) $validation .= "Debe escribir un titulo para el comentario.<br />";
        if (empty($guest_pub)) $validation .= "Debe seleccionar si el comentario será publicado o no.<br />";
        if (empty($guest_text)) $validation .= "Debe escribir el texto del comentario. <br />";
        if (empty($guest_nombre) OR empty($guest_text) OR empty($guest_pub)){$validation .= "</div>";}
        echo $validation;

?>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'guest_nombre','text',$guest_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Email: ",'guest_email','text',$guest_email,'','table_horiz',50,'opc'); ?>
      <?php echo draw_input("Web: ",'guest_web','text',$guest_web,'','table_horiz',50,'opc'); ?>
      <?php echo draw_input("Ciudad: ",'guest_ciudad','text',$guest_ciudad,'','table_horiz',50,'opc'); ?>
      <?php echo draw_input("Pa&iacute;s: ",'guest_pais','text',$guest_pais,'','table_horiz',50,'opc'); ?>
      <?php echo draw_select("Publicar: ",'guest_pub',$bin_array,$guest_pub,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="guest_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('guest_text',$guest_text,60,20); ?></td>
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
              $sql_data = array('guest_nombre' => $guest_nombre,
                                'guest_email' => $guest_email,
                                'guest_web' => $guest_web,
                                'guest_ciudad' => $guest_ciudad,
                                'guest_pais' => $guest_pais,
                                'guest_text' => $guest_text,
                                'guest_pub' => $guest_pub);
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
    $datos = mysql_query("SELECT * FROM ".$tabla." WHERE guest_id=".$id."") or die(mysql_error());
      $guest_nombre = str_output(mysql_result($datos,0,'guest_nombre'));
      $guest_email = str_output(mysql_result($datos,0,'guest_email'));
      $guest_web = str_output(mysql_result($datos,0,'guest_web'));
      $guest_ciudad = str_output(mysql_result($datos,0,'guest_ciudad'));
      $guest_pais = str_output(mysql_result($datos,0,'guest_pais'));
      $guest_text = str_output(mysql_result($datos,0,'guest_text'));
      $guest_pub = str_output(mysql_result($datos,0,'guest_pub'));

?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'guest_nombre','text',$guest_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Email: ",'guest_email','text',$guest_email,'','table_horiz',50,'opc'); ?>
      <?php echo draw_input("Web: ",'guest_web','text',$guest_web,'','table_horiz',50,'opc'); ?>
      <?php echo draw_input("Ciudad: ",'guest_ciudad','text',$guest_ciudad,'','table_horiz',50,'opc'); ?>
      <?php echo draw_input("Pa&iacute;s: ",'guest_pais','text',$guest_pais,'','table_horiz',50,'opc'); ?>
      <?php echo draw_select("Publicar: ",'guest_pub',$bin_array,$guest_pub,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="guest_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('guest_text',$guest_text,60,20); ?></td>
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
      $guest_nombre = str_input($_POST['guest_nombre']);
      $guest_email = str_input($_POST['guest_email']);
      $guest_web = str_input($_POST['guest_web']);
      $guest_ciudad = str_input($_POST['guest_ciudad']);
      $guest_pais = str_input($_POST['guest_pais']);
      $guest_text = str_input($_POST['guest_text']);
      $guest_pub = str_input($_POST['guest_pub']);

      if (empty($guest_nombre) OR empty($guest_text) OR empty($guest_pub)){
        if (empty($guest_nombre) OR empty($guest_text) OR empty($guest_pub)){$validation .= "<div class=\"box_validation\">";}
        if (empty($guest_nombre)) $validation .= "Debe escribir un titulo para el producto.<br />";
        if (empty($guest_pub)) $validation .= "Debe seleccionar si el producto será publicado o no.<br />";
        if (empty($guest_text)) $validation .= "Debe escribir el texto del comentario. <br />";
        if (empty($guest_nombre) OR empty($guest_text) OR empty($guest_pub)){$validation .= "</div>";}
        echo $validation;

?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'guest_nombre','text',$guest_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Email: ",'guest_email','text',$guest_email,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Web: ",'guest_web','text',$guest_web,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Ciudad: ",'guest_ciudad','text',$guest_ciudad,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Pa&iacute;s: ",'guest_pais','text',$guest_pais,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Publicar: ",'guest_pub',$bin_array,$guest_pub,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="guest_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('guest_text',$guest_text,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden','$id','','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {

              $sql_data = array('guest_nombre' => $guest_nombre,
                                'guest_email' => $guest_email,
                                'guest_web' => $guest_web,
                                'guest_ciudad' => $guest_ciudad,
                                'guest_pais' => $guest_pais,
                                'guest_text' => $guest_text,
                                'guest_pub' => $guest_pub);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"guest_idx".$id);
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
      break;

    case 'ce':
//Confirmar eliminación
    //código de confirmación de eliminación de elemento
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT guest_nombre FROM ".$tabla." WHERE guest_id=".$id."") or die(mysql_error());
          $guest_nombre = str_output(mysql_result($datos,0,"guest_nombre"));

?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este comentario?</p>
        </div>
          <div align="center">
          <strong>Nombre:</strong> <?php echo $guest_nombre; ?></p>
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
          if(mysql_query("DELETE FROM ".$tabla." WHERE guest_id='".$id."'") or die(mysql_error())){
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
      $sql_data = array('guest_id' => $id,
                        'guest_pub' => 'si');
      sql_input($tabla,$sql_data,'update',"guest_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'dp':
//Despublicar
    //código de despublicación de elemento
      $id = str_input($_GET['id']);
      $sql_data = array('guest_id' => $id,
                        'guest_pub' => 'no');
      sql_input($tabla,$sql_data,'update',"guest_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vp':
//Ver publicados
    //código de visualización de elementos publicados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE guest_pub = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT guest_id FROM ".$tabla." WHERE guest_pub = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE guest_pub = 'si' ORDER BY guest_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"guest_id");
          $guest_nombre = str_output(mysql_result($datos,$j,"guest_nombre"));
      $guest_email = str_output(mysql_result($datos,$j,'guest_email'));
      $guest_web = str_output(mysql_result($datos,$j,'guest_web'));
      $guest_ciudad = str_output(mysql_result($datos,$j,'guest_ciudad'));
      $guest_pais = str_output(mysql_result($datos,$j,'guest_pais'));
          $guest_text = str_output(mysql_result($datos,$j,"guest_text"));
          $guest_pub = str_output(mysql_result($datos,$j,"guest_pub"));
          $p = $j;
          $p++;
          foreach ($bin_array as $key => $value) {if ($key == $guest_pub) {$guest_pub_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $guest_nombre; ?></a></p>
              <p><strong>Email:</strong> <?php echo $guest_email; ?></p>
              <p><strong>Web:</strong> <?php echo $guest_web; ?></p>
              <p><strong>Ciudad:</strong> <?php echo $guest_ciudad; ?></p>
              <p><strong>Pais:</strong> <?php echo $guest_pais; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($guest_pub_name); ?></p>
            </td>
<?php disp_icons_librovisitas($celda,$phpself,$id,$guest_pub); ?>
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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE guest_pub = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT guest_id FROM ".$tabla." WHERE guest_pub = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE guest_pub = 'no' ORDER BY guest_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"guest_id");
          $guest_nombre = str_output(mysql_result($datos,$j,"guest_nombre"));
      $guest_email = str_output(mysql_result($datos,$j,'guest_email'));
      $guest_web = str_output(mysql_result($datos,$j,'guest_web'));
      $guest_ciudad = str_output(mysql_result($datos,$j,'guest_ciudad'));
      $guest_pais = str_output(mysql_result($datos,$j,'guest_pais'));
          $guest_text = str_output(mysql_result($datos,$j,"guest_text"));
          $guest_pub = str_output(mysql_result($datos,$j,"guest_pub"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $guest_pub) {$guest_pub_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $guest_nombre; ?></a></p>
              <p><strong>Email:</strong> <?php echo $guest_email; ?></p>
              <p><strong>Web:</strong> <?php echo $guest_web; ?></p>
              <p><strong>Ciudad:</strong> <?php echo $guest_ciudad; ?></p>
              <p><strong>Pais:</strong> <?php echo $guest_pais; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($guest_pub_name); ?></p>
            </td>
<?php disp_icons_librovisitas($celda,$phpself,$id,$guest_pub); ?>
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
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE guest_id=".$id."") or die(mysql_error());
?>
      <fieldset>
        <legend>Visualizar <?php echo $title; ?></legend>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"guest_id");
          $guest_nombre = str_output(mysql_result($datos,$j,"guest_nombre"));
      $guest_email = str_output(mysql_result($datos,$j,'guest_email'));
      $guest_web = str_output(mysql_result($datos,$j,'guest_web'));
      $guest_ciudad = str_output(mysql_result($datos,$j,'guest_ciudad'));
      $guest_pais = str_output(mysql_result($datos,$j,'guest_pais'));
          $guest_text = str_output(mysql_result($datos,$j,"guest_text"));
          $guest_pub = str_output(mysql_result($datos,$j,"guest_pub"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $guest_pub) {$guest_pub_name = "$value";}}
?>
              <h1><?php echo $guest_nombre; ?></h1>
              <p><strong>Email:</strong> <?php echo $guest_email; ?></p>
              <p><strong>Web:</strong> <?php echo $guest_web; ?></p>
              <p><strong>Ciudad:</strong> <?php echo $guest_ciudad; ?></p>
              <p><strong>Pais:</strong> <?php echo $guest_pais; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($guest_pub_name); ?></p>

              <hr />
              <?php echo $guest_text; ?>
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

  $datos = mysql_query("SELECT guest_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY guest_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"guest_id");
          $guest_nombre = str_output(mysql_result($datos,$j,"guest_nombre"));
      $guest_email = str_output(mysql_result($datos,$j,'guest_email'));
      $guest_web = str_output(mysql_result($datos,$j,'guest_web'));
      $guest_ciudad = str_output(mysql_result($datos,$j,'guest_ciudad'));
      $guest_pais = str_output(mysql_result($datos,$j,'guest_pais'));
          $guest_text = str_output(mysql_result($datos,$j,"guest_text"));
          $guest_pub = str_output(mysql_result($datos,$j,"guest_pub"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $guest_pub) {$guest_pub_name = "$value";}}

?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre:</strong>
              <a href="<?php echo $phpself; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $guest_nombre; ?></a></p>
              <p><strong>Email:</strong> <?php echo $guest_email; ?></p>
              <p><strong>Web:</strong> <?php echo $guest_web; ?></p>
              <p><strong>Ciudad:</strong> <?php echo $guest_ciudad; ?></p>
              <p><strong>Pais:</strong> <?php echo $guest_pais; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($guest_pub_name); ?></p>
            </td>
<?php disp_icons_librovisitas($celda,$phpself,$id,$guest_pub); ?>
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
   else {draw_additem("Comentario");}


  disp_fin_info();
  disp_footer_admin($pre);
?>
