   <div id="menu">
      <ul>
        <li><a href="inicio.php" class="inicio">Inicio</a></li> |
        <li><a href="administracion.php" class="administracion">Administrar Textos</a></li> |
<?php  if ($_SESSION['type']==="admin" || $_SESSION['type']==="editor"){?>
        <li><a href="herramientas.php" class="herramientas">Herramientas</a></li> |
<?php  } ?>
<?php  if ($_SESSION['type']==="admin"){?>
        <li><a href="configuracion.php" class="configuracion">Configuración</a></li> |
<?php  } ?>
        <li><a href="cuenta.php" class="cuenta">Mi Cuenta</a></li> |
        <li><a href="logout.php" class="salir">Salir</a></li>
      </ul>
    </div>

<?php
  //Submenú Administración
  if ($phpself==="administracion.php" OR $phpself==="secciones.php" OR $phpself==="cursos.php" OR $phpself==="noticias.php" OR $phpself==="articulos.php" OR $phpself==="breves.php" OR $phpself==="testimonios.php" OR $phpself==="librovisitas.php" OR $phpself==="imagenes.php" OR $phpself==="libros.php"){ ?>
   <div id="submenu">
      <ul>

      <?php
        //   <li><span class="administracion">Textos: </span></li>
?>

        <li><a href="secciones.php" class="secciones">Secciones</a></li>
        |<li><a href="noticias.php" class="articulos">Noticias</a></li>
        |<li><a href="articulos.php" class="articulos">Art&iacute;culos</a></li>
        |<li><a href="breves.php" class="articulos">Textos Breves</a></li>
        |<li><a href="libros.php" class="articulos">Libros</a></li>
        |<li><a href="cursos.php" class="articulos">Cursos</a></li>
        |<li><a href="testimonios.php" class="librovisitas">Testimonios</a></li>
        |<li><a href="librovisitas.php" class="librovisitas">Libro Visitas</a></li>
        |<li><a href="imagenes.php" class="imagenes">Imágenes</a></li>
      </ul>
    </div>
<?php
  }
  //Submenú Herramientas
  if ($phpself==="herramientas.php" OR $phpself==="archivos.php" OR $phpself==="rss.php" OR $phpself==="backup.php" OR $phpself==="logs_login.php" OR $phpself==="estadisticas.php"){ ?>
   <div id="submenu">
      <ul>
        <li><span class="herramientas">Herramientas: </span></li>
        <li><a href="archivos.php" class="archivos">Archivos</a></li>
<?php if ($_SESSION['type']=="admin"){ ?>
        | <li><a href="backup.php" class="backup">Backup</a></li>
        | <li><a href="logs_login.php" class="login_log">Registro de Intentos de Ingreso</a></li>
<?php        /*| <li><a href="estadisticas.php" class="estadisticas">Estadísticas*</a></li>*/  ?>

<?php } ?>
      </ul>
    </div>
<?php
  }
  //Submenú Configuración
  if ($phpself==="configuracion.php" OR $phpself==="usuarios.php" OR $phpself==="categorias.php" OR $phpself==="opciones.php"){ ?>
   <div id="submenu">
      <ul>
        <li><span class="configuracion">Configuración: </span></li>
        <li><a href="usuarios.php" class="usuarios">Usuarios</a></li> |
        <li><a href="categorias.php" class="categorias">Categorías</a></li> |
        <li><a href="opciones.php" class="opciones">Opciones</a></li>
      </ul>
    </div>
<?php
  }
  //Submenú Mi Cuenta
  if ($phpself==="cuenta.php" OR $phpself==="info_usuario.php" OR $phpself==="password.php"){ ?>
   <div id="submenu">
      <ul>
        <li><span class="cuenta">Mi Cuenta: </span></li>
        <li><a href="info_usuario.php" class="info_usuario">Información del Usuario</a></li> |
        <li><a href="password.php" class="password">Contraseña</a></li>
      </ul>
    </div>
<?php
  }
  if ($phpself==="libros.php"){ ?>
   <div id="submenu">
      <ul>
        <li><span class="noticias">Libros: </span></li>
        <li><a href="<?php echo $phpself; ?>?&act=vp" class="vp">Ver Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnp" class="vnp">Ver No Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vd" class="vd">Ver Destacados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnd" class="vnd">Ver No Destacados</a></li>
      </ul>
    </div>
<?php
  }
  if ($phpself==="cursos.php"){ ?>
   <div id="submenu">
      <ul>
        <li><span class="noticias">Cursos: </span></li>
        <li><a href="<?php echo $phpself; ?>?&act=vp" class="vp">Ver Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnp" class="vnp">Ver No Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vd" class="vd">Ver Destacados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnd" class="vnd">Ver No Destacados</a></li>
      </ul>
    </div>
<?php
  }
  if ($phpself==="noticias.php"){ ?>
   <div id="submenu">
      <ul>
        <li><span class="noticias">Noticias: </span></li>
        <li><a href="<?php echo $phpself; ?>?&act=vp" class="vp">Ver Publicadas</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnp" class="vnp">Ver No Publicadas</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vd" class="vd">Ver Destacadas</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnd" class="vnd">Ver No Destacadas</a></li>
      </ul>
    </div>
<?php
  }
  if ($phpself==="articulos.php"){ ?>
   <div id="submenu">
      <ul>
        <li><span class="articulos">Artículos: </span></li>
        <li><a href="<?php echo $phpself; ?>?&act=vp" class="vp">Ver Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnp" class="vnp">Ver No Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vd" class="vd">Ver Destacados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnd" class="vnd">Ver No Destacados</a></li>
      </ul>
    </div>
<?php
  }
  if ($phpself==="breves.php"){ ?>
   <div id="submenu">
      <ul>
        <li><span class="articulos">Textos Breves: </span></li>
        <li><a href="<?php echo $phpself; ?>?&act=vp" class="vp">Ver Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnp" class="vnp">Ver No Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vd" class="vd">Ver Destacados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnd" class="vnd">Ver No Destacados</a></li>
      </ul>
    </div>
<?php
  }
  if ($phpself==="servicios.php"){ ?>
   <div id="submenu">
      <ul>
        <li><span class="servicios">Servicios: </span></li>
        <li><a href="<?php echo $phpself; ?>?&act=vp" class="vp">Ver Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnp" class="vnp">Ver No Publicados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vd" class="vd">Ver Destacados</a></li> |
        <li><a href="<?php echo $phpself; ?>?&act=vnd" class="vnd">Ver No Destacados</a></li>
      </ul>
    </div>
<?php
  }
?>