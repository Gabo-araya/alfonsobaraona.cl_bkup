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
$title = "Blog";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."blog";

// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

// Categorías de articulos
    $cat_blog_res = mysql_query("SELECT cat_id,cat_blog FROM ".$pre."cat WHERE cat_blog IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_blog_res); ++$j) {
          $cat_id = mysql_result($cat_blog_res,$j,"cat_id");
          $cat_blog[$cat_id] = str_output(mysql_result($cat_blog_res,$j,"cat_blog"));
        }
      asort($cat_blog);
      //echo disp_array_asoc($cat_blog);

  $bin_array = array('si' => 'Sí','no' => 'No');

  $date = time();

  $meses = array('1' => 'Enero',
                 '2' => 'Febrero',
                 '3' => 'Marzo',
                 '4' => 'Abril',
                 '5' => 'Mayo',
                 '6' => 'Junio',
                 '7' => 'Julio',
                 '8' => 'Agosto',
                 '9' => 'Septiembre',
                 '10' => 'Octubre',
                 '11' => 'Noviembre',
                 '12' => 'Diciembre');

  $dias = array('1' => 'Lunes',
                '2' => 'Martes',
                '3' => 'Miércoles',
                '4' => 'Jueves',
                '5' => 'Viernes',
                '6' => 'Sábado',
                '7' => 'Domingo');

  $hoy = date('d', $date);
  $dia_hoy = date('N', $date);
  $mes = date('m', $date);
  $anio = date('Y', $date);
  $hora = date('H', $date);
  $minut = date('i', $date);

disp_inicio_info($title,'articulos');

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
   else {draw_additem("Artículo");}

