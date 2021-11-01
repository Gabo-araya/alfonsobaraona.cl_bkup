<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = ucfirst($self);
$section = "not";
$tabla = $pre.$section;

disp_header($pre,$title);


if(isset($_GET['cat'])){$categ_id = str_input($_GET['cat']);} else {$categ_id = '1';}
// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

  $date =time ();

  $meses = array('1' => 'Enero',
                 '2' => 'Febrero',
                 '3' => 'Marzo',
                 '4' => 'Abril',
                 '5' => 'Mayo',
                 '6' => 'Junio',
                 '7' => 'Julio',
                 '8' => 'Agosto',
                 '9' => 'Septiembre',
                 '10' => 'Octubre',
                 '11' => 'Noviembre',
                 '12' => 'Diciembre');

  $dias = array('1' => 'Lunes',
                '2' => 'Martes',
                '3' => 'Miércoles',
                '4' => 'Jueves',
                '5' => 'Viernes',
                '6' => 'Sábado',
                '7' => 'Domingo');

  $hoy = date('d', $date);
  $dia_hoy = date('N', $date);
  $mes = date('m', $date);
  $anio = date('Y', $date);
  $hora = date('H', $date);
  $minut = date('i', $date);

// Categorías de productos
     $cat_noticias = mysql_query("SELECT cat_id,cat_noticias FROM ".$pre."cat WHERE cat_noticias IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_noticias); ++$j) {
          $cat_id = mysql_result($cat_noticias,$j,"cat_id");
          $cat_not[$cat_id] = str_output(mysql_result($cat_noticias,$j,"cat_noticias"));
        }
      asort($cat_not);

?>
<div id="content">
         <?php echo "<h1>".$title."</h1>"; ?>
         <div class="article">
      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>

<?php
    foreach ($cat_not as $key => $value) {if ($key == $categ_id) {$secc_name = $value;}}
  if (isset($_GET['not_id'])){
    $not_id = str_input($_GET['not_id']);
    $datos = mysql_query("SELECT * FROM ".$tabla." WHERE not_id='".$not_id."' AND not_pub='si'") or die(mysql_error());
    $num_rows = mysql_num_rows($datos);

    if ($num_rows == 0) {
        draw_noitems();
    }
    else {

      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"not_id");
          $not_nombre = str_output(mysql_result($datos,$j,"not_nombre"));
          $not_resena = str_output(mysql_result($datos,$j,"not_resena"));
          $not_text = str_output(mysql_result($datos,$j,"not_text"));
          $not_pub = str_output(mysql_result($datos,$j,"not_pub"));
          $not_dest = str_output(mysql_result($datos,$j,"not_dest"));
          $not_fecha = str_output(mysql_result($datos,$j,"not_fecha"));

      $nombre_not_dia = date('N',$not_fecha);
      $not_mes = date("m",$not_fecha);
      $not_dia = date("d",$not_fecha);
      $not_anio = date("Y",$not_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_not_dia) {$fecha = $value.", ";} }
      $fecha .= $not_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {$fecha .= $value;} }
      $fecha .= " de ".$not_anio;

?>



        <h2><?php echo $not_nombre; ?></h2>
          <?php echo $not_resena; ?>
          <?php echo $not_text; ?>




<?php     }
        }
  }
  else {
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE not_cat='".$categ_id."' AND not_pub='si'") or die(mysql_error());
  $total = mysql_result($datos,0);

  $datos = mysql_query("SELECT * FROM ".$tabla." WHERE not_cat='".$categ_id."' AND not_pub='si' ORDER BY not_fecha DESC LIMIT ".$st.",".$pp_pub."") or die(mysql_error());
    $num_rows = mysql_num_rows($datos);

    if ($num_rows == 0) {
        draw_noitems();
    }
    else {
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"not_id");
          $not_nombre = str_output(mysql_result($datos,$j,"not_nombre"));
          $not_resena = str_output(mysql_result($datos,$j,"not_resena"));
          $not_text = str_output(mysql_result($datos,$j,"not_text"));
          $not_cat = str_output(mysql_result($datos,$j,"not_cat"));
          $not_pub = str_output(mysql_result($datos,$j,"not_pub"));
          $not_dest = str_output(mysql_result($datos,$j,"not_dest"));
          $not_fecha = str_output(mysql_result($datos,$j,"not_fecha"));

      $nombre_not_dia = date('N',$not_fecha);
      $not_mes = date("m",$not_fecha);
      $not_dia = date("d",$not_fecha);
      $not_anio = date("Y",$not_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_not_dia) {$fecha = $value.", ";} }
      $fecha .= $not_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $not_mes) {$fecha .= $value;} }
      $fecha .= " de ".$not_anio;

?>



        <?php
          if(!empty($not_text)) { echo "<a href=\"noticias.php?not_id=".$id."\"><h2>".$not_nombre."</h2></a>";}
          else {echo "<h2>".$not_nombre."</h2>";}
        ?>
          <?php echo $not_resena; ?>
          <?php if(!empty($not_text)) { echo "<div class=\"leermas\"><a href=\"noticias.php?not_id=".$id."\">[leer más]</a></div>";} ?>


<?php
       }
    echo paginar($total,$pp_pub,$st,$thisurl."?&amp;cat=".$categ_id."&amp;st=");
    }
  }

?>

</div></div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>

<?php disp_footer($pre); ?>