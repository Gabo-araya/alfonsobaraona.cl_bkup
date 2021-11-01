<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = "Libro de Visitas";
$tabla = $pre.$self;

disp_header($pre,$title);


?>
       <div id="content">
       <h1><?php echo $title; ?></h1>
         <div class="article">
      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>

<?php
  if (isset($_GET['act']) AND ($_GET['act'] == 'nuevo' OR $_GET['act'] == 'validar' OR $_GET['act'] == 'gracias')){}
  else{ ?>
<br /><div align="center"><form action="<?php echo $self; ?>.php?act=nuevo" method="post">
<input type="submit" value="Agregar Comentario" /></form></div>
<?php
    }

// si la acción es nuevo...
  if (isset($_GET['act']) AND $_GET['act'] == 'nuevo') { ?>
   <h2>Agregar Comentario</h2>
   <p>Tenga en cuenta que deberá esperar a que el administrador del sitio web revise su comentario antes de que éste sea publicado.</p>
   <p>Los mensajes de contenido soez o que no se adapten al espíritu del sitio, no serán publicados.</p>
   <form action="<?php echo $self; ?>.php?act=validar" method="post" >
   <div class="modificar">
   <table width="100%"  border="0"  align="center">
     <tr>
       <td><strong>Nombre: </strong></td>
       <td align="center"><input type="text" name="nombre" id="nombre" size="30" /></td>
       <td><?php echo draw_req(); ?></td>
     </tr><tr>
       <td><strong>E-mail: </strong><span class="mini">(no será mostrado)</span></td>
       <td align="center"><input type="text" name="email" id="email"  size="30" /></td>
       <td><?php echo draw_req(); ?></td>
     </tr><tr>
       <td><strong>Sitio Web: </strong><span class="mini">(no será mostrado)</span></td>
       <td align="center"><input type="text" name="web" id="web" size="30"  /></td>
       <td><?php echo draw_opc(); ?></td>
     </tr><tr>
       <td><strong>Ciudad: </strong></td>
       <td align="center"><input type="text" name="ciudad" id="ciudad"  size="30"  /></td>
       <td><?php echo draw_req(); ?></td>
     </tr><tr>
           <td><strong>País: </strong></td>
       <td align="center"><input type="text" name="pais" id="pais"  size="30"  /></td>
       <td><?php echo draw_req(); ?></td>
     </tr><tr>
       <td><strong>Comentario: </strong><?php echo draw_req(); ?></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr><tr>
       <td colspan="3" align="center"><textarea name="text" id="text" cols="50" rows="10"></textarea></td>
     </tr>
   </table>
   <center><br /><input type="submit" value="Agregar" /><br /></center>
   </div></form>
<?php
  }