if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'blog_nombre','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_select("Categoría: ",'blog_cat',$cat_blog,'','table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'blog_pub',$bin_array,'','table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'blog_dest',$bin_array,'','table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','blog_dia','text',$hoy,'','',2)."de".draw_select('','blog_mes',$meses,$mes,'','')."de".draw_input('','blog_anio','text',$anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="blog_resena">Reseña: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('blog_resena','',60,20); ?></td>
      </tr>
      <tr>
        <td align="right"><label for="blog_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_opc(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('blog_text','',60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Guardar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $blog_nombre = str_input($_POST['blog_nombre']);
      $blog_resena = str_input($_POST['blog_resena']);
      $blog_text = str_input($_POST['blog_text']);
      $blog_cat = str_input($_POST['blog_cat']);
      $blog_pub = str_input($_POST['blog_pub']);
      $blog_dest = str_input($_POST['blog_dest']);
      if (empty($_POST['blog_dia'])){$blog_dia = $hoy;} else {$blog_dia = str_input($_POST['blog_dia']);}
      if (empty($_POST['blog_mes'])){$blog_mes = $mes;} else {$blog_mes = str_input($_POST['blog_mes']);}
      if (empty($_POST['blog_anio'])){$blog_anio = $anio;} else {$blog_anio = str_input($_POST['blog_anio']);}

      $blog_fecha = mktime(1,0,0,$blog_mes,$blog_dia,$blog_anio);

      if (empty($blog_nombre) OR empty($blog_resena) OR empty($blog_cat) OR empty($blog_pub) OR empty($blog_dest)){
        if (empty($blog_nombre) OR empty($blog_resena) OR empty($blog_cat) OR empty($blog_pub) OR empty($blog_dest)){$validation .= "<div class=\"box_validation\">";}
        if (empty($blog_nombre)) $validation .= "Debe escribir un nombre para el artículo.<br />";
        if (empty($blog_cat)) $validation .= "Debe seleccionar una categoría para el artículo.<br />";
        if (empty($blog_pub)) $validation .= "Debe seleccionar si el artículo será publicado o no.<br />";
        if (empty($blog_dest)) $validation .= "Debe seleccionar si el artículo será destacado o no.<br />";
        if (empty($blog_resena)) $validation .= "Debe incorporar una reseña para el artículo. <br />";
        if (empty($blog_nombre) OR empty($blog_resena) OR empty($blog_cat) OR empty($blog_pub) OR empty($blog_dest)){$validation .= "</div>";}
        echo $validation;

    $diasmes = date('t',mktime(0,0,0,$blog_mes,1,$blog_anio));
    if ($diasmes<$blog_dia){
      echo "<div class=\"box_validation\">El mes ";
      foreach ($meses as $key => $value) { if ($key == $blog_mes) {echo $value;} }
      echo " tiene $diasmes días. <br />
      Revise su calendario e inténtelo nuevamente.</div>";
      echo "<form action=\"".$phpself."\" method=\"POST\">
      <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      exit;
      }

?>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'blog_nombre','text',$blog_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Categoría: ",'blog_cat',$cat_blog,$blog_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'blog_pub',$bin_array,$blog_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'blog_dest',$bin_array,$blog_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','blog_dia','text',$blog_dia,'','',2)."de".draw_select('','blog_mes',$meses,$blog_mes,'','')."de".draw_input('','blog_anio','text',$blog_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="blog_resena">Reseña: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('blog_resena',$blog_resena,60,20); ?></td>
      </tr>
      <tr>
        <td align="right"><label for="blog_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_opc(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('blog_text',$blog_text,60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Guardar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
// Insertar
        if ($_SERVER['REQUEST_METHOD']=='POST'){
              $sql_data = array('blog_nombre' => $blog_nombre,
                                'blog_text' => $blog_text,
                                'blog_resena' => $blog_resena,
                                'blog_fecha' => $blog_fecha,
                                'blog_cat' => $blog_cat,
                                'blog_autor' => $_SESSION['user'],
                                'blog_pub' => $blog_pub,
                                'blog_dest' => $blog_dest);
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
    $datos = mysql_query("SELECT * FROM ".$tabla." WHERE blog_id=".$id."") or die(mysql_error());
      $blog_nombre = str_output(mysql_result($datos,0,'blog_nombre'));
      $blog_resena = str_output(mysql_result($datos,0,'blog_resena'));
      $blog_text = str_output(mysql_result($datos,0,'blog_text'));
      $blog_pub = str_output(mysql_result($datos,0,'blog_pub'));
      $blog_cat = str_output(mysql_result($datos,0,'blog_cat'));
      $blog_dest = str_output(mysql_result($datos,0,'blog_dest'));
      $blog_fecha = str_output(mysql_result($datos,0,'blog_fecha'));
      $blog_mes = date("m",$blog_fecha);
      $blog_dia = date("d",$blog_fecha);
      $blog_anio = date("Y",$blog_fecha);

// draw_select($label,$name,$data_select,$selected,$param,'',req)
?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'blog_nombre','text',$blog_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Categoría: ",'blog_cat',$cat_blog,$blog_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'blog_pub',$bin_array,$blog_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'blog_dest',$bin_array,$blog_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','blog_dia','text',$blog_dia,'','',2)."de".draw_select('','blog_mes',$meses,$blog_mes,'','')."de".draw_input('','blog_anio','text',$blog_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="blog_resena">Reseña: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('blog_resena',$blog_resena,60,20); ?></td>
      </tr>
      <tr>
        <td align="right"><label for="blog_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_opc(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('blog_text',$blog_text,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Guardar'); ?></div>
    </fieldset>
    </form>
<?php break;

    case 'vm':
//Validación de Modificación
    //código de validación de modificación de elemento
      $id = str_input($_POST['id']);
      $blog_nombre = str_input($_POST['blog_nombre']);
      $blog_resena = str_input($_POST['blog_resena']);
      $blog_text = str_input($_POST['blog_text']);
      $blog_cat = str_input($_POST['blog_cat']);
      $blog_pub = str_input($_POST['blog_pub']);
      $blog_dest = str_input($_POST['blog_dest']);
      if (empty($_POST['blog_dia'])){$blog_dia = $hoy;} else {$blog_dia = str_input($_POST['blog_dia']);}
      if (empty($_POST['blog_mes'])){$blog_mes = $mes;} else {$blog_mes = str_input($_POST['blog_mes']);}
      if (empty($_POST['blog_anio'])){$blog_anio = $anio;} else {$blog_anio = str_input($_POST['blog_anio']);}

      $blog_fecha = mktime(1,0,0,$blog_mes,$blog_dia,$blog_anio);

      if (empty($blog_nombre) OR empty($blog_resena) OR empty($blog_cat) OR empty($blog_pub) OR empty($blog_dest)){
        if (empty($blog_nombre) OR empty($blog_resena) OR empty($blog_cat) OR empty($blog_pub) OR empty($blog_dest)){$validation .= "<div class=\"box_validation\">";}
        if (empty($blog_nombre)) $validation .= "Debe escribir un nombre para el producto.<br />";
        if (empty($blog_cat)) $validation .= "Debe seleccionar una categoría para el producto.<br />";
        if (empty($blog_pub)) $validation .= "Debe seleccionar si el producto será publicado o no.<br />";
        if (empty($blog_dest)) $validation .= "Debe seleccionar si el producto será destacado o no.<br />";
        if (empty($blog_resena)) $validation .= "Debe incorporar una reseña para el artículo. <br />";
        if (empty($blog_nombre) OR empty($blog_resena) OR empty($blog_cat) OR empty($blog_pub) OR empty($blog_dest)){$validation .= "</div>";}
        echo $validation;

    $diasmes = date('t',mktime(0,0,0,$blog_mes,1,$blog_anio));
    if ($diasmes<$blog_dia){
      echo "<div class=\"box_validation\">El mes ";
      foreach ($meses as $key => $value) { if ($key == $blog_mes) {echo $value;} }
      echo " tiene $diasmes días. <br />
      Revise su calendario e inténtelo nuevamente.</div>";
      echo "<form action=\"".$phpself."\" method=\"POST\">
      <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      exit;
      }
?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'blog_nombre','text',$blog_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Categoría: ",'blog_cat',$cat_blog,$blog_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'blog_pub',$bin_array,$blog_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'blog_dest',$bin_array,$blog_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','blog_dia','text',$blog_dia,'','',2)."de".draw_select('','blog_mes',$meses,$blog_mes,'','')."de".draw_input('','blog_anio','text',$blog_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="blog_resena">Reseña: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('blog_resena',$blog_resena,60,20); ?></td>
      </tr>
      <tr>
        <td align="right"><label for="blog_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_opc(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('blog_text',$blog_text,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden','$id','','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Guardar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {

              $sql_data = array('blog_nombre' => $blog_nombre,
                                'blog_text' => $blog_text,
                                'blog_resena' => $blog_resena,
                                'blog_fecha' => $blog_fecha,
                                'blog_cat' => $blog_cat,
                                'blog_pub' => $blog_pub,
                                'blog_dest' => $blog_dest);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"blog_idx".$id);
?>
        <div align="center">
          <strong>Nombre del Artículo:</strong> <?php echo $blog_nombre; ?></p>
          <br /><br />
          <table width="100" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td align="left">
                <form action="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" method="POST">
                <input type="submit" value="&laquo; Seguir editando" /></form>
              </td>
              <td>&nbsp;</td>
              <td align="right">
                <form action="<?php echo $phpself; ?>" method="POST">
                <input type="submit" value="He terminado de editar &raquo;" />
                </form>
              </td>
            </tr>
          </table>
        </div>
<?php
      }
      break;

    case 'ce':
//Confirmar eliminación
    //código de confirmación de eliminación de elemento
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT blog_nombre FROM ".$tabla." WHERE blog_id=".$id."") or die(mysql_error());
          $blog_nombre = str_output(mysql_result($datos,0,"blog_nombre"));

?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este artículo?</p>
        </div>
          <div align="center">
          <strong>Nombre del Artículo:</strong> <?php echo $blog_nombre; ?></p>
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

    case 'e':
//Eliminar imagen y registro
    //código de eliminación de elemento
      if($_SERVER['REQUEST_METHOD']=='POST'){
        $id = str_input($_POST['id']);
          if(mysql_query("DELETE FROM ".$tabla." WHERE blog_id='".$id."'") or die(mysql_error())){
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
      $sql_data = array('blog_id' => $id,
                        'blog_pub' => 'si');
      sql_input($tabla,$sql_data,'update',"blog_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'dp':
//Despublicar
    //código de despublicación de elemento
      $id = str_input($_GET['id']);
      $sql_data = array('blog_id' => $id,
                        'blog_pub' => 'no');
      sql_input($tabla,$sql_data,'update',"blog_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vp':
//Ver publicados
    //código de visualización de elementos publicados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE blog_pub = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT blog_id FROM ".$tabla." WHERE blog_pub = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE blog_pub = 'si' ORDER BY blog_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"blog_id");
          $blog_nombre = str_output(mysql_result($datos,$j,"blog_nombre"));
          $blog_resena = str_output(mysql_result($datos,$j,"blog_resena"));
          $blog_text = str_output(mysql_result($datos,$j,"blog_text"));
          $blog_cat = str_output(mysql_result($datos,$j,"blog_cat"));
          $blog_pub = str_output(mysql_result($datos,$j,"blog_pub"));
          $blog_dest = str_output(mysql_result($datos,$j,"blog_dest"));
          $blog_fecha = str_output(mysql_result($datos,$j,"blog_fecha"));
          $p = $j;
          $p++;

          foreach ($cat_blog as $key => $value) {if ($key == $blog_cat) {$blog_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_pub) {$blog_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_dest) {$blog_dest_name = "$value";}}

      $nombre_blog_dia = date('N',$blog_fecha);
      $blog_mes = date("m",$blog_fecha);
      $blog_dia = date("d",$blog_fecha);
      $blog_anio = date("Y",$blog_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_blog_dia) {$fecha = $value.", ";} }
      $fecha .= $blog_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $blog_mes) {$fecha .= $value;} }
      $fecha .= " de ".$blog_anio;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título del Artículo:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $blog_nombre; ?></a></p>
              <p><strong>Categoría:</strong> <?php echo $blog_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($blog_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($blog_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_artic($celda,$phpself,$id,$blog_pub,$blog_dest); ?>
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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE blog_pub = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT blog_id FROM ".$tabla." WHERE blog_pub = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE blog_pub = 'no' ORDER BY blog_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"blog_id");
          $blog_nombre = str_output(mysql_result($datos,$j,"blog_nombre"));
          $blog_resena = str_output(mysql_result($datos,$j,"blog_resena"));
          $blog_text = str_output(mysql_result($datos,$j,"blog_text"));
          $blog_cat = str_output(mysql_result($datos,$j,"blog_cat"));
          $blog_pub = str_output(mysql_result($datos,$j,"blog_pub"));
          $blog_dest = str_output(mysql_result($datos,$j,"blog_dest"));
          $blog_fecha = str_output(mysql_result($datos,$j,"blog_fecha"));
          $p = $j;
          $p++;

          foreach ($cat_blog as $key => $value) {if ($key == $blog_cat) {$blog_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_pub) {$blog_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_dest) {$blog_dest_name = "$value";}}

      $nombre_blog_dia = date('N',$blog_fecha);
      $blog_mes = date("m",$blog_fecha);
      $blog_dia = date("d",$blog_fecha);
      $blog_anio = date("Y",$blog_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_blog_dia) {$fecha = $value.", ";} }
      $fecha .= $blog_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $blog_mes) {$fecha .= $value;} }
      $fecha .= " de ".$blog_anio;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título del Artículo:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $blog_nombre; ?></a></p>
              <p><strong>Categoría:</strong> <?php echo $blog_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($blog_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($blog_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_artic($celda,$phpself,$id,$blog_pub,$blog_dest); ?>
          </tr>
<?php     } ?>
        </table>
      </fieldset>
      <?php echo paginar($total,$pp,$st,$thisurl."?&amp;act=vnp&amp;st="); ?>
<?php  }
      break;

    case 'd':
