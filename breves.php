<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = "Textos Breves: Comida R&aacute;pida";
$section = "breves";
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


// Categorías de breves
     $cat_breves = mysql_query("SELECT cat_id,cat_breves FROM ".$pre."cat WHERE cat_breves IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_breves); ++$j) {
          $cat_id = mysql_result($cat_breves,$j,"cat_id");
          $cat_brev[$cat_id] = str_output(mysql_result($cat_breves,$j,"cat_breves"));
        }
      asort($cat_brev);
      //echo disp_array_asoc($cat_brev);
/*  $bin_array = array('si' => 'Sí','no' => 'No');      */

  $url_images_folder = "./".str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")))."/";
  $url_thumbs_folder = $url_images_folder."thumbs/";

?>
<div id="content">
         <?php echo "<h1>".$title."</h1>"; ?>
         <div class="article">
      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>
 <div align="center"><img src="img/quicklunch.jpg" alt="Textos Breves, Comida Rápida" border="0" width="430" /></div>
 <h2>Vea la carta del Chef, seleccione y disfrute.</h2>
<?php
    if (isset($_GET['brev_id'])){$secc_name = "Destacados";}
    else {foreach ($cat_brev as $key => $value) {if ($key == $categ_id) {$secc_name = $value;}}}
?>


<?php
  $datos = mysql_query("SELECT brev_id FROM ".$tabla." WHERE brev_cat='".$categ_id."' AND brev_pub='si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
    if (isset($_GET['brev_id'])){
      $brev_id = str_input($_GET['brev_id']);
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE brev_id='".$brev_id."' AND brev_pub='si'") or die(mysql_error());
      $total = 1;
    }
    else {
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE brev_cat='".$categ_id."' AND brev_pub='si'") or die(mysql_error());
  $total = mysql_result($datos,0);
  $datos = mysql_query("SELECT * FROM ".$tabla." WHERE brev_cat='".$categ_id."' AND brev_pub='si' ORDER BY brev_id DESC") or die(mysql_error());
    }
      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"brev_id");
          $brev_titulo = str_output(mysql_result($datos,$j,"brev_titulo"));
          $brev_imagen = str_output(mysql_result($datos,$j,"brev_imagen"));
          $brev_text = str_output(mysql_result($datos,$j,"brev_text"));
          $brev_cat = str_output(mysql_result($datos,$j,"brev_cat"));
          $brev_pub = str_output(mysql_result($datos,$j,"brev_pub"));
          $brev_dest = str_output(mysql_result($datos,$j,"brev_dest"));
          $brev_fecha = str_output(mysql_result($datos,$j,"brev_fecha"));

      $nombre_brev_dia = date('N',$brev_fecha);
      $brev_mes = date("m",$brev_fecha);
      $brev_dia = date("d",$brev_fecha);
      $brev_anio = date("Y",$brev_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_brev_dia) {$fecha = $value.", ";} }
      $fecha .= $brev_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $brev_mes) {$fecha .= $value;} }
      $fecha .= " de ".$brev_anio;


     if (!isset($_GET['brev_id'])){


?>
       <div class="descrip">
       <h3><a href="<?php echo $self.".php?brev_id=".$id; ?>" title="<?php echo $brev_titulo; ?>"><?php echo $brev_titulo; ?></a></h3>

<?php
/*
       <a href="<?php echo $self.".php?brev_id=".$id; ?>" title="<?php echo $brev_titulo; ?>">
       <img src="<?php echo $url_thumbs_folder.$brev_imagen; ?>" border="0" align="left" alt="<?php echo $brev_titulo; ?>" /></a>
*/

     }
     else {echo "<h3>$brev_titulo</h3><div align=\"center\"><img src=\"$url_images_folder$brev_imagen\" border=\"0\" alt=\"$brev_titulo\" /></div>";}

?>




<?php
     if (isset($_GET['brev_id'])){
       if(!empty($brev_text)) {echo $brev_text;}
     }
     else {
// agrega texto introductorio de reseña
/*
          $extracto_resena = substr($brev_text, 0, 250);
          $ultimo_espacio = strrpos($extracto_resena, ' ');
          $brev_text = substr($extracto_resena, 0, $ultimo_espacio).'... ';
          echo $brev_text;
*/
     }


     if (!isset($_GET['brev_id'])){
/*
       echo "<div class=\"leermas\"><a href=\"".$self.".php?brev_id=".$id."\">[leer más]</a></div>";
*/
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