// si la acción es validar...
  if (isset($_GET['act']) AND $_GET['act'] == 'validar') {
    $nombre = str_input($_POST['nombre']);
    $email = str_input($_POST['email']);
    $web = str_input($_POST['web']);
    $text = str_input($_POST['text']);
    $ciudad = str_input($_POST['ciudad']);
    $pais = str_input($_POST['pais']);

     if (!empty($email)){
       if (!ereg("([A-Za-z0-9_\.-]+@[A-Za-z0-9_\.-]+\.[A-Za-z0-9_-]+)",$email)){
         $email_valido = "NO";
       }else {$email_valido = "SI";}
     }

       if (empty($nombre) OR empty($email) OR empty($text) OR empty($ciudad) OR empty($pais) OR $email_valido === "NO"){
         echo "<div class=\"box_validation\">";
         if (empty($nombre)) echo "Debe escribir su nombre.<br />";
         if (empty($email)) echo "Debe escribir su email.<br />";
         if (empty($ciudad)) echo "Debe escribir la ciudad donde vive.<br />";
         if (empty($pais)) echo "Debe escribir el pa&iacute;s donde vive.<br />";
         if (empty($text)) echo "Debe escribir un mensaje.<br />";
         if ($email_valido === "NO") {echo "Escriba una dirección de correo electrónico válida.<br /> Ejemplos:<br />
            <ul>
              <li>correo.electronico@ejemplo.com,</li>
              <li>correoelectronico@ejemplo.com,</li>
              <li>correo.electronico@ejem.plo.com,</li>
              <li>correoelectronico@ejem.plo.com</li></ul>";}
         echo "</div>";

  ?>
   <h2>Agregar Comentario</h2>
   <p>Tenga en cuenta que deberá esperar a que el administrador del sitio web revise su comentario antes de que éste sea publicado.</p>
   <p>Los mensajes de contenido soez o que no se adapten al espíritu del sitio, no serán publicados.</p>
   <form action="<?php echo $self; ?>.php?act=validar" method="post" >
   <div class="modificar">
   <table width="100%"  border="0"  align="center">
     <tr>
       <td><strong>Nombre: </strong></td>
       <td align="center"><input type="text" name="nombre" id="nombre" size="40" value="<?php echo $nombre; ?>" /></td>
       <td><?php echo draw_req(); ?></td>
     </tr><tr>
       <td><strong>E-mail: </strong><span class="mini">(no ser&aacute; mostrado)</span></td>
       <td align="center"><input type="text" name="email" id="email"  size="40" value="<?php echo $email; ?>" /></td>
       <td><?php echo draw_req(); ?></td>
     </tr><tr>
       <td><strong>Sitio Web: </strong><span class="mini">(no ser&aacute; mostrado)</span></td>
       <td align="center"><input type="text" name="web" id="web" size="40"  value="<?php echo $web; ?>" /></td>
       <td><?php echo draw_opc(); ?></td>
     </tr><tr>
       <td><strong>Ciudad: </strong></td>
       <td align="center"><input type="text" name="ciudad" id="ciudad"  size="40"  value="<?php echo $ciudad; ?>" /></td>
       <td><?php echo draw_req(); ?></td>
     </tr><tr>
           <td><strong>País: </strong></td>
       <td align="center"><input type="text" name="pais" id="pais"  size="40"  value="<?php echo $pais; ?>" /></td>
       <td><?php echo draw_req(); ?></td>
     </tr><tr>
       <td><strong>Comentario: </strong><?php echo draw_req(); ?></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr><tr>
       <td colspan="3" align="center"><textarea name="text" id="text" cols="50" rows="10"><?php echo $text; ?></textarea></td>
     </tr>
   </table>
   <center><br /><input type="submit" value="Agregar" /><br /></center>
   </div></form>
<?php
      }
      else {
// insertar
if ($_SERVER['REQUEST_METHOD']=='POST'){
    $nombre = str_input($_POST['nombre']);
    $email = str_input($_POST['email']);
    $web = str_input($_POST['web']);
    $text = str_input($_POST['text']);
    $ciudad = str_input($_POST['ciudad']);
    $pais = str_input($_POST['pais']);

          $query = mysql_query("INSERT INTO ".$tabla." SET
                   guest_nombre='".$nombre."',
                   guest_email='".$email."',
                   guest_web='".$web."',
                   guest_text='".$text."',
                   guest_ciudad='".$ciudad."',
                   guest_pais='".$pais."',
                   guest_pub='no'
                   ") or die(mysql_error());
          echo "<script>document.location.replace('".$self.".php?act=gracias')</script>";
}


      }
  }

  if (isset($_GET['act']) AND $_GET['act'] == 'gracias'){
?>
         <h2>&iexcl;Muchas gracias!</h2>
         <div class="box_success">

         <p>Su informaci&oacute;n ha sido ingresada exitosamente. </p>
         <p>Pronto la publicaremos.</p>
         </div>
<?php
    }

// aparece por defecto...
if (isset($_GET['act']) AND ($_GET['act'] == 'nuevo' OR $_GET['act'] == 'validar' OR $_GET['act'] == 'gracias')){}
else{
  $query = mysql_query("SELECT guest_pub FROM ".$tabla." WHERE guest_pub='si' ORDER BY guest_id") or die(mysql_error());
  if (mysql_num_rows($query) == 0) {
        draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE guest_pub='si' ORDER BY guest_id DESC") or die(mysql_error());
          $content = "";
      for($j=0; $j<mysql_num_rows($datos); $j++) {
          $id = mysql_result($datos,$j,"guest_id");
          $nombre = str_output(mysql_result($datos,$j,"guest_nombre"));
          $email = str_output(mysql_result($datos,$j,"guest_email"));
          $web = str_output(mysql_result($datos,$j,"guest_web"));
          $text = str_output(mysql_result($datos,$j,"guest_text"));
          $pub = str_output(mysql_result($datos,$j,"guest_pub"));
          $ciudad = str_output(mysql_result($datos,$j,"guest_ciudad"));
          $pais = str_output(mysql_result($datos,$j,"guest_pais"));

          $content .= "<div class=\"descrip\">";
          $content .= "<p>Nombre: <strong>".$nombre."</strong></p>";

          if (!empty($ciudad)) $content .= "<p>Ciudad: ".$ciudad."</p>";
          if (!empty($pais)) $content .= "<p>Pa&iacute;s: ".$pais."</p>";
          $content .= "<blockquote><p>Comentario: </p>";
          $content .= $text;
          $content .= "</blockquote></div>";
          }

      echo $content;
  }
}

  if (isset($_GET['act']) AND ($_GET['act'] == 'nuevo' OR $_GET['act'] == 'validar' OR $_GET['act'] == 'gracias')){}
  else{ ?>
<br /><div align="center"><form action="<?php echo $self; ?>.php?act=nuevo" method="post">
<input type="submit" value="Agregar Comentario" /></form></div>
<?php
    }

?>

          </div>
        </div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>