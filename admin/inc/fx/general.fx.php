<?php

/* Javascript: Eliminar Item */
  function js_delete(){
?>
  <script>
    function eliminar(id){
      if(confirm("Si acepta, este registro va a ser eliminado.")){
        window.location='<?php base(); ?>?&act=eliminar&id=' + id;
      }
    }
</script>
<noscript>
  <div class="box_warning">
    <p>Para el completo funcionamiento de este sitio, por favor active Javascript en su navegador web.</p>
    <p>Si no sabe cómo hacerlo, quizá en algún buscador (<a href="http://www.google.cl/">Google</a>,
       <a href="http://www.yahoo.es/">Yahoo!</a>,
       <a href="http://www.altavista.com/">Altavista</a>,
       <a href="http://www.ixquick.com/">Ixquick</a>) encuentre la solución.</p>
  </div>
</noscript>
<?php
  }
/* Obtiene el nombre de archivo */
  function base() {
//    global($_SERVER[PHP_SELF]);
    $base = basename($_SERVER['PHP_SELF']);
    echo $base;
  }
/* Obtiene la ruta de la carpeta de imagenes */
//url_images_folder($_SERVER['HTTP_REFERER'],$phpself);
  function url_images_folder($http_referer,$phpself){
    $cadena = explode('/',str_replace(".","",strrev(strrchr(strrev($http_referer),"."))), -1);
    array_pop($cadena);
    $cadena2 = str_replace(".","",strrev(strrchr(strrev($phpself),".")));
    $cadena = implode("/",$cadena);
    return $cadena."/".$cadena2."/";
  }
/* Obtiene la ruta de la carpeta de thumbs */
//url_thumbs_folder($_SERVER['HTTP_REFERER'],$phpself);
  function url_thumbs_folder($http_referer,$phpself){
    $cadena = explode('/',str_replace(".","",strrev(strrchr(strrev($http_referer),"."))), -1);
    array_pop($cadena);
    $cadena2 = str_replace(".","",strrev(strrchr(strrev($phpself),".")));
    $cadena = implode("/",$cadena);
    return $cadena."/".$cadena2."/thumbs/";
  }
/* Agregar Item */
  function draw_additem($item = 'Item'){
?>
<br /><div align="center"><form action="<?php basename($_SERVER['PHP_SELF']); ?>?&amp;act=n" method="POST">
<input type="submit" value="Agregar <?php echo $item ?>" /></form></div>
<?php
  }
/* Modificar Item */
  function draw_moditem($item = 'Item'){
?>
<br /><div align="center"><form action="<?php basename($_SERVER['PHP_SELF']); ?>?&amp;act=m" method="POST">
<input type="submit" value="Modificar <?php echo $item ?>" /></form></div>

<?php
  }
/* No Items */
  function draw_noitems(){
?>
<div class="box_warning">No hay registros ingresados.</div>
<?php
  }

/* Crea input con los valores especificados */
//draw_input($label,$name,$type,$value,$checked,$param,$size,$req)
  function draw_input($label,$name,$type,$value = '',$checked = false,$param = false,$size = '',$req = ''){
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    settype($input, "string");
    if ($param == "table_vert") $input .= "<tr>\t<td>";
    if ($param == "table_horiz") $input .= "<tr>\t<td align=\"right\">";

    if (not_null($label)) $input .= "\n".'<label for="'.str_output($name).'">'.str_output($label).'</label>'."\n";
    if ($param == "table_vert") $input .= "</td>\t</tr>\n<tr>\t<td align=\"left\">";
    if ($param == "table_horiz") $input .= "</td>\t<td>";
    if ($param == "br") $input .= "<br />";
 /*   if ($type == "submit") {$input .= '<input type="button" name="'.str_output($name).'"';}
    else {*/$input .= '<input type="'.str_output($type).'" name="'.str_output($name).'"';//}
    if ($param == "disabled") $input .= "disabled";
    if (not_null($value)) $input .= ' value="'.str_output($value).'"';
    if ( ($checked == true) || (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name]) && (($HTTP_GET_VARS[$name] == 'on') || (stripslashes($HTTP_GET_VARS[$name]) == $value))) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name]) && (($HTTP_POST_VARS[$name] == 'on') || (stripslashes($HTTP_POST_VARS[$name]) == $value))) ) {
      $input .= ' checked="checked"';
    }
    if (not_null($size)) $input .= "size=\"".$size."\"";
    if ($req == "req"){$input .= ' /><span class="requerido">[Obligatorio]</span><br />'."\n";}
    else if ($req == "opc"){$input .= ' /><span class="opcional">[Opcional]</span><br />'."\n";}
    else {$input .= ' />'."\n";}
    if ($param == "table_vert") $input .= "</td>\t</tr>";
    if ($param == "table_horiz") $input .= "</td>\t</tr>";
    return $input;
  }

