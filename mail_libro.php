<?php
include('inc.inc.php');
$title = "Solicitar Manual de Autoconocimiento y Desarrollo Personal";
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

     if (empty($nombre) OR empty($email)){
       $val_empty = "NO";
     }
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
    // Escribir registro de información
    $log_login = fopen('mail_libro.log','a');
    fwrite($log_login,"[".date("D, j/m/y-H:i:s")."] Nombre: [".$nombre."] Email: [".$email."]\r\n");
    fclose($log_login);

?>
          <p>Muchas gracias por completar el formulario.</p>
          <p>Desde aquí usted puede descargar el libro <strong>"Manual de Autoconocimiento y Desarrollo Personal"</strong> de Alfonso Baraona.</p>
          <p>&nbsp;</p>
          <div align="center">
          <a href="manual_autoconocimiento_libro.pdf" target"_blank">
          <img src="img/descargar.jpg" alt="Descargar libro" border="0">
          </a></div>
          <p>&nbsp;</p>

<?php
    }
      break;

    default:
    echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=mail_libro.php\" />";
      exit;
  }
}
else{
?>
          <p>Para poder descargar el libro "Manual de Autoconocimiento y Desarrollo Personal", debe llenar el siguiente formulario.</p>
          <p>Los datos requeridos se utilizarán sólo como registro del número de personas que han descargado el libro.</p>
          <p>&nbsp;</p>
      <form action="<?php echo $phpself; ?>?&amp;act=v" method="post" >
      <table width="100%"  border="0"  align="center">
        <?php echo draw_input("Nombre: ",'nombre','text','','','table_horiz',30,'req'); ?>
        <?php echo draw_input("E-mail: ",'email','text','','','table_horiz',30,'req'); ?>
        <?php echo draw_input('','id','hidden',$id,'','','',''); ?>
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