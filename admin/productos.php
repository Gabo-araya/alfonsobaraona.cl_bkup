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
$title = "Productos";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."prod";

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

// Categorías de productos
      $cat_productos = mysql_query("SELECT cat_id,cat_productos FROM ".$pre."cat WHERE cat_productos IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_productos); ++$j) {
          $cat_id = mysql_result($cat_productos,$j,"cat_id");
          $cat_prod[$cat_id] = str_output(mysql_result($cat_productos,$j,"cat_productos"));
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

disp_inicio_info($title,'productos');

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
   else {draw_additem("Producto");}

if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'prod_nombre','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'prod_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'prod_cat',$cat_prod,'','table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'prod_pub',$bin_array,'','table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'prod_dest',$bin_array,'','table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','prod_dia','text',$hoy,'','',2)."de".draw_select('','prod_mes',$meses,$mes,'','')."de".draw_input('','prod_anio','text',$anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('prod_resena','',60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $prod_nombre = str_input($_POST['prod_nombre']);
      $prod_resena = str_input($_POST['prod_resena']);
      $prod_cat = str_input($_POST['prod_cat']);
      $prod_pub = str_input($_POST['prod_pub']);
      $prod_dest = str_input($_POST['prod_dest']);
      if (empty($_POST['prod_dia'])){$prod_dia = $hoy;} else {$prod_dia = str_input($_POST['prod_dia']);}
      if (empty($_POST['prod_mes'])){$prod_mes = $mes;} else {$prod_mes = str_input($_POST['prod_mes']);}
      if (empty($_POST['prod_anio'])){$prod_anio = $anio;} else {$prod_anio = str_input($_POST['prod_anio']);}

      $prod_fecha = mktime(1,0,0,$prod_mes,$prod_dia,$prod_anio);

      $img_name = str_input(strtolower(str_replace(' ', '_', $_FILES['prod_imagen']['name'])));
      $img_size = $_FILES['prod_imagen']['size'];
      $img_error = $_FILES['prod_imagen']['error'];
      $img_type = $_FILES['prod_imagen']['type'];
      $img_temp = $_FILES['prod_imagen']['tmp_name'];
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
      if (!$sizeOK OR !$typeOK OR empty($prod_nombre) OR empty($prod_resena) OR empty($img_name) OR empty($prod_cat) OR empty($prod_pub) OR empty($prod_dest)){
        if (empty($prod_nombre) OR empty($prod_resena) OR empty($img_name) OR empty($prod_cat) OR empty($prod_pub) OR empty($prod_dest)){
          $validation .= "<div class=\"box_validation\">";
        }
        if (empty($prod_nombre)) $validation .= "Debe escribir un nombre para el producto.<br />";
        if (empty($prod_cat)) $validation .= "Debe seleccionar una categoría para el producto.<br />";
        if (empty($prod_pub)) $validation .= "Debe seleccionar si el producto será publicado o no.<br />";
        if (empty($prod_dest)) $validation .= "Debe seleccionar si el producto será destacado o no.<br />";
        if (empty($img_name)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($prod_resena)) $validation .= "Debe escribir una descripción para el producto.<br />";
        if (empty($prod_nombre) OR empty($prod_resena) OR empty($img_name) OR empty($prod_cat) OR empty($prod_pub) OR empty($prod_dest)){
          $validation .= "</div>";
        }
        echo $validation;

    $diasmes = date('t',mktime(0,0,0,$prod_mes,1,$prod_anio));
    if ($diasmes<$prod_dia){
      echo "<div class=\"box_validation\">El mes ";
      foreach ($meses as $key => $value) { if ($key == $prod_mes) {echo $value;} }
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
      <?php echo draw_input("Nombre: ",'prod_nombre','text',$prod_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'prod_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'prod_cat',$cat_prod,$prod_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'prod_pub',$bin_array,$prod_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'prod_dest',$bin_array,$prod_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','prod_dia','text',$prod_dia,'','',2)."de".draw_select('','prod_mes',$meses,$prod_mes,'','')."de".draw_input('','prod_anio','text',$prod_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('prod_resena',$prod_resena,60,20); ?></td>
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
              $sql_data = array('prod_nombre' => $prod_nombre,
                                'prod_resena' => $prod_resena,
                                'prod_fecha' => $prod_fecha,
                                'prod_imagen' => $img_now_name,
                                'prod_cat' => $prod_cat,
                                'prod_pub' => $prod_pub,
                                'prod_dest' => $prod_dest);
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
    $datos = mysql_query("SELECT * FROM ".$tabla." WHERE prod_id=".$id."") or die(mysql_error());
      $prod_nombre = str_output(mysql_result($datos,0,'prod_nombre'));
      $prod_imagen = str_output(mysql_result($datos,0,'prod_imagen'));
      $prod_resena = str_output(mysql_result($datos,0,'prod_resena'));
      $prod_pub = str_output(mysql_result($datos,0,'prod_pub'));
      $prod_cat = str_output(mysql_result($datos,0,'prod_cat'));
      $prod_dest = str_output(mysql_result($datos,0,'prod_dest'));
      $prod_fecha = str_output(mysql_result($datos,0,'prod_fecha'));
      $prod_mes = date("m",$prod_fecha);
      $prod_dia = date("d",$prod_fecha);
      $prod_anio = date("Y",$prod_fecha);
// draw_select($label,$name,$data_select,$selected,$param,'',req)
?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'prod_nombre','text',$prod_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen Antigua: ",'prod_imagen_old','text',$prod_imagen,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'prod_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'prod_cat',$cat_prod,$prod_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'prod_pub',$bin_array,$prod_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'prod_dest',$bin_array,$prod_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','prod_dia','text',$prod_dia,'','',2)."de".draw_select('','prod_mes',$meses,$prod_mes,'','')."de".draw_input('','prod_anio','text',$prod_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('prod_resena',$prod_resena,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <?php echo draw_input('','prod_imagen_old','hidden',$prod_imagen,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
    </fieldset>
    </form>
<?php break;

    case 'vm':
//Validación de Modificación
    //código de validación de modificación de elemento
      $id = str_input($_POST['id']);
      $prod_nombre = str_input($_POST['prod_nombre']);
      $prod_resena = str_input($_POST['prod_resena']);
      $prod_cat = str_input($_POST['prod_cat']);
      $prod_pub = str_input($_POST['prod_pub']);
      $prod_dest = str_input($_POST['prod_dest']);
      if (empty($_POST['prod_dia'])){$prod_dia = $hoy;} else {$prod_dia = str_input($_POST['prod_dia']);}
      if (empty($_POST['prod_mes'])){$prod_mes = $mes;} else {$prod_mes = str_input($_POST['prod_mes']);}
      if (empty($_POST['prod_anio'])){$prod_anio = $anio;} else {$prod_anio = str_input($_POST['prod_anio']);}

      $prod_fecha = mktime(1,0,0,$prod_mes,$prod_dia,$prod_anio);

      $prod_imagen_old = str_input($_POST['prod_imagen_old']);
      $img_name = str_input(strtolower(str_replace(' ', '_', $_FILES['prod_imagen']['name'])));
      $img_size = $_FILES['prod_imagen']['size'];
      $img_error = $_FILES['prod_imagen']['error'];
      $img_type = $_FILES['prod_imagen']['type'];
      $img_temp = $_FILES['prod_imagen']['tmp_name'];
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
      if (!$sizeOK OR !$typeOK OR empty($prod_nombre) OR empty($prod_resena) OR empty($img_name) OR empty($prod_cat) OR empty($prod_pub) OR empty($prod_dest)){
        if (empty($prod_nombre) OR empty($prod_resena) OR empty($img_name) OR empty($prod_cat) OR empty($prod_pub) OR empty($prod_dest)){
          $validation .= "<div class=\"box_validation\">";
        }
        if (empty($prod_nombre)) $validation .= "Debe escribir un nombre para el producto.<br />";
        if (empty($prod_cat)) $validation .= "Debe seleccionar una categoría para el producto.<br />";
        if (empty($prod_pub)) $validation .= "Debe seleccionar si el producto será publicado o no.<br />";
        if (empty($prod_dest)) $validation .= "Debe seleccionar si el producto será destacado o no.<br />";
        if (empty($img_name)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($prod_resena)) $validation .= "Debe escribir una descripción para el producto.<br />";
        if (empty($prod_nombre) OR empty($prod_resena) OR empty($img_name) OR empty($prod_cat) OR empty($prod_pub) OR empty($prod_dest)){
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
      <?php echo draw_input("Nombre: ",'prod_nombre','text',$prod_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen Antigua: ",'prod_imagen_old','text',$prod_imagen_old,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'prod_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'prod_cat',$cat_prod,$prod_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'prod_pub',$bin_array,$prod_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'prod_dest',$bin_array,$prod_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','prod_dia','text',$prod_dia,'','',2)."de".draw_select('','prod_mes',$meses,$prod_mes,'','')."de".draw_input('','prod_anio','text',$prod_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('prod_resena',$prod_resena,60,20); ?></td>
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
              $sql_data = array('prod_nombre' => $prod_nombre,
                                'prod_resena' => $prod_resena,
                                'prod_fecha' => $prod_fecha,
                                'prod_imagen' => $img_now_name,
                                'prod_cat' => $prod_cat,
                                'prod_pub' => $prod_pub,
                                'prod_dest' => $prod_dest);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"prod_idx".$id);
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
      if (empty($prod_nombre) OR empty($prod_resena) OR empty($prod_imagen_old) OR empty($prod_cat) OR empty($prod_pub) OR empty($prod_dest)){
          $validation .= "<div class=\"box_validation\">";
        if (empty($prod_nombre)) $validation .= "Debe escribir un nombre para el producto.<br />";
        if (empty($prod_cat)) $validation .= "Debe seleccionar una categoría para el producto.<br />";
        if (empty($prod_pub)) $validation .= "Debe seleccionar si el producto será publicado o no.<br />";
        if (empty($prod_dest)) $validation .= "Debe seleccionar si el producto será destacado o no.<br />";
        if (empty($prod_imagen_old)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($prod_resena)) $validation .= "Debe escribir una descripción para el producto.<br />";
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
      <?php echo draw_input("Nombre: ",'prod_nombre','text',$prod_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen Antigua: ",'prod_imagen_old','text',$prod_imagen_old,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'prod_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'prod_cat',$cat_prod,$prod_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'prod_pub',$bin_array,$prod_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'prod_dest',$bin_array,$prod_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label>Fecha: </label></td>
        <td><?php echo draw_input('','prod_dia','text',$prod_dia,'','',2)."de".draw_select('','prod_mes',$meses,$prod_mes,'','')."de".draw_input('','prod_anio','text',$prod_anio,'','',2).draw_req(); ?>
        </td>
      </tr>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('prod_resena',$prod_resena,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
              $sql_data = array('prod_nombre' => $prod_nombre,
                                'prod_resena' => $prod_resena,
                                'prod_fecha' => $prod_fecha,
                                'prod_imagen' => $prod_imagen_old,
                                'prod_cat' => $prod_cat,
                                'prod_pub' => $prod_pub,
                                'prod_dest' => $prod_dest);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"prod_idx".$id);
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
    }
      break;

    case 'ce':
//Confirmar eliminación
    //código de confirmación de eliminación de elemento
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT * FROM ".$tabla." WHERE prod_id=".$id."") or die(mysql_error());
          $prod_nombre = str_output(mysql_result($datos,0,"prod_nombre"));
          $prod_imagen = str_output(mysql_result($datos,0,"prod_imagen"));
?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este producto?</p>
        </div>
          <div align="center">
          <a href="<?php echo $url_images_folder.$prod_imagen; ?>" title="<?php echo $prod_nombre; ?>">
          <img src="<?php echo $url_thumbs_folder.$prod_imagen; ?>" border="0" align="center" alt="<?php echo $prod_nombre; ?>" /></a>
          <br />
          <strong>Nombre del Producto:</strong> <?php echo $prod_nombre; ?></p>
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
        $datos = mysql_query("SELECT prod_imagen FROM ".$tabla." WHERE prod_id=".$id."") or die(mysql_error());
        $archivo = str_output(mysql_result($datos,0,'prod_imagen'));
        $del_img = $images_folder.$archivo;
        $del_img_thumbs = $thumbs_folder.$archivo;
        if(unlink ($del_img)){
          unlink ($del_img_thumbs);
          if(mysql_query("DELETE FROM ".$tabla." WHERE prod_id='".$id."'") or die(mysql_error())){
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
      $sql_data = array('prod_id' => $id,
                        'prod_pub' => 'si');
      sql_input($tabla,$sql_data,'update',"prod_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'dp':
//Despublicar
    //código de despublicación de elemento
      $id = str_input($_GET['id']);
      $sql_data = array('prod_id' => $id,
                        'prod_pub' => 'no');
      sql_input($tabla,$sql_data,'update',"prod_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vp':
//Ver publicados
    //código de visualización de elementos publicados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE prod_pub = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT prod_id FROM ".$tabla." WHERE prod_pub = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE prod_pub = 'si' ORDER BY prod_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"prod_id");
          $prod_nombre = str_output(mysql_result($datos,$j,"prod_nombre"));
          $prod_imagen = str_output(mysql_result($datos,$j,"prod_imagen"));
          $prod_resena = str_output(mysql_result($datos,$j,"prod_resena"));
          $prod_cat = str_output(mysql_result($datos,$j,"prod_cat"));
          $prod_pub = str_output(mysql_result($datos,$j,"prod_pub"));
          $prod_dest = str_output(mysql_result($datos,$j,"prod_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $prod_cat) {$prod_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_pub) {$prod_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_dest) {$prod_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$prod_imagen; ?>" title="<?php echo $prod_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$prod_imagen; ?>" border="0" align="center" alt="<?php echo $prod_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Producto:</strong> <?php echo $prod_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $prod_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($prod_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($prod_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $prod_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$prod_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$prod_imagen; ?></span>
              <div class="descrip">
                <?php echo $prod_resena; ?>
              </div>
            </td>
<?php disp_icons_prod($celda,$phpself,$id,$prod_pub,$prod_dest); ?>

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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE prod_pub = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT prod_id FROM ".$tabla." WHERE prod_pub = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE prod_pub = 'no' ORDER BY prod_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"prod_id");
          $prod_nombre = str_output(mysql_result($datos,$j,"prod_nombre"));
          $prod_imagen = str_output(mysql_result($datos,$j,"prod_imagen"));
          $prod_resena = str_output(mysql_result($datos,$j,"prod_resena"));
          $prod_cat = str_output(mysql_result($datos,$j,"prod_cat"));
          $prod_pub = str_output(mysql_result($datos,$j,"prod_pub"));
          $prod_dest = str_output(mysql_result($datos,$j,"prod_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $prod_cat) {$prod_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_pub) {$prod_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_dest) {$prod_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$prod_imagen; ?>" title="<?php echo $prod_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$prod_imagen; ?>" border="0" align="center" alt="<?php echo $prod_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Producto:</strong> <?php echo $prod_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $prod_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($prod_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($prod_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $prod_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$prod_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$prod_imagen; ?></span>
              <div class="descrip">
                <?php echo $prod_resena; ?>
              </div>
            </td>
<?php disp_icons_prod($celda,$phpself,$id,$prod_pub,$prod_dest); ?>
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
      $sql_data = array('prod_id' => $id,
                        'prod_dest' => 'si');
      sql_input($tabla,$sql_data,'update',"prod_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'nd':
//No destacados
    //código para quitar destacación de un elemento
      $id = str_input($_GET['id']);
      $sql_data = array('prod_id' => $id,
                        'prod_dest' => 'no');
      sql_input($tabla,$sql_data,'update',"prod_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vd':
//Ver destacados
    //código de visualización de elementos destacados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE prod_dest = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT prod_id FROM ".$tabla." WHERE prod_dest = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE prod_dest = 'si' ORDER BY prod_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"prod_id");
          $prod_nombre = str_output(mysql_result($datos,$j,"prod_nombre"));
          $prod_imagen = str_output(mysql_result($datos,$j,"prod_imagen"));
          $prod_resena = str_output(mysql_result($datos,$j,"prod_resena"));
          $prod_cat = str_output(mysql_result($datos,$j,"prod_cat"));
          $prod_pub = str_output(mysql_result($datos,$j,"prod_pub"));
          $prod_dest = str_output(mysql_result($datos,$j,"prod_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $prod_cat) {$prod_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_pub) {$prod_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_dest) {$prod_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$prod_imagen; ?>" title="<?php echo $prod_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$prod_imagen; ?>" border="0" align="center" alt="<?php echo $prod_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Producto:</strong> <?php echo $prod_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $prod_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($prod_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($prod_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $prod_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$prod_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$prod_imagen; ?></span>
              <div class="descrip">
                <?php echo $prod_resena; ?>
              </div>
            </td>
<?php disp_icons_prod($celda,$phpself,$id,$prod_pub,$prod_dest); ?>
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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE prod_dest = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT prod_id FROM ".$tabla." WHERE prod_dest = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE prod_dest = 'no' ORDER BY prod_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"prod_id");
          $prod_nombre = str_output(mysql_result($datos,$j,"prod_nombre"));
          $prod_imagen = str_output(mysql_result($datos,$j,"prod_imagen"));
          $prod_resena = str_output(mysql_result($datos,$j,"prod_resena"));
          $prod_cat = str_output(mysql_result($datos,$j,"prod_cat"));
          $prod_pub = str_output(mysql_result($datos,$j,"prod_pub"));
          $prod_dest = str_output(mysql_result($datos,$j,"prod_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $prod_cat) {$prod_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_pub) {$prod_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_dest) {$prod_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$prod_imagen; ?>" title="<?php echo $prod_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$prod_imagen; ?>" border="0" align="center" alt="<?php echo $prod_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Producto:</strong> <?php echo $prod_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $prod_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($prod_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($prod_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $prod_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$prod_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$prod_imagen; ?></span>
              <div class="descrip">
                <?php echo $prod_resena; ?>
              </div>
            </td>
<?php disp_icons_prod($celda,$phpself,$id,$prod_pub,$prod_dest); ?>
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

  $datos = mysql_query("SELECT prod_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY prod_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"prod_id");
          $prod_nombre = str_output(mysql_result($datos,$j,"prod_nombre"));
          $prod_imagen = str_output(mysql_result($datos,$j,"prod_imagen"));
          $prod_resena = str_output(mysql_result($datos,$j,"prod_resena"));
          $prod_cat = str_output(mysql_result($datos,$j,"prod_cat"));
          $prod_pub = str_output(mysql_result($datos,$j,"prod_pub"));
          $prod_dest = str_output(mysql_result($datos,$j,"prod_dest"));
          $p = $j;
          $p++;

          foreach ($cat_prod as $key => $value) {if ($key == $prod_cat) {$prod_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_pub) {$prod_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $prod_dest) {$prod_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$prod_imagen; ?>" title="<?php echo $prod_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$prod_imagen; ?>" border="0" align="center" alt="<?php echo $prod_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre del Producto:</strong> <?php echo $prod_nombre; ?></p>
              <p><strong>Categoría:</strong> <?php echo $prod_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($prod_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($prod_dest_name); ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $prod_imagen; ?></p>
              <p><strong>URL imagen pequeña:</strong></p>
                <span class="mini"><?php echo $url_thumbs_folder.$prod_imagen; ?></span>
              <p><strong>URL imagen original:</strong></p>
                <span class="mini"><?php echo $url_images_folder.$prod_imagen; ?></span>
              <div class="descrip">
                <?php echo $prod_resena; ?>
              </div>
            </td>
<?php disp_icons_prod($celda,$phpself,$id,$prod_pub,$prod_dest); ?>
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
   else {draw_additem("Producto");}


  disp_fin_info();
  disp_footer_admin($pre);
?>