/* Crea textarea con los valores especificados */
  function draw_textarea($name,$value = '',$cols,$rows){
    settype($textarea, "string");
    $textarea = "\n".'<textarea name="'.str_output($name).'" cols="'.$cols.' " rows="'.$rows.'">';
    if (not_null($value)) $textarea .= str_output($value);
    $textarea .= '</textarea>'."\n";
    return $textarea;
  }

/* Crea select con los valores especificados */
// draw_select($label,$name,$data_select,$selected,$param,'',req)
  function draw_select($label,$name,$data_select,$selected = false,$param = false,$size = '1',$req = false){
     settype($select, "string");
    if ($param == "table_vert") $select .= "<tr>\t<td>";
    if ($param == "table_horiz") $select .= "<tr>\t<td align=\"right\" valign=\"top\">";

    if (not_null($label)) $select .= "\n".'<label for="'.str_output($name).'">'.str_output($label).'</label>'."\n";
    if ($param == "table_vert") $select .= "</td>\t</tr>\n<tr>\t<td align=\"left\">";
    if ($param == "table_horiz") $select .= "</td>\t<td>";
    if ($param == "br") $select .= "<br />";

    $select .= "\n".'<select name="'.str_output($name).'" ';
    if (not_null($size)) {$select .= 'size="'.$size.'" multiple="multiple"';} else {$select .= 'size="1"';}
    $select .= '>'."\n  ";
//    $select .= '<strong>Seleccionar</strong>'."\n";
    $select .= '<option value=\'\'>Seleccionar</option>'."\n";
    if(is_array($data_select)){
      reset($data_select);
      foreach ($data_select as $key => $value) {
        if ($key == $selected) {$select .= "<option value=\"$key\" selected=\"selected\">$value</option>\n";}
        else {$select .= "<option value=\"$key\">$value</option>\n";}
      }
    }
    else {echo "$data_select NO ES ARRAY";}
    if ($req == "req"){$select .= '</select><span class="requerido">[Obligatorio]</span>'."\n";}
    if ($req == "opc"){$select .= '</select><span class="opcional">[Opcional]</span>'."\n";}
    else {$select .= '</select>'."\n";}

    if ($param == "table_vert") $select .= "</td>\t</tr>";
    if ($param == "table_horiz") $select .= "</td>\t</tr>";
    return $select;
  }

/* Items Requeridos */
  function draw_req(){
    $req = "<span class=\"requerido\">[Obligatorio]</span>";
    return $req;
  }

/* Items Opcionales */
  function draw_opc(){
    $req = "<span class=\"opcional\">[Opcional]</span>";
    return $req;
  }

