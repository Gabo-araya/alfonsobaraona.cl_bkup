<?php
/*********************************************************************/
/* Crea header ADMIN*/
/*********************************************************************/
  function disp_header_admin($pre,$title = ''){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
    $tabla = $pre."conf";
    $datos = mysql_query("SELECT conf_nombre_sitio FROM ".$tabla."") or die(mysql_error());
      for($j=0; $j<mysql_num_rows($datos); $j++) {
        $conf_nombre_sitio = str_output(mysql_result($datos,$j,"conf_nombre_sitio"));
      }
    if(isset($title)){echo "<title>$title | $conf_nombre_sitio</title>\n";}
    else {echo "<title>$conf_nombre_sitio</title>\n";}

?>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link rel="shortcut icon" type="images/x-icon" href="favicon.ico">
  <link href="css/reset.css" rel="stylesheet" type="text/css" />
  <link href="css/layout.css" rel="stylesheet" type="text/css" />
  <link href="css/generic.css" rel="stylesheet" type="text/css" />
  <link href="css/niftyCorners.css" rel="stylesheet" type="text/css" />

<?php
  if (isset($_SESSION['auth']) AND $_SESSION['auth'] == 'Pearl Jam'){
?>
<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
        // Notice: The simple theme does not use all options some of them are limited to the advanced theme
        tinyMCE.init({
                mode : "textareas",
                theme : "advanced"
        });
</script>
<!-- /tinyMCE -->
  <script src="js/embeddedcontent.js" type="text/javascript" defer="defer"></script>
<?php } ?>
  <script src="js/niftycube.js" type="text/javascript"></script>
  <script type="text/javascript">
    window.onload=function(){
    Nifty("div#articulo","big transparent");
    Nifty("div.box_secc","transparent fixed-height");
    }
  </script>

</head>
<body>
<div id="todo">
  <div id="header_admin">
    <h1><?php echo $conf_nombre_sitio; ?></h1>
    <br><p>[Panel de Control - Pharos CMS]</p>
  </div>
<?php
  }

/* Crea footer ADMIN */
  function disp_footer_admin($pre){
?>
      <div id="footer">
        <br />
<?php

    $tabla = $pre."conf";
    $datos = mysql_query("SELECT * FROM ".$tabla."") or die(mysql_error());
      for($j=0; $j<mysql_num_rows($datos); $j++) {
        $conf_nombre_sitio = str_output(mysql_result($datos,$j,"conf_nombre_sitio"));
        $conf_slogan = str_output(mysql_result($datos,$j,"conf_slogan"));
        $conf_descripcion = str_output(mysql_result($datos,$j,"conf_descripcion"));
        $conf_mail = str_output(mysql_result($datos,$j,"conf_mail"));
        $conf_telefono = str_output(mysql_result($datos,$j,"conf_telefono"));
        $conf_direccion = str_output(mysql_result($datos,$j,"conf_direccion"));
      }
    $host = $_SERVER['HTTP_HOST'];
?>
      <p><?php echo $conf_nombre_sitio; ?></p>
      <?php if(!empty($conf_slogan)){echo "<p>".$conf_slogan."</p>";} ?>
      <?php if(!empty($conf_descripcion)){echo "<p>".$conf_descripcion."</p>";} ?>
      <?php if(!empty($conf_direccion)){echo "<p><strong>Dirección:</strong>".$conf_direccion."</p>";} ?>
      <?php if(!empty($conf_telefono)){echo "<p><strong>Teléfono:</strong>".$conf_telefono."</p>";} ?>
      <p><a href="http://<?php echo $host; ?>/">http://<?php echo $host; ?>/</a> -
      <a href="mailto:<?php echo $conf_mail; ?>" target="_blank"><?php echo $conf_mail; ?></a></p>

      </div>
      <div id="credits">
        Pharos CMS | <?php echo date('Y')?> | Desarrollado por <a href="http://portrait.cl/">Gabriel Araya</a> |
        Iconos: <a href="http://www.famfamfam.com/">FamFamFam</a>
      </div>
    </div>
  </div>
</body>
</html>
<?php
  }


  function disp_inicio_info($title,$estilo_seccion = ''){
?>
  <div id="contenedor">
    <div id="articulo">
    <h1 <?php if(isset($estilo_seccion)){echo "class=\"".$estilo_seccion."\"";}?>><?php echo $title ?></h1>
<?php
  }
  function disp_fin_info(){
?>
    </div>
  </div>
<?php
  }

