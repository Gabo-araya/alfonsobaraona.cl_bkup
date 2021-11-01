<?php
session_start();
ob_start();
// run this script only if the logout button has been clicked
if (array_key_exists('logout', $_POST)) {
  // empty the $_SESSION array
  $_SESSION = array();
  // invalidate the session cookie
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-86400, '/');
  }
  // end session and redirect
  session_destroy();
//  header('Location: login.php');
//  exit;
}
else {
  // empty the $_SESSION array
  $_SESSION = array();
  // invalidate the session cookie
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-86400, '/');
  }
  // end session and redirect
  session_destroy();
//  header('Location: login.php');
//  exit;
}
 include('inc/inc.inc.php');
  $title = "Salir";
 disp_header_admin($pre,$title); ?>
  <div id="contenedor">
    <div id="articulo">
    <h1><?php echo $title ?></h1>
    <fieldset>
      <legend><?php echo $title ?></legend>

      <div class="box_success">Usted ha salido correctamente del sistema.</div>
      <form action="index.php" method="POST">
        <div align="center"><br />
          <input type="submit" value="Continuar &raquo;" />
        </div>
      </form>
    </fieldset>
    </div>
  </div>
<?php disp_footer_admin($pre); ?>