//Descatados
    //código para destacar un elemento
      $id = str_input($_GET['id']);
      $sql_data = array('blog_id' => $id,
                        'blog_dest' => 'si');
      sql_input($tabla,$sql_data,'update',"blog_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'nd':
//No destacados
    //código para quitar destacación de un elemento
      $id = str_input($_GET['id']);
      $sql_data = array('blog_id' => $id,
                        'blog_dest' => 'no');
      sql_input($tabla,$sql_data,'update',"blog_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vd':
//Ver destacados
    //código de visualización de elementos destacados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE blog_dest = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT blog_id FROM ".$tabla." WHERE blog_dest = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE blog_dest = 'si' ORDER BY blog_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> Destacados</legend>
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
          $id = mysql_result($datos,$j,"blog_id");
          $blog_nombre = str_output(mysql_result($datos,$j,"blog_nombre"));
          $blog_resena = str_output(mysql_result($datos,$j,"blog_resena"));
          $blog_text = str_output(mysql_result($datos,$j,"blog_text"));
          $blog_cat = str_output(mysql_result($datos,$j,"blog_cat"));
          $blog_pub = str_output(mysql_result($datos,$j,"blog_pub"));
          $blog_dest = str_output(mysql_result($datos,$j,"blog_dest"));
          $blog_fecha = str_output(mysql_result($datos,$j,"blog_fecha"));
          $p = $j;
          $p++;

          foreach ($cat_blog as $key => $value) {if ($key == $blog_cat) {$blog_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_pub) {$blog_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_dest) {$blog_dest_name = "$value";}}

      $nombre_blog_dia = date('N',$blog_fecha);
      $blog_mes = date("m",$blog_fecha);
      $blog_dia = date("d",$blog_fecha);
      $blog_anio = date("Y",$blog_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_blog_dia) {$fecha = $value.", ";} }
      $fecha .= $blog_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $blog_mes) {$fecha .= $value;} }
      $fecha .= " de ".$blog_anio;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título del Artículo:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $blog_nombre; ?></a></p>
              <p><strong>Categoría:</strong> <?php echo $blog_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($blog_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($blog_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_artic($celda,$phpself,$id,$blog_pub,$blog_dest); ?>
          </tr>
<?php     } ?>
        </table>
      </fieldset>
      <?php echo paginar($total,$pp,$st,$thisurl."?&amp;act=vd&amp;st="); ?>
<?php  }
      break;

    case 'vnd':
