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
switch ($_SESSION['type']){
  case 'admin':
    break;
  case 'editor':
    break;
  default:
    $_SESSION = array();
    header('Location: index.php');
    exit;
}
$title = "Archivos";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."files";

// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

// Variables de imágenes
  $files_folder = "../".str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")))."/";
  $url_files_folder = url_images_folder($_SERVER['HTTP_REFERER'],$phpself);
  $max_file_size = MAX_FILE_SIZE;
  $max_file_size_str = number_format($max_file_size/1024, 1).' Kb';

  $permitidas = array('image/gif',
                      'image/jpeg',
                      'image/pjpeg',
                      'image/png',
                      'image/x-png',
                      'image/x-icon',
                      'text/plain',
                      'text/html',
                      'text/css',
                      'text/x-sql',
                      'application/x-javascript',
                      'application/x-msdownload',
                      'application/pdf',
                      'application/x-pdf',
                      'application/octet-stream',  //zip, rar, php
                      'application/msword',
                      'application/vnd.ms-excel',
                      'application/x-msexcel',
                      'application/ms-excel',
                      'application/vnd.ms-powerpoint',
                      'application/ms-powerpoint',
                      'text/enriched',
                      'application/rtf',
                      'application/x-shockwave-flash',
                      'audio/mpeg',
                      'image/svg+xml');

  $filetipe = array("jpg" => 'Joint Photographic Experts Group ',
                    "jpeg" => 'Joint Photographic Experts Group ',
                    "jpe" => 'Joint Photographic Experts Group ',
                    "png" => 'Portable Network Graphic',
                    "gif" => 'Graphics Interchange Format',
                    "txt" => 'Texto Plano',
                    "htm" => 'Documento de HiperTexto',
                    "html" => 'Documento de HiperTexto',
                    "css" => 'Hoja de Estilo en Cascada',
                    "js" => 'Javascript',
                    "doc" => 'Documento de Word',
                    "rtf" => 'Texto de Formato Enriquecido',
                    "xls" => 'Hoja de Cálculo',
                    "ppt" => 'Presentación de Diapositivas',
                    "pps" => 'Presentación de Diapositivas',
                    "pdf" => 'Documento de Formato Portable',
                    "zip" => 'Archivo Comprimido',
                    "swf" => 'Animación Shockwave Flash',
                    "mp3" => 'Archivo de Música',
                    "svg" => 'Archivo de Imagen Vectorial');


  $sizeOK = false;
  $typeOK = false;
  $now = date('dmY-Hi_');

disp_inicio_info($title,'archivos');

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
   else {draw_additem("Archivo");}


