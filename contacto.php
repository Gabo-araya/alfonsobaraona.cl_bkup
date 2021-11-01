<?php
session_start();
ob_start();
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = ucfirst($self);
$secc = "info_".$self;

disp_header($pre,$title);

$secc_princ = mysql_query("SELECT ".$secc." FROM ".$pre."info") or die(mysql_error());
  $txt = str_output(mysql_result($secc_princ,0,$secc));
?>
       <div id="content">
       <h1><?php echo $title; ?></h1>
         <div class="article">
<?php


if (isset($_GET['act'])){
  switch ($_GET['act']){

//Validación de email
    case 've':
    //código de validación de email
      $nombre = $_POST['nombre'];
      $telefono = $_POST['telefono'];
      $email = $_POST['email'];
      $mensaje = $_POST['mensaje'];

     echo $txt;
     if (empty($nombre) OR empty($email) OR empty($mensaje)){
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
         if (empty($mensaje)) echo "Debe escribir un mensaje.<br />";
         if ($email_valido === "NO") {echo "Escriba una dirección de correo electrónico válida.<br /> Ejemplos:<br />
            <ul>
              <li>correo.electronico@ejemplo.com,</li>
              <li>correoelectronico@ejemplo.com,</li>
              <li>correo.electronico@ejem.plo.com,</li>
              <li>correoelectronico@ejem.plo.com</li></ul>";}
         echo "</div>";

?>

    <form action="<?php echo $phpself; ?>?act=ve" method="post">

    <fieldset>
      <legend>Enviar Correo </legend>
      <table align="center" border="0" width="100%">
      <tbody>
        <tr>
          <td align="right"><label for="nombre">Nombre:</label></td>
          <td><input name="nombre" size="20" type="text" value="<?php echo $nombre; ?>"/><?php echo draw_req(); ?></td>
        </tr>
        <tr>
          <td align="right"><label for="telefono">Teléfono:</label></td>
          <td><input name="telefono" size="20" type="text" value="<?php echo $telefono; ?>"/><?php echo draw_opc(); ?></td>
        </tr>
        <tr>
          <td align="right"><label for="email">Correo Electrónico:</label></td>
          <td><input name="email" size="20" type="text" value="<?php echo $email; ?>"/><?php echo draw_req(); ?></td>
        </tr>
        <tr>
          <td align="right"><label for="mensaje">Mensaje: </label></td>
          <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="center"><textarea name="mensaje" cols="30" rows="12"><?php echo $mensaje; ?></textarea></td>
      </tr>
      </tbody></table>
      <div align="center"><br /><input name="submit" value="Enviar" type="submit" /></div>
    </fieldset>

    </form>

<?php

       }
       else {

         if(isset($_POST['submit'])){
           $tabla = $pre."conf";
           $datos = mysql_query("SELECT conf_mail FROM ".$tabla."") or die(mysql_error());
             for($j=0; $j<mysql_num_rows($datos); $j++) {
             $conf_mail = str_output(mysql_result($datos,$j,"conf_mail"));
           }

           $para = $conf_mail;
           $asunto = "Información desde el sitio Web";

           $cuerpo = "From: $email\n Reply-To: $email\n\n\n \tEnviado por: $nombre\n \tE-Mail: $email\n \tTelefono: $telefono\n \tMensaje:\n\n\n \t$mensaje\n";

           if(@mail($para, $asunto, $cuerpo)){
             header("Location: contacto_exito.php");
           }
           else{
             header("Location: contacto_error.php");
           }
         }
         else{
           header("Location: contacto_error.php");
         }
       }
      break;

    default:
      header('Location: contacto.php');
    }
  }
else{
  echo $txt;
?>
    <form action="<?php echo $phpself; ?>?act=ve" method="post">

    <fieldset>
      <legend>Enviar Correo </legend>
      <table align="center" border="0" width="100%">
      <tbody>
        <tr>
          <td align="right"><label for="nombre">Nombre:</label></td>
          <td><input name="nombre" size="20" type="text"/><?php echo draw_req(); ?></td>
        </tr>
        <tr>
          <td align="right"><label for="telefono">Teléfono:</label></td>
          <td><input name="telefono" size="20" type="text"/><?php echo draw_opc(); ?></td>
        </tr>
        <tr>
          <td align="right"><label for="email">Correo Electrónico:</label></td>
          <td><input name="email" size="20" type="text"/><?php echo draw_req(); ?></td>
        </tr>
        <tr>
          <td align="right"><label for="mensaje">Mensaje: </label></td>
          <td>&nbsp;<?php echo draw_req(); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="center"><textarea name="mensaje" cols="30" rows="12"> </textarea></td>
      </tr>
      </tbody></table>
      <div align="center"><br /><input name="submit" value="Enviar" type="submit" /></div>
    </fieldset>

    </form>
<?php
  }
?>
          </div>
        </div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>