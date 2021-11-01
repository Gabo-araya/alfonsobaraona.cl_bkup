<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));  
$title = "Relaxacción - Video Testimonial";
disp_header($pre,$title);
?>
       <div id="content">
       <h1><?php echo $title; ?></h1>
         <div class="article">
<div align="center">
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="350" height="320" id="relaxaccion3" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="video/relaxaccion3.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<embed src="video/relaxaccion3.swf" quality="high" bgcolor="#ffffff" width="350" height="320" name="relaxaccion3" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object></div>

          </div>
        </div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>