//Ver no destacados
    //código de visualización de elementos no destacados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE blog_dest = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT blog_id FROM ".$tabla." WHERE blog_dest = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE blog_dest = 'no' ORDER BY blog_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> No Destacados</legend>
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
          $id = mysql_result($datos,$j,"blog_id");
          $blog_nombre = str_output(mysql_result($datos,$j,"blog_nombre"));
          $blog_resena = str_output(mysql_result($datos,$j,"blog_resena"));
          $blog_text = str_output(mysql_result($datos,$j,"blog_text"));
          $blog_cat = str_output(mysql_result($datos,$j,"blog_cat"));
          $blog_pub = str_output(mysql_result($datos,$j,"blog_pub"));
          $blog_dest = str_output(mysql_result($datos,$j,"blog_dest"));
          $blog_fecha = str_output(mysql_result($datos,$j,"blog_fecha"));
          $p = $j;
          $p++;

          foreach ($cat_blog as $key => $value) {if ($key == $blog_cat) {$blog_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_pub) {$blog_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_dest) {$blog_dest_name = "$value";}}

      $nombre_blog_dia = date('N',$blog_fecha);
      $blog_mes = date("m",$blog_fecha);
      $blog_dia = date("d",$blog_fecha);
      $blog_anio = date("Y",$blog_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_blog_dia) {$fecha = $value.", ";} }
      $fecha .= $blog_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $blog_mes) {$fecha .= $value;} }
      $fecha .= " de ".$blog_anio;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título del Artículo:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $blog_nombre; ?></a></p>
              <p><strong>Categoría:</strong> <?php echo $blog_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($blog_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($blog_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_artic($celda,$phpself,$id,$blog_pub,$blog_dest); ?>
          </tr>
<?php     } ?>
        </table>
      </fieldset>
      <?php echo paginar($total,$pp,$st,$thisurl."?&amp;act=vnd&amp;st="); ?>
<?php  }
      break;

    case 'v':
