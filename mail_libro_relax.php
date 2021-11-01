<?php
include('inc.inc.php');
$title = "Solicitar Curso de Meditación";
disp_header($pre,$title);
?>
       <div id="content">
       <h1><?php echo $title; ?></h1>
         <div class="article">
<?php
if (isset($_GET['act'])){
  switch ($_GET['act']){

//Validación
    case 'v':
    //código de validación

    $nombre = str_input($_POST['nombre']);
    $email = str_input($_POST['email']);
    
    if (!empty($_POST['comentario_opc'])){
       $comentario_opc = "<blockquote><p></p>".str_input($_POST['comentario_opc'])."</blockquote>";
    } else { $comentario_opc = ""; }   
    
    
     if (empty($nombre) OR empty($email)){
       $val_empty = "NO";
     }
     $email_valido = "NO";
     if (!empty($email)){
       if (!ereg("([A-Za-z0-9_\.-]+@[A-Za-z0-9_\.-]+\.[A-Za-z0-9_-]+)",$email)){
         $email_valido = "NO";
       }else {$email_valido = "SI";}
     }
       if ($val_empty === "NO" OR $email_valido === "NO"){
         echo "<div class=\"box_validation\">";
         if (empty($nombre)) echo "Debe escribir su nombre.<br />";
         if (empty($email)) echo "Debe escribir su email.<br />";
         if ($email_valido === "NO") {echo "Escriba una dirección de correo electrónico válida.<br /> Ejemplos:<br />
            <ul>
              <li>correo.electronico@ejemplo.com,</li>
              <li>correoelectronico@ejemplo.com,</li>
              <li>correo.electronico@ejem.plo.com,</li>
              <li>correoelectronico@ejem.plo.com</li></ul>";}
         echo "</div>";

?>
      <form action="<?php echo $phpself; ?>" method="POST">
        <div align="center"><br />
          <input type="submit" value="Intentar nuevamente &raquo;" />
        </div>
      </form>

          </div>
        </div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>
<?php

      exit;
    }
    else {
		
	//Formateo de info
	$info_registro_libro = "<div class=\"descrip\"><p><strong>Fecha: ".date("D, j/m/y-H:i:s")."</strong></p> <p><strong>Nombre:</strong> ".$nombre."</p> <p><strong>Email:</strong> ".$email."</p>".$comentario_opc."</div>\r\n";	
    // Escribir registro de información
    $log_login = fopen('mail_libro_relax.log','a');
    fwrite($log_login,$info_registro_libro);
    fclose($log_login);
    //echo $info_registro_libro;


$mensaje_correo = <<<HERE
          <p>Muchas gracias por completar el formulario.</p>
          <p>Esperamos que, siguiendo el curso, recupere su estabilidad emocional.</p>
          <p>Desde aquí usted puede descargar el Curso <strong>"<a href="http://www.alfonsobaraona.cl/relaxaccion_gratuito.pdf" target"_blank">Relaxacción: Meditación para el éxito emocional</a>"</strong> de Alfonso Baraona.</p>
          <div align="center">
          <a href="http://www.alfonsobaraona.cl/relaxaccion_gratuito.pdf" target"_blank">
          <img src="http://www.alfonsobaraona.cl/img/descargar.jpg" alt="Descargar libro" border="0">
          </a></div>
HERE;


// enviar aquí mail con link de descarga.
	
	       $para = ($_POST['email']);
           $asunto = "Descarga de Libro - alfonsobaraona.cl";
           $cuerpo = "From: jbaraona@vtr.net\n Reply-To: jbaraona@vtr.net\n\n\n \tMensaje:\n\n\n \t$mensaje_correo\n";
           $cabeceras = "From: jbaraona@vtr.net\r\nReply-To: jbaraona@vtr.net\r\nMime-Version: 1.0\r\nContent-type: text/html\r\n";
 //          echo $mensaje_correo;

          if(@mail($para, $asunto, $cuerpo, $cabeceras)){ ?>
           
          <p>Muchas gracias por completar el formulario.</p>
          <p>Esperamos que, siguiendo el curso, recupere su estabilidad emocional.</p>
          <p>En algunos instantes recibirá un correo electrónico con el vínculo para descargar el Curso <strong>"Relaxacción: Meditación para el éxito emocional"</strong>.</p>
<?php
           }
	
	
    }
      break;

    default:
    echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=mail_libro_relax.php\" />";
      exit;
  }
}
else{
?>
          <h2>Recupere su estabilidad emocional después del terremoto.</h2>

          <p>A los afectados por el terremoto de Chile:</p>
          <p>Dada la necesidad de recuperar la estabilidad emocional luego de esta manifestación de la naturaleza que ha afectado a millones de personas, damos la oportunidad de adquirir la capacidad de normalizar el sistema nervioso alterado por esta fuerte experiencia a través de un curso gratuito de meditación, basado en la técnica relaxacción creada por el profesor Alfonso Baraona Sotomayor.</p>
          <p>Este curso viene en formato PDF y usted puede descargarlo gratuitamente. </p>
          <p>Para descargar el Curso <strong>"Relaxacción: Meditación para el éxito emocional"</strong>, debe llenar el siguiente formulario. Al enviar la información, le llegará un correo electrónico con el vínculo para descargar el curso de meditación.</p>
          <p>Los datos requeridos se utilizarán sólo como registro del número de personas que han descargado el libro.</p>
          <p>&nbsp;</p>
      <form action="<?php echo $phpself; ?>?&amp;act=v" method="post" >
      <table width="100%"  border="0"  align="center">
        <?php echo draw_input("Nombre: ",'nombre','text','','','table_horiz',30,'req'); ?>
        <?php echo draw_input("E-mail: ",'email','text','','','table_horiz',30,'req'); ?>
      <tr>
        <td align="right"><label for="not_text">Texto: </label></td>
        <td>&nbsp;<?php echo draw_opc(); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php echo draw_textarea('comentario_opc','',50,15); ?></td>
      </tr>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Enviar'); ?></div>
      </form>
<?php
}

?>
          </div>
        </div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>
