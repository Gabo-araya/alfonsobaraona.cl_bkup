<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));  
$title = "Entrevista a Alfonso Baraona";
disp_header($pre,$title);
?>
       <div id="content">
       <h1><?php echo $title; ?></h1>
         <div class="article">

         <p>Esta entrevista fue realizada durante el año 2000, en un canal de cable.</p>

<div align="center">
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="350" height="320" id="entrev_JABS_01" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="video/entrev_JABS_01.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<embed src="video/entrev_JABS_01.swf" quality="high" bgcolor="#ffffff" width="350" height="320" name="entrev_JABS_01" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object></div>

          </div>
        </div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>