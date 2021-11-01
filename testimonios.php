<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = "Testimonios de Relaxacción";
$tabla = $pre.$self;
disp_header($pre,$title);
?>
       <div id="content">
       <h1><?php echo $title; ?></h1>
         <div class="article">
      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>
<?php
  $query = mysql_query("SELECT test_pub FROM ".$tabla." WHERE test_pub='si' ORDER BY test_id") or die(mysql_error());
  if (mysql_num_rows($query) == 0) {
        draw_noitems();
  }
  else {
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE test_pub='si' ORDER BY test_id DESC") or die(mysql_error());
          $content = "";
      for($j=0; $j<mysql_num_rows($datos); $j++) {
          $nombre = str_output(mysql_result($datos,$j,"test_nombre"));
          $text = str_output(mysql_result($datos,$j,"test_text"));
          $pub = str_output(mysql_result($datos,$j,"test_pub"));

          $content .= "<div class=\"descrip\">";
          $content .= "<p><strong>".$nombre."</strong></p>";
          $content .= $text;
          $content .= "</div>";
          }

      echo $content;
  }
?>
          </div>
        </div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>