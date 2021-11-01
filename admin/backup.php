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
  default:
    $_SESSION = array();
    header('Location: index.php');
    exit;
  }
$title = "Backup";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
  $files_folder = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")))."/";
//  echo $files_folder;

  $data_select = array("not" => 'Noticias',
                       "prod" => 'Productos',
                       "artic" => 'Artículos');

disp_inicio_info($title,'backup');

if (isset($_GET['act'])){
  switch ($_GET['act']){
//Crear Backup de base de datos
    case 'c':
      /* Nombre del fichero que se descargará. */
      $fecha = date("d.m.Y_-_h.i.s_a", time());
      $filename = "backup_".$fecha;
      /* Determina si la tabla será vaciada (si existe) cuando  restauremos la tabla. */
      $drop = true;
      /*
       * Array que contiene las tablas de la base de datos que seran resguardadas.
       * Puede especificarse un valor false para resguardar todas las tablas
       * de la base de datos especificada en  $bd.
       *
       * Ejs.:
       * $tablas = false;
       *    o
       * $tablas = array("tabla1", "tabla2", "tablaetc");
       *
       */
      $tablas = false;
      /*
       * Tipo de compresion.
       * Puede ser "gz", "bz2", o false (sin comprimir)
       */
       $compresion = false;

       /* Se busca las tablas en la base de datos */
       if( empty($tablas)){
         $consulta = "SHOW TABLES FROM $sql_db;";
         $respuesta = mysql_query($consulta)
         or die("No se pudo ejecutar la consulta: ".mysql_error());
         while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)){
           $tablas[] = $fila[0];
         }
       }

       /* Se crea la cabecera del archivo */
       $info['dumpversion'] = "1.2";
       $info['fecha'] = date("d-m-Y");
       $info['hora'] = date("h:m:s A");
       $info['mysqlver'] = mysql_get_server_info();
       $info['phpver'] = phpversion();
//       ob_start();
//       print_r($tablas);
       $representacion = ob_get_contents();
//       ob_end_clean ();
       preg_match_all('/(\[\d+\] => .*)\n/', $representacion, $matches);
       $info['tablas'] = implode(";  ", $matches[1]);
$dump = <<<EOT
# +===================================================================
# |
# | Generado el {$info['fecha']} a las {$info['hora']}
# | Servidor: {$_SERVER['HTTP_HOST']}
# | MySQL Version: {$info['mysqlver']}
# | PHP Version: {$info['phpver']}
# | Base de datos: '$sql_db'
# |
# +-------------------------------------------------------------------
EOT;

       foreach($tablas as $tabla){
         $drop_table_query = "";
         $create_table_query = "";
         $insert_into_query = "";

         /* Vaciar la tabla. */
         if($drop){
           $drop_table_query = "DROP TABLE IF EXISTS `$tabla`;";
         }
         else{
           $drop_table_query = "# No especificado.";
         }

         /* Recrear la estructura de la tabla. */
         $create_table_query = "";
         $consulta = "SHOW CREATE TABLE $tabla;";
         $respuesta = mysql_query($consulta) or die("No se pudo ejecutar la consulta: ".mysql_error());
         while($fila = mysql_fetch_array($respuesta, MYSQL_NUM)){
           $create_table_query = $fila[1].";";
         }

         /* Insertar los datos. */
         $insert_into_query = "";
         $consulta = "SELECT * FROM $tabla;";
         $respuesta = mysql_query($consulta)
         or die("No se pudo ejecutar la consulta: ".mysql_error());
         while($fila = mysql_fetch_array($respuesta, MYSQL_ASSOC)){
           $columnas = array_keys($fila);
           foreach ($columnas as $columna) {
             if(gettype($fila[$columna]) == "NULL"){
               $values[] = "NULL";
             }
             else{
               $values[] = "'".mysql_real_escape_string($fila[$columna])."'";
             }
           }
           $insert_into_query .= "INSERT INTO `$tabla` VALUES (".implode(", ", $values).");\n";
           unset($values);
         }

$dump .= <<<EOT

# | Vaciado de tabla '$tabla'
# +------------------------------------->
$drop_table_query


# | Estructura de la tabla '$tabla'
# +------------------------------------->
$create_table_query


# | Carga de datos de la tabla '$tabla'
# +------------------------------------->
$insert_into_query

EOT;
       } //fin foreach tablas...

        $backup_filename = "backup/".$filename.".sql";
        $backup_file = fopen("/home/jbaraona/public_html/admin/".$backup_filename,"a");
        if($backup_file){
          fwrite($backup_file,$dump);
          fclose($backup_file);
          echo "<div class=\"box_success\">El archivo \"".$filename.".sql\" ha sido creado exitosamente.</div>";
          echo "<form action=\"".$phpself."\" method=\"POST\">
          <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
        }
        else{
          echo "<div class=\"box_error\">Ha ocurrido un error y no se creado el archivo \"".$filename.".sql\". Avise al administrador.</div>";
          echo "<form action=\"".$phpself."\" method=\"POST\">
          <div align=\"center\"><br /><input type=\"submit\" value=\"Continuar &raquo;\" /></div></form>";
        }
      break;

