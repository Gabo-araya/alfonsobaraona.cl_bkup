<?php

/* Crea header */
  function disp_header($pre,$title=''){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
    $tabla = $pre."conf";
    $datos = mysql_query("SELECT * FROM ".$tabla."") or die(mysql_error());
      for($j=0; $j<mysql_num_rows($datos); $j++) {
        $conf_nombre_sitio = str_output(mysql_result($datos,$j,"conf_nombre_sitio"));
        $conf_slogan = str_output(mysql_result($datos,$j,"conf_slogan"));
      }
    if(isset($title)){echo "<title>$title | $conf_nombre_sitio</title>\n";}
    else {echo "<title>$conf_nombre_sitio</title>\n";}
    $host = "http://".$_SERVER['HTTP_HOST']."/";
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php
$phpself = basename($_SERVER['PHP_SELF']);
$rss_self = str_replace(".","",strrev(strstr(strrev($phpself),"."))).".xml";
    if(file_exists($rss_self)){
      $rss_host = 'http://'.$_SERVER['HTTP_HOST'];
      $cadena = implode('/',explode('/',strrev(strstr(strrev($_SERVER['SCRIPT_NAME']),'/')),-1));
      $url = $rss_host.$cadena.'/'.$rss_self;
      echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"".$url."\" />\n";
    }
?>

  <link href="css/reset.css" rel="stylesheet" type="text/css" />
  <link href="css/estilo.css" rel="stylesheet" type="text/css" />
  <link href="css/generic.css" rel="stylesheet" type="text/css" />
  <link href="css/niftyCorners.css" rel="stylesheet" type="text/css" />
  <script src="js/embeddedcontent.js" type="text/javascript" defer="defer"></script>
  <script src="js/niftycube.js" type="text/javascript"></script>
  <script type="text/javascript">
    window.onload=function(){
      Nifty("div.article","big transparent");
      Nifty("div.descrip","big transparent");
    }
  </script>
</head>
<body>
  <a id="skip" href="#content" accesskey="c" title="Saltar men&uacute;: Ir a contenidos [C]">Saltar a contenidos [C]</a>
  <div id="wrapper">
    <div id="header">
      <h1 class="hidden"><a href="<?php echo $host; ?>"><?php echo $conf_nombre_sitio; ?></a></h1>
      <a href="<?php echo $host; ?>" title="Ir al Inicio">
      <img src="img/header.jpg" alt="Desarrollo Personal - Alfonso Baraona" width="760" height="70" border="0" /></a>
    </div>
<?php
  }

/* Crea header IMG */
  function disp_header_img($pre,$title=''){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
    $tabla = $pre."conf";
    $datos = mysql_query("SELECT * FROM ".$tabla."") or die(mysql_error());
      for($j=0; $j<mysql_num_rows($datos); $j++) {
        $conf_nombre_sitio = str_output(mysql_result($datos,$j,"conf_nombre_sitio"));
        $conf_slogan = str_output(mysql_result($datos,$j,"conf_slogan"));
      }
    if(isset($title)){echo "<title>$title | $conf_nombre_sitio</title>\n";}
    else {echo "<title>$conf_nombre_sitio</title>\n";}
    $host = "http://".$_SERVER['HTTP_HOST']."/";
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link href="../css/estilo.css" rel="stylesheet" type="text/css" />
  <link href="../css/generic.css" rel="stylesheet" type="text/css" />
  <link href="../css/niftyCorners.css" rel="stylesheet" type="text/css" />
  <script src="../js/embeddedcontent.js" type="text/javascript" defer="defer"></script>
  <script src="../js/niftycube.js" type="text/javascript"></script>
  <script type="text/javascript">
    window.onload=function(){
      Nifty("div.article","big transparent");
    }
  </script>
</head>
<body>
  <a id="skip" href="#content" accesskey="c" title="Saltar men&uacute;: Ir a contenidos [C]">Saltar a contenidos [C]</a>
  <div id="wrapper">
    <div id="header">
      <h1><a href="<?php echo $host; ?>"><?php echo $conf_nombre_sitio; ?></a></h1>
    </div>
<?php
  }

/* Crea header PRINT */
  function disp_header_print($pre,$title = ''){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
    $tabla = $pre."conf";
    $datos = mysql_query("SELECT * FROM ".$tabla."") or die(mysql_error());
      for($j=0; $j<mysql_num_rows($datos); $j++) {
        $conf_nombre_sitio = str_output(mysql_result($datos,$j,"conf_nombre_sitio"));
        $conf_slogan = str_output(mysql_result($datos,$j,"conf_slogan"));
      }
    if(isset($title)){echo "<title>$title | $conf_nombre_sitio</title>\n";}
    else {echo "<title>$conf_nombre_sitio</title>\n";}
    $host = "http://".$_SERVER['HTTP_HOST']."/";
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link href="css/reset.css" rel="stylesheet" type="text/css" />
  <link href="css/print.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div id="container">
  <div id="header">
    <div id="logo">
    <h1><a href="<?php echo $host; ?>"><?php echo $conf_nombre_sitio; ?></a></h1>
    <p><?php echo $conf_slogan; ?></p>
    </div>
  </div>
<?php
  }

