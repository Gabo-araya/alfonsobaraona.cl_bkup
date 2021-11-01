<?php
  $url_images_folder_libros = "./libros/";
  $url_thumbs_folder_libros = $url_images_folder_libros."thumbs/";
  $url_images_folder_cursos = "./cursos/";
  $url_thumbs_folder_cursos = $url_images_folder_cursos."thumbs/";
  $url_images_folder_breves = "./breves/";
  $url_thumbs_folder_breves = $url_images_folder_breves."thumbs/";
?>


        <div id="flash">
          <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="200" height="200">
            <param name="movie" value="img/relaxaccion.swf" />
            <param name="quality" value="high" />
            <embed src="img/relaxaccion.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="200" height="200"></embed>
          </object>
        </div>

        <div id="side">
<?php


/* Mostrar textos breves destacados */
  $datos = mysql_query("SELECT brev_id FROM ".$pre."breves ") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {}
  else {
      $datos = mysql_query("SELECT * FROM ".$pre."breves WHERE brev_pub='si' AND brev_dest='si' ORDER BY RAND() LIMIT 1") or die(mysql_error());

      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"brev_id");
          $brev_titulo = str_output(mysql_result($datos,$j,"brev_titulo"));
          $brev_imagen = str_output(mysql_result($datos,$j,"brev_imagen"));
?>
        <h3>Textos Breves</h3>
          <div align="center"><br />
            <a href="breves.php" title="<?php echo $brev_titulo; ?>">
            <img src="<?php echo $url_thumbs_folder_breves.$brev_imagen; ?>" border="0" alt="<?php echo $brev_titulo; ?>" /></a>
            <p><a href="breves.php" title="<?php echo $brev_titulo; ?>"><?php echo $brev_titulo; ?></a></p>
          </div>

<?php
          }
  }
/* Mostrar libros destacados */
  $datos = mysql_query("SELECT lib_id FROM ".$pre."libros ") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {}
  else {
      $datos = mysql_query("SELECT * FROM ".$pre."libros WHERE lib_pub='si' AND lib_dest='si' ORDER BY RAND() LIMIT 1") or die(mysql_error());

      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"lib_id");
          $lib_titulo = str_output(mysql_result($datos,$j,"lib_titulo"));
          $lib_imagen = str_output(mysql_result($datos,$j,"lib_imagen"));
?>
        <h3>Libros</h3>
          <div align="center"><br />
            <a href="libros.php" title="<?php echo $lib_titulo; ?>">
            <img src="<?php echo $url_thumbs_folder_libros.$lib_imagen; ?>" border="0" alt="<?php echo $lib_titulo; ?>" /></a>
            <p><a href="libros.php" title="<?php echo $lib_titulo; ?>"><?php echo $lib_titulo; ?></a></p>
          </div>

<?php
          }
  }
/* Mostrar cursos destacados */
  $datos = mysql_query("SELECT cur_id FROM ".$pre."cursos ") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {}
  else {
      $datos = mysql_query("SELECT * FROM ".$pre."cursos WHERE cur_pub='si' AND cur_dest='si' ORDER BY RAND() LIMIT 1") or die(mysql_error());

      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"cur_id");
          $cur_titulo = str_output(mysql_result($datos,$j,"cur_titulo"));
          $cur_imagen = str_output(mysql_result($datos,$j,"cur_imagen"));
?>
        <h3>Cursos</h3>
          <div align="center"><br />
            <a href="cursos.php" title="<?php echo $cur_titulo; ?>">
            <img src="<?php echo $url_thumbs_folder_cursos.$cur_imagen; ?>" border="0" alt="<?php echo $cur_titulo; ?>" /></a>
            <p><a href="cursos.php" title="<?php echo $cur_titulo; ?>"><?php echo $cur_titulo; ?></a></p>
          </div>

<?php
          }
  }
/* Mostrar servicios destacados
  $datos = mysql_query("SELECT serv_id FROM ".$pre."serv ") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {}
  else {
      $datos = mysql_query("SELECT * FROM ".$pre."serv WHERE serv_pub='si' AND serv_dest='si' ORDER BY RAND() LIMIT 1") or die(mysql_error());

      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"serv_id");
          $serv_nombre = str_output(mysql_result($datos,$j,"serv_nombre"));
          $serv_imagen = str_output(mysql_result($datos,$j,"serv_imagen"));
          $serv_cat = str_output(mysql_result($datos,$j,"serv_cat"));

?>
        <h3>Servicios Destacados</h3>
          <div align="center"><br />
            <a href="<?php echo "servicios.php?serv_id=".$id; ?>" title="<?php echo $serv_nombre; ?>">
            <img src="<?php echo $url_thumbs_folder_serv.$serv_imagen; ?>" border="0" align="center" alt="<?php echo $serv_nombre; ?>" />
            <p><?php echo $serv_nombre; ?></p></a>
          </div>

<?php
          }
  }                    */

?>


<?php
/* Mostrar Sindicación RSS */
$rss_self = $self.".xml";
    if(file_exists($rss_self)){
      $rss_host = 'http://'.$_SERVER['HTTP_HOST'];
      $cadena = implode('/',explode('/',strrev(strstr(strrev($_SERVER['SCRIPT_NAME']),'/')),-1));
      $url = $rss_host.$cadena.'/'.$rss_self;
      //echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"".$url."\" />\n";
?>
        <h3>Sindicación RSS</h3>
        <div align="center"><br />
            <a href="<?php echo $url; ?>" title="Sindicación RSS">
            <img src="img/rss.png" border="0" alt="Sindicación RSS" />
            <strong>Sindicación RSS</strong></a></div>

<?php

    }
?>
       </div>