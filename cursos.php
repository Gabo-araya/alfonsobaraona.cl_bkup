<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = "Cursos Gratuitos";
$section = "cursos";
$tabla = $pre.$section;

disp_header($pre,$title);
   if (isset($_GET['cat'])){$categ_id = str_input($_GET['cat']);}
   else {$categ_id='1';}
// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

//$secc_princ = mysql_query("SELECT ".$secc." FROM ".$pre."info") or die(mysql_error());
//  $txt = str_output(mysql_result($secc_princ,0,$secc));


// Categorías de cursos
     $cat_cursos = mysql_query("SELECT cat_id,cat_cursos FROM ".$pre."cat WHERE cat_cursos IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_cursos); ++$j) {
          $cat_id = mysql_result($cat_cursos,$j,"cat_id");
          $cat_cur[$cat_id] = str_output(mysql_result($cat_cursos,$j,"cat_cursos"));
        }
      asort($cat_cur);
      //echo disp_array_asoc($cat_cur);
/*  $bin_array = array('si' => 'Sí','no' => 'No');      */

  $url_images_folder = "./".str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")))."/";
  $url_thumbs_folder = $url_images_folder."thumbs/";

?>
<div id="content">
         <?php echo "<h1>".$title."</h1>"; ?>
         <div class="article">
      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>

      <p>Tengo el agrado de informar a mis visitantes que mis cursos <strong>"Inteligencia Emocional para Emprendedores"</strong>; <strong>"Cómo enfrentar el Estrés Laboral"</strong> e <strong>"Inteligencia Emocional en la Pareja"</strong> han sido organizados como actividades gratuitas por la empresa Learning Group y la editora Emprenden, especialistas en capacitación y cursos a distancia.</p>
<?php
    if (isset($_GET['cur_id'])){$secc_name = "Destacados";}
    else {foreach ($cat_cur as $key => $value) {if ($key == $categ_id) {$secc_name = $value;}}}
?>


<?php

  $datos = mysql_query("SELECT cur_id FROM ".$tabla." WHERE cur_cat='".$categ_id."' AND cur_pub='si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE cur_cat='".$categ_id."' AND cur_pub='si'") or die(mysql_error());
  $total = mysql_result($datos,0);
  $datos = mysql_query("SELECT * FROM ".$tabla." WHERE cur_cat='".$categ_id."' AND cur_pub='si' ORDER BY cur_id DESC") or die(mysql_error());

      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"cur_id");
          $cur_titulo = str_output(mysql_result($datos,$j,"cur_titulo"));
          $cur_imagen = str_output(mysql_result($datos,$j,"cur_imagen"));
          $cur_text = str_output(mysql_result($datos,$j,"cur_text"));
          $cur_cat = str_output(mysql_result($datos,$j,"cur_cat"));
          $cur_pub = str_output(mysql_result($datos,$j,"cur_pub"));
          $cur_dest = str_output(mysql_result($datos,$j,"cur_dest"));


?>
       <div class="descrip">
       <h3><?php echo $cur_titulo; ?></h3>
       <img src="<?php echo $url_thumbs_folder.$cur_imagen; ?>" border="0" align="left" alt="<?php echo $cur_titulo; ?>" />

<?php
          echo $cur_text;
          echo "</div>";
      }
   // echo paginar($total,$pp_pub,$st,$thisurl."?&amp;cat=".$categ_id."&amp;st=");
  }
?>
     </div>
</div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>