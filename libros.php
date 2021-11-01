<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = ucfirst($self);
$section = "libros";
$tabla = $pre.$section;

disp_header($pre,$title);
   if (isset($_GET['cat'])){$categ_id = str_input($_GET['cat']);}
   else {$categ_id='1';}
// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

//$secc_princ = mysql_query("SELECT ".$secc." FROM ".$pre."info") or die(mysql_error());
//  $txt = str_output(mysql_result($secc_princ,0,$secc));


// Categorías de libes
     $cat_libros = mysql_query("SELECT cat_id,cat_libros FROM ".$pre."cat WHERE cat_libros IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_libros); ++$j) {
          $cat_id = mysql_result($cat_libros,$j,"cat_id");
          $cat_lib[$cat_id] = str_output(mysql_result($cat_libros,$j,"cat_libros"));
        }
      asort($cat_lib);

  $url_images_folder = "./".str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")))."/";
  $url_thumbs_folder = $url_images_folder."thumbs/";

?>
<div id="content">
         <?php echo "<h1>".$title."</h1>"; ?>

         <div class="article">
      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>

<?php
    if (isset($_GET['lib_id'])){$secc_name = "Destacados";}
    else {foreach ($cat_lib as $key => $value) {if ($key == $categ_id) {$secc_name = $value;}}}
?>

<div class="box_success">
<h2>INVITACIÓN  A CASAS EDITORIALES</h2>
<p>Se invita a agentes literarios y a editoriales especializadas en temas de desarrollo personal y similares a ofrecer proyectos de edición con el autor de los siguientes libros.</p>

</div>

<?php

  $datos = mysql_query("SELECT lib_id FROM ".$tabla." WHERE lib_cat='".$categ_id."' AND lib_pub='si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
    if (isset($_GET['lib_id'])){
      $lib_id = str_input($_GET['lib_id']);
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE lib_id='".$lib_id."' AND lib_pub='si'") or die(mysql_error());
      $total = 1;
    }
    else {
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE lib_cat='".$categ_id."' AND lib_pub='si'") or die(mysql_error());
  $total = mysql_result($datos,0);
  $datos = mysql_query("SELECT * FROM ".$tabla." WHERE lib_cat='".$categ_id."' AND lib_pub='si' ORDER BY lib_id DESC") or die(mysql_error());
    }

      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"lib_id");
          $lib_titulo = str_output(mysql_result($datos,$j,"lib_titulo"));
          $lib_imagen = str_output(mysql_result($datos,$j,"lib_imagen"));
          $lib_text = str_output(mysql_result($datos,$j,"lib_text"));
          $lib_resumen = str_output(mysql_result($datos,$j,"lib_resumen"));
          $lib_cat = str_output(mysql_result($datos,$j,"lib_cat"));
          $lib_pub = str_output(mysql_result($datos,$j,"lib_pub"));
          $lib_dest = str_output(mysql_result($datos,$j,"lib_dest"));

     if (!isset($_GET['lib_id'])){
?>
       <div class="descrip">
       <h3><a href="<?php echo $self.".php?lib_id=".$id; ?>" title="<?php echo $lib_titulo; ?>"><?php echo $lib_titulo; ?></a></h3>
       <a href="<?php echo $self.".php?lib_id=".$id; ?>" title="<?php echo $lib_titulo; ?>">
       <img src="<?php echo $url_thumbs_folder.$lib_imagen; ?>" border="0" align="left" alt="<?php echo $lib_titulo; ?>" /></a>

<?php
     }
     else {echo "<h3>$lib_titulo</h3><div align=\"center\"><img src=\"$url_images_folder$lib_imagen\" border=\"0\" alt=\"$lib_titulo\" /></div>";}

?>




<?php
     if (isset($_GET['lib_id'])){
       if(!empty($lib_text)) {echo $lib_text;}
     }
     else {
          echo $lib_resumen;
     }
     if (!isset($_GET['lib_id'])){
       if(!empty($lib_text)) {
         echo "<div class=\"leermas\"><a href=\"".$self.".php?lib_id=".$id."\">[leer más]</a></div>";
         echo "</div>";
       }
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