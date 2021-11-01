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
$title = "Noticias";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."not";

// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

  $bin_array = array('si' => 'Sí','no' => 'No');

  $date =time ();

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

disp_inicio_info($title,'noticias');

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
   else {draw_additem("Noticia");}


if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'not_nombre','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_select("Publicar: ",'not_pub',$bin_array,'','table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'not_dest',$bin_array,'','table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','not_dia','text',$hoy,'','',2)."de".draw_select('','not_mes',$meses,$mes,'','')."de".draw_input('','not_anio','text',$anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="not_resena">Reseña: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('not_resena','',60,20); ?></td>
      </tr>
      <tr>
        <td align="right"><label for="not_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_opc(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('not_text','',60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $not_nombre = str_input($_POST['not_nombre']);
      $not_resena = str_input($_POST['not_resena']);
      $not_text = str_input($_POST['not_text']);
      $not_pub = str_input($_POST['not_pub']);
      $not_dest = str_input($_POST['not_dest']);
      if (empty($_POST['not_dia'])){$not_dia = $hoy;} else {$not_dia = str_input($_POST['not_dia']);}
      if (empty($_POST['not_mes'])){$not_mes = $mes;} else {$not_mes = str_input($_POST['not_mes']);}
      if (empty($_POST['not_anio'])){$not_anio = $anio;} else {$not_anio = str_input($_POST['not_anio']);}

      $not_fecha = mktime(1,0,0,$not_mes,$not_dia,$not_anio);

      if (empty($not_nombre) OR empty($not_resena) OR empty($not_pub) OR empty($not_dest)){
        if (empty($not_nombre) OR empty($not_resena) OR empty($not_cat) OR empty($not_pub) OR empty($not_dest)){$validation .= "<div class=\"box_validation\">";}
        if (empty($not_nombre)) $validation .= "Debe escribir un nombre para el artículo.<br />";
        if (empty($not_pub)) $validation .= "Debe seleccionar si el artículo será publicado o no.<br />";
        if (empty($not_dest)) $validation .= "Debe seleccionar si el artículo será destacado o no.<br />";
        if (empty($not_resena)) $validation .= "Debe incorporar una reseña para el artículo. <br />";
        if (empty($not_nombre) OR empty($not_resena) OR empty($not_cat) OR empty($not_pub) OR empty($not_dest)){$validation .= "</div>";}
        echo $validation;

    $diasmes = date('t',mktime(0,0,0,$not_mes,1,$not_anio));
    if ($diasmes<$not_dia){
      echo "<div class=\"box_validation\">El mes ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {echo $value;} }
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
      <?php echo draw_input("Nombre: ",'not_nombre','text',$not_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Publicar: ",'not_pub',$bin_array,$not_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'not_dest',$bin_array,$not_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','not_dia','text',$not_dia,'','',2)."de".draw_select('','not_mes',$meses,$not_mes,'','')."de".draw_input('','not_anio','text',$not_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="not_resena">Reseña: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('not_resena',$not_resena,60,20); ?></td>
      </tr>
      <tr>
        <td align="right"><label for="not_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_opc(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('not_text',$not_text,60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
// Insertar
//        echo $not_fecha;
        if ($_SERVER['REQUEST_METHOD']=='POST'){
              $sql_data = array('not_nombre' => $not_nombre,
                                'not_text' => $not_text,
                                'not_resena' => $not_resena,
                                'not_fecha' => $not_fecha,
                                'not_pub' => $not_pub,
                                'not_dest' => $not_dest);
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
    $datos = mysql_query("SELECT * FROM ".$tabla." WHERE not_id=".$id."") or die(mysql_error());
      $not_nombre = str_output(mysql_result($datos,0,'not_nombre'));
      $not_resena = str_output(mysql_result($datos,0,'not_resena'));
      $not_text = str_output(mysql_result($datos,0,'not_text'));
      $not_pub = str_output(mysql_result($datos,0,'not_pub'));
      $not_dest = str_output(mysql_result($datos,0,'not_dest'));
      $not_fecha = str_output(mysql_result($datos,0,'not_fecha'));
      $not_mes = date("m",$not_fecha);
      $not_dia = date("d",$not_fecha);
      $not_anio = date("Y",$not_fecha);

// draw_select($label,$name,$data_select,$selected,$param,'',req)
?>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'not_nombre','text',$not_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Publicar: ",'not_pub',$bin_array,$not_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'not_dest',$bin_array,$not_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','not_dia','text',$not_dia,'','',2)."de".draw_select('','not_mes',$meses,$not_mes,'','')."de".draw_input('','not_anio','text',$not_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="not_resena">Reseña: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('not_resena',$not_resena,60,20); ?></td>
      </tr>
      <tr>
        <td align="right"><label for="not_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_opc(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('not_text',$not_text,60,20); ?></td>
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
      $not_nombre = str_input($_POST['not_nombre']);
      $not_resena = str_input($_POST['not_resena']);
      $not_text = str_input($_POST['not_text']);
      $not_pub = str_input($_POST['not_pub']);
      $not_dest = str_input($_POST['not_dest']);
      if (empty($_POST['not_dia'])){$not_dia = $hoy;} else {$not_dia = str_input($_POST['not_dia']);}
      if (empty($_POST['not_mes'])){$not_mes = $mes;} else {$not_mes = str_input($_POST['not_mes']);}
      if (empty($_POST['not_anio'])){$not_anio = $anio;} else {$not_anio = str_input($_POST['not_anio']);}

      $not_fecha = mktime(1,0,0,$not_mes,$not_dia,$not_anio);

      if (empty($not_nombre) OR empty($not_resena) OR empty($not_pub) OR empty($not_dest)){
        if (empty($not_nombre) OR empty($not_resena) OR empty($not_cat) OR empty($not_pub) OR empty($not_dest)){$validation .= "<div class=\"box_validation\">";}
        if (empty($not_nombre)) $validation .= "Debe escribir un nombre para el producto.<br />";
        if (empty($not_pub)) $validation .= "Debe seleccionar si el producto será publicado o no.<br />";
        if (empty($not_dest)) $validation .= "Debe seleccionar si el producto será destacado o no.<br />";
        if (empty($not_resena)) $validation .= "Debe incorporar una reseña para el artículo. <br />";
        if (empty($not_nombre) OR empty($not_resena) OR empty($not_cat) OR empty($not_pub) OR empty($not_dest)){$validation .= "</div>";}
        echo $validation;

    $diasmes = date('t',mktime(0,0,0,$not_mes,1,$not_anio));
    if ($diasmes<$not_dia){
      echo "<div class=\"box_validation\">El mes ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {echo $value;} }
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
      <?php echo draw_input("Nombre: ",'not_nombre','text',$not_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_select("Publicar: ",'not_pub',$bin_array,$not_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'not_dest',$bin_array,$not_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','not_dia','text',$not_dia,'','',2)."de".draw_select('','not_mes',$meses,$not_mes,'','')."de".draw_input('','not_anio','text',$not_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="not_resena">Reseña: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('not_resena',$not_resena,60,20); ?></td>
      </tr>
      <tr>
        <td align="right"><label for="not_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_opc(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('not_text',$not_text,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {

              $sql_data = array('not_nombre' => $not_nombre,
                                'not_text' => $not_text,
                                'not_resena' => $not_resena,
                                'not_fecha' => $not_fecha,
                                'not_pub' => $not_pub,
                                'not_dest' => $not_dest);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"not_idx".$id);
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
      break;

    case 'ce':
//Confirmar eliminación
    //código de confirmación de eliminación de elemento
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT not_nombre FROM ".$tabla." WHERE not_id=".$id."") or die(mysql_error());
          $not_nombre = str_output(mysql_result($datos,0,"not_nombre"));

?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este artículo?</p>
        </div>
          <div align="center">
          <strong>Nombre de la Noticia:</strong> <?php echo $not_nombre; ?></p>
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
          if(mysql_query("DELETE FROM ".$tabla." WHERE not_id='".$id."'") or die(mysql_error())){
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
      $sql_data = array('not_id' => $id,
                        'not_pub' => 'si');
      sql_input($tabla,$sql_data,'update',"not_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'dp':
//Despublicar
    //código de despublicación de elemento
      $id = str_input($_GET['id']);
      $sql_data = array('not_id' => $id,
                        'not_pub' => 'no');
      sql_input($tabla,$sql_data,'update',"not_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vp':
//Ver publicados
    //código de visualización de elementos publicados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE not_pub = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT not_id FROM ".$tabla." WHERE not_pub = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE not_pub = 'si' ORDER BY not_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> Publicadas</legend>
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
          $id = mysql_result($datos,$j,"not_id");
          $not_nombre = str_output(mysql_result($datos,$j,"not_nombre"));
          $not_resena = str_output(mysql_result($datos,$j,"not_resena"));
          $not_text = str_output(mysql_result($datos,$j,"not_text"));
          $not_pub = str_output(mysql_result($datos,$j,"not_pub"));
          $not_dest = str_output(mysql_result($datos,$j,"not_dest"));
          $not_fecha = str_output(mysql_result($datos,$j,"not_fecha"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $not_pub) {$not_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $not_dest) {$not_dest_name = "$value";}}

      $nombre_not_dia = date('N',$not_fecha);
      $not_mes = date("m",$not_fecha);
      $not_dia = date("d",$not_fecha);
      $not_anio = date("Y",$not_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_not_dia) {$fecha = $value.", ";} }
      $fecha .= $not_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {$fecha .= $value;} }
      $fecha .= " de ".$not_anio;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título de la Noticia:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $not_nombre; ?></a></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($not_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($not_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_not($celda,$phpself,$id,$not_pub,$not_dest); ?>
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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE not_pub = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT not_id FROM ".$tabla." WHERE not_pub = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE not_pub = 'no' ORDER BY not_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> No Publicadas</legend>
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
          $id = mysql_result($datos,$j,"not_id");
          $not_nombre = str_output(mysql_result($datos,$j,"not_nombre"));
          $not_resena = str_output(mysql_result($datos,$j,"not_resena"));
          $not_text = str_output(mysql_result($datos,$j,"not_text"));
          $not_pub = str_output(mysql_result($datos,$j,"not_pub"));
          $not_dest = str_output(mysql_result($datos,$j,"not_dest"));
          $not_fecha = str_output(mysql_result($datos,$j,"not_fecha"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $not_pub) {$not_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $not_dest) {$not_dest_name = "$value";}}

      $nombre_not_dia = date('N',$not_fecha);
      $not_mes = date("m",$not_fecha);
      $not_dia = date("d",$not_fecha);
      $not_anio = date("Y",$not_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_not_dia) {$fecha = $value.", ";} }
      $fecha .= $not_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {$fecha .= $value;} }
      $fecha .= " de ".$not_anio;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título de la Noticia:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $not_nombre; ?></a></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($not_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($not_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_not($celda,$phpself,$id,$not_pub,$not_dest); ?>
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
      $sql_data = array('not_id' => $id,
                        'not_dest' => 'si');
      sql_input($tabla,$sql_data,'update',"not_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'nd':
//No destacados
    //código para quitar destacación de un elemento
      $id = str_input($_GET['id']);
      $sql_data = array('not_id' => $id,
                        'not_dest' => 'no');
      sql_input($tabla,$sql_data,'update',"not_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vd':
//Ver destacados
    //código de visualización de elementos destacados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE not_dest = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT not_id FROM ".$tabla." WHERE not_dest = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE not_dest = 'si' ORDER BY not_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> Destacadas</legend>
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
          $id = mysql_result($datos,$j,"not_id");
          $not_nombre = str_output(mysql_result($datos,$j,"not_nombre"));
          $not_resena = str_output(mysql_result($datos,$j,"not_resena"));
          $not_text = str_output(mysql_result($datos,$j,"not_text"));
          $not_pub = str_output(mysql_result($datos,$j,"not_pub"));
          $not_dest = str_output(mysql_result($datos,$j,"not_dest"));
          $not_fecha = str_output(mysql_result($datos,$j,"not_fecha"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $not_pub) {$not_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $not_dest) {$not_dest_name = "$value";}}

      $nombre_not_dia = date('N',$not_fecha);
      $not_mes = date("m",$not_fecha);
      $not_dia = date("d",$not_fecha);
      $not_anio = date("Y",$not_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_not_dia) {$fecha = $value.", ";} }
      $fecha .= $not_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {$fecha .= $value;} }
      $fecha .= " de ".$not_anio;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título de la Noticia:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $not_nombre; ?></a></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($not_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($not_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_not($celda,$phpself,$id,$not_pub,$not_dest); ?>
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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE not_dest = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT not_id FROM ".$tabla." WHERE not_dest = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE not_dest = 'no' ORDER BY not_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> No Destacadas</legend>
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
          $id = mysql_result($datos,$j,"not_id");
          $not_nombre = str_output(mysql_result($datos,$j,"not_nombre"));
          $not_resena = str_output(mysql_result($datos,$j,"not_resena"));
          $not_text = str_output(mysql_result($datos,$j,"not_text"));
          $not_pub = str_output(mysql_result($datos,$j,"not_pub"));
          $not_dest = str_output(mysql_result($datos,$j,"not_dest"));
          $not_fecha = str_output(mysql_result($datos,$j,"not_fecha"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $not_pub) {$not_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $not_dest) {$not_dest_name = "$value";}}

      $nombre_not_dia = date('N',$not_fecha);
      $not_mes = date("m",$not_fecha);
      $not_dia = date("d",$not_fecha);
      $not_anio = date("Y",$not_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_not_dia) {$fecha = $value.", ";} }
      $fecha .= $not_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {$fecha .= $value;} }
      $fecha .= " de ".$not_anio;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título de la Noticia:</strong>
              <a href="<?php echo $php_self; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $not_nombre; ?></a></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($not_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($not_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_not($celda,$phpself,$id,$not_pub,$not_dest); ?>
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
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE not_id=".$id."") or die(mysql_error());
?>
      <fieldset>
        <legend>Visualizar <?php echo $title; ?></legend>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"not_id");
          $not_nombre = str_output(mysql_result($datos,$j,"not_nombre"));
          $not_resena = str_output(mysql_result($datos,$j,"not_resena"));
          $not_text = str_output(mysql_result($datos,$j,"not_text"));
          $not_pub = str_output(mysql_result($datos,$j,"not_pub"));
          $not_dest = str_output(mysql_result($datos,$j,"not_dest"));
          $not_fecha = str_output(mysql_result($datos,$j,"not_fecha"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $not_pub) {$not_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $not_dest) {$not_dest_name = "$value";}}

      $nombre_not_dia = date('N',$not_fecha);
      $not_mes = date("m",$not_fecha);
      $not_dia = date("d",$not_fecha);
      $not_anio = date("Y",$not_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_not_dia) {$fecha = $value.", ";} }
      $fecha .= $not_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {$fecha .= $value;} }
      $fecha .= " de ".$not_anio;
?>
              <h1><?php echo $not_nombre; ?></h1>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
              <?php echo $not_resena; ?>
              <hr />
<?php       if (!empty($not_text)){
               echo $not_text;
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

  $datos = mysql_query("SELECT not_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY not_fecha DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"not_id");
          $not_nombre = str_output(mysql_result($datos,$j,"not_nombre"));
          $not_resena = str_output(mysql_result($datos,$j,"not_resena"));
          $not_text = str_output(mysql_result($datos,$j,"not_text"));
          $not_pub = str_output(mysql_result($datos,$j,"not_pub"));
          $not_dest = str_output(mysql_result($datos,$j,"not_dest"));
          $not_fecha = str_output(mysql_result($datos,$j,"not_fecha"));
          $p = $j;
          $p++;

          foreach ($bin_array as $key => $value) {if ($key == $not_pub) {$not_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $not_dest) {$not_dest_name = "$value";}}

      $nombre_not_dia = date('N',$not_fecha);
      $not_mes = date("m",$not_fecha);
      $not_dia = date("d",$not_fecha);
      $not_anio = date("Y",$not_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_not_dia) {$fecha = $value.", ";} }
      $fecha .= $not_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {$fecha .= $value;} }
      $fecha .= " de ".$not_anio;

?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Título de la Noticia:</strong>
              <a href="<?php echo $phpself; ?>?&amp;act=v&amp;id=<?php echo $id; ?>" title="Visualizar">
              <?php echo $not_nombre; ?></a></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($not_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($not_dest_name); ?></p>
              <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            </td>
<?php disp_icons_not($celda,$phpself,$id,$not_pub,$not_dest); ?>
          </tr>
<?php     } ?>
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
   else {draw_additem("Noticia");}

  disp_fin_info();
  disp_footer_admin($pre);
?>