//Confirmar eliminación
    case 'ce':
    //código de confirmación de eliminación de elemento
        $file_file = str_input($_GET['f']);
?>
      <fieldset>
        <legend>Eliminar <?php echo $title; ?></legend>
        <div class="box_info">
          <p>¿Está seguro/a que desea eliminar este archivo?</p>
        </div>
          <div align="center">
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
                <?php echo draw_input('','file_file','hidden',$file_file,'','','',''); ?>
                <input type="submit" value="Sí &raquo;" /></form>
              </td>
            </tr>
          </table>
          </div>
      </fieldset>
<?php
      break;

//Eliminar imagen y registro
    case 'e':
    //código de eliminación de elemento
      if($_SERVER['REQUEST_METHOD']=='POST'){
        $file_file = str_input($_POST['file_file']);
        $del_img = $files_folder.$file_file;
        if(unlink($del_img)){
?>
          <div class="box_success">El archivo se eliminó exitosamente.</div>
          <form action="<?php echo $phpself; ?>" method="post">
          <div align="center"><br /><input type="submit" value="Continuar &raquo;" /></div></form>
<?php   }
        else { ?>
          <div class="box_error">Ocurrió un error. El archivo <?php echo $archivo; ?> no se pudo eliminar.</div>
          <form action="<?php echo $phpself; ?>" method="post">
          <div align="center"><br /><input type="submit" value="Continuar &raquo;" /></div></form>
<?php   }
      }
      break;

//Visualización
    case 'v':
    //código de visualización
      include('backup/files.php');
      break;

    default:
      $_SESSION = array();
      header('Location: index.php');
      exit;
    }
  }
else {
?>
      <fieldset>
        <legend>Crear <?php echo $title; ?></legend>
          <p>En esta sección usted puede crear, descargar y eliminar copias de seguridad incrementales de su base de datos.</p><br />
          <span class="backup_save"><strong>Crear Copia de Seguridad de la Base de Datos</strong></span>
          <div align="center"><br />
                <form action="<?php echo $phpself; ?>?&amp;act=c" method="post">
                <?php echo draw_input('','submit','submit','Crear Backup &raquo;'); ?></form>
          </div>
          <br />
          <span class="backup_search"><strong>Visualizar Copias de Seguridad de la Base de Datos</strong></span>
          <div align="center"><br />
                <form action="<?php echo $phpself; ?>?&amp;act=v" method="post">
                <?php echo draw_input('','submit','submit','Visualizar Backup &raquo;'); ?></form>
          </div>
      </fieldset>
<?php
}
  disp_fin_info();
  disp_footer_admin($pre);
?>