/* Muestra iconos de textos breves */
  function disp_icons_brev($celda,$php_self,$id,$brev_pub,$brev_dest){
    //echo $celda.", ".$php_self.", ".$id.", ".$brev_pub.", ".$brev_dest."| <br />";

?>
            <td class="<?php echo $celda; ?>">
<?php     if ($brev_pub == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=p&amp;id=<?php echo $id; ?>" title="Publicar">
              <img src="<?php echo ICONS; ?>icon_no_pub.png" border="0" alt="Publicar"/></a><br /><?php } ?>

<?php     if ($brev_pub == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=dp&amp;id=<?php echo $id; ?>" title="Quitar Publicación">
              <img src="<?php echo ICONS; ?>icon_si_pub.png" border="0" alt="Quitar Publicación"/></a><br /><?php } ?>

<?php     if ($brev_dest == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=d&amp;id=<?php echo $id; ?>" title="Destacar">
              <img src="<?php echo ICONS; ?>icon_no_dest.png" border="0" alt="Destacar"/></a><br /><?php } ?>

<?php     if ($brev_dest == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=nd&amp;id=<?php echo $id; ?>" title="No Destacar">
              <img src="<?php echo ICONS; ?>icon_si_dest.png" border="0" alt="No Destacar"/></a><br /><?php } ?>

              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a><br />

<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br /><?php } ?>
            </td>
<?php
  }

  /* Muestra iconos de cursos */
  function disp_icons_cur($celda,$php_self,$id,$cur_pub,$cur_dest){
    //echo $celda.", ".$php_self.", ".$id.", ".$cur_pub.", ".$cur_dest."| <br />";

?>
            <td class="<?php echo $celda; ?>">
<?php     if ($cur_pub == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=p&amp;id=<?php echo $id; ?>" title="Publicar">
              <img src="<?php echo ICONS; ?>icon_no_pub.png" border="0" alt="Publicar"/></a><br /><?php } ?>

<?php     if ($cur_pub == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=dp&amp;id=<?php echo $id; ?>" title="Quitar Publicación">
              <img src="<?php echo ICONS; ?>icon_si_pub.png" border="0" alt="Quitar Publicación"/></a><br /><?php } ?>

<?php     if ($cur_dest == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=d&amp;id=<?php echo $id; ?>" title="Destacar">
              <img src="<?php echo ICONS; ?>icon_no_dest.png" border="0" alt="Destacar"/></a><br /><?php } ?>

<?php     if ($cur_dest == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=nd&amp;id=<?php echo $id; ?>" title="No Destacar">
              <img src="<?php echo ICONS; ?>icon_si_dest.png" border="0" alt="No Destacar"/></a><br /><?php } ?>

              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a><br />

<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br /><?php } ?>
            </td>
<?php
  }
  /* Muestra iconos de libro de visitas */
  function disp_icons_librovisitas($celda,$php_self,$id,$guest_pub){
    //echo $celda.", ".$php_self.", ".$id.", ".$cur_pub.", ".$cur_dest."| <br />";

?>
            <td class="<?php echo $celda; ?>">
<?php     if ($guest_pub == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=p&amp;id=<?php echo $id; ?>" title="Publicar">
              <img src="<?php echo ICONS; ?>icon_no_pub.png" border="0" alt="Publicar"/></a><br /><?php } ?>

<?php     if ($guest_pub == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=dp&amp;id=<?php echo $id; ?>" title="Quitar Publicación">
              <img src="<?php echo ICONS; ?>icon_si_pub.png" border="0" alt="Quitar Publicación"/></a><br /><?php } ?>

              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a><br />

<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br /><?php } ?>
            </td>
<?php
  }
  /* Muestra iconos de testimonios */
  function disp_icons_testimonios($celda,$php_self,$id,$test_pub){
    //echo $celda.", ".$php_self.", ".$id.", ".$cur_pub.", ".$cur_dest."| <br />";

?>
            <td class="<?php echo $celda; ?>">
<?php     if ($test_pub == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=p&amp;id=<?php echo $id; ?>" title="Publicar">
              <img src="<?php echo ICONS; ?>icon_no_pub.png" border="0" alt="Publicar"/></a><br /><?php } ?>

<?php     if ($test_pub == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=dp&amp;id=<?php echo $id; ?>" title="Quitar Publicación">
              <img src="<?php echo ICONS; ?>icon_si_pub.png" border="0" alt="Quitar Publicación"/></a><br /><?php } ?>

              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a><br />

<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br /><?php } ?>
            </td>
<?php
  }
