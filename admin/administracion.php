<?php
session_start();
ob_start();
include('inc/inc.inc.php');
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== 'Pearl Jam') {
  $_SESSION = array();
  header('Location: index.php');
  exit;
  }

  $title = "Administrar Textos";
  disp_header_admin($pre,$title);
  include('inc/menu_admin.inc.php');
  $vinculo = "info";
  $tabla = $pre.$vinculo;
  disp_inicio_info($title,'administracion');
?>
    <fieldset>
      <legend><span class="administracion">Administrar Textos</span></legend>
      <div class="box_secc">
        <h1 class="secciones">Secciones de Información</h1>
        <p>Aquí se puede modificar la información de las secciones principales del sitio.</p>
        <form action="secciones.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="noticias">Noticias</h1>
        <p>En esta sección usted puede crear, modificar y eliminar noticias.</p>
        <form action="noticias.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="articulos">Artículos</h1>
        <p>En esta sección usted puede crear, modificar y eliminar artículos.</p>
        <form action="articulos.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="articulos">Textos Breves</h1>
        <p>En esta sección usted puede crear, modificar y eliminar textos breves.</p>
        <form action="breves.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="articulos">Libros</h1>
        <p>En esta sección usted puede crear, modificar y eliminar libros.</p>
        <form action="libros.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="articulos">Cursos</h1>
        <p>En esta sección usted puede crear, modificar y eliminar cursos.</p>
        <form action="cursos.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="librovisitas">Testimonios</h1>
        <p>En esta sección usted puede crear, modificar y eliminar testimonios de relaxacción.</p>
        <form action="testimonios.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="librovisitas">Libro de Visitas</h1>
        <p>En esta sección usted puede crear, modificar y eliminar comentarios del libro de visitas.</p>
        <form action="librovisitas.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="imagenes">Imágenes</h1>
        <p>En esta sección usted puede agregar y eliminar imágenes para ser usadas en los artículos.</p>
        <form action="imagenes.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="noticias">Registro de Descargas de libro</h1>
        <p>En esta sección usted puede revisar los registros de la gente que ha descargado el libro.</p>
        <form action="mail_libro.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
    </fieldset>

    </div>
  </div>
<?php disp_footer_admin($pre); ?>