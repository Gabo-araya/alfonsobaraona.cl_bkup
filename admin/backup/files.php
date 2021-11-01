<?php
  $self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
  $thisdir = strrev(strstr(strrev($_SERVER['SCRIPT_FILENAME']),'/')).'backup/';
  $dir_file = $thisdir;
  $thisdir = scandir($thisdir);
  arsort($thisdir);
?>
      <fieldset>
        <legend>Visualizar Copias de Seguridad</legend>
<table width="100%" border="0">
  <thead>
    <th>&nbsp;</th>
    <th align="left"><strong>Nombre</strong></th>
    <th align="left"><strong>Tamaño</strong></th>
    <th align="left"><strong>Acción</strong></th>
  </thead>
<?php
  foreach ($thisdir as $value) {
    $file_ext = str_replace(".","",strrchr($value, "."));
    if($file_ext!=='sql'){}
    else{
      // abrir un archivo
      $f = fopen($dir_file.$value, "r");
      // reunir estadísticas
      $fstats = fstat($f);
      // cerrar el archivo
      fclose($f);
      $tamano = $fstats['size'];
?>
  <tr>
    <td align="left"><img src="<?php echo ICONS."file_".$file_ext; ?>.png" alt="Archivo <?php echo $file_ext; ?>" /></td>
    <td align="left" valign="top">
      <?php echo $value; ?></td>
    <td align="left" valign="top">[<?php echo number_format($tamano/1024, 1).' Kb'; ?>]</td>
<?php disp_icons_files_backup($php_self,$value); ?>
  </tr>
<?php
    }
  }
?>
</table>
</fieldset>