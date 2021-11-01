<?php
//iniciar el script si el form ha sido posteado
if (array_key_exists('login', $_POST)) {
  session_start();
  $_SESSION = array();
  ob_start();
  include('inc/inc.inc.php');
  $tabla = $pre."usuarios";

  if (!empty($_POST['usuario'])) {
    $usuario = str_input($_POST['usuario']);
    }
  else {
    $error_user = 'Debe ingresar un nombre de usuario.<br />';
    }
  if (!empty($_POST['pass'])) {
    $pass_original = str_input($_POST['pass']);
    $pass = md5($pass_original);
    }
  else {
    $error_pass = 'Debe ingresar una contrase&ntilde;a.<br />';
    }
  $login = mysql_query("SELECT * FROM ".$tabla." WHERE usuarios_nombre='".$usuario."' AND usuarios_password='".$pass."'") or die(mysql_error());
  if (mysql_num_rows($login)>0){
    // if there's a match, set a session variable: type && auth
    $type = str_output(mysql_result($login,0,'usuarios_tipo'));
    $usuario = str_output(mysql_result($login,0,'usuarios_nombre'));
    $_SESSION['type'] = $type;
    $_SESSION['user'] = $usuario;
    $_SESSION['auth'] = 'Pearl Jam';
    /* Escribir acceso en el log */
    $log_login = fopen('login_auth.log','a');
    fwrite($log_login,"[".date("D, j/m/y-H:i:s")."] - [IP: ".getenv("REMOTE_ADDR")."] Autorizado: [".$usuario.":".$pass."]\r\n");
    fclose($log_login);
  }

    // if the session variable has been set, redirect
  if (isset($_SESSION['auth']) && $_SESSION['auth'] == 'Pearl Jam') {
    header('Location: inicio.php');
    exit;
  }
  // if the session variable hasn't been set, refuse entry
  else {
    $error = 'Usuario o Password incorrecto.';
    /* Escribir intento de acceso en el log */
    $log_login = fopen('login.log','a');
    fwrite($log_login,"[".date("D, j/m/y-H:i:s")."] - [IP: ".getenv("REMOTE_ADDR")."] NO Autorizado: [".$usuario.":".$pass_original."]\r\n");
    fclose($log_login);
  }
}

include_once('inc/inc.inc.php');
$title = "Panel de Administraci&oacute;n";
?>
<?php disp_header_admin($pre,$title,''); ?>
  <div id="contenedor">
    <div id="articulo">
      <?php if (isset($error_user)) {echo "<div class=\"box_error\">$error_user</div>";} ?>
      <?php if (isset($error_pass)) {echo "<div class=\"box_error\">$error_pass</div>";} ?>
      <?php if (isset($error)) {echo "<div class=\"box_error\">$error</div>";} ?>
      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>
      <fieldset>
        <legend><span class="login"><?php echo $title; ?></span></legend>
          <form action="" method="post" name="login">
            <div align="center"><table>
            <?php echo draw_input("Usuario: ",'usuario','text','','','table_horiz'); ?>
            <?php echo draw_input("Contrase&ntilde;a: ",'pass','password','','','table_horiz'); ?>
            <?php /* echo draw_input('',login,submit,Ingresar,'',table_horiz);*/ ?>
            <?php echo draw_input('','login','submit','Ingresar','','table_horiz'); ?>

            </table></div>
          </form>
      </fieldset>
    </div>
  </div>
<?php disp_footer_admin($pre); ?>