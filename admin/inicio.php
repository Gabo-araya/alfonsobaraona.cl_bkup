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
  $title = "Inicio";
  disp_header_admin($pre,$title);
  include('inc/menu_admin.inc.php');
  $vinculo = "info";
  $tabla = $pre.$vinculo;
  disp_inicio_info($title,'inicio');
?>

      <div class="box_info">
        <p>Usted ha ingresado como [<?php echo $_SESSION['user']; ?>]
        y tiene privilegios de [<?php if($_SESSION['type'] =='admin') {echo 'administrador';} else {echo $_SESSION['type'];} ?>].</p>
      </div>

    <fieldset>
      <legend><span class="administracion">Administrar Textos</span></legend>
      <div class="box_secc">
        <h1 class="secciones">Secciones de Informaci�n</h1>
        <p>Aqu� se puede modificar la informaci�n de las secciones principales del sitio.</p>
        <form action="secciones.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="noticias">Noticias</h1>
        <p>En esta secci�n usted puede crear, modificar y eliminar noticias.</p>
        <form action="noticias.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="articulos">Art�culos</h1>
        <p>En esta secci�n usted puede crear, modificar y eliminar art�culos.</p>
        <form action="articulos.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="articulos">Textos Breves</h1>
        <p>En esta secci�n usted puede crear, modificar y eliminar textos breves.</p>
        <form action="breves.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="articulos">Libros</h1>
        <p>En esta secci�n usted puede crear, modificar y eliminar libros.</p>
        <form action="libros.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="articulos">Cursos</h1>
        <p>En esta secci�n usted puede crear, modificar y eliminar cursos.</p>
        <form action="cursos.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="librovisitas">Testimonios</h1>
        <p>En esta secci�n usted puede crear, modificar y eliminar testimonios de relaxacci�n.</p>
        <form action="testimonios.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="librovisitas">Libro de Visitas</h1>
        <p>En esta secci�n usted puede crear, modificar y eliminar comentarios del libro de visitas.</p>
        <form action="librovisitas.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="imagenes">Im�genes</h1>
        <p>En esta secci�n usted puede agregar y eliminar im�genes para ser usadas en los art�culos.</p>
        <form action="imagenes.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="noticias">Registro de Descargas de libro</h1>
        <p>En esta secci�n usted puede revisar los registros de la gente que ha descargado el libro.</p>
        <form action="mail_libro.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
    </fieldset>

<?php if ($_SESSION['type']=="admin" OR $_SESSION['type']=="editor"){ ?>

    <fieldset>
      <legend><span class="herramientas">Herramientas</span></legend>
      <div class="box_secc">
        <h1 class="archivos">Archivos</h1>
        <p>En esta secci�n usted puede subir archivos para descarga.</p>
        <form action="archivos.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
<?php if ($_SESSION['type']=="admin"){ ?>
      <div class="box_secc">
        <h1 class="backup">Backup de Base de Datos</h1>
        <p>En esta secci�n usted puede crear copias de seguridad de la base de datos, descargarlas o eliminarlas.</p>
        <form action="backup.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="login_log">Registro de Intentos de Ingreso al Sistema</h1>
        <p>En esta secci�n usted puede revisar o resetear los intentos de ingreso err�neos al sistema.</p>
        <form action="logs_login.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
<?php } ?>
<?php } ?>
    </fieldset>

<?php if ($_SESSION['type']=="admin"){ ?>
    <fieldset>
      <legend><span class="configuracion">Configuraci�n</span></legend>
      <div class="box_secc">
        <h1 class="usuarios">Usuarios</h1>
        <p>En esta secci�n usted puede crear, modificar y eliminar usuarios del sistema.</p>
        <form action="usuarios.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="categorias">Categor�as</h1>
        <p>En esta secci�n usted puede crear, modificar y eliminar categor�as de algunos elementos del sitio web.</p>
        <p>Usar con precauci�n.</p>
        <form action="categorias.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="opciones">Opciones</h1>
        <p>En esta secci�n usted puede modificar algunos elementos del sistema.</p>
        <form action="opciones.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
    </fieldset>
<?php } ?>

    <fieldset>
      <legend><span class="cuenta">Mi Cuenta</span></legend>
      <div class="box_secc">
        <h1 class="info_usuario">Informaci�n del Usuario</h1>
        <p>En esta secci�n usted puede modificar su informaci�n personal.</p>
        <form action="info_usuario.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>
      <div class="box_secc">
        <h1 class="password">Contrase�a</h1>
        <p>En esta secci�n usted puede modificar su contrase�a de usuario.</p>
        <form action="password.php" method="post"><div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div></form>
      </div>

    </fieldset>

    </div>
  </div>
<?php disp_footer_admin($pre); ?>