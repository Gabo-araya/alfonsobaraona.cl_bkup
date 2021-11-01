<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = "Ponencia";
$secc = "info_".$self;

disp_header($pre,$title);

$secc_princ = mysql_query("SELECT ".$secc." FROM ".$pre."info") or die(mysql_error());
  $txt = str_output(mysql_result($secc_princ,0,$secc));
?>
       <div id="content">
       <h1><?php echo $title; ?></h1>
         <div class="article">
      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>

<?php
  echo $txt;
?>
          </div>
        </div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>