if (isset($_GET['act'])){
  switch ($_GET['act']){
//Elemento Nuevo
    case 'n': ?>
    <div class="box_info">Tamaño máximo de archivos: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'file_nombre','text','','','table_horiz',50,'req'); ?>
      <?php echo draw_input("Archivo: ",'file_file','file','','','table_horiz',38,'req'); ?>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php break;

//Validación de Elemento Nuevo
    case 'vn':
      $file_nombre = str_input($_POST['file_nombre']);
      //$file_file = str_input($_POST['file_file']);
      $file_name = str_input(strtolower(str_replace(' ', '_', $_FILES['file_file']['name'])));
      $file_ext = str_replace(".", "", strrchr($file_name, "."));
      $file_size = $_FILES['file_file']['size'];
      $file_error = $_FILES['file_file']['error'];
      $file_type = $_FILES['file_file']['type'];
      $file_temp = $_FILES['file_file']['tmp_name'];
      $file_now_name = $now.$file_name;
      //echo $file_now_name;

     //check that file is of an permitted MIME type
      foreach ($permitidas as $type_file) {
        if ($type_file == $file_type){
          $typeOK = true;
          $error = "";
          break;
        }
        else {$error = "<div class=\"box_error\">Tipo de archivo no permitido.</div>";}
      }
      if ($file_size <= $max_file_size){
        $sizeOK = true;
      }
      else {$error .= "<div class=\"box_error\">El archivo sobrepasa el tamaño máximo. </div>";}
      echo $error;
      if (!$sizeOK OR !$typeOK OR empty($file_nombre) OR empty($file_name)){
        if (empty($file_nombre) OR empty($file_name)){
          $validation = "<div class=\"box_validation\">";
        }
        if (empty($file_nombre)) $validation .= "Debe escribir un nombre para el archivo.<br />";
        if (empty($file_name)) $validation .= "Debe incorporar un archivo al formulario. <br />";
        if (empty($file_nombre) OR empty($file_name)){
          $validation .= "</div>";
        }
        echo $validation;
?>
    <div class="box_info">Tamaño máximo de archivos: <?php echo $max_file_size_str; ?></div>
    <form action="<?php echo $phpself; ?>?&amp;act=vn" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Agregar <?php echo $title; ?> </legend>
      <table width="100%"  border="0"  align="center">
      <?php echo draw_input("Nombre: ",'file_nombre','text',$file_nombre,'','table_horiz',50,'req'); ?>
      <?php echo draw_input("Archivo: ",'file_file','file','','','table_horiz',38,'req'); ?>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Agregar'); ?></div>
    </fieldset>
    </form>
<?php
      }
      else {
// Insertar
        if ($_SERVER['REQUEST_METHOD']=='POST'){
        switch($file_error) {
          case 0:
        // check if a file of the same name has been uploaded
            $success = move_uploaded_file($file_temp,$files_folder.$file_now_name);
            if($success) {
              $resultado = "<div class=\"box_success\">El archivo ha sido subido con éxito.</div>";
              $sql_data = array('files_nombre' => $file_nombre,
                                'files_file' => $file_now_name,
                                'files_size' => $file_size,
                                'files_ext' => $file_ext);
              //disp_array_asoc($sql_data);
              sql_input($tabla,$sql_data,'insert','');
            }
            else {
              $resultado = "<div class=\"box_error\">Ocurrió un error al subir el archivo. Inténtelo nuevamente.</div>";
            }
            break;
        case 3:
            $resultado = "<div class=\"box_error\">Ocurrió un error inesperado al subir el archivo. </div>";
        default:
            $resultado = "<div class=\"box_error\">Ocurrió un error inesperado al subir el archivo. Inténtelo nuevamente.
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
        $datos = mysql_query("SELECT * FROM ".$tabla." WHERE files_id=".$id."") or die(mysql_error());
          $file_nombre = str_output(mysql_result($datos,0,"files_nombre"));
          $file_file = str_output(mysql_result($datos,0,"files_file"));
?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este archivo?</p>
        </div>
          <div align="center">
          <strong>Nombre:</strong> <?php echo $file_nombre; ?><br />
          <strong>Archivo:</strong> <?php echo $file_file; ?>
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
        $datos = mysql_query("SELECT files_file FROM ".$tabla." WHERE files_id=".$id."") or die(mysql_error());
        $archivo = mysql_result($datos,0,'files_file');
        $del_img = $files_folder.$archivo;
        if(unlink($del_img)){
          if(mysql_query("DELETE FROM ".$tabla." WHERE files_id='".$id."'") or die(mysql_error())){
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

  $datos = mysql_query("SELECT files_id FROM ".$tabla."") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." ORDER BY files_id DESC LIMIT ".$st.",".$pp."") or die(mysql_error());
?>
      <fieldset>
        <legend><?php echo $title; ?></legend>
        <table cellpadding="4" cellspacing="2" class="table">
          <thead>
            <th width="36">Tipo</th>
            <th><?php echo $title; ?></th>
            <th>Acci&oacute;n</th>
          </thead>
<?php
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"files_id");
          $file_nombre = str_output(mysql_result($datos,$j,"files_nombre"));
          $file_file = str_output(mysql_result($datos,$j,"files_file"));
          $file_size = str_output(mysql_result($datos,$j,"files_size"));
          $file_ext = str_output(mysql_result($datos,$j,"files_ext"));
          foreach ($filetipe as $extension => $nombre) {if ($extension == $file_ext) {$name_ext = $nombre;}}
?>
          <tr>
            <td class="<?php echo $celda; ?>">
              <img src="<?php echo ICONS."file_".$file_ext; ?>.png" alt="Archivo <?php echo $file_ext; ?>" />
            </td>
            <td class="<?php echo $celda; ?>">
              <p><strong>Nombre:</strong> <?php echo $file_nombre; ?></p>
              <p><strong>Archivo:</strong> <?php echo $file_file; ?></p>
              <p><strong>Tipo:</strong> <?php echo $name_ext; ?></p>
              <p><strong>Tamaño:</strong> [<?php echo number_format($file_size/1024, 1).' Kb'; ?>]</p>
              <p><strong>URL archivo:</strong></p>
                <span class="mini"><?php echo $url_files_folder."descargar.php?&f=".$file_file; ?></span>
            </td>
<?php disp_icons_files($celda,$phpself,$url_files_folder,$id,$file_file); ?>
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
   else {draw_additem("Archivo");}

  disp_fin_info();
  disp_footer_admin($pre);
?>