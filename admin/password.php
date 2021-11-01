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
$title = "Cambiar Contraseña";
disp_header_admin($pre,$title);
include('inc/menu_admin.inc.php');
$tabla = $pre."usuarios";

disp_inicio_info($title,'password');

?>
    <fieldset>
      <legend>Cambiar Contraseña</legend>
<?php
if (isset($_GET['act'])){
  switch ($_GET['act']){

//Validación de Modificación
    case 'vm':
    //código de validación

    $user = str_input($_POST['user']);
    $old_pass = str_input(md5($_POST['old_pass']));
    $new_pass = str_input($_POST['new_pass']);
    $new_pass2 = str_input($_POST['new_pass2']);

    if (empty($old_pass)) {$error .= "Debe escribir su contraseña actual.<br />";}
    $login = mysql_query("SELECT usuarios_id FROM ".$tabla." WHERE usuarios_nombre='".$user."' AND usuarios_password='".$old_pass."'") or die(mysql_error());
    if (mysql_num_rows($login)>0){}
    else {$error = "Su contraseña actual no coincide. Escríbala correctamente.<br />";}

    if (empty($new_pass)) {$error .= "Debe escribir una nueva contraseña.<br />";}
    if (empty($new_pass2)) {$error .= "Debe repetir la nueva contraseña.<br />";}
    if ($new_pass !== $new_pass2) {$error .= "La nueva contraseña y su repetición no coinciden.<br />";}

  if (!empty($error)) {
      echo "<div class=\"box_validation\">".$error."</div>";
?>
      <form action="<?php echo $phpself; ?>" method="POST">
        <div align="center"><br />
          <input type="submit" value="Intentar nuevamente &raquo;" />
        </div>
      </form>
<?php
      disp_fin_info();
      disp_footer_admin($pre);
      exit;
    }
    else {
    // Actualizar
      if ($_SERVER['REQUEST_METHOD']=='POST'){
        $user = str_input($_POST['user']);
        $new_pass = str_input(md5($_POST['new_pass']));
        $sql_data = array('usuarios_password' => $new_pass);
//        echo disp_array_asoc($sql_data);
        sql_input($tabla,$sql_data,'update',"usuarios_nombrex".$user);
        echo "<form action=\"logout.php\" method=\"POST\">
        <div align=\"center\"><br /><input type=\"submit\" value=\"Salir &raquo;\" /></div></form>";
      }
    }
      break;

    default:
      $_SESSION = array();
      header('Location: index.php');
      exit;

  }
}
else{
?>
      <div class="box_info">
        <p>Usted ha ingresado como [<?php echo $_SESSION['user']; ?>]
        y tiene privilegios de [<?php if($_SESSION['type'] =='admin') {echo 'administrador';} else {echo $_SESSION['type'];} ?>].</p>
        <p>Tenga en cuenta que <strong>si cambia su contraseña, deberá salir inmediatamente para que los cambios tengan efecto.</strong></p>
      </div>

      <form action="<?php echo $phpself; ?>?&amp;act=vm" method="post" >
      <table width="100%"  border="0"  align="center">
        <?php echo draw_input("Ingrese su contraseña actual: ",'old_pass','password','','','table_horiz',30,'req'); ?>
        <?php echo draw_input("Ingrese su nueva contraseña: ",'new_pass','password','','','table_horiz',30,'req'); ?>
        <?php echo draw_input("Repita su nueva contraseña: ",'new_pass2','password','','','table_horiz',30,'req'); ?>
        <?php echo draw_input('','user','hidden',$_SESSION['user']); ?>
      </table>
      <div align="center"><br /><?php echo draw_input('','submit','submit','Modificar'); ?></div>
      </form>
    </fieldset>
<?php
}
  disp_fin_info();
  disp_footer_admin($pre);
?>