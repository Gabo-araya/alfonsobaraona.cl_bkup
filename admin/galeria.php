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
$title = "Galería de Imágenes";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."gal";

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

disp_inicio_info($title,'galeria');

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
   else {draw_additem("Imagen");}


if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'gal_nombre','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_input("Descripción: ",'gal_resena','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'gal_imagen','file','','','table_horiz',38,'req'); ?>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $gal_nombre = str_input($_POST['gal_nombre']);
      $gal_resena = str_input($_POST['gal_resena']);
      $gal_name = str_input(strtolower(str_replace(' ', '_', $_FILES['gal_imagen']['name'])));
      $gal_size = $_FILES['gal_imagen']['size'];
      $gal_error = $_FILES['gal_imagen']['error'];
      $gal_type = $_FILES['gal_imagen']['type'];
      $gal_temp = $_FILES['gal_imagen']['tmp_name'];
      $gal_now_name = $now.$gal_name;
      //echo $gal_now_name;

     //check that file is of an permitted MIME type
      foreach ($permitidas as $type_image) {
        if ($type_image == $gal_type){
          $typeOK = true;
          $error = "";
          break;
        }
        else {$error = "<div class=\"box_error\">Tipo de archivo no permitido. Sólo se permiten imágenes tipo JPG, PNG y GIF. </div>";}
      }
      if ($gal_size <= $max_file_size){
        $sizeOK = true;
      }
      else {$error .= "<div class=\"box_error\">El archivo sobrepasa el tamaño máximo. </div>";}
      echo $error;
      if (!$sizeOK OR !$typeOK OR empty($gal_nombre) OR empty($gal_name) OR empty($gal_resena)){
        if (empty($gal_nombre) OR empty($gal_name) OR empty($gal_name)){
          $validation = "<div class=\"box_validation\">";
        }
        if (empty($gal_nombre)) $validation .= "Debe escribir un nombre para la imagen.<br />";
        if (empty($gal_resena)) $validation .= "Debe escribir una descripción para la imagen.<br />";
        if (empty($gal_name)) $validation .= "Debe incorporar una imagen al formulario. <br />";
        if (empty($gal_nombre) OR empty($gal_name)){
          $validation .= "</div>";
        }
        echo $validation;
?>
    <div class="box_info">Tamaño máximo de imágenes: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'gal_nombre','text',$gal_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Descripción: ",'gal_resena','text',$gal_resena,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Imagen: ",'gal_imagen','file','','','table_horiz',38,'req'); ?>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
// Insertar
        if ($_SERVER['REQUEST_METHOD']=='POST'){
        switch($gal_error) {
          case 0:
        // check if a file of the same name has been uploaded
            $success = move_uploaded_file($gal_temp,$images_folder.$gal_now_name);
        // redim_img($red,$nombre_imagen,$ruta_original,$ruta_thumbs);
            redim_img($red,$gal_now_name,$images_folder,$thumbs_folder);
            if($success) {
              $resultado = "<div class=\"box_success\">La imagen ha sido subida con éxito.</div>";
              $sql_data = array('gal_nombre' => $gal_nombre,
                                'gal_resena' => $gal_resena,
                                'gal_imagen' => $gal_now_name);
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

    case 'ce':
//Confirmar eliminación
    //código de confirmación de eliminación de elemento
        $id = str_input($_GET['id']);
        $datos = mysql_query("SELECT * FROM ".$tabla." WHERE gal_id=".$id."") or die(mysql_error());
          $gal_nombre = str_output(mysql_result($datos,0,"gal_nombre"));
          $gal_resena = str_output(mysql_result($datos,0,"gal_resena"));
          $gal_imagen = str_output(mysql_result($datos,0,"gal_imagen"));
?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar esta imagen?</p>
        </div>
          <div align="center">
          <a href="<?php echo $url_images_folder.$gal_imagen; ?>" title="<?php echo $gal_nombre; ?>">
          <img src="<?php echo $url_thumbs_folder.$gal_imagen; ?>" border="0" align="center" alt="<?php echo $gal_nombre; ?>" /></a>
          <br />
          <strong>Nombre:</strong> <?php echo $gal_nombre; ?></p>
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
        $datos = mysql_query("SELECT gal_imagen FROM ".$tabla." WHERE gal_id=".$id."") or die(mysql_error());
        $archivo = str_output(mysql_result($datos,0,'gal_imagen'));
        $del_img = $images_folder.$archivo;
        $del_gal_thumbs = $thumbs_folder.$archivo;
        if(unlink ($del_img) AND unlink ($del_gal_thumbs)){
          if(mysql_query("DELETE FROM ".$tabla." WHERE gal_id='".$id."'") or die(mysql_error())){
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

    default:
      $_SESSION = array();
      header('Location: index.php');
      exit;
    }
  }
else {
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla."") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT gal_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY gal_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
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
          $id = mysql_result($datos,$j,"gal_id");
          $gal_nombre = str_output(mysql_result($datos,$j,"gal_nombre"));
          $gal_resena = str_output(mysql_result($datos,$j,"gal_resena"));
          $gal_imagen = str_output(mysql_result($datos,$j,"gal_imagen"));
          $p = $j;
          $p++;
?>
          <tr>
            <td class="<?php echo $celda; ?>"><?php echo $p; ?></td>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $url_images_folder.$gal_imagen; ?>" title="<?php echo $gal_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$gal_imagen; ?>" border="0" align="center" alt="<?php echo $gal_nombre; ?>" /></a>
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre de la imagen:</strong> <?php echo $gal_nombre; ?></p>
              <p><strong>Nombre de archivo:</strong> <?php echo $gal_imagen; ?></p>
              <div class="descrip"> <?php echo $gal_resena; ?></div>
            </td>
<?php disp_icons_img($celda,$phpself,$id); ?>
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
   else {draw_additem("Imagen");}

  disp_fin_info();
  disp_footer_admin($pre);
?>