<?php
/***** Configuraciones *****/
//echo date_default_timezone_get();
date_default_timezone_set('America/Santiago');

//date_default_timezone_set('Europe/Paris');

//date_default_timezone_set('Chile/Continental');

//nombre del archivo actual
$phpself = basename($_SERVER['PHP_SELF']);
$php_self = $phpself;

//url del archivo actual
$thisurl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

//resultados por pagina
$pp = 15;  //Panel de Administración
$pp_pub = 15;  //Sitio Web
$pp_gal = 12;  //Galería de Imágenes

/* Definiciones */
define('ICONS','css/icons/'); //ruta relativa de carpeta de iconos

define('THUMB_WIDTH',120); //Tamaño de ancho de imagen en pixeles
define('THUMB_HEIGHT',120); //Tamaño de alto de imagen en pixeles
define('MAX_FILE_SIZE',5120000); //Tamaño máximo de archivo: 5000 Kb
define('MAX_IMAGE_SIZE',307200); //Tamaño máximo de archivo: 300 Kb

/* Prevenir errores de inicialización de variables*/
settype($validation,"string");
settype($categoria_id,"string");
settype($usuario,"string");
settype($pass,"string");
settype($pass_original,"string");

?>