/* Redimensiona imágenes */
  function redim_img($red,$nombre_imagen,$ruta_original,$ruta_thumbs){
    $fichero_original = "$ruta_original$nombre_imagen";
    $fichero_thumbs = "$ruta_thumbs$nombre_imagen";
   // Dimensiones nuevas de la imagen
    $red_datos=explode ('x',$red);      // Dividir cadena en dos partes
    $ancho_nuevo=$red_datos[0];         // Ancho de la imagen redimensionada
    $alto_nuevo=$red_datos[1];          // Alto de la imagen redimensionada
   // Datos de la imagen original
    $datos = getimagesize($fichero_original);
        // Hacer una imagen reducida, funciona con GIF, JPG, PNG
    if (($datos[1]>$alto_nuevo) || ($datos[0]>$ancho_nuevo)){
      // Comprobar el soporte GD para este tipo de archivo y devuelve identificador de la imagen
      if ($datos[2]==1) // GIF
          if (function_exists("imagecreatefromgif"))
              $img = imagecreatefromgif($fichero_original);

      if ($datos[2]==2) // JPG
          if (function_exists("imagecreatefromjpeg"))
              $img = imagecreatefromjpeg($fichero_original);

      if ($datos[2]==3) // PNG
          if (function_exists("imagecreatefrompng"))
              $img = imagecreatefrompng($fichero_original);

      // Calculo de las nuevas dimensiones de la imagen
     $ancho_orig=$datos[0];               // Anchura de la imagen original
     $alto_orig=$datos[1];                // Altura de la imagen original
     if ($ancho_orig>$alto_orig){
       $ancho_dest=$ancho_nuevo;
       $alto_dest=($ancho_dest/$ancho_orig)*$alto_orig;
     }
     else{
       $alto_dest=$alto_nuevo;
       $ancho_dest=($alto_dest/$alto_orig)*$ancho_orig;
     }

    // Imagen destino
    // imagecreatetruecolor, solo estan en G.D. 2.0.1 con PHP 4.0.6+
     $img2=@imagecreatetruecolor($ancho_dest,$alto_dest) or $img2=imagecreate($ancho_dest,$alto_dest);

    // Redimensionar
    // imagecopyresampled, solo estan en G.D. 2.0.1 con PHP 4.0.6+
     @imagecopyresampled($img2,$img,0,0,0,0,$ancho_dest,$alto_dest,$ancho_orig,$alto_orig) or imagecopyresized($img2,$img,0,0,0,0,$ancho_dest,$alto_dest,$ancho_orig,$alto_orig);

    //Crear fichero nuevo, según extensión.
     if ($datos[2]==1) // GIF
         if (function_exists("imagegif"))
             imagegif($img2, $fichero_thumbs);

     if ($datos[2]==2) // JPG
         if (function_exists("imagejpeg"))
             imagejpeg($img2, $fichero_thumbs);

     if ($datos[2]==3)  // PNG
         if (function_exists("imagepng"))
             imagepng($img2, $fichero_thumbs);
    }
    else {
      //código de copia de imagen desde imagen original a thumbs.
      if (!copy($fichero_original, $fichero_thumbs)) {
        echo "<div class=\"box_warning\">Falló la copia del archivo: <strong>$file...</strong></div>";
      }
    }
    clearstatcache(); // para limpiar el cache de tamaño de archivos
 /*   imagedestroy($fichero_original);
    imagedestroy($fichero_thumbs);*/
  }



/* Crea un br */
  function br(){echo "<br />\n";}


/* Transforma el texto de entrada hacia SQL */
  function str_input($text){
    $text = addslashes(htmlentities(trim($text), ENT_QUOTES));
    return $text;
  }

/* Transforma el texto de salida desde SQL */
  function str_output($text){
    $text = stripslashes(html_entity_decode(trim($text), ENT_QUOTES));
    return $text;
  }

/* Transforma el texto de salida desde SQL */
  function str_xml_decode($text){
    $text = htmlspecialchars(stripslashes(html_entity_decode(trim($text), ENT_QUOTES)), ENT_QUOTES);
    return $text;
  }

/* Verifica que la variable está vacía */
  function not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }




/* Muestra un array asociativo */
  function disp_array_asoc($array_asoc = '') {
    if(!empty($array_asoc)){
      foreach ($array_asoc as $key => $value) {
        $asoc .= "\"$key\" => $value<br />";
      }
      return $asoc;
    }
  }

/* Muestra un array */
  function disp_array($this_array = '') {
    if(!empty($this_array)){
      foreach ($this_array as $value) {
        $asoc .= "-> $value<br />";
      }
      return $asoc;
    }
  }