//Visualizar
    //código de visualización de elementos
      $id = str_input($_GET['id']);
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE blog_id=".$id."") or die(mysql_error());
?>
      <fieldset>
        <legend>Visualizar <?php echo $title; ?></legend>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"blog_id");
          $blog_nombre = str_output(mysql_result($datos,$j,"blog_nombre"));
          $blog_resena = str_output(mysql_result($datos,$j,"blog_resena"));
          $blog_text = str_output(mysql_result($datos,$j,"blog_text"));
          $blog_cat = str_output(mysql_result($datos,$j,"blog_cat"));
          $blog_pub = str_output(mysql_result($datos,$j,"blog_pub"));
          $blog_dest = str_output(mysql_result($datos,$j,"blog_dest"));
          $blog_fecha = str_output(mysql_result($datos,$j,"blog_fecha"));
          $p = $j;
          $p++;

          foreach ($cat_blog as $key => $value) {if ($key == $blog_cat) {$blog_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_pub) {$blog_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_dest) {$blog_dest_name = "$value";}}

      $nombre_blog_dia = date('N',$blog_fecha);
      $blog_mes = date("m",$blog_fecha);
      $blog_dia = date("d",$blog_fecha);
      $blog_anio = date("Y",$blog_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_blog_dia) {$fecha = $value.", ";} }
      $fecha .= $blog_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $blog_mes) {$fecha .= $value;} }
      $fecha .= " de ".$blog_anio;
