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
  $title = "Mi Cuenta";
  disp_header_admin($pre,$title);
  include('inc/menu_admin.inc.php');
  $vinculo = "info";
  $tabla = $pre.$vinculo;
  disp_inicio_info($title,'cuenta');
?>
    <fieldset>
      <legend><span class="cuenta">Mi Cuenta</span></legend>
      <div class="box_secc">
        <h1 class="info_usuario">Información del Usuario</h1>
        <p>En esta sección usted puede modificar su información personal.</p> 
        <form action="info_usuario.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="password">Contraseña</h1>
        <p>En esta sección usted puede modificar su contraseña de usuario.</p>
        <form action="password.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
    </fieldset>
    </div>
  </div>
<?php disp_footer_admin($pre); ?>