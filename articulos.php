<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = "Artículos";
$section = "artic";
$tabla = $pre.$section;

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

disp_header($pre,$title);
   if (isset($_GET['cat'])){$categ_id = str_input($_GET['cat']);}
   else {$categ_id='1';}
// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

//$secc_princ = mysql_query("SELECT ".$secc." FROM ".$pre."info") or die(mysql_error());
//  $txt = str_output(mysql_result($secc_princ,0,$secc));


// Categorías de artic
     $cat_articulos = mysql_query("SELECT cat_id,cat_articulos FROM ".$pre."cat WHERE cat_articulos IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_articulos); ++$j) {
          $cat_id = mysql_result($cat_articulos,$j,"cat_id");
          $cat_artic[$cat_id] = str_output(mysql_result($cat_articulos,$j,"cat_articulos"));
        }
      asort($cat_artic);
      //echo disp_array_asoc($cat_artic);
/*  $bin_array = array('si' => 'Sí','no' => 'No');      */



?>
<div id="content">
         <?php echo "<h1>".$title."</h1>"; ?>
         <div class="article">
      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>

<?php
    if (isset($_GET['artic_id'])){$secc_name = "Destacados";}
    else {foreach ($cat_artic as $key => $value) {if ($key == $categ_id) {$secc_name = $value;}}}
?>


<?php

  $datos = mysql_query("SELECT artic_id FROM ".$tabla." WHERE artic_cat='".$categ_id."' AND artic_pub='si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
    if (isset($_GET['artic_id'])){
      $artic_id = str_input($_GET['artic_id']);
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE artic_id='".$artic_id."' AND artic_pub='si'") or die(mysql_error());
      $total = 1;
    }
    else {
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE artic_cat='".$categ_id."' AND artic_pub='si'") or die(mysql_error());
  $total = mysql_result($datos,0);
  $datos = mysql_query("SELECT * FROM ".$tabla." WHERE artic_cat='".$categ_id."' AND artic_pub='si' ORDER BY artic_id DESC") or die(mysql_error());
    }

      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"artic_id");
          $artic_titulo = str_output(mysql_result($datos,$j,"artic_titulo"));
          $artic_text = str_output(mysql_result($datos,$j,"artic_text"));
          $artic_cat = str_output(mysql_result($datos,$j,"artic_cat"));
          $artic_pub = str_output(mysql_result($datos,$j,"artic_pub"));
          $artic_dest = str_output(mysql_result($datos,$j,"artic_dest"));
          $artic_fecha = str_output(mysql_result($datos,$j,"artic_fecha"));

      $nombre_artic_dia = date('N',$artic_fecha);
      $artic_mes = date("m",$artic_fecha);
      $artic_dia = date("d",$artic_fecha);
      $artic_anio = date("Y",$artic_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_artic_dia) {$fecha = $value.", ";} }
      $fecha .= $artic_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $artic_mes) {$fecha .= $value;} }
      $fecha .= " de ".$artic_anio;


     if (!isset($_GET['artic_id'])){
?>
       <div class="descrip">
       <h3><a href="<?php echo $self.".php?artic_id=".$id; ?>" title="<?php echo $artic_titulo; ?>"><?php echo $artic_titulo; ?></a></h3>

<?php
     }
     else {echo "<h3>$artic_titulo</h3>";}

?>




<?php
     if (isset($_GET['artic_id'])){
       if(!empty($artic_text)) {echo $artic_text;}
     }
     else {

          $extracto_resena = substr($artic_text, 0, 300);
          $ultimo_espacio = strrpos($extracto_resena, ' ');
          $artic_text = substr($extracto_resena, 0, $ultimo_espacio).'... ';
          echo $artic_text;
     }
     if (!isset($_GET['artic_id'])){
       echo "<div class=\"leermas\"><a href=\"".$self.".php?artic_id=".$id."\">[leer más]</a></div>";
       echo "</div>";
     }
?>

<?php     }
   // echo paginar($total,$pp_pub,$st,$thisurl."?&amp;cat=".$categ_id."&amp;st=");
  }
?>
     </div>
</div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>