/* Muestra iconos de libros */
  function disp_icons_lib($celda,$php_self,$id,$lib_pub,$lib_dest){
    //echo $celda.", ".$php_self.", ".$id.", ".$lib_pub.", ".$lib_dest."| <br />";

?>
            <td class="<?php echo $celda; ?>">
<?php     if ($lib_pub == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=p&amp;id=<?php echo $id; ?>" title="Publicar">
              <img src="<?php echo ICONS; ?>icon_no_pub.png" border="0" alt="Publicar"/></a><br /><?php } ?>

<?php     if ($lib_pub == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=dp&amp;id=<?php echo $id; ?>" title="Quitar Publicación">
              <img src="<?php echo ICONS; ?>icon_si_pub.png" border="0" alt="Quitar Publicación"/></a><br /><?php } ?>

<?php     if ($lib_dest == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=d&amp;id=<?php echo $id; ?>" title="Destacar">
              <img src="<?php echo ICONS; ?>icon_no_dest.png" border="0" alt="Destacar"/></a><br /><?php } ?>

<?php     if ($lib_dest == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=nd&amp;id=<?php echo $id; ?>" title="No Destacar">
              <img src="<?php echo ICONS; ?>icon_si_dest.png" border="0" alt="No Destacar"/></a><br /><?php } ?>

              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a><br />

<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br /><?php } ?>
            </td>
<?php
  }

/* Muestra iconos de productos */
  function disp_icons_prod($celda,$php_self,$id,$prod_pub,$prod_dest){
    //echo $celda.", ".$php_self.", ".$id.", ".$prod_pub.", ".$prod_dest."| <br />";

?>
            <td class="<?php echo $celda; ?>">
<?php     if ($prod_pub == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=p&amp;id=<?php echo $id; ?>" title="Publicar">
              <img src="<?php echo ICONS; ?>icon_no_pub.png" border="0" alt="Publicar"/></a><br /><?php } ?>

<?php     if ($prod_pub == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=dp&amp;id=<?php echo $id; ?>" title="Quitar Publicación">
              <img src="<?php echo ICONS; ?>icon_si_pub.png" border="0" alt="Quitar Publicación"/></a><br /><?php } ?>

<?php     if ($prod_dest == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=d&amp;id=<?php echo $id; ?>" title="Destacar">
              <img src="<?php echo ICONS; ?>icon_no_dest.png" border="0" alt="Destacar"/></a><br /><?php } ?>

<?php     if ($prod_dest == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=nd&amp;id=<?php echo $id; ?>" title="No Destacar">
              <img src="<?php echo ICONS; ?>icon_si_dest.png" border="0" alt="No Destacar"/></a><br /><?php } ?>

              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a><br />

<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br /><?php } ?>
            </td>
<?php
  }

/* Muestra iconos de servicios */
  function disp_icons_serv($celda,$php_self,$id,$prod_pub,$prod_dest){
    //echo $celda.", ".$php_self.", ".$id.", ".$prod_pub.", ".$prod_dest."| <br />";

?>
            <td class="<?php echo $celda; ?>">
<?php     if ($prod_pub == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=p&amp;id=<?php echo $id; ?>" title="Publicar">
              <img src="<?php echo ICONS; ?>icon_no_pub.png" border="0" alt="Publicar"/></a><br /><?php } ?>

<?php     if ($prod_pub == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=dp&amp;id=<?php echo $id; ?>" title="Quitar Publicación">
              <img src="<?php echo ICONS; ?>icon_si_pub.png" border="0" alt="Quitar Publicación"/></a><br /><?php } ?>

<?php     if ($prod_dest == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=d&amp;id=<?php echo $id; ?>" title="Destacar">
              <img src="<?php echo ICONS; ?>icon_no_dest.png" border="0" alt="Destacar"/></a><br /><?php } ?>

<?php     if ($prod_dest == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=nd&amp;id=<?php echo $id; ?>" title="No Destacar">
              <img src="<?php echo ICONS; ?>icon_si_dest.png" border="0" alt="No Destacar"/></a><br /><?php } ?>

              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a><br />

<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br /><?php } ?>
            </td>
<?php
  }