/* Inserta/Actualiza los registros de una tabla */
  function sql_input($tabla, $data, $action = '', $param = '', $verbose = '1') {
    settype($i, "integer");
    $cantidad = count($data);
    reset($data);
    if ($action == 'insert') {
      $insert = "INSERT INTO ".$tabla." SET ";
      foreach ($data as $key => $value) {
        $insert .= "$key = '$value'";
        $i++;
        if ($i<$cantidad){$insert .= ", ";}
      }
//     echo $insert;
//     $query =  or die(mysql_error());
        if(mysql_query("$insert")){
          if ($verbose == '1'){
            echo "<div class=\"box_success\">La información ha sido ingresada exitosamente.</div>";
/*            echo "<div class=\"box_info\">
              <p>Se han insertado los siguientes datos:</p>
                <div class=\"descrip\">";
            foreach ($data as $key => $value) {echo str_output($value)."<br />";}
            echo "</div>\n  </div>";     */
          }
        }
        else {echo "<div class=\"box_error\">Ha ocurrido un error y no se han ingresado los datos. Avise al administrador.</div>";}
    }
    elseif ($action == 'update') {
      $update = "UPDATE ".$tabla." SET ";
      foreach ($data as $key => $value) {
        $update .= "$key = '$value'";
        $i++;
        if ($i<$cantidad){$update .= ", ";}
      }
      if (!empty($param)) {

        $x = explode ('x',$param);
        $update .= " WHERE ".$x[0]."='".$x[1]."'";
      }
//      echo $update;
//      $query = mysql_query("$update") or die(mysql_error());

      if(mysql_query("$update")){
        if ($verbose == '1'){
            echo "<div class=\"box_success\">La información se actualizó exitosamente.</div>";
/*            echo "<div class=\"box_info\">
              <p>Se han insertado los siguientes datos:</p>
                <div class=\"descrip\">";
            foreach ($data as $key => $value) {echo str_output($value)."<br />";}
            echo "</div>\n  </div>";           */
        }
      }
      else {echo "<div class=\"box_error\">Ha ocurrido un error y no se actualizaron los datos. Avise al administrador.</div>";}

    }
  }

/* Crea un log de errores */
  function gestor_error($num_err,$txt_err,$file_err,$linea_err){
    $ddf = fopen('error.log','a');
    fwrite($ddf,"[".date("r")."] | ".basename($file_err)." [Linea:$linea_err] | Error $num_err: $txt_err\r\n");
    fclose($ddf);
  }
//  set_error_handler('gestor_error');
/* array binario (si/no)
  function bin_array() {
  $data_select = array('si' => 'Sí','no' => 'No');
  return $data_select;
  }                      */


/* Selecciona registros de una tabla
  sql_select($tabla,$data,'autor_idxASC'):
  sql_select($tabla,'autor_id');
  SELECT * FROM ".$tabla." ORDER BY autor_id DESC

  function sql_select($tabla, $data, $param = '') {
//    global $select;
    if (is_array($data)) {
      $cantidad = count($data);
      reset($data);
      $select = "SELECT ";
      foreach ($data as $key => $value) {
        $select .= "$value";
        $i++;
        if ($i<$cantidad){$select .= ", ";}
      }
      $select .="FROM ".$tabla;
      if (!empty($param)) {
        $x = explode ('x',$param);
        $select .= "ORDER BY ".$x[0]." ".$x[1]."'";
      }
    }
    elseif (empty($data)) {
      $select = "SELECT * FROM ".$tabla;
      if (!empty($param)) {
        $x = explode ('x',$param);
        $select .= "ORDER BY ".$x[0]." ".$x[1]."'";
      }
    }
    elseif (is_string($data)) {
      $select = "SELECT ".$data." FROM ".$tabla;
      if (!empty($param)) {
        $x = explode ('x',$param);
        $select .= "ORDER BY ".$x[0]." ".$x[1]."'";
      }
    }

//  $datos = mysql_query("$select") or die(mysql_error());
//  return $datos;
    return $select;


  }   */

?>