?>
              <h1><?php echo $blog_nombre; ?></h1>
              <p><strong>Categoría:</strong> <?php echo $blog_cat_name; ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
              <hr />
              <?php echo $blog_resena; ?>
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

  $datos = mysql_query("SELECT blog_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY blog_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"blog_id");
          $blog_nombre = str_output(mysql_result($datos,$j,"blog_nombre"));
          $blog_resena = str_output(mysql_result($datos,$j,"blog_resena"));
          $blog_text = str_output(mysql_result($datos,$j,"blog_text"));
          $blog_cat = str_output(mysql_result($datos,$j,"blog_cat"));
          $blog_autor = str_output(mysql_result($datos,$j,"blog_autor"));
          $blog_pub = str_output(mysql_result($datos,$j,"blog_pub"));
          $blog_dest = str_output(mysql_result($datos,$j,"blog_dest"));
          $blog_fecha = str_output(mysql_result($datos,$j,"blog_fecha"));
          $p = $j;
          $p++;

          foreach ($cat_blog as $key => $value) {if ($key == $blog_cat) {$blog_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_pub) {$blog_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $blog_dest) {$blog_dest_name = "$value";}}

      $nombre_blog_dia = date('N',$blog_fecha);
      $blog_mes = date("m",$blog_fecha);
      $blog_dia = date("d",$blog_fecha);
      $blog_anio = date("Y",$blog_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_blog_dia) {$fecha = $value.", ";} }
      $fecha .= $blog_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $blog_mes) {$fecha .= $value;} }
      $fecha .= " de ".$blog_anio;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título del Artículo:</strong>
              <a href="<?php echo $phpself; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $blog_nombre; ?></a></p>
              <p><strong>Autor:</strong> <?php echo $blog_autor; ?></p>
              <p><strong>Categoría:</strong> <?php echo $blog_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($blog_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($blog_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_artic($celda,$phpself,$id,$blog_pub,$blog_dest); ?>
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
   else {draw_additem("Artículo");}


  disp_fin_info();
  disp_footer_admin($pre);
?>