/* Muestra iconos de articulos */
  function disp_icons_artic($celda,$php_self,$id,$prod_pub,$prod_dest){
    //echo $celda.", ".$php_self.", ".$id.", ".$prod_pub.", ".$prod_dest."| <br />";

?>
            <td class="<?php echo $celda; ?>">
<?php     if ($prod_pub == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=p&amp;id=<?php echo $id; ?>" title="Publicar">
              <img src="<?php echo ICONS; ?>icon_no_pub.png" border="0" alt="Publicar"/></a><br /><?php } ?>

<?php     if ($prod_pub == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=dp&amp;id=<?php echo $id; ?>" title="Quitar Publicación">
              <img src="<?php echo ICONS; ?>icon_si_pub.png" border="0" alt="Quitar Publicación"/></a><br /><?php } ?>

<?php     if ($prod_dest == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=d&amp;id=<?php echo $id; ?>" title="Destacar">
              <img src="<?php echo ICONS; ?>icon_no_dest.png" border="0" alt="Destacar"/></a><br /><?php } ?>

<?php     if ($prod_dest == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=nd&amp;id=<?php echo $id; ?>" title="No Destacar">
              <img src="<?php echo ICONS; ?>icon_si_dest.png" border="0" alt="No Destacar"/></a><br /><?php } ?>

              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a><br />

<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br /><?php } ?>
            </td>
<?php
  }

/* Muestra iconos de noticias */
  function disp_icons_not($celda,$php_self,$id,$prod_pub,$prod_dest){
    //echo $celda.", ".$php_self.", ".$id.", ".$prod_pub.", ".$prod_dest."| <br />";

?>
            <td class="<?php echo $celda; ?>">
<?php     if ($prod_pub == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=p&amp;id=<?php echo $id; ?>" title="Publicar">
              <img src="<?php echo ICONS; ?>icon_no_pub.png" border="0" alt="Publicar"/></a><br /><?php } ?>

<?php     if ($prod_pub == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=dp&amp;id=<?php echo $id; ?>" title="Quitar Publicación">
              <img src="<?php echo ICONS; ?>icon_si_pub.png" border="0" alt="Quitar Publicación"/></a><br /><?php } ?>

<?php     if ($prod_dest == 'no'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=d&amp;id=<?php echo $id; ?>" title="Destacar">
              <img src="<?php echo ICONS; ?>icon_no_dest.png" border="0" alt="Destacar"/></a><br /><?php } ?>

<?php     if ($prod_dest == 'si'){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=nd&amp;id=<?php echo $id; ?>" title="No Destacar">
              <img src="<?php echo ICONS; ?>icon_si_dest.png" border="0" alt="No Destacar"/></a><br /><?php } ?>

              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a><br />

<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br /><?php } ?>
            </td>
<?php
  }

/* Muestra iconos de categorias */
  function disp_icons_cat($celda,$php_self,$id,$cat_id,$section){
    //echo $celda.", ".$php_self.", ".$id.", ".$prod_pub.", ".$prod_dest."| <br />";
?>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $php_self; ?>?&amp;act=<?php echo $section; ?>&amp;cat=<?php echo $id; ?>&amp;id=<?php echo $cat_id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" /></a>
              <?php     if ($section=="m"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;cat=<?php echo $id; ?>&amp;id=<?php echo $cat_id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><?php } ?>
            </td>
<?php
  }

/* Muestra iconos de usuarios */
  function disp_icons_users($celda,$php_self,$id){
    //echo $celda.", ".$php_self.", ".$id.", ".$prod_pub.", ".$prod_dest."| <br />";
?>
            <td align="center" valign="top" class="<?php echo $celda; ?>">
              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" /></a>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a>
            </td>
<?php
  }

/* Muestra iconos de información de usuarios */
  function disp_icons_info_users($celda,$php_self,$id){
    //echo $celda.", ".$php_self.", ".$id.", ".$prod_pub.", ".$prod_dest."| <br />";
?>
            <td align="center" valign="top" class="<?php echo $celda; ?>">
              <a href="<?php echo $php_self; ?>?&amp;act=m&amp;id=<?php echo $id; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" /></a><br />
            </td>
<?php
  }

/* Muestra iconos de imagenes */
  function disp_icons_img($celda,$php_self,$id){
    //echo $celda.", ".$php_self.", ".$id."| <br />";
?>
            <td class="<?php echo $celda; ?>">
<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br />
<?php     } ?>
            </td>
<?php
  }

/* Muestra iconos de archivos */
  function disp_icons_files($celda,$php_self,$url_files_folder,$id,$file_file){
    //echo $celda.", ".$php_self.", ".$id."| <br />";
?>
            <td class="<?php echo $celda; ?>">
        <a href="<?php echo $url_files_folder."descargar.php?&f=".$file_file; ?>" title="Descargar">
        <img src="<?php echo ICONS; ?>icon_download.png" border="0" alt="Descargar" class="borde_img"/></a><br />
<?php     if ($_SESSION['type']=="admin"){ ?>
              <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;id=<?php echo $id; ?>" title="Eliminar">
              <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar"/></a><br />
<?php     } ?>
            </td>
<?php
  }