/* Crea footer */
  function disp_footer($pre){
?>

      <div id="footer">
        <p>Desarrollo Personal - <a href="http://www.alfonsobaraona.cl/"><strong>AlfonsoBaraona.cl</strong></a> | <?php echo date("Y"); ?> |
       Diseño: <a href="http://portrait.cl/" title="Ir al Sitio Web">Gabriel Araya Rocha</a></p>
        <p> Meditaci&oacute;n - Realizaci&oacute;n personal - Inteligencia Emocional -
                 Entrenamiento Antiestr&eacute;s - Reflexiones/Lecturas - Talleres</p>
      </div>
  </div>

<!-- Begin Nedstat Basic code --><!-- Title: desarrollo personal --><!-- URL: <http://www.vtr.net/~abaraona> -->
<script language="JavaScript">
<!--
nedstatbasic("ABKorAlIB/ReFlXfMoflm0R5k7bQ", 0);
// -->
</script><a target="_blank" href="http://webstats.motigo.com/stats?ABKorAlIB/ReFlXfMoflm0R5k7bQ"><img src="http://m1.webstats.motigo.com/n?id=ABKorAlIB/ReFlXfMoflm0R5k7bQ&amp;p=11&amp;w=1440&amp;h=900&amp;c=32&amp;v=2" alt="Webstats4U - Free web site statistics" border="0" height="18" width="18"></a><script language="JavaScript" type="text/javascript" src="http://m1.webstats.motigo.com/md.js?country=cl&amp;id=ABKorAlIB/ReFlXfMoflm0R5k7bQ&amp;_t=1227703998156"></script><a target="_blank" href="http://webstats.motigo.com/stats?ABKorAlIB/ReFlXfMoflm0R5k7bQ"><img src="libros_files/n.gif" alt="Webstats4U - Free web site statistics" border="0" height="18" width="18"></a><script language="JavaScript" type="text/javascript" src="libros_files/md.js"></script>
<noscript>
<a href="http://v1.nedstatbasic.net/stats?ABKorAlIB/ReFlXfMoflm0R5k7bQ"
target="_blank"></a>
<!--[if gte vml 1]><v:shapetype id="_x0000_t75" coordsize="21600,21600"
o:spt="75" o:preferrelative="t" path="m@4@5l@4@11@9@11@9@5xe"
filled="f" stroked="f"> <v:stroke joinstyle="miter"/> <v:formulas> <v:f
eqn="if lineDrawn pixelLineWidth 0"/> <v:f eqn="sum @0 1 0"/> <v:f
eqn="sum 0 0 @1"/> <v:f eqn="prod @2 1 2"/> <v:f eqn="prod @3 21600
pixelWidth"/> <v:f eqn="prod @3 21600 pixelHeight"/> <v:f eqn="sum @0 0
1"/> <v:f eqn="prod @6 1 2"/> <v:f eqn="prod @7 21600 pixelWidth"/>
<v:f eqn="sum @8 21600 0"/> <v:f eqn="prod @7 21600 pixelHeight"/> <v:f
eqn="sum @10 21600 0"/> </v:formulas> <v:path o:extrusionok="f"
gradientshapeok="t" o:connecttype="rect"/> <o:lock v:ext="edit"
aspectratio="t"/>
</v:shapetype><v:shape id="_x0000_s1026" type="#_x0000_t75" alt=""
href="http://v1.nedstatbasic.net/stats?ABKorAlIB/ReFlXfMoflm0R5k7bQ"
target="&quot;_blank&quot;" style='position:absolute;margin-left:0;
margin-top:0;width:13.5pt;height:13.5pt;z-index:1;mso-wrap-distance-left:0;
mso-wrap-distance-top:0;mso-wrap-distance-right:0;mso-wrap-distance-bottom:0;
mso-position-horizontal:left;mso-position-horizontal-relative:text;
mso-position-vertical-relative:line' o:allowoverlap="f" o:button="t">
<v:imagedata
src="http://m1.nedstatbasic.net/n?id=ABKorAlIB/ReFlXfMoflm0R5k7bQ"/>
<w:wrap type="square"/>
</v:shape><![endif]-->
<![if !vml]>
<a href="http://v1.nedstatbasic.net/stats?ABKorAlIB/ReFlXfMoflm0R5k7bQ"
target="&quot;_blank&quot;">
<img border=0 width=18 height=18
src="http://m1.nedstatbasic.net/n?id=ABKorAlIB/ReFlXfMoflm0R5k7bQ"
align=left nosave v:shapes="_x0000_s1026">
</a>
<![endif]>
<a
href="http://v1.nedstatbasic.net/stats?ABKorAlIB/ReFlXfMoflm0R5k7bQ"></a>
<a
href="http://v1.nedstatbasic.net/stats?ABKorAlIB/ReFlXfMoflm0R5k7bQ"></a>
<a href="http://v1.nedstatbasic.net/stats?ABKorAlIB/ReFlXfMoflm0R5k7bQ"
target="_blank"></a>

<o:p></o:p></p>
</noscript>
<!-- End Nedstat Basic code -->

</body>
</html>
<?php
  }

