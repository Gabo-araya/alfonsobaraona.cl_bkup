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
$title = "Cursos";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."cursos";

// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

// Variables de imágenes
  $images_folder = "../".str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")))."/";
  $thumbs_folder = $images_folder."thumbs/";
//  $url_images_folder = $images_folder;
//  $url_thumbs_folder = $thumbs_folder;
  $url_images_folder = url_images_folder($_SERVER['HTTP_REFERER'],$phpself);
  $url_thumbs_folder = url_thumbs_folder($_SERVER['HTTP_REFERER'],$phpself);
  $red = THUMB_WIDTH."x".THUMB_HEIGHT;
  $max_file_size = MAX_IMAGE_SIZE;
  $max_file_size_str = number_format($max_file_size/1024, 1).' Kb';
  $permitidas = array('image/gif','image/jpeg','image/pjpeg','image/png');
  $sizeOK = false;
  $typeOK = false;

// Categorías de texto cursos
      $cat_cursos = mysql_query("SELECT cat_id,cat_cursos FROM ".$pre."cat WHERE cat_cursos IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_cursos); ++$j) {
          $cat_id = mysql_result($cat_cursos,$j,"cat_id");
          $cat_cur[$cat_id] = str_output(mysql_result($cat_cursos,$j,"cat_cursos"));
        }
      asort($cat_cur);
      //echo disp_array_asoc($cat_cur);
  $bin_array = array('si' => 'Sí','no' => 'No');

disp_inicio_info($title,'cursos');

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
   else {draw_additem("Curso");}

