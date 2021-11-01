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
  $title = "Ayuda";
  disp_header_admin($pre,$title);
  include('inc/menu_admin.inc.php');
  $vinculo = "info";
  $tabla = $pre.$vinculo;
  disp_inicio_info($title,'ayuda');
?>
    <fieldset>
      <legend><span class="ayuda">Ayuda</span></legend>
      <div class="box_secc">
        <h1 class="ayuda">Documentación</h1>
        <p>En esta sección usted puede revisar la documentación de Pharos CMS.</p>
        <p><strong>Se abre en ventanta nueva.</strong></p>
        <p><strong>AUN NO IMPLEMENTADO.</strong></p>
        <p><a href="ayuda/ayuda.php" class="ayuda" target="_blank">Revisar Documentación &raquo;</a></p>
      </div>
<?php /*
      <div class="box_secc">
        <h1 class="creditos">Créditos</h1>
        <p>En esta sección usted puede revisar los elementos que conforman Pharos CMS.</p>
        <p><strong>AUN NO IMPLEMENTADO.</strong></p>
        <form action="creditos.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>

      <div class="box_secc">
        <h1 class="licencia">Licencia</h1>
        <p>En esta sección usted puede revisar la licencia de Pharos CMS.</p>
        <p><strong>AUN NO IMPLEMENTADO.</strong></p>
        <form action="licencia.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>  */   ?>
      <div class="box_secc">
        <h1 class="acercade">Acerca de Pharos CMS</h1>
        <p>En esta sección usted puede revisar información sobre la creación del Sistema de Administración de Contenido Pharos CMS.</p>
        <p><strong>AUN NO IMPLEMENTADO.</strong></p>
        <form action="acercade.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
    </fieldset>
    </div>
  </div>
<?php disp_footer_admin($pre); ?>