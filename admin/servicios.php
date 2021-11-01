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
$title = "Servicios";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."serv";

// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

// Variables de imágenes
  $images_folder = "../".str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")))."/";
  $thumbs_folder = $images_folder."thumbs/";
  $url_images_folder = url_images_folder($_SERVER['HTTP_REFERER'],$phpself);
  $url_thumbs_folder = url_thumbs_folder($_SERVER['HTTP_REFERER'],$phpself);
  $red = THUMB_WIDTH."x".THUMB_HEIGHT;
  $max_file_size = MAX_IMAGE_SIZE;
  $max_file_size_str = number_format($max_file_size/1024, 1).' Kb';
  $permitidas = array('image/gif','image/jpeg','image/pjpeg','image/png');
  $sizeOK = false;
  $typeOK = false;
  $now = date('dmY-Hi_');

// Categorías de servicios
      $cat_servicios = mysql_query("SELECT cat_id,cat_servicios FROM ".$pre."cat WHERE cat_servicios IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_servicios); ++$j) {
          $cat_id = mysql_result($cat_servicios,$j,"cat_id");
          $cat_prod[$cat_id] = str_output(mysql_result($cat_servicios,$j,"cat_servicios"));
        }
      asort($cat_prod);
      //echo disp_array_asoc($cat_prod);
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

disp_inicio_info($title,'servicios');

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
      OR $_GET['act'] == 'vnd')){}
   else {draw_additem("Servicio");}