if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Titulo: ",'cur_titulo','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'cur_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'cur_cat',$cat_cur,'','table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'cur_pub',$bin_array,'','table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'cur_dest',$bin_array,'','table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('cur_text','',60,20); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $cur_titulo = str_input($_POST['cur_titulo']);
      $cur_text = str_input($_POST['cur_text']);
      $cur_cat = str_input($_POST['cur_cat']);
      $cur_pub = str_input($_POST['cur_pub']);
      $cur_dest = str_input($_POST['cur_dest']);

      $img_name = str_input(strtolower(str_replace(' ', '_', $_FILES['cur_imagen']['name'])));
      $img_size = $_FILES['cur_imagen']['size'];
      $img_error = $_FILES['cur_imagen']['error'];
      $img_type = $_FILES['cur_imagen']['type'];
      $img_temp = $_FILES['cur_imagen']['tmp_name'];
      $img_now_name = $img_name;
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
      if (!$sizeOK OR !$typeOK OR empty($cur_titulo) OR empty($cur_text) OR empty($img_name) OR empty($cur_cat) OR empty($cur_pub) OR empty($cur_dest)){
        if (empty($cur_titulo) OR empty($cur_text) OR empty($img_name) OR empty($cur_cat) OR empty($cur_pub) OR empty($cur_dest)){
          $validation .= "<div class=\"box_validation\">";
        }
        if (empty($cur_titulo)) $validation .= "Debe escribir un titulo para el texto cure.<br />";
        if (empty($cur_cat)) $validation .= "Debe seleccionar una categoría para el texto cure.<br />";
        if (empty($cur_pub)) $validation .= "Debe seleccionar si el texto cure será publicado o no.<br />";
        if (empty($cur_dest)) $validation .= "Debe seleccionar si el texto cure será destacado o no.<br />";
        if (empty($img_name)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($cur_text)) $validation .= "Debe escribir una descripción para el texto cure.<br />";
        if (empty($cur_titulo) OR empty($cur_text) OR empty($img_name) OR empty($cur_cat) OR empty($cur_pub) OR empty($cur_dest)){
          $validation .= "</div>";
        }
        echo $validation;

    $diasmes = date('t',mktime(0,0,0,$cur_mes,1,$cur_anio));
    if ($diasmes<$cur_dia){
      echo "<div class=\"box_validation\">El mes ";
      foreach ($meses as $key => $value) { if ($key == $cur_mes) {echo $value;} }
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
      <?php echo draw_input("Titulo: ",'cur_titulo','text',$cur_titulo,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'cur_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'cur_cat',$cat_cur,$cur_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'cur_pub',$bin_array,$cur_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'cur_dest',$bin_array,$cur_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('cur_text',$cur_text,60,20); ?></td>
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
        // redim_img($red,$titulo_imagen,$ruta_original,$ruta_thumbs);
            redim_img($red,$img_now_name,$images_folder,$thumbs_folder);
            if($success) {
              $resultado = "<div class=\"box_success\">La imagen ha sido subida con éxito.</div>";
              $sql_data = array('cur_titulo' => $cur_titulo,
                                'cur_text' => $cur_text,
                                'cur_imagen' => $img_now_name,
                                'cur_cat' => $cur_cat,
                                'cur_pub' => $cur_pub,
                                'cur_dest' => $cur_dest);
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
    $datos = mysql_query("SELECT * FROM ".$tabla." WHERE cur_id=".$id."") or die(mysql_error());
      $cur_titulo = str_output(mysql_result($datos,0,'cur_titulo'));
      $cur_imagen = str_output(mysql_result($datos,0,'cur_imagen'));
      $cur_text = str_output(mysql_result($datos,0,'cur_text'));
      $cur_pub = str_output(mysql_result($datos,0,'cur_pub'));
      $cur_cat = str_output(mysql_result($datos,0,'cur_cat'));
      $cur_dest = str_output(mysql_result($datos,0,'cur_dest'));
// draw_select($label,$name,$data_select,$selected,$param,'',req)
?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Titulo: ",'cur_titulo','text',$cur_titulo,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen Antigua: ",'cur_imagen_old','text',$cur_imagen,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'cur_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'cur_cat',$cat_cur,$cur_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'cur_pub',$bin_array,$cur_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'cur_dest',$bin_array,$cur_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('cur_text',$cur_text,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <?php echo draw_input('','cur_imagen_old','hidden',$cur_imagen,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
    </fieldset>
    </form>
<?php break;

    case 'vm':
//Validación de Modificación
    //código de validación de modificación de elemento
      $id = str_input($_POST['id']);
      $cur_titulo = str_input($_POST['cur_titulo']);
      $cur_text = str_input($_POST['cur_text']);
      $cur_cat = str_input($_POST['cur_cat']);
      $cur_pub = str_input($_POST['cur_pub']);
      $cur_dest = str_input($_POST['cur_dest']);

      $cur_imagen_old = str_input($_POST['cur_imagen_old']);
      $img_name = str_input(strtolower(str_replace(' ', '_', $_FILES['cur_imagen']['name'])));
      $img_size = $_FILES['cur_imagen']['size'];
      $img_error = $_FILES['cur_imagen']['error'];
      $img_type = $_FILES['cur_imagen']['type'];
      $img_temp = $_FILES['cur_imagen']['tmp_name'];
      $img_now_name = $img_name;

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
      if (!$sizeOK OR !$typeOK OR empty($cur_titulo) OR empty($cur_text) OR empty($img_name) OR empty($cur_cat) OR empty($cur_pub) OR empty($cur_dest)){
        if (empty($cur_titulo) OR empty($cur_text) OR empty($img_name) OR empty($cur_cat) OR empty($cur_pub) OR empty($cur_dest)){
          $validation .= "<div class=\"box_validation\">";
        }
        if (empty($cur_titulo)) $validation .= "Debe escribir un titulo para el texto cure.<br />";
        if (empty($cur_cat)) $validation .= "Debe seleccionar una categoría para el texto cure.<br />";
        if (empty($cur_pub)) $validation .= "Debe seleccionar si el texto cure será publicado o no.<br />";
        if (empty($cur_dest)) $validation .= "Debe seleccionar si el texto cure será destacado o no.<br />";
        if (empty($img_name)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($cur_text)) $validation .= "Debe escribir una descripción para el texto cure.<br />";
        if (empty($cur_titulo) OR empty($cur_text) OR empty($img_name) OR empty($cur_cat) OR empty($cur_pub) OR empty($cur_dest)){
          $validation .= "</div>";
        }
        echo $validation;

?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Titulo: ",'cur_titulo','text',$cur_titulo,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen Antigua: ",'cur_imagen_old','text',$cur_imagen_old,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'cur_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'cur_cat',$cat_cur,$cur_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'cur_pub',$bin_array,$cur_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'cur_dest',$bin_array,$cur_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('cur_text',$cur_text,60,20); ?></td>
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
        // redim_img($red,$titulo_imagen,$ruta_original,$ruta_thumbs);
            redim_img($red,$img_now_name,$images_folder,$thumbs_folder);
            if($success) {
              $resultado = "<div class=\"box_success\">La imagen ha sido subida con éxito.</div>";
              $sql_data = array('cur_titulo' => $cur_titulo,
                                'cur_text' => $cur_text,
                                'cur_imagen' => $img_now_name,
                                'cur_cat' => $cur_cat,
                                'cur_pub' => $cur_pub,
                                'cur_dest' => $cur_dest);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"cur_idx".$id);
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
      if (empty($cur_titulo) OR empty($cur_text) OR empty($cur_imagen_old) OR empty($cur_cat) OR empty($cur_pub) OR empty($cur_dest)){
          $validation .= "<div class=\"box_validation\">";
        if (empty($cur_titulo)) $validation .= "Debe escribir un titulo para el texto cure.<br />";
        if (empty($cur_cat)) $validation .= "Debe seleccionar una categoría para el texto cure.<br />";
        if (empty($cur_pub)) $validation .= "Debe seleccionar si el texto cure será publicado o no.<br />";
        if (empty($cur_dest)) $validation .= "Debe seleccionar si el texto cure será destacado o no.<br />";
        if (empty($cur_imagen_old)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($cur_text)) $validation .= "Debe escribir una descripción para el texto cure.<br />";
          $validation .= "</div>";
        echo $validation;

?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Modificar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Titulo: ",'cur_titulo','text',$cur_titulo,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen Antigua: ",'cur_imagen_old','text',$cur_imagen_old,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'cur_imagen','file','','','table_horiz',38,'req'); ?>
      <?php echo draw_select("Categoría: ",'cur_cat',$cat_cur,$cur_cat,'table_horiz','','req'); ?>
      <?php echo draw_select("Publicar: ",'cur_pub',$bin_array,$cur_pub,'table_horiz','','req'); ?>
      <?php echo draw_select("Destacado: ",'cur_dest',$bin_array,$cur_dest,'table_horiz','','req'); ?>
      <tr>
        <td align="right"><label for="txt">Descripción: </label></td>
        <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('cur_text',$cur_text,60,20); ?></td>
      </tr>
      </table>
      <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
              $sql_data = array('cur_titulo' => $cur_titulo,
                                'cur_text' => $cur_text,
                                'cur_imagen' => $cur_imagen_old,
                                'cur_cat' => $cur_cat,
                                'cur_pub' => $cur_pub,
                                'cur_dest' => $cur_dest);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'update',"cur_idx".$id);
        echo "<form action=\"".$phpself."\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
      }
    }
      break;

    case 'ce':
//Confirmar eliminación
    //código de confirmación de eliminación de elemento
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT * FROM ".$tabla." WHERE cur_id=".$id."") or die(mysql_error());
          $cur_titulo = str_output(mysql_result($datos,0,"cur_titulo"));
          $cur_imagen = str_output(mysql_result($datos,0,"cur_imagen"));
?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este curso?</p>
        </div>
          <div align="center">
          <a href="<?php echo $images_folder.$cur_imagen; ?>" title="<?php echo $cur_titulo; ?>">
          <img src="<?php echo $thumbs_folder.$cur_imagen; ?>" border="0" align="center" alt="<?php echo $cur_titulo; ?>" /></a>
          <br />
          <strong>Titulo del Curso:</strong> <?php echo $cur_titulo; ?></p>
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
        $datos = mysql_query("SELECT cur_imagen FROM ".$tabla." WHERE cur_id=".$id."") or die(mysql_error());
        $archivo = str_output(mysql_result($datos,0,'cur_imagen'));
        $del_img = $images_folder.$archivo;
        $del_img_thumbs = $thumbs_folder.$archivo;
        if(unlink ($del_img)){
          unlink ($del_img_thumbs);
          if(mysql_query("DELETE FROM ".$tabla." WHERE cur_id='".$id."'") or die(mysql_error())){
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
      $sql_data = array('cur_id' => $id,
                        'cur_pub' => 'si');
      sql_input($tabla,$sql_data,'update',"cur_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'dp':
//Despublicar
    //código de despublicación de elemento
      $id = str_input($_GET['id']);
      $sql_data = array('cur_id' => $id,
                        'cur_pub' => 'no');
      sql_input($tabla,$sql_data,'update',"cur_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vp':
//Ver publicados
    //código de visualización de elementos publicados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE cur_pub = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT cur_id FROM ".$tabla." WHERE cur_pub = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE cur_pub = 'si' ORDER BY cur_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"cur_id");
          $cur_titulo = str_output(mysql_result($datos,$j,"cur_titulo"));
          $cur_imagen = str_output(mysql_result($datos,$j,"cur_imagen"));
          $cur_text = str_output(mysql_result($datos,$j,"cur_text"));
          $cur_cat = str_output(mysql_result($datos,$j,"cur_cat"));
          $cur_pub = str_output(mysql_result($datos,$j,"cur_pub"));
          $cur_dest = str_output(mysql_result($datos,$j,"cur_dest"));
          $p = $j;
          $p++;

          foreach ($cat_cur as $key => $value) {if ($key == $cur_cat) {$cur_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_pub) {$cur_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_dest) {$cur_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $images_folder.$cur_imagen; ?>" title="<?php echo $cur_titulo; ?>">
              <img src="<?php echo $thumbs_folder.$cur_imagen; ?>" border="0" align="center" alt="<?php echo $cur_titulo; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Titulo del Curso:</strong> <?php echo $cur_titulo; ?></p>
              <p><strong>Categoría:</strong> <?php echo $cur_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($cur_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($cur_dest_name); ?></p>
              <p><strong>Titulo de archivo:</strong> <?php echo $cur_imagen; ?></p>
            </td>
<?php disp_icons_cur($celda,$phpself,$id,$cur_pub,$cur_dest); ?>

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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE cur_pub = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT cur_id FROM ".$tabla." WHERE cur_pub = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE cur_pub = 'no' ORDER BY cur_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"cur_id");
          $cur_titulo = str_output(mysql_result($datos,$j,"cur_titulo"));
          $cur_imagen = str_output(mysql_result($datos,$j,"cur_imagen"));
          $cur_text = str_output(mysql_result($datos,$j,"cur_text"));
          $cur_cat = str_output(mysql_result($datos,$j,"cur_cat"));
          $cur_pub = str_output(mysql_result($datos,$j,"cur_pub"));
          $cur_dest = str_output(mysql_result($datos,$j,"cur_dest"));
          $p = $j;
          $p++;

          foreach ($cat_cur as $key => $value) {if ($key == $cur_cat) {$cur_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_pub) {$cur_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_dest) {$cur_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $images_folder.$cur_imagen; ?>" title="<?php echo $cur_titulo; ?>">
              <img src="<?php echo $thumbs_folder.$cur_imagen; ?>" border="0" align="center" alt="<?php echo $cur_titulo; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Titulo del Curso:</strong> <?php echo $cur_titulo; ?></p>
              <p><strong>Categoría:</strong> <?php echo $cur_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($cur_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($cur_dest_name); ?></p>
              <p><strong>Titulo de archivo:</strong> <?php echo $cur_imagen; ?></p>
            </td>
<?php disp_icons_cur($celda,$phpself,$id,$cur_pub,$cur_dest); ?>
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
      $sql_data = array('cur_id' => $id,
                        'cur_dest' => 'si');
      sql_input($tabla,$sql_data,'update',"cur_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'nd':
//No destacados
    //código para quitar destacación de un elemento
      $id = str_input($_GET['id']);
      $sql_data = array('cur_id' => $id,
                        'cur_dest' => 'no');
      sql_input($tabla,$sql_data,'update',"cur_idx".$id,'');
      header("Location: ".$phpself);
      break;

    case 'vd':
//Ver destacados
    //código de visualización de elementos destacados
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE cur_dest = 'si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT cur_id FROM ".$tabla." WHERE cur_dest = 'si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE cur_dest = 'si' ORDER BY cur_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"cur_id");
          $cur_titulo = str_output(mysql_result($datos,$j,"cur_titulo"));
          $cur_imagen = str_output(mysql_result($datos,$j,"cur_imagen"));
          $cur_text = str_output(mysql_result($datos,$j,"cur_text"));
          $cur_cat = str_output(mysql_result($datos,$j,"cur_cat"));
          $cur_pub = str_output(mysql_result($datos,$j,"cur_pub"));
          $cur_dest = str_output(mysql_result($datos,$j,"cur_dest"));
          $p = $j;
          $p++;

          foreach ($cat_cur as $key => $value) {if ($key == $cur_cat) {$cur_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_pub) {$cur_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_dest) {$cur_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $images_folder.$cur_imagen; ?>" title="<?php echo $cur_titulo; ?>">
              <img src="<?php echo $thumbs_folder.$cur_imagen; ?>" border="0" align="center" alt="<?php echo $cur_titulo; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Titulo del Curso:</strong> <?php echo $cur_titulo; ?></p>
              <p><strong>Categoría:</strong> <?php echo $cur_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($cur_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($cur_dest_name); ?></p>
              <p><strong>Titulo de archivo:</strong> <?php echo $cur_imagen; ?></p>
            </td>
<?php disp_icons_cur($celda,$phpself,$id,$cur_pub,$cur_dest); ?>
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
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE cur_dest = 'no'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT cur_id FROM ".$tabla." WHERE cur_dest = 'no'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla."  WHERE cur_dest = 'no' ORDER BY cur_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"cur_id");
          $cur_titulo = str_output(mysql_result($datos,$j,"cur_titulo"));
          $cur_imagen = str_output(mysql_result($datos,$j,"cur_imagen"));
          $cur_text = str_output(mysql_result($datos,$j,"cur_text"));
          $cur_cat = str_output(mysql_result($datos,$j,"cur_cat"));
          $cur_pub = str_output(mysql_result($datos,$j,"cur_pub"));
          $cur_dest = str_output(mysql_result($datos,$j,"cur_dest"));
          $p = $j;
          $p++;

          foreach ($cat_cur as $key => $value) {if ($key == $cur_cat) {$cur_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_pub) {$cur_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_dest) {$cur_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $images_folder.$cur_imagen; ?>" title="<?php echo $cur_titulo; ?>">
              <img src="<?php echo $thumbs_folder.$cur_imagen; ?>" border="0" align="center" alt="<?php echo $cur_titulo; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Titulo del Curso:</strong> <?php echo $cur_titulo; ?></p>
              <p><strong>Categoría:</strong> <?php echo $cur_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($cur_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($cur_dest_name); ?></p>
              <p><strong>Titulo de archivo:</strong> <?php echo $cur_imagen; ?></p>
            </td>
<?php disp_icons_cur($celda,$phpself,$id,$cur_pub,$cur_dest); ?>
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

  $datos = mysql_query("SELECT cur_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY cur_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"cur_id");
          $cur_titulo = str_output(mysql_result($datos,$j,"cur_titulo"));
          $cur_imagen = str_output(mysql_result($datos,$j,"cur_imagen"));
          $cur_text = str_output(mysql_result($datos,$j,"cur_text"));
          $cur_cat = str_output(mysql_result($datos,$j,"cur_cat"));
          $cur_pub = str_output(mysql_result($datos,$j,"cur_pub"));
          $cur_dest = str_output(mysql_result($datos,$j,"cur_dest"));
          $p = $j;
          $p++;

          foreach ($cat_cur as $key => $value) {if ($key == $cur_cat) {$cur_cat_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_pub) {$cur_pub_name = "$value";}}
          foreach ($bin_array as $key => $value) {if ($key == $cur_dest) {$cur_dest_name = "$value";}}
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $images_folder.$cur_imagen; ?>" title="<?php echo $cur_titulo; ?>">
              <img src="<?php echo $thumbs_folder.$cur_imagen; ?>" border="0" align="center" alt="<?php echo $cur_titulo; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Titulo del Curso:</strong> <?php echo $cur_titulo; ?></p>
              <p><strong>Categoría:</strong> <?php echo $cur_cat_name; ?></p>
              <p><strong>Publicado:</strong> <?php echo ucfirst($cur_pub_name); ?></p>
              <p><strong>Destacado:</strong> <?php echo ucfirst($cur_dest_name); ?></p>
              <p><strong>Titulo de archivo:</strong> <?php echo $cur_imagen; ?></p>
            </td>
<?php disp_icons_cur($celda,$phpself,$id,$cur_pub,$cur_dest); ?>
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
   else {draw_additem("Curso");}


  disp_fin_info();
  disp_footer_admin($pre);
?>