/* Muestra iconos de secciones principales */
  function disp_icons_seccprinc($celda,$php_self,$cat,$section){
?>
            <td class="<?php echo $celda; ?>">
              <a href="<?php echo $php_self; ?>?&amp;act=<?php echo $section; ?>&amp;secc=<?php echo $cat; ?>" title="Modificar">
              <img src="<?php echo ICONS; ?>icon_modificar.png" border="0" alt="Modificar"/></a>
            </td>
<?php
  }

/* Muestra iconos de secciones principales */
  function disp_icons_files_backup($php_self,$value){
?>
      <td align="left" valign="bottom">
        <a href="backup/descargar.php?f=<?php echo $value; ?>" title="Descargar Copia de Seguridad">
        <img src="<?php echo ICONS; ?>icon_download.png" border="0" alt="Descargar" class="borde_img"/></a>
<?php if ($_SESSION['type']=="admin"){ ?>
        <a href="<?php echo $php_self; ?>?&amp;act=ce&amp;f=<?php echo $value; ?>" title="Eliminar Copia de Seguridad">
        <img src="<?php echo ICONS; ?>icon_eliminar.png" border="0" alt="Eliminar" class="borde_img"/></a>
<?php } ?>
      </td>
<?php
  }

/* Muestra resultados paginados */
  function paginar($total,$pp,$st,$url){
    settype($page_first, "string");
    settype($page_previous, "string");
    settype($page_nav, "string");
    settype($page_next, "string");
    settype($page_last, "string");
    settype($paginar, "string");

    if($total>$pp){
      $resto=$total%$pp;
      if($resto==0) {
        $pages=$total/$pp;
      }
      else{
        $pages=(($total-$resto)/$pp)+1;
      }

      if($pages>10){
        $current_page=($st/$pp)+1;
        if($st==0){
          $first_page=0;
          $last_page=10;
        }
        else if($current_page>=5 && $current_page<=($pages-5)){
          $first_page=$current_page-5;
          $last_page=$current_page+5;
        }
        else if($current_page<5){
          $first_page=0;
          $last_page=$current_page+5+(5-$current_page);
        }
        else{
          $first_page=$current_page-5-(($current_page+5)-$pages);
          $last_page=$pages;
        }
      }
      else{
        $first_page=0;
        $last_page=$pages;
      }

      for($i=$first_page;$i<$last_page;$i++){
        $pge=$i+1;
        $nextst=$i*$pp;
        if($st==$nextst) {
          $page_nav .= "<em>".$pge."</em>";
        }
        else{
          $page_nav .= "<a href=\"".$url.$nextst."\" title=\"Ir a esta página\">".$pge."</a>";
//          $page_nav .= "<a href=\"".$url.$nextst."\" mce_href=\"".$url.$nextst."\">".$pge."</a>";
        }
      }

      if($st==0){ $current_page = 1; }
      else{ $current_page = ($st/$pp)+1; }

      if($current_page< $pages){
        $page_last = "<a href=\"".$url.($pages-1)*$pp."\" title=\"Último\">Último &raquo;</a>";
        $page_next = "<a href=\"".$url.$current_page*$pp."\" title=\"Siguiente\">Siguiente &#8250;</a>";
//        $page_last = "<a href=\"".$url.($pages-1)*$pp."\" mce_href=\"".$url.($pages-1)*$pp."\">[&raquo;]</a>";
//        $page_next = "<a href=\"".$url.$current_page*$pp."\" mce_href=\"".$url.$current_page*$pp."\">[&#8250;]</a>";
      }

      if($st>0) {
        $page_first = "<a href=\"".$url."0\" title=\"Primero\">&laquo; Primero</a>";
        $page_previous = "<a href=\"".$url."".($current_page-2)*$pp."\" title=\"Anterior\">&#8249; Anterior</a>";
//        $page_first = "<a href=\"".$url."0\" mce_href=\"".$url."0\">[&laquo;]</a></a>";
//        $page_previous = "<a href=\"".$url."".($current_page-2)*$pp."\" mce_href=\"".$url."".($current_page-2)*$pp."\">[&#8249;]</a>";
      }
    }
    $paginar .= "<div class=\"paginar\">$page_first $page_previous $page_nav $page_next $page_last</div>";
    return $paginar;
  }

/* Muestra Flash */
  function disp_swf($name,$width,$height){
?>
    <div id="flash">
      <object width="<?php echo $width; ?>" height="<?php echo $height; ?>">
        <param name="movie" value="swf/<?php echo $name; ?>" />
        <param name="quality" value="high" />
        <embed src="swf/<?php echo $name; ?>" quality="high" type="application/x-shockwave-flash" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></embed>
      </object>
    </div>
<?php
  }



?>