if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'serv_nombre','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'serv_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'serv_cat',$cat_prod,'','table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'serv_pub',$bin_array,'','table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'serv_dest',$bin_array,'','table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','serv_dia','text',$hoy,'','',2)."de".draw_select('','serv_mes',$meses,$mes,'','')."de".draw_input('','serv_anio','text',$anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('serv_resena','',60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $serv_nombre = str_input($_POST['serv_nombre']);
      $serv_resena = str_input($_POST['serv_resena']);
      $serv_cat = str_input($_POST['serv_cat']);
      $serv_pub = str_input($_POST['serv_pub']);
      $serv_dest = str_input($_POST['serv_dest']);
      if (empty($_POST['serv_dia'])){$serv_dia = $hoy;} else {$serv_dia = str_input($_POST['serv_dia']);}
      if (empty($_POST['serv_mes'])){$serv_mes = $mes;} else {$serv_mes = str_input($_POST['serv_mes']);}
      if (empty($_POST['serv_anio'])){$serv_anio = $anio;} else {$serv_anio = str_input($_POST['serv_anio']);}

      $serv_fecha = mktime(1,0,0,$serv_mes,$serv_dia,$serv_anio);

      $img_name = str_input(strtolower(str_replace(' ', '_', $_FILES['serv_imagen']['name'])));
      $img_size = $_FILES['serv_imagen']['size'];
      $img_error = $_FILES['serv_imagen']['error'];
      $img_type = $_FILES['serv_imagen']['type'];
      $img_temp = $_FILES['serv_imagen']['tmp_name'];
      $img_now_name = $now.$img_name;
      //echo $img_now_name;

     //check that file is of an permitted MIME type
      foreach ($permitidas as $type_image) {
        if ($type_image == $img_type){
          $typeOK = true;
          $error = "";
          break;
        }
        else {$error = "<div class=\"box_error\">Tipo de archivo no permitido. Sólo se permiten imágenes tipo JPG, PNG y GIF. </div>";}
      }
      if ($img_size <= $max_file_size){
        $sizeOK = true;
      }
      else {$error .= "<div class=\"box_error\">El archivo sobrepasa el tamaño máximo. </div>";}
      echo $error;
      if (!$sizeOK OR !$typeOK OR empty($serv_nombre) OR empty($serv_resena) OR empty($img_name) OR empty($serv_cat) OR empty($serv_pub) OR empty($serv_dest)){
        if (empty($serv_nombre) OR empty($serv_resena) OR empty($img_name) OR empty($serv_cat) OR empty($serv_pub) OR empty($serv_dest)){
          $validation .= "<div class=\"box_validation\">";
        }
        if (empty($serv_nombre)) $validation .= "Debe escribir un nombre para el servicio.<br />";
        if (empty($serv_cat)) $validation .= "Debe seleccionar una categoría para el servicio.<br />";
        if (empty($serv_pub)) $validation .= "Debe seleccionar si el servicio será publicado o no.<br />";
        if (empty($serv_dest)) $validation .= "Debe seleccionar si el servicio será destacado o no.<br />";
        if (empty($img_name)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($serv_resena)) $validation .= "Debe escribir una descripción para el servicio.<br />";
        if (empty($serv_nombre) OR empty($serv_resena) OR empty($img_name) OR empty($serv_cat) OR empty($serv_pub) OR empty($serv_dest)){
          $validation .= "</div>";
        }
        echo $validation;

    $diasmes = date('t',mktime(0,0,0,$serv_mes,1,$serv_anio));
    if ($diasmes<$serv_dia){
      echo "<div class=\"box_validation\">El mes ";
      foreach ($meses as $key => $value) { if ($key == $serv_mes) {echo $value;} }
      echo " tiene $diasmes días. <br />
      Revise su calendario e inténtelo nuevamente.</div>";
      echo "<form action=\"".$phpself."\" method=\"POST\">
      <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      exit;
      }
?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'serv_nombre','text',$serv_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'serv_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'serv_cat',$cat_prod,$serv_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'serv_pub',$bin_array,$serv_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'serv_dest',$bin_array,$serv_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','serv_dia','text',$serv_dia,'','',2)."de".draw_select('','serv_mes',$meses,$serv_mes,'','')."de".draw_input('','serv_anio','text',$serv_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('serv_resena',$serv_resena,60,20); ?></td>
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
        switch($img_error) {
          case 0:
        // check if a file of the same name has been uploaded
            $success = move_uploaded_file($img_temp,$images_folder.$img_now_name);
        // redim_img($red,$nombre_imagen,$ruta_original,$ruta_thumbs);
            redim_img($red,$img_now_name,$images_folder,$thumbs_folder);
            if($success) {
              $resultado = "<div class=\"box_success\">La imagen ha sido subida con éxito.</div>";
              $sql_data = array('serv_nombre' => $serv_nombre,
                                'serv_resena' => $serv_resena,
                                'serv_fecha' => $serv_fecha,
                                'serv_imagen' => $img_now_name,
                                'serv_cat' => $serv_cat,
                                'serv_pub' => $serv_pub,
                                'serv_dest' => $serv_dest);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'insert','');
            }
            else {
              $resultado = "<div class=\"box_error\">Ocurrió un error al subir la imagen. Inténtelo nuevamente.</div>";
            }
            break;
        case 3:
            $resultado = "<div class=\"box_error\">Ocurrió un error inesperado al subir la imagen. </div>";
        default:
            $resultado = "<div class=\"box_error\">Ocurrió un error inesperado al subir la imagen. Inténtelo nuevamente.
                          Si el error persiste, contacte al webmaster.</div>";
          }
        echo $resultado;

        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
    }
      break;

//Modificación
    case 'm':
    $id = str_input($_GET['id']);
    $datos = mysql_query("SELECT * FROM ".$tabla." WHERE serv_id=".$id."") or die(mysql_error());
      $serv_nombre = str_output(mysql_result($datos,0,'serv_nombre'));
      $serv_imagen = str_output(mysql_result($datos,0,'serv_imagen'));
      $serv_resena = str_output(mysql_result($datos,0,'serv_resena'));
      $serv_pub = str_output(mysql_result($datos,0,'serv_pub'));
      $serv_cat = str_output(mysql_result($datos,0,'serv_cat'));
      $serv_dest = str_output(mysql_result($datos,0,'serv_dest'));
      $serv_fecha = str_output(mysql_result($datos,0,'serv_fecha'));
      $serv_mes = date("m",$serv_fecha);
      $serv_dia = date("d",$serv_fecha);
      $serv_anio = date("Y",$serv_fecha);
// draw_select($label,$name,$data_select,$selected,$param,'',req)
?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'serv_nombre','text',$serv_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen Antigua: ",'serv_imagen_old','text',$serv_imagen,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'serv_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'serv_cat',$cat_prod,$serv_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'serv_pub',$bin_array,$serv_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'serv_dest',$bin_array,$serv_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','serv_dia','text',$serv_dia,'','',2)."de".draw_select('','serv_mes',$meses,$serv_mes,'','')."de".draw_input('','serv_anio','text',$serv_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('serv_resena',$serv_resena,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <?php echo draw_input('','serv_imagen_old','hidden',$serv_imagen,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
    </fieldset>
    </form>
<?php break;

    case 'vm':
//Validación de Modificación
    //código de validación de modificación de elemento
      $id = str_input($_POST['id']);
      $serv_nombre = str_input($_POST['serv_nombre']);
      $serv_resena = str_input($_POST['serv_resena']);
      $serv_cat = str_input($_POST['serv_cat']);
      $serv_pub = str_input($_POST['serv_pub']);
      $serv_dest = str_input($_POST['serv_dest']);
      if (empty($_POST['serv_dia'])){$serv_dia = $hoy;} else {$serv_dia = str_input($_POST['serv_dia']);}
      if (empty($_POST['serv_mes'])){$serv_mes = $mes;} else {$serv_mes = str_input($_POST['serv_mes']);}
      if (empty($_POST['serv_anio'])){$serv_anio = $anio;} else {$serv_anio = str_input($_POST['serv_anio']);}

      $serv_fecha = mktime(1,0,0,$serv_mes,$serv_dia,$serv_anio);

      $serv_imagen_old = str_input($_POST['serv_imagen_old']);
      $img_name = str_input(strtolower(str_replace(' ', '_', $_FILES['serv_imagen']['name'])));
      $img_size = $_FILES['serv_imagen']['size'];
      $img_error = $_FILES['serv_imagen']['error'];
      $img_type = $_FILES['serv_imagen']['type'];
      $img_temp = $_FILES['serv_imagen']['tmp_name'];
      $img_now_name = $now.$img_name;

      if (!empty($img_name)){
      //echo $img_now_name;

     //check that file is of an permitted MIME type
      foreach ($permitidas as $type_image) {
        if ($type_image == $img_type){
          $typeOK = true;
          $error = "";
          break;
        }
        else {$error = "<div class=\"box_error\">Tipo de archivo no permitido. Sólo se permiten imágenes tipo JPG, PNG y GIF. </div>";}
      }
      if ($img_size <= $max_file_size){
        $sizeOK = true;
      }
      else {$error .= "<div class=\"box_error\">El archivo sobrepasa el tamaño máximo. </div>";}
      echo $error;
      if (!$sizeOK OR !$typeOK OR empty($serv_nombre) OR empty($serv_resena) OR empty($img_name) OR empty($serv_cat) OR empty($serv_pub) OR empty($serv_dest)){
        if (empty($serv_nombre) OR empty($serv_resena) OR empty($img_name) OR empty($serv_cat) OR empty($serv_pub) OR empty($serv_dest)){
          $validation .= "<div class=\"box_validation\">";
        }
        if (empty($serv_nombre)) $validation .= "Debe escribir un nombre para el servicio.<br />";
        if (empty($serv_cat)) $validation .= "Debe seleccionar una categoría para el servicio.<br />";
        if (empty($serv_pub)) $validation .= "Debe seleccionar si el servicio será publicado o no.<br />";
        if (empty($serv_dest)) $validation .= "Debe seleccionar si el servicio será destacado o no.<br />";
        if (empty($img_name)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($serv_resena)) $validation .= "Debe escribir una descripción para el servicio.<br />";
        if (empty($serv_nombre) OR empty($serv_resena) OR empty($img_name) OR empty($serv_cat) OR empty($serv_pub) OR empty($serv_dest)){
          $validation .= "</div>";
        }
        echo $validation;

    $diasmes = date('t',mktime(0,0,0,$artic_mes,1,$artic_anio));
    if ($diasmes<$artic_dia){
      echo "<div class=\"box_validation\">El mes ";
      foreach ($meses as $key => $value) { if ($key == $artic_mes) {echo $value;} }
      echo " tiene $diasmes días. <br />
      Revise su calendario e inténtelo nuevamente.</div>";
      echo "<form action=\"".$phpself."\" method=\"POST\">
      <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      exit;
      }
?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'serv_nombre','text',$serv_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen Antigua: ",'serv_imagen_old','text',$serv_imagen_old,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'serv_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'serv_cat',$cat_prod,$serv_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'serv_pub',$bin_array,$serv_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'serv_dest',$bin_array,$serv_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','serv_dia','text',$serv_dia,'','',2)."de".draw_select('','serv_mes',$meses,$serv_mes,'','')."de".draw_input('','serv_anio','text',$serv_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('serv_resena',$serv_resena,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
// Insertar
        if ($_SERVER['REQUEST_METHOD']=='POST'){
        switch($img_error) {
          case 0:
        // check if a file of the same name has been uploaded
            $success = move_uploaded_file($img_temp,$images_folder.$img_now_name);
        // redim_img($red,$nombre_imagen,$ruta_original,$ruta_thumbs);
            redim_img($red,$img_now_name,$images_folder,$thumbs_folder);
            if($success) {
              $resultado = "<div class=\"box_success\">La imagen ha sido subida con éxito.</div>";
              $sql_data = array('serv_nombre' => $serv_nombre,
                                'serv_resena' => $serv_resena,
                                'serv_fecha' => $serv_fecha,
                                'serv_imagen' => $img_now_name,
                                'serv_cat' => $serv_cat,
                                'serv_pub' => $serv_pub,
                                'serv_dest' => $serv_dest);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"serv_idx".$id);
            }
            else {
              $resultado = "<div class=\"box_error\">Ocurrió un error al subir la imagen. Inténtelo nuevamente.</div>";
            }
            break;
        case 3:
            $resultado = "<div class=\"box_error\">Ocurrió un error inesperado al subir la imagen. </div>";
        default:
            $resultado = "<div class=\"box_error\">Ocurrió un error inesperado al subir la imagen. Inténtelo nuevamente.
                          Si el error persiste, contacte al webmaster.</div>";
          }
        echo $resultado;

        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
    }
    }
    else{
      if (empty($serv_nombre) OR empty($serv_resena) OR empty($serv_imagen_old) OR empty($serv_cat) OR empty($serv_pub) OR empty($serv_dest)){
          $validation .= "<div class=\"box_validation\">";
        if (empty($serv_nombre)) $validation .= "Debe escribir un nombre para el servicio.<br />";
        if (empty($serv_cat)) $validation .= "Debe seleccionar una categoría para el servicio.<br />";
        if (empty($serv_pub)) $validation .= "Debe seleccionar si el servicio será publicado o no.<br />";
        if (empty($serv_dest)) $validation .= "Debe seleccionar si el servicio será destacado o no.<br />";
        if (empty($serv_imagen_old)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($serv_resena)) $validation .= "Debe escribir una descripción para el servicio.<br />";
          $validation .= "</div>";
        echo $validation;

    $diasmes = date('t',mktime(0,0,0,$artic_mes,1,$artic_anio));
    if ($diasmes<$artic_dia){
      echo "<div class=\"box_validation\">El mes ";
      foreach ($meses as $key => $value) { if ($key == $artic_mes) {echo $value;} }
      echo " tiene $diasmes días. <br />
      Revise su calendario e inténtelo nuevamente.</div>";
      echo "<form action=\"".$phpself."\" method=\"POST\">
      <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      exit;
      }
?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'serv_nombre','text',$serv_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen Antigua: ",'serv_imagen_old','text',$serv_imagen_old,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'serv_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'serv_cat',$cat_prod,$serv_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'serv_pub',$bin_array,$serv_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'serv_dest',$bin_array,$serv_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','serv_dia','text',$serv_dia,'','',2)."de".draw_select('','serv_mes',$meses,$serv_mes,'','')."de".draw_input('','serv_anio','text',$serv_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('serv_resena',$serv_resena,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
              $sql_data = array('serv_nombre' => $serv_nombre,
                                'serv_resena' => $serv_resena,
                                'serv_fecha' => $serv_fecha,
                                'serv_imagen' => $serv_imagen_old,
                                'serv_cat' => $serv_cat,
                                'serv_pub' => $serv_pub,
                                'serv_dest' => $serv_dest);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"serv_idx".$id);
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
    }
      break;

    case 'ce':
//Confirmar eliminación
    //código de confirmación de eliminación de elemento
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT * FROM ".$tabla." WHERE serv_id=".$id."") or die(mysql_error());
          $serv_nombre = str_output(mysql_result($datos,0,"serv_nombre"));
          $serv_imagen = str_output(mysql_result($datos,0,"serv_imagen"));
?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este servicio?</p>
        </div>
          <div align="center">
          <a href="<?php echo $url_images_folder.$serv_imagen; ?>" title="<?php echo $serv_nombre; ?>">
          <img src="<?php echo $url_thumbs_folder.$serv_imagen; ?>" border="0" align="center" alt="<?php echo $serv_nombre; ?>" /></a>
          <br />
          <strong>Nombre del Servicio:</strong> <?php echo $serv_nombre; ?></p>
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
        //$id = str_input($_GET['id']);
        $id = str_input($_POST['id']);
        $datos = mysql_query("SELECT serv_imagen FROM ".$tabla." WHERE serv_id=".$id."") or die(mysql_error());
        $archivo = str_output(mysql_result($datos,0,'serv_imagen'));
        $del_img = $images_folder.$archivo;
        $del_img_thumbs = $thumbs_folder.$archivo;
        if(unlink ($del_img)){
          unlink ($del_img_thumbs);
          if(mysql_query("DELETE FROM ".$tabla." WHERE serv_id='".$id."'") or die(mysql_error())){
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
        else { ?>
          <div class="box_error">Ocurrió un error. El archivo <?php echo $archivo; ?> no se pudo eliminar.</div>
          <form action="<?php echo $phpself; ?>" method="post">
          <div align="center"><br /><input type="submit" value="Continuar &raquo;" /></div></form>
<?php   }
      }
      break;

    case 'p':
//Publicar
    //código de publicación de elemento
      $id = str_input($_GET['id']);
      $sql_data = array('serv_id' => $id,
                        'serv_pub' => 'si');
      sql_input($tabla,$sql_data,'update',"serv_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'dp':
//Despublicar
    //código de despublicación de elemento
      $id = str_input($_GET['id']);
      $sql_data = array('serv_id' => $id,
                        'serv_pub' => 'no');
      sql_input($tabla,$sql_data,'update',"serv_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vp':
//Ver publicados
    //código de visualización de elementos publicados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE serv_pub = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT serv_id FROM ".$tabla." WHERE serv_pub = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE serv_pub = 'si' ORDER BY serv_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> Publicados</legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="20">N&ordm;</th>
            <th>Imagen</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"serv_id");
          $serv_nombre = str_output(mysql_result($datos,$j,"serv_nombre"));
          $serv_imagen = str_output(mysql_result($datos,$j,"serv_imagen"));
          $serv_resena = str_output(mysql_result($datos,$j,"serv_resena"));
          $serv_cat = str_output(mysql_result($datos,$j,"serv_cat"));
          $serv_pub = str_output(mysql_result($datos,$j,"serv_pub"));
          $serv_dest = str_output(mysql_result($datos,$j,"serv_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $serv_cat) {$serv_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_pub) {$serv_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_dest) {$serv_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$serv_imagen; ?>" title="<?php echo $serv_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$serv_imagen; ?>" border="0" align="center" alt="<?php echo $serv_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Servicio:</strong> <?php echo $serv_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $serv_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($serv_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($serv_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $serv_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$serv_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$serv_imagen; ?></span>
              <div class="descrip">
                <?php echo $serv_resena; ?>
              </div>
            </td>
<?php disp_icons_serv($celda,$phpself,$id,$serv_pub,$serv_dest); ?>

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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE serv_pub = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT serv_id FROM ".$tabla." WHERE serv_pub = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE serv_pub = 'no' ORDER BY serv_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> No Publicados</legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="20">N&ordm;</th>
            <th>Imagen</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"serv_id");
          $serv_nombre = str_output(mysql_result($datos,$j,"serv_nombre"));
          $serv_imagen = str_output(mysql_result($datos,$j,"serv_imagen"));
          $serv_resena = str_output(mysql_result($datos,$j,"serv_resena"));
          $serv_cat = str_output(mysql_result($datos,$j,"serv_cat"));
          $serv_pub = str_output(mysql_result($datos,$j,"serv_pub"));
          $serv_dest = str_output(mysql_result($datos,$j,"serv_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $serv_cat) {$serv_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_pub) {$serv_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_dest) {$serv_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$serv_imagen; ?>" title="<?php echo $serv_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$serv_imagen; ?>" border="0" align="center" alt="<?php echo $serv_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Servicio:</strong> <?php echo $serv_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $serv_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($serv_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($serv_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $serv_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$serv_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$serv_imagen; ?></span>
              <div class="descrip">
                <?php echo $serv_resena; ?>
              </div>
            </td>
<?php disp_icons_serv($celda,$phpself,$id,$serv_pub,$serv_dest); ?>
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
      $sql_data = array('serv_id' => $id,
                        'serv_dest' => 'si');
      sql_input($tabla,$sql_data,'update',"serv_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'nd':
//No destacados
    //código para quitar destacación de un elemento
      $id = str_input($_GET['id']);
      $sql_data = array('serv_id' => $id,
                        'serv_dest' => 'no');
      sql_input($tabla,$sql_data,'update',"serv_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vd':
//Ver destacados
    //código de visualización de elementos destacados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE serv_dest = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT serv_id FROM ".$tabla." WHERE serv_dest = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE serv_dest = 'si' ORDER BY serv_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> Destacados</legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="20">N&ordm;</th>
            <th>Imagen</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"serv_id");
          $serv_nombre = str_output(mysql_result($datos,$j,"serv_nombre"));
          $serv_imagen = str_output(mysql_result($datos,$j,"serv_imagen"));
          $serv_resena = str_output(mysql_result($datos,$j,"serv_resena"));
          $serv_cat = str_output(mysql_result($datos,$j,"serv_cat"));
          $serv_pub = str_output(mysql_result($datos,$j,"serv_pub"));
          $serv_dest = str_output(mysql_result($datos,$j,"serv_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $serv_cat) {$serv_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_pub) {$serv_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_dest) {$serv_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$serv_imagen; ?>" title="<?php echo $serv_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$serv_imagen; ?>" border="0" align="center" alt="<?php echo $serv_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Servicio:</strong> <?php echo $serv_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $serv_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($serv_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($serv_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $serv_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$serv_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$serv_imagen; ?></span>
              <div class="descrip">
                <?php echo $serv_resena; ?>
              </div>
            </td>
<?php disp_icons_serv($celda,$phpself,$id,$serv_pub,$serv_dest); ?>
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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE serv_dest = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT serv_id FROM ".$tabla." WHERE serv_dest = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE serv_dest = 'no' ORDER BY serv_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?> No Destacados</legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="20">N&ordm;</th>
            <th>Imagen</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"serv_id");
          $serv_nombre = str_output(mysql_result($datos,$j,"serv_nombre"));
          $serv_imagen = str_output(mysql_result($datos,$j,"serv_imagen"));
          $serv_resena = str_output(mysql_result($datos,$j,"serv_resena"));
          $serv_cat = str_output(mysql_result($datos,$j,"serv_cat"));
          $serv_pub = str_output(mysql_result($datos,$j,"serv_pub"));
          $serv_dest = str_output(mysql_result($datos,$j,"serv_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $serv_cat) {$serv_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_pub) {$serv_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_dest) {$serv_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$serv_imagen; ?>" title="<?php echo $serv_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$serv_imagen; ?>" border="0" align="center" alt="<?php echo $serv_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Servicio:</strong> <?php echo $serv_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $serv_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($serv_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($serv_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $serv_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$serv_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$serv_imagen; ?></span>
              <div class="descrip">
                <?php echo $serv_resena; ?>
              </div>
            </td>
<?php disp_icons_serv($celda,$phpself,$id,$serv_pub,$serv_dest); ?>
          </tr>
<?php     } ?>
        </table>
      </fieldset>
      <?php echo paginar($total,$pp,$st,$thisurl."?&amp;act=vnd&amp;st="); ?>
<?php  }
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

  $datos = mysql_query("SELECT serv_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY serv_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?></legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="20">N&ordm;</th>
            <th>Imagen</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"serv_id");
          $serv_nombre = str_output(mysql_result($datos,$j,"serv_nombre"));
          $serv_imagen = str_output(mysql_result($datos,$j,"serv_imagen"));
          $serv_resena = str_output(mysql_result($datos,$j,"serv_resena"));
          $serv_cat = str_output(mysql_result($datos,$j,"serv_cat"));
          $serv_pub = str_output(mysql_result($datos,$j,"serv_pub"));
          $serv_dest = str_output(mysql_result($datos,$j,"serv_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $serv_cat) {$serv_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_pub) {$serv_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $serv_dest) {$serv_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$serv_imagen; ?>" title="<?php echo $serv_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$serv_imagen; ?>" border="0" align="center" alt="<?php echo $serv_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Servicio:</strong> <?php echo $serv_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $serv_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($serv_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($serv_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $serv_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$serv_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$serv_imagen; ?></span>
              <div class="descrip">
                <?php echo $serv_resena; ?>
              </div>
            </td>
<?php disp_icons_serv($celda,$phpself,$id,$serv_pub,$serv_dest); ?>
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
      OR $_GET['act'] == 'vnd')){}
   else {draw_additem("Servicio");}


  disp_fin_info();
  disp_footer_admin($pre);
?>