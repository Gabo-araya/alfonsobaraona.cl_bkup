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
  $title = "Herramientas";
  disp_header_admin($pre,$title);
  include('inc/menu_admin.inc.php');
  $vinculo = "info";
  $tabla = $pre.$vinculo;
  disp_inicio_info($title,'herramientas');
?>
    <fieldset>
      <legend><span class="herramientas">Herramientas</span></legend>
      <div class="box_secc">
        <h1 class="archivos">Archivos</h1>
        <p>En esta sección usted puede subir archivos para descarga.</p>
        <form action="archivos.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
<?php if ($_SESSION['type']=="admin"){ ?>
      <div class="box_secc">
        <h1 class="backup">Backup de Base de Datos</h1>
        <p>En esta sección usted puede crear copias de seguridad de la base de datos, descargarlas o eliminarlas.</p>
        <form action="backup.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="login_log">Registro de Intentos de Ingreso al Sistema</h1>
        <p>En esta sección usted puede revisar o resetear los intentos de ingreso erróneos al sistema.</p>
        <form action="logs_login.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
<?php } ?>
    </fieldset>
    </div>
  </div>
<?php disp_footer_admin($pre); ?>