/* Crea footer */
  function disp_footer_print($pre){
?>
      <div id="footer">
        <p>Desarrollo Personal - <a href="http://www.alfonsobaraona.cl/"><strong>AlfonsoBaraona.cl</strong></a> |
                <?php echo date("Y"); ?> &copy;
       Todos los derechos resevados |
       Diseño: <a href="http://www.portrait.cl/" title="Ir a Portrait">Portrait.cl</a></p>
        <p> Meditaci&oacute;n - Realizaci&oacute;n personal - Inteligencia Emocional -
                 Entrenamiento Antiestr&eacute;s - Reflexiones/Lecturas - Talleres</p>
      </div>
    </div>
  </div>
</body>
</html>
<?php
  }

/* Muestra resultados paginados */
  function paginar($total,$pp,$st,$url){
    settype($page_first, "string");
    settype($page_previous, "string");
    settype($page_nav, "string");
    settype($page_next, "string");
    settype($page_last, "string");
    settype($paginar, "string");

    if($total>$pp){
      $resto=$total%$pp;
      if($resto==0) {
        $pages=$total/$pp;
      }
      else{
        $pages=(($total-$resto)/$pp)+1;
      }

      if($pages>10){
        $current_page=($st/$pp)+1;
        if($st==0){
          $first_page=0;
          $last_page=10;
        }
        else if($current_page>=5 && $current_page<=($pages-5)){
          $first_page=$current_page-5;
          $last_page=$current_page+5;
        }
        else if($current_page<5){
          $first_page=0;
          $last_page=$current_page+5+(5-$current_page);
        }
        else{
          $first_page=$current_page-5-(($current_page+5)-$pages);
          $last_page=$pages;
        }
      }
      else{
        $first_page=0;
        $last_page=$pages;
      }

      for($i=$first_page;$i<$last_page;$i++){
        $pge=$i+1;
        $nextst=$i*$pp;
        if($st==$nextst) {
          $page_nav .= "<em>".$pge."</em>";
        }
        else{
          $page_nav .= "<a href=\"".$url.$nextst."\" title=\"Ir a esta página\">".$pge."</a>";
//          $page_nav .= "<a href=\"".$url.$nextst."\" mce_href=\"".$url.$nextst."\">".$pge."</a>";
        }
      }
      if($st==0){ $current_page = 1; }
      else{ $current_page = ($st/$pp)+1; }

      if($current_page< $pages){
        $page_last = "<a href=\"".$url.($pages-1)*$pp."\" title=\"Último\">&raquo;</a>";
        $page_next = "<a href=\"".$url.$current_page*$pp."\" title=\"Siguientes\">Siguientes &#8250;</a>";
//        $page_last = "<a href=\"".$url.($pages-1)*$pp."\" mce_href=\"".$url.($pages-1)*$pp."\">[&raquo;]</a>";
//        $page_next = "<a href=\"".$url.$current_page*$pp."\" mce_href=\"".$url.$current_page*$pp."\">[&#8250;]</a>";
      }

      if($st>0) {
        $page_first = "<a href=\"".$url."0\" title=\"Primero\">&laquo;</a>";
        $page_previous = "<a href=\"".$url."".($current_page-2)*$pp."\" title=\"Anteriores\">&#8249; Anteriores</a>";
//        $page_first = "<a href=\"".$url."0\" mce_href=\"".$url."0\">[&laquo;]</a></a>";
//        $page_previous = "<a href=\"".$url."".($current_page-2)*$pp."\" mce_href=\"".$url."".($current_page-2)*$pp."\">[&#8249;]</a>";
      }
    }
    $paginar .= "<div class=\"paginar\">$page_first $page_previous $page_nav $page_next $page_last</div>";
